<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusRequest;
use App\Http\Requests\UpdateBusRequest;
use App\Models\Bus;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class BusController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $query = Bus::withCount('seats')
            ->when($request->search, fn($q) =>
                $q->where('vehicle_no', 'like', "%{$request->search}%")
                  ->orWhere('depot_reg_no', 'like', "%{$request->search}%")
                  ->orWhere('brand', 'like', "%{$request->search}%"))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest();

        $buses = $query->paginate(15)->withQueryString();
        return view('buses.index', compact('buses'));
    }

    public function create()
    {
        return view('buses.create');
    }

    public function store(StoreBusRequest $request)
    {
        $bus = Bus::create($request->validated());

        // Auto-generate seat records
        for ($i = 1; $i <= $bus->seat_count; $i++) {
            $bus->seats()->create([
                'seat_number' => str_pad($i, 2, '0', STR_PAD_LEFT),
                'seat_type'   => 'window',
            ]);
        }

        $this->logActivity('created', 'Bus', $bus->id,
            "Bus {$bus->depot_reg_no} ({$bus->vehicle_no}) added by " . auth()->user()->name);

        return redirect()->route('buses.show', $bus)
            ->with('success', "Bus {$bus->depot_reg_no} added successfully with {$bus->seat_count} seats generated.");
    }

    public function show(Bus $bus)
    {
        $bus->load('seats');
        $recentSchedules = $bus->schedules()
            ->with('route')
            ->orderByDesc('schedule_date')
            ->take(5)
            ->get();
        return view('buses.show', compact('bus', 'recentSchedules'));
    }

    public function edit(Bus $bus)
    {
        return view('buses.edit', compact('bus'));
    }

    public function update(UpdateBusRequest $request, Bus $bus)
    {
        $old = $bus->only(['vehicle_no', 'depot_reg_no', 'brand', 'seat_count', 'status']);
        $newSeatCount = $request->seat_count;
        $oldSeatCount = $bus->seat_count;

        $bus->update($request->validated());

        // If seat count increased, add more seats
        if ($newSeatCount > $oldSeatCount) {
            for ($i = $oldSeatCount + 1; $i <= $newSeatCount; $i++) {
                $bus->seats()->create([
                    'seat_number' => str_pad($i, 2, '0', STR_PAD_LEFT),
                    'seat_type'   => 'window',
                ]);
            }
        }

        $this->logActivity('updated', 'Bus', $bus->id, "Bus {$bus->depot_reg_no} updated", $old, $request->validated());

        return redirect()->route('buses.show', $bus)
            ->with('success', "Bus {$bus->depot_reg_no} updated successfully.");
    }

    public function updateStatus(Request $request, Bus $bus)
    {
        $request->validate([
            'status' => ['required', 'in:active,needs_repair,under_repair,maintenance,condemned'],
        ]);

        // Condemned is irreversible
        if ($bus->isCondemned()) {
            return back()->with('error', 'A condemned bus cannot change status.');
        }

        $oldStatus = $bus->status;
        $bus->update(['status' => $request->status]);

        $this->logActivity('status_changed', 'Bus', $bus->id,
            "Bus {$bus->depot_reg_no} status changed from {$oldStatus} to {$request->status}");

        return back()->with('success', "Bus {$bus->depot_reg_no} status updated to {$bus->status_label}.");
    }
}