<?php

namespace App\Http\Controllers;

use App\Models\BreakdownReport;
use App\Models\InventoryItem;
use App\Models\InventoryRelease;
use App\Services\NotificationService;
use App\Traits\LogsActivity;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    use LogsActivity;

    public function __construct(private NotificationService $notify) {}

    // ── List all items ────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = InventoryItem::withCount('releases')
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('category', 'like', "%{$request->search}%"))
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->when($request->stock === 'low',
                fn($q) => $q->whereColumn('quantity', '<=', 'low_stock_threshold'))
            ->when($request->stock === 'out',
                fn($q) => $q->where('quantity', '<=', 0))
            ->orderBy('name');

        $items      = $query->paginate(20)->withQueryString();
        $categories = InventoryItem::distinct()->orderBy('category')->pluck('category');
        $lowCount   = InventoryItem::lowStock()->count();

        return view('inventory.index', compact('items', 'categories', 'lowCount'));
    }

    // ── Create form ───────────────────────────────────────────────────────
    public function create()
    {
        $categories = InventoryItem::distinct()->orderBy('category')->pluck('category');
        return view('inventory.create', compact('categories'));
    }

    // ── Store new item ────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'category'            => ['required', 'string', 'max:100'],
            'quantity'            => ['required', 'numeric', 'min:0'],
            'unit'                => ['required', 'string', 'max:20'],
            'low_stock_threshold' => ['required', 'numeric', 'min:0'],
            'unit_price'          => ['nullable', 'numeric', 'min:0'],
            'supplier'            => ['nullable', 'string', 'max:255'],
            'notes'               => ['nullable', 'string'],
        ]);

        $item = InventoryItem::create($validated);

        $this->logActivity('created', 'InventoryItem', $item->id,
            "Inventory item '{$item->name}' added by " . auth()->user()->name);

        return redirect()->route('inventory.index')
            ->with('success', "'{$item->name}' added to inventory.");
    }

    // ── Show single item with release history ─────────────────────────────
    public function show(InventoryItem $inventory)
    {
        $inventory->load(['releases.releasedBy', 'releases.breakdownReport.bus']);
        return view('inventory.show', compact('inventory'));
    }

    // ── Edit form ─────────────────────────────────────────────────────────
    public function edit(InventoryItem $inventory)
    {
        $categories = InventoryItem::distinct()->orderBy('category')->pluck('category');
        return view('inventory.edit', compact('inventory', 'categories'));
    }

    // ── Update item ───────────────────────────────────────────────────────
    public function update(Request $request, InventoryItem $inventory)
    {
        $validated = $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'category'            => ['required', 'string', 'max:100'],
            'quantity'            => ['required', 'numeric', 'min:0'],
            'unit'                => ['required', 'string', 'max:20'],
            'low_stock_threshold' => ['required', 'numeric', 'min:0'],
            'unit_price'          => ['nullable', 'numeric', 'min:0'],
            'supplier'            => ['nullable', 'string', 'max:255'],
            'notes'               => ['nullable', 'string'],
        ]);

        $old = $inventory->only(['name', 'quantity', 'unit']);
        $inventory->update($validated);

        $this->logActivity('updated', 'InventoryItem', $inventory->id,
            "Inventory item '{$inventory->name}' updated", $old, $validated);

        return redirect()->route('inventory.show', $inventory)
            ->with('success', "'{$inventory->name}' updated.");
    }

    // ── Release stock (issue parts) ───────────────────────────────────────
    public function release(Request $request, InventoryItem $inventory)
    {
        $validated = $request->validate([
            'quantity_released'   => [
                'required', 'numeric', 'min:0.01',
                "max:{$inventory->quantity}",
            ],
            'reason'              => ['required', 'string', 'max:255'],
            'breakdown_report_id' => ['nullable', 'exists:breakdown_reports,id'],
            'notes'               => ['nullable', 'string'],
        ]);

        // Deduct from stock
        $inventory->decrement('quantity', $validated['quantity_released']);

        // Create release record
        InventoryRelease::create([
            'inventory_item_id'   => $inventory->id,
            'breakdown_report_id' => $validated['breakdown_report_id'] ?? null,
            'released_by'         => auth()->id(),
            'quantity_released'   => $validated['quantity_released'],
            'reason'              => $validated['reason'],
            'notes'               => $validated['notes'] ?? null,
        ]);

        // Alert if now low stock
        if ($inventory->fresh()->isLowStock()) {
            $this->notify->sendToRoles(
                ['admin', 'executive_officer'],
                "Low stock alert: {$inventory->name}",
                "Stock level for '{$inventory->name}' has dropped to {$inventory->fresh()->quantity} {$inventory->unit}.",
                'warning',
                route('inventory.show', $inventory)
            );
        }

        $this->logActivity('released', 'InventoryItem', $inventory->id,
            "Released {$validated['quantity_released']} {$inventory->unit} of '{$inventory->name}'");

        return back()->with('success',
            "{$validated['quantity_released']} {$inventory->unit} of '{$inventory->name}' released from stock.");
    }

    // ── Restock (add to quantity) ─────────────────────────────────────────
    public function restock(Request $request, InventoryItem $inventory)
    {
        $request->validate([
            'quantity_added' => ['required', 'numeric', 'min:0.01'],
        ]);

        $old = $inventory->quantity;
        $inventory->increment('quantity', $request->quantity_added);

        $this->logActivity('restocked', 'InventoryItem', $inventory->id,
            "Restocked '{$inventory->name}' by {$request->quantity_added} {$inventory->unit} (was {$old})");

        return back()->with('success',
            "Added {$request->quantity_added} {$inventory->unit} to '{$inventory->name}'.");
    }

    // ── Download inventory PDF report ─────────────────────────────────────
    public function downloadReport()
    {
        $items    = InventoryItem::orderBy('category')->orderBy('name')->get();
        $lowItems = $items->filter(fn($i) => $i->isLowStock());

        $pdf = Pdf::loadView('pdf.inventory-report', compact('items', 'lowItems'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('inventory-report-' . today()->format('Y-m-d') . '.pdf');
    }
}