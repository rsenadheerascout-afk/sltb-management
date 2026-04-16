<?php

namespace App\Http\Controllers;

use App\Models\Schedule;

class DriverController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $todaySchedule = Schedule::where(function ($q) use ($user) {
                $q->where('driver_id', $user->id)
                  ->orWhere('conductor_id', $user->id);
            })
            ->where('status', 'active')
            ->whereDate('schedule_date', today())
            ->with(['route', 'bus', 'bookings'])
            ->first();

        $upcomingSchedules = Schedule::where(function ($q) use ($user) {
                $q->where('driver_id', $user->id)
                  ->orWhere('conductor_id', $user->id);
            })
            ->where('status', 'active')
            ->whereDate('schedule_date', '>', today())
            ->with(['route', 'bus'])
            ->orderBy('schedule_date')
            ->orderBy('departure_time')
            ->take(5)
            ->get();

        return view('driver.dashboard', compact('todaySchedule', 'upcomingSchedules'));
    }

    public function schedule()
    {
        $user = auth()->user();

        $schedules = Schedule::where(function ($q) use ($user) {
                $q->where('driver_id', $user->id)
                  ->orWhere('conductor_id', $user->id);
            })
            ->where('status', 'active')
            ->whereDate('schedule_date', '>=', today())
            ->with(['route', 'bus'])
            ->orderBy('schedule_date')
            ->orderBy('departure_time')
            ->paginate(15);

        $pastSchedules = Schedule::where(function ($q) use ($user) {
                $q->where('driver_id', $user->id)
                  ->orWhere('conductor_id', $user->id);
            })
            ->whereDate('schedule_date', '<', today())
            ->with(['route', 'bus'])
            ->orderByDesc('schedule_date')
            ->take(10)
            ->get();

        return view('driver.schedule', compact('schedules', 'pastSchedules'));
    }
}