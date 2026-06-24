<?php

namespace App\Http\Controllers;

use App\Models\DutyRoster;
use App\Models\Schedule;
use App\Models\User;
use App\Services\NotificationService;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class DutyRosterController extends Controller
{
    use LogsActivity;

    public function __construct(private NotificationService $notify)
    {
    }

    public function index(Request $request)
    {
        $rosters = DutyRoster::with(['user', 'schedule.route', 'schedule.bus'])
            ->when($request->date, fn($q) => $q->where('duty_date', $request->date))
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->orderBy('duty_date')
            ->paginate(20)
            ->withQueryString();

        $drivers = User::where('role', 'driver')->where('status', 'active')->orderBy('name')->get();
        $conductors = User::where('role', 'conductor')->where('status', 'active')->orderBy('name')->get();

        return view('rosters.index', compact('rosters', 'drivers', 'conductors'));
    }

    public function create()
    {
        $schedules = Schedule::with(['route', 'bus'])
            ->where('status', 'active')
            ->whereDate('schedule_date', '>=', today())
            ->orderBy('schedule_date')
            ->orderBy('departure_time')
            ->get();

        $drivers = User::where('role', 'driver')->where('status', 'active')
            ->where('is_approved', true)->orderBy('name')->get();
        $conductors = User::where('role', 'conductor')->where('status', 'active')
            ->where('is_approved', true)->orderBy('name')->get();

        return view('rosters.create', compact('schedules', 'drivers', 'conductors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => ['required', 'exists:schedules,id'],
            'user_id' => ['required', 'exists:users,id'],
            'duty_date' => ['required', 'date'],
            'status' => ['required', 'in:assigned,completed,absent'],
        ]);

        // Prevent duplicate assignment for same user + schedule + date
        $exists = DutyRoster::where('user_id', $validated['user_id'])
            ->where('schedule_id', $validated['schedule_id'])
            ->where('duty_date', $validated['duty_date'])
            ->exists();

        if ($exists) {
            return back()
                ->with('error', 'This employee is already assigned to this schedule on that date.')
                ->withInput();
        }

        $roster = DutyRoster::create($validated);

        // Notify the assigned employee
        $employee = User::find($validated['user_id']);
        $schedule = Schedule::with(['route', 'bus'])->find($validated['schedule_id']);

        $this->notify->send(
            $employee->id,
            'New duty assignment',
            "You have been assigned to {$schedule->route->name} on " .
            \Carbon\Carbon::parse($validated['duty_date'])->format('D, d M Y') .
            " (Bus {$schedule->bus->depot_reg_no}, departs " .
            \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') . ").",
            'info'
        );

        $this->logActivity(
            'created',
            'DutyRoster',
            $roster->id,
            "Duty assigned: {$employee->name} → {$schedule->route->name} on {$validated['duty_date']}"
        );

        return redirect()->route('rosters.index')
            ->with('success', "Duty assigned to {$employee->name} for {$schedule->route->name}.");
    }

    public function destroy(DutyRoster $roster)
    {
        $name = $roster->user->name;
        $roster->delete();

        $this->logActivity(
            'deleted',
            'DutyRoster',
            $roster->id,
            "Duty roster removed for {$name}"
        );

        return back()->with('success', "Duty assignment removed for {$name}.");
    }

    // Download PDF of duty roster
    public function downloadPdf(Request $request)
    {
        $date = $request->date ?? today()->format('Y-m-d');

        $rosters = DutyRoster::with(['user', 'schedule.route', 'schedule.bus'])
            ->whereDate('duty_date', $date)
            ->orderBy('duty_date')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.roster', compact('rosters', 'date'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('duty-roster-' . $date . '.pdf');
    }
}