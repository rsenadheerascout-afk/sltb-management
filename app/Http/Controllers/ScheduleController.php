<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Bus;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    use LogsActivity;

    // Public view — no auth required
    public function publicIndex(Request $request)
    {
        $schedules = Schedule::with(['route', 'bus'])
            ->where('status', 'active')
            ->where('schedule_date', '>=', today())
            ->when($request->route_id, fn($q) => $q->where('route_id', $request->route_id))
            ->when($request->date,     fn($q) => $q->where('schedule_date', $request->date))
            ->orderBy('schedule_date')
            ->orderBy('departure_time')
            ->paginate(20)
            ->withQueryString();

        $routes = Route::where('status', 'active')->orderBy('name')->get();

        return view('schedules.public', compact('schedules', 'routes'));
    }

    // Authenticated index
    public function index(Request $request)
    {
        $schedules = Schedule::with(['route', 'bus', 'driver', 'conductor'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->route_id, fn($q) => $q->where('route_id', $request->route_id))
            ->when($request->date, fn($q) => $q->where('schedule_date', $request->date))
            ->orderByDesc('schedule_date')
            ->orderBy('departure_time')
            ->paginate(20)
            ->withQueryString();

        $routes = Route::where('status', 'active')->orderBy('name')->get();
        return view('schedules.index', compact('schedules', 'routes'));
    }

    public function create()
    {
        $routes     = Route::where('status', 'active')->orderBy('name')->get();
        $buses      = Bus::where('status', 'active')->orderBy('depot_reg_no')->get();
        $drivers    = User::whereIn('role', ['driver'])->where('status', 'active')->orderBy('name')->get();
        $conductors = User::whereIn('role', ['conductor'])->where('status', 'active')->orderBy('name')->get();

        return view('schedules.create', compact('routes', 'buses', 'drivers', 'conductors'));
    }

    public function store(StoreScheduleRequest $request)
    {
        $schedule = Schedule::create(array_merge(
            $request->validated(),
            ['created_by' => auth()->id()]
        ));

        $this->logActivity('created', 'Schedule', $schedule->id,
            "Schedule created: {$schedule->route->name} on {$schedule->schedule_date}");

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule created successfully.');
    }

    public function show(Schedule $schedule)
    {
        $schedule->load(['route', 'bus.seats', 'driver', 'conductor', 'bookings']);
        return view('schedules.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $routes     = Route::where('status', 'active')->orderBy('name')->get();
        $buses      = Bus::where('status', 'active')->orderBy('depot_reg_no')->get();
        $drivers    = User::where('role', 'driver')->where('status', 'active')->orderBy('name')->get();
        $conductors = User::where('role', 'conductor')->where('status', 'active')->orderBy('name')->get();

        return view('schedules.edit', compact('schedule', 'routes', 'buses', 'drivers', 'conductors'));
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $schedule->update($request->validated());
        $this->logActivity('updated', 'Schedule', $schedule->id, "Schedule {$schedule->id} updated");
        return redirect()->route('schedules.show', $schedule)->with('success', 'Schedule updated successfully.');
    }

    public function deactivate(Schedule $schedule)
    {
        $schedule->update(['status' => 'inactive']);
        $this->logActivity('deactivated', 'Schedule', $schedule->id, "Schedule {$schedule->id} deactivated");
        return back()->with('success', 'Schedule has been deactivated.');
    }

    public function activate(Schedule $schedule)
    {
        $schedule->update(['status' => 'active']);
        return back()->with('success', 'Schedule has been activated.');
    }

    // Returns JSON for FullCalendar (used by timekeeper)
    public function calendarEvents()
    {
        $schedules = Schedule::with(['route', 'bus'])
            ->where('status', 'active')
            ->get()
            ->map(fn($s) => [
                'id'    => $s->id,
                'title' => $s->route->name . ' — ' . $s->bus->depot_reg_no,
                'start' => $s->schedule_date->format('Y-m-d') . 'T' . $s->departure_time,
                'url'   => route('schedules.show', $s),
            ]);

        return response()->json($schedules);
    }
}