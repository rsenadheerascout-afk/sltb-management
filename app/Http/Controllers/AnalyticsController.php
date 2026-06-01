<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BreakdownReport;
use App\Models\Bus;
use App\Models\EmployeeRegistration;
use App\Models\InventoryItem;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    // ── Admin analytics data ─────────────────────────────────────────────
    public function adminData()
    {
        $year = now()->year;

        // Monthly revenue for current year (12 months)
        $monthlyRevenue = Booking::where('payment_status', 'paid')
            ->whereYear('booked_at', $year)
            ->select(
                DB::raw('MONTH(booked_at) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $revenueData = collect(range(1, 12))->map(fn($m) => [
            'month'   => Carbon::create($year, $m, 1)->format('M'),
            'revenue' => (float) ($monthlyRevenue->get($m)?->total ?? 0),
        ]);

        // Monthly booking count
        $monthlyBookings = Booking::where('payment_status', 'paid')
            ->whereYear('booked_at', $year)
            ->select(
                DB::raw('MONTH(booked_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $bookingCountData = collect(range(1, 12))->map(fn($m) => [
            'month' => Carbon::create($year, $m, 1)->format('M'),
            'count' => (int) ($monthlyBookings->get($m)?->count ?? 0),
        ]);

        // Breakdown status distribution
        $breakdownStatus = BreakdownReport::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(fn($r) => [$r->status => $r->count]);

        // Bus status distribution
        $busStatus = Bus::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(fn($r) => [$r->status => $r->count]);

        // Employee role distribution (excluding admin)
        $roleDistribution = User::where('role', '!=', 'admin')
            ->where('role', '!=', 'passenger')
            ->select('role', DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->get()
            ->mapWithKeys(fn($r) => [$r->role => $r->count]);

        // Top routes by booking count (last 30 days)
        $topRoutes = Booking::where('payment_status', 'paid')
            ->where('booked_at', '>=', now()->subDays(30))
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->join('routes', 'schedules.route_id', '=', 'routes.id')
            ->select('routes.name', DB::raw('COUNT(*) as count'), DB::raw('SUM(bookings.amount) as revenue'))
            ->groupBy('routes.id', 'routes.name')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        // Recent activity summary
        $last30Days = now()->subDays(30);

        return response()->json([
            'monthly_revenue'    => $revenueData,
            'booking_counts'     => $bookingCountData,
            'breakdown_status'   => $breakdownStatus,
            'bus_status'         => $busStatus,
            'role_distribution'  => $roleDistribution,
            'top_routes'         => $topRoutes,
            'totals' => [
                'revenue_30d'      => (float) Booking::where('payment_status', 'paid')
                                        ->where('booked_at', '>=', $last30Days)->sum('amount'),
                'bookings_30d'     => Booking::where('payment_status', 'paid')
                                        ->where('booked_at', '>=', $last30Days)->count(),
                'new_employees_30d'=> User::where('created_at', '>=', $last30Days)
                                        ->where('role', '!=', 'passenger')->count(),
                'open_breakdowns'  => BreakdownReport::whereIn('status', ['pending','approved','in_progress'])->count(),
            ],
        ]);
    }

    // ── Officer analytics data ────────────────────────────────────────────
    public function officerData()
    {
        $year = now()->year;

        $monthlyRevenue = Booking::where('payment_status', 'paid')
            ->whereYear('booked_at', $year)
            ->select(DB::raw('MONTH(booked_at) as month'), DB::raw('SUM(amount) as total'))
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $revenueData = collect(range(1, 12))->map(fn($m) => [
            'month'   => Carbon::create($year, $m, 1)->format('M'),
            'revenue' => (float) ($monthlyRevenue->get($m)?->total ?? 0),
        ]);

        $breakdownStatus = BreakdownReport::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(fn($r) => [$r->status => $r->count]);

        $pendingRegistrations = EmployeeRegistration::where('status', 'pending')->count();

        $scheduleStats = [
            'today'    => Schedule::where('status','active')->whereDate('schedule_date', today())->count(),
            'this_week'=> Schedule::where('status','active')
                            ->whereBetween('schedule_date', [today(), today()->addDays(7)])->count(),
            'unassigned'=> Schedule::where('status','active')
                            ->whereDate('schedule_date', '>=', today())
                            ->where(fn($q) => $q->whereNull('driver_id')->orWhereNull('conductor_id'))
                            ->count(),
        ];

        return response()->json([
            'monthly_revenue'     => $revenueData,
            'breakdown_status'    => $breakdownStatus,
            'pending_registrations'=> $pendingRegistrations,
            'schedule_stats'      => $scheduleStats,
        ]);
    }

    // ── Timekeeper analytics data ─────────────────────────────────────────
    public function timekeeperData()
    {
        // Schedules per day for the next 14 days
        $upcomingSchedules = Schedule::where('status', 'active')
            ->whereBetween('schedule_date', [today(), today()->addDays(13)])
            ->select('schedule_date', DB::raw('COUNT(*) as count'))
            ->groupBy('schedule_date')
            ->orderBy('schedule_date')
            ->get()
            ->keyBy(fn($r) => Carbon::parse($r->schedule_date)->format('Y-m-d'));

        $scheduleByDay = collect(range(0, 13))->map(fn($d) => [
            'date'  => today()->addDays($d)->format('d M'),
            'count' => (int) ($upcomingSchedules->get(today()->addDays($d)->format('Y-m-d'))?->count ?? 0),
        ]);

        // Roster completion stats for today
        $todayRosters = \App\Models\DutyRoster::whereDate('duty_date', today())
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(fn($r) => [$r->status => $r->count]);

        // Unassigned schedules by date
        $unassigned = Schedule::where('status', 'active')
            ->whereDate('schedule_date', '>=', today())
            ->where(fn($q) => $q->whereNull('driver_id')->orWhereNull('conductor_id'))
            ->select('schedule_date', DB::raw('COUNT(*) as count'))
            ->groupBy('schedule_date')
            ->orderBy('schedule_date')
            ->take(7)
            ->get();

        return response()->json([
            'schedule_by_day' => $scheduleByDay,
            'roster_status'   => $todayRosters,
            'unassigned'      => $unassigned,
        ]);
    }

    // ── Storekeeper analytics data ────────────────────────────────────────
    public function storekeeperData()
    {
        // Stock levels by category
        $categoryStock = InventoryItem::select(
                'category',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('COUNT(*) as item_count'),
                DB::raw('SUM(CASE WHEN quantity <= low_stock_threshold THEN 1 ELSE 0 END) as low_count')
            )
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        // Monthly releases (last 6 months)
        $monthlyReleases = \App\Models\InventoryRelease::where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(quantity_released) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(fn($r) => [
                'label' => Carbon::create($r->year, $r->month, 1)->format('M Y'),
                'total' => (float) $r->total,
            ]);

        // Low stock items
        $lowStockItems = InventoryItem::lowStock()
            ->orderBy('quantity')
            ->take(10)
            ->get(['id', 'name', 'quantity', 'low_stock_threshold', 'unit']);

        return response()->json([
            'category_stock'  => $categoryStock,
            'monthly_releases'=> $monthlyReleases,
            'low_stock_items' => $lowStockItems,
        ]);
    }
}