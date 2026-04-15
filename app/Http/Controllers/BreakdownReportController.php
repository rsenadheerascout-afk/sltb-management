<?php

namespace App\Http\Controllers;

use App\Models\BreakdownReport;
use App\Models\Bus;
use App\Models\Schedule;
use App\Services\NotificationService;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BreakdownReportController extends Controller
{
    use LogsActivity;

    public function __construct(private NotificationService $notify) {}

    // ── Admin / Officer: list all reports ────────────────────────────────
    public function index(Request $request)
    {
        $reports = BreakdownReport::with(['bus', 'reportedBy'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15);

        return view('breakdowns.index', compact('reports'));
    }

    // ── Driver / Conductor: show report form ─────────────────────────────
    public function create()
    {
        // Only show buses assigned to this driver's active schedule
        $user = auth()->user();

        $assignedBus = Schedule::where(function ($q) use ($user) {
                $q->where('driver_id', $user->id)
                  ->orWhere('conductor_id', $user->id);
            })
            ->where('status', 'active')
            ->whereDate('schedule_date', today())
            ->with('bus')
            ->first()?->bus;

        // Fallback: all active buses (in case schedule not yet set)
        $buses = Bus::where('status', 'active')->orderBy('depot_reg_no')->get();

        return view('breakdowns.create', compact('assignedBus', 'buses'));
    }

    // ── Driver / Conductor: submit report ────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id'      => ['required', 'exists:buses,id'],
            'description' => ['required', 'string', 'min:10'],
            'latitude'    => ['nullable', 'numeric'],
            'longitude'   => ['nullable', 'numeric'],
            'photo'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $filename  = 'breakdowns/' . Str::uuid() . '.' . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->storeAs('', $filename, 'public');
            $photoPath = $filename;
        }

        $report = BreakdownReport::create([
            'bus_id'      => $validated['bus_id'],
            'reported_by' => auth()->id(),
            'description' => $validated['description'],
            'latitude'    => $validated['latitude'] ?? null,
            'longitude'   => $validated['longitude'] ?? null,
            'photo_path'  => $photoPath,
            'status'      => 'pending',
        ]);

        // Notify admin and executive officers
        $this->notify->sendToRoles(
            ['admin', 'executive_officer'],
            'New breakdown report filed',
            auth()->user()->name . ' reported a breakdown on bus ' . $report->bus->depot_reg_no . '.',
            'danger',
            route('breakdowns.show', $report)
        );

        $this->logActivity('created', 'BreakdownReport', $report->id,
            "Breakdown report filed for bus {$report->bus->depot_reg_no}");

        return redirect()->route('driver.dashboard')
            ->with('success', 'Breakdown report submitted. The depot has been notified.');
    }

    // ── View single report ────────────────────────────────────────────────
    public function show(BreakdownReport $breakdown)
    {
        $breakdown->load(['bus', 'reportedBy', 'approvedBy', 'respondedBy', 'replacementBus']);

        $availableBuses = [];
        if ($breakdown->isApproved() && !$breakdown->isResolved()) {
            $availableBuses = Bus::where('status', 'active')
                ->where('id', '!=', $breakdown->bus_id)
                ->orderBy('depot_reg_no')
                ->get();
        }

        return view('breakdowns.show', compact('breakdown', 'availableBuses'));
    }

    // ── Officer: approve report ───────────────────────────────────────────
    public function approve(BreakdownReport $breakdown)
    {
        if (!$breakdown->isPending()) {
            return back()->with('error', 'This report has already been reviewed.');
        }

        // Mark the bus as needing repair
        $breakdown->bus->update(['status' => 'needs_repair']);

        $breakdown->update([
            'status'      => 'approved',
            'approved_by' => auth()->id(),
        ]);

        // Notify the driver
        $this->notify->send(
            $breakdown->reported_by,
            'Breakdown report approved',
            "Your breakdown report for bus {$breakdown->bus->depot_reg_no} has been approved. Response is being coordinated.",
            'info'
        );

        $this->logActivity('approved', 'BreakdownReport', $breakdown->id,
            "Breakdown report approved by " . auth()->user()->name);

        return back()->with('success', 'Report approved. Bus status set to Needs Repair. Choose a response action below.');
    }

    // ── Officer: reject report ────────────────────────────────────────────
    public function reject(Request $request, BreakdownReport $breakdown)
    {
        $request->validate(['reject_reason' => 'required|string|min:5']);

        if (!$breakdown->isPending()) {
            return back()->with('error', 'This report has already been reviewed.');
        }

        $breakdown->update([
            'status'        => 'rejected',
            'approved_by'   => auth()->id(),
            'reject_reason' => $request->reject_reason,
        ]);

        // Notify driver
        $this->notify->send(
            $breakdown->reported_by,
            'Breakdown report rejected',
            "Your report for bus {$breakdown->bus->depot_reg_no} was rejected. Reason: {$request->reject_reason}",
            'warning'
        );

        $this->logActivity('rejected', 'BreakdownReport', $breakdown->id,
            "Breakdown report rejected: {$request->reject_reason}");

        return back()->with('success', 'Report has been rejected.');
    }

    // ── Officer: respond with action ──────────────────────────────────────
    public function respond(Request $request, BreakdownReport $breakdown)
    {
        $request->validate([
            'response_action'  => ['required', 'in:spare_parts,carrier,replacement_bus,notify_only'],
            'response_notes'   => ['nullable', 'string'],
            'replacement_bus_id' => ['required_if:response_action,replacement_bus', 'nullable', 'exists:buses,id'],
        ]);

        $data = [
            'response_action' => $request->response_action,
            'response_notes'  => $request->response_notes,
            'responded_by'    => auth()->id(),
            'status'          => 'in_progress',
        ];

        // Handle replacement bus
        if ($request->response_action === 'replacement_bus' && $request->replacement_bus_id) {
            $replacementBus = Bus::findOrFail($request->replacement_bus_id);
            $data['replacement_bus_id'] = $replacementBus->id;

            // Notify storekeeper about the swap
            $this->notify->sendToRole(
                'storekeeper',
                'Replacement bus dispatched',
                "Bus {$replacementBus->depot_reg_no} replacing breakdown bus {$breakdown->bus->depot_reg_no}.",
                'info'
            );
        }

        // Handle spare parts — notify storekeeper
        if ($request->response_action === 'spare_parts') {
            $this->notify->sendToRole(
                'storekeeper',
                'Spare parts needed for breakdown',
                "Breakdown on bus {$breakdown->bus->depot_reg_no}. Please check inventory and prepare required parts.",
                'warning',
                route('breakdowns.show', $breakdown)
            );
        }

        $breakdown->update($data);

        // Notify the reporting driver of the action taken
        $actionLabels = [
            'spare_parts'     => 'Spare parts are being dispatched.',
            'carrier'         => 'A recovery carrier is being sent.',
            'replacement_bus' => 'A replacement bus has been assigned.',
            'notify_only'     => 'The depot has been informed.',
        ];

        $this->notify->send(
            $breakdown->reported_by,
            'Response to your breakdown report',
            "Action taken for bus {$breakdown->bus->depot_reg_no}: " . $actionLabels[$request->response_action],
            'success'
        );

        $this->logActivity('responded', 'BreakdownReport', $breakdown->id,
            "Response action: {$request->response_action}");

        return back()->with('success', 'Response action recorded and driver notified.');
    }

    // ── Officer: mark resolved ────────────────────────────────────────────
    public function resolve(BreakdownReport $breakdown)
    {
        $breakdown->update([
            'status'      => 'resolved',
            'resolved_at' => now(),
        ]);

        // Restore bus to under_repair or active depending on what happened
        $breakdown->bus->update(['status' => 'under_repair']);

        $this->notify->send(
            $breakdown->reported_by,
            'Breakdown resolved',
            "The breakdown for bus {$breakdown->bus->depot_reg_no} has been marked as resolved.",
            'success'
        );

        $this->logActivity('resolved', 'BreakdownReport', $breakdown->id,
            "Breakdown report resolved by " . auth()->user()->name);

        return back()->with('success', 'Breakdown marked as resolved. Bus status set to Under Repair.');
    }
}