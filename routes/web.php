<?php

use Illuminate\Support\Facades\Route;

// ══════════════════════════════════════════════════════════════
// PUBLIC ROUTES — accessible to everyone (passengers + guests)
// ══════════════════════════════════════════════════════════════

// Homepage — passenger-friendly landing page
// Route::get('/', function () {
//     return view('public.home');
// })->name('home');
// New homepage route with upcoming (5) schedules and active routes
Route::get('/', function () {
    $upcomingSchedules = \App\Models\Schedule::with(['route', 'bus'])
        ->where('status', 'active')
        ->where('schedule_date', '>=', today())
        ->orderBy('schedule_date')
        ->orderBy('departure_time')
        ->take(5)
        ->get();

    $routes = \App\Models\Route::where('status', 'active')
        ->orderBy('name')
        ->get();

    return view('public.home', compact('upcomingSchedules', 'routes'));
})->name('home');

// Public employee registration application form
Route::get('/apply', [App\Http\Controllers\EmployeeRegistrationController::class, 'create'])
    ->name('employee.apply');
Route::post('/apply', [App\Http\Controllers\EmployeeRegistrationController::class, 'store']);
Route::get('/apply/success', [App\Http\Controllers\EmployeeRegistrationController::class, 'success'])
    ->name('registration.success');

// ══════════════════════════════════════════════════════════════
// AUTHENTICATED REDIRECT — sends each role to their dashboard
// ══════════════════════════════════════════════════════════════

Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'executive_officer' => redirect()->route('officer.dashboard'),
        'timekeeper' => redirect()->route('timekeeper.dashboard'),
        'storekeeper' => redirect()->route('storekeeper.dashboard'),
        'driver', 'conductor' => redirect()->route('driver.dashboard'),
        default => redirect()->route('employee.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// ══════════════════════════════════════════════════════════════
// PROFILE (all authenticated users)
// ══════════════════════════════════════════════════════════════

Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// ══════════════════════════════════════════════════════════════
// NOTIFICATIONS (all authenticated users)
// ══════════════════════════════════════════════════════════════

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::post('/notifications/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markRead'])
        ->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllRead'])
        ->name('notifications.read-all');
});

// ══════════════════════════════════════════════════════════════
// ADMIN DASHBOARD
// ══════════════════════════════════════════════════════════════

// Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
//     Route::get('/dashboard', function () {
//         return view('admin.dashboard', [
//             'totalEmployees'       => \App\Models\User::where('role', '!=', 'admin')->count(),
//             'activeBuses'          => 0,
//             'todaySchedules'       => 0,
//             'pendingRegistrations' => \App\Models\EmployeeRegistration::where('status', 'pending')->count(),
//             'todayRevenue'         => \App\Models\Booking::whereDate('booked_at', today())->where('payment_status', 'paid')->sum('amount'),
//             'totalBookings'        => \App\Models\Booking::where('payment_status', 'paid')->count(),
//         ]);
//     })->name('dashboard');
// });

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard', [
            'totalEmployees' => \App\Models\User::where('role', '!=', 'admin')->count(),
            'activeBuses' => \App\Models\Bus::where('status', 'active')->count(),
            'todaySchedules' => \App\Models\Schedule::whereDate('schedule_date', today())->where('status', 'active')->count(),
            'pendingRegistrations' => \App\Models\EmployeeRegistration::where('status', 'pending')->count(),
            'todayRevenue' => \App\Models\Booking::whereDate('booked_at', today())->where('payment_status', 'paid')->sum('amount'),
            'totalBookings' => \App\Models\Booking::where('payment_status', 'paid')->count(),
            'openBreakdowns' => \App\Models\BreakdownReport::whereIn(
                'status',
                ['pending', 'approved', 'in_progress']
            )->count(),
            'lowStockItems' => \App\Models\InventoryItem::lowStock()->count(),
            'totalInventory' => \App\Models\InventoryItem::count(),
            'recentBookings' => \App\Models\Booking::with(['schedule.route'])
                ->where('payment_status', 'paid')
                ->latest('booked_at')->take(6)->get(),
            'recentBreakdowns' => \App\Models\BreakdownReport::with(['bus', 'reportedBy'])
                ->latest()->take(5)->get(),
        ]);
    })->name('dashboard');
});

// ══════════════════════════════════════════════════════════════
// EXECUTIVE OFFICER DASHBOARD
// ══════════════════════════════════════════════════════════════

Route::middleware(['auth', 'role:executive_officer'])->prefix('officer')->name('officer.')->group(function () {
    Route::get('/dashboard', function () {
        return view('officer.dashboard', [
            'pendingRegistrations' => \App\Models\EmployeeRegistration::where('status', 'pending')->count(),
            'openBreakdowns' => \App\Models\BreakdownReport::whereIn(
                'status',
                ['pending', 'approved', 'in_progress']
            )->count(),
            'todaySchedules' => \App\Models\Schedule::where('status', 'active')
                ->whereDate('schedule_date', today())->count(),
            'unassignedSchedules' => \App\Models\Schedule::where('status', 'active')
                ->whereDate('schedule_date', '>=', today())
                ->where(fn($q) => $q->whereNull('driver_id')->orWhereNull('conductor_id'))
                ->count(),
            'totalBookings' => \App\Models\Booking::where('payment_status', 'paid')->count(),
            'monthRevenue' => \App\Models\Booking::where('payment_status', 'paid')
                ->whereMonth('booked_at', now()->month)
                ->whereYear('booked_at', now()->year)
                ->sum('amount'),
        ]);
    })->name('dashboard');
});

// ══════════════════════════════════════════════════════════════
// TIMEKEEPER DASHBOARD
// ══════════════════════════════════════════════════════════════

// Route::middleware(['auth', 'role:timekeeper'])->prefix('timekeeper')->name('timekeeper.')->group(function () {
//     Route::get('/dashboard', fn() => view('timekeeper.dashboard'))->name('dashboard');
// });

// ══════════════════════════════════════════════════════════════
// STOREKEEPER DASHBOARD
// ══════════════════════════════════════════════════════════════

// Route::middleware(['auth', 'role:storekeeper'])->prefix('storekeeper')->name('storekeeper.')->group(function () {
//     Route::get('/dashboard', fn() => view('storekeeper.dashboard'))->name('dashboard');
// });

// ══════════════════════════════════════════════════════════════
// DRIVER / CONDUCTOR DASHBOARD
// ══════════════════════════════════════════════════════════════

Route::middleware(['auth', 'role:driver,conductor'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DriverController::class, 'dashboard'])
        ->name('dashboard');
    Route::get('/schedule', [App\Http\Controllers\DriverController::class, 'schedule'])
        ->name('schedule');
});

// ══════════════════════════════════════════════════════════════
// GENERAL EMPLOYEE DASHBOARD
// ══════════════════════════════════════════════════════════════

Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', fn() => view('employee.dashboard'))->name('dashboard');
});

// ══════════════════════════════════════════════════════════════
// EMPLOYEE MANAGEMENT (admin + executive_officer)
// ══════════════════════════════════════════════════════════════

Route::middleware(['auth', 'role:admin,executive_officer'])->group(function () {

    Route::resource('employees', App\Http\Controllers\EmployeeController::class)
        ->except(['destroy']);
    Route::post('employees/{employee}/disable', [App\Http\Controllers\EmployeeController::class, 'disable'])
        ->name('employees.disable');
    Route::post('employees/{employee}/enable', [App\Http\Controllers\EmployeeController::class, 'enable'])
        ->name('employees.enable');

    Route::get('registrations', [App\Http\Controllers\EmployeeRegistrationController::class, 'index'])
        ->name('registrations.index');
    Route::get('registrations/{registration}', [App\Http\Controllers\EmployeeRegistrationController::class, 'show'])
        ->name('registrations.show');
    Route::post('registrations/{registration}/approve', [App\Http\Controllers\EmployeeRegistrationController::class, 'approve'])
        ->name('registrations.approve');
    Route::post('registrations/{registration}/reject', [App\Http\Controllers\EmployeeRegistrationController::class, 'reject'])
        ->name('registrations.reject');
});

// ══════════════════════════════════════════════════════════════
// PASSENGER AUTH ROUTES (uses passenger guard)
// ══════════════════════════════════════════════════════════════

// Unified register page (toggle: passenger account OR staff application)
Route::get('/register', [App\Http\Controllers\Passenger\PassengerAuthController::class, 'showRegister'])
    ->name('passenger.register')
    ->middleware('guest');
Route::post('/register', [App\Http\Controllers\Passenger\PassengerAuthController::class, 'register'])
    ->middleware('guest');

// Passenger login
Route::get('/passenger/login', [App\Http\Controllers\Passenger\PassengerAuthController::class, 'showLogin'])
    ->name('passenger.login')
    ->middleware('guest');
Route::post('/passenger/login', [App\Http\Controllers\Passenger\PassengerAuthController::class, 'login'])
    ->middleware('guest');

// Passenger logout
Route::post('/passenger/logout', [App\Http\Controllers\Passenger\PassengerAuthController::class, 'logout'])
    ->name('passenger.logout');

// ══════════════════════════════════════════════════════════════
// PASSENGER DASHBOARD (passenger guard required)
// ══════════════════════════════════════════════════════════════

Route::middleware('passenger')->prefix('passenger')->name('passenger.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Passenger\PassengerController::class, 'dashboard'])
        ->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Passenger\PassengerController::class, 'profile'])
        ->name('profile');
    Route::patch('/profile', [App\Http\Controllers\Passenger\PassengerController::class, 'updateProfile'])
        ->name('profile.update');
    Route::post('/avatar', [App\Http\Controllers\Passenger\PassengerController::class, 'uploadAvatar'])
        ->name('avatar');
    Route::get('/bookings', [App\Http\Controllers\Passenger\PassengerController::class, 'bookings'])
        ->name('bookings');
});

// ══════════════════════════════════════════════════════════════
// EMPLOYEE AVATAR UPLOAD (employee auth guard)
// ══════════════════════════════════════════════════════════════

Route::middleware('auth')->post('/account/avatar', [App\Http\Controllers\AvatarController::class, 'upload'])
    ->name('account.avatar');

// ── Public schedule view (no auth) ────────────────────────────────────────
Route::get('/schedules', [App\Http\Controllers\ScheduleController::class, 'publicIndex'])
    ->name('schedules.public');

// ── Bus management (admin + executive_officer) ────────────────────────────
Route::middleware(['auth', 'role:admin,executive_officer'])->group(function () {
    Route::resource('buses', App\Http\Controllers\BusController::class)->except(['destroy']);
    Route::post('buses/{bus}/status', [App\Http\Controllers\BusController::class, 'updateStatus'])
        ->name('buses.status');

    Route::resource('routes', App\Http\Controllers\RouteController::class)->except(['destroy']);
    Route::post('routes/{route}/toggle-status', [App\Http\Controllers\RouteController::class, 'toggleStatus'])
        ->name('routes.toggle-status');
});

// ── Schedule management (admin + executive_officer + timekeeper) ───────────
Route::middleware(['auth', 'role:admin,executive_officer,timekeeper'])->group(function () {
    Route::get('schedules/manage', [App\Http\Controllers\ScheduleController::class, 'index'])
        ->name('schedules.index');
    Route::get('schedules/create', [App\Http\Controllers\ScheduleController::class, 'create'])
        ->name('schedules.create');
    Route::post('schedules', [App\Http\Controllers\ScheduleController::class, 'store'])
        ->name('schedules.store');
    Route::get('schedules/{schedule}', [App\Http\Controllers\ScheduleController::class, 'show'])
        ->name('schedules.show');
    Route::get('schedules/{schedule}/edit', [App\Http\Controllers\ScheduleController::class, 'edit'])
        ->name('schedules.edit');
    Route::patch('schedules/{schedule}', [App\Http\Controllers\ScheduleController::class, 'update'])
        ->name('schedules.update');
    Route::get('api/calendar-events', [App\Http\Controllers\ScheduleController::class, 'calendarEvents'])
        ->name('schedules.calendar-events');
});

// ── Deactivate/activate schedules (admin + executive_officer only) ─────────
Route::middleware(['auth', 'role:admin,executive_officer'])->group(function () {
    Route::post('schedules/{schedule}/deactivate', [App\Http\Controllers\ScheduleController::class, 'deactivate'])
        ->name('schedules.deactivate');
    Route::post('schedules/{schedule}/activate', [App\Http\Controllers\ScheduleController::class, 'activate'])
        ->name('schedules.activate');
});


//admin dashboard routes found here, but moved to top of file to avoid merge conflicts with timekeeper/storekeeper dashboard routes

// ══════════════════════════════════════════════════════════════
// SEAT BOOKING — public, no auth required
// ══════════════════════════════════════════════════════════════

Route::prefix('book')->name('booking.')->group(function () {

    // Step 2: Visual seat picker
    Route::get('/seats/{schedule}', [App\Http\Controllers\BookingController::class, 'selectSeats'])
        ->name('select-seats');

    // Step 3: Passenger details
    // Accepts GET (browser refresh reads from session) and POST (form submission from seat picker)
    Route::match(['GET', 'POST'], '/details/{schedule}', [App\Http\Controllers\BookingController::class, 'passengerDetails'])
        ->name('passenger-details');

    // Step 4: Checkout
    Route::post('/checkout', [App\Http\Controllers\BookingController::class, 'checkout'])
        ->name('checkout');

    // Step 5: Stripe redirects back here after payment
    Route::get('/confirm', [App\Http\Controllers\BookingController::class, 'confirm'])
        ->name('confirm');

    // Step 6: Confirmation page
    Route::get('/confirmation', [App\Http\Controllers\BookingController::class, 'confirmation'])
        ->name('confirmation');

    // PDF receipt — accessible with booking reference
    Route::get('/receipt/{ref}', [App\Http\Controllers\BookingController::class, 'downloadReceipt'])
        ->name('receipt');
});

// ══════════════════════════════════════════════════════════════
// BOOKINGS ADMIN LIST
// ══════════════════════════════════════════════════════════════

Route::middleware(['auth', 'role:admin,executive_officer'])->group(function () {
    Route::get('/bookings', [App\Http\Controllers\BookingController::class, 'index'])
        ->name('bookings.index');
});

// ══════════════════════════════════════════════════════════════
// BREAKDOWN REPORTS
// ══════════════════════════════════════════════════════════════

// Driver/conductor: file a report
Route::middleware(['auth', 'role:driver,conductor'])->group(function () {
    Route::get('/breakdowns/create', [App\Http\Controllers\BreakdownReportController::class, 'create'])
        ->name('breakdowns.create');
    Route::post('/breakdowns', [App\Http\Controllers\BreakdownReportController::class, 'store'])
        ->name('breakdowns.store');
});

// Admin/officer/storekeeper: view breakdown reports list
Route::middleware(['auth', 'role:admin,executive_officer,storekeeper'])->group(function () {
    Route::get('/breakdowns', [App\Http\Controllers\BreakdownReportController::class, 'index'])
        ->name('breakdowns.index');
});

// Admin/officer: manage reports
Route::middleware(['auth', 'role:admin,executive_officer'])->group(function () {
    Route::post('/breakdowns/{breakdown}/approve', [App\Http\Controllers\BreakdownReportController::class, 'approve'])
        ->name('breakdowns.approve');
    Route::post('/breakdowns/{breakdown}/reject', [App\Http\Controllers\BreakdownReportController::class, 'reject'])
        ->name('breakdowns.reject');
    Route::post('/breakdowns/{breakdown}/respond', [App\Http\Controllers\BreakdownReportController::class, 'respond'])
        ->name('breakdowns.respond');
    Route::post('/breakdowns/{breakdown}/resolve', [App\Http\Controllers\BreakdownReportController::class, 'resolve'])
        ->name('breakdowns.resolve');
});

// Both admin/officer AND driver/conductor + storekeeper can view a report
Route::middleware(['auth', 'role:admin,executive_officer,driver,conductor,storekeeper'])->group(function () {
    Route::get('/breakdowns/{breakdown}', [App\Http\Controllers\BreakdownReportController::class, 'show'])
        ->name('breakdowns.show');
});

// ══════════════════════════════════════════════════════════════
// STOREKEEPER DASHBOARD
// ══════════════════════════════════════════════════════════════

Route::middleware(['auth', 'role:storekeeper'])->prefix('storekeeper')->name('storekeeper.')->group(function () {
    Route::get('/dashboard', function () {
        return view('storekeeper.dashboard', [
            'sparesNeeded' => \App\Models\BreakdownReport::where('response_action', 'spare_parts')
                ->whereIn('status', ['approved', 'in_progress'])
                ->count(),
            'totalItems' => \App\Models\InventoryItem::count(),
            'lowStockItems' => \App\Models\InventoryItem::lowStock()->count(),
            'recentReleases' => \App\Models\InventoryRelease::with(['inventoryItem', 'releasedBy'])
                ->latest()->take(5)->get(),
        ]);
    })->name('dashboard');

    Route::get('/breakdowns', [App\Http\Controllers\BreakdownReportController::class, 'storekeeperIndex'])
        ->name('breakdowns');
});

// ══════════════════════════════════════════════════════════════
// TIMEKEEPER DASHBOARD
// ══════════════════════════════════════════════════════════════

Route::middleware(['auth', 'role:timekeeper'])->prefix('timekeeper')->name('timekeeper.')->group(function () {
    Route::get('/dashboard', function () {
        $today = today();
        $weekEnd = today()->addDays(7);

        // Schedules missing a driver or conductor (for alert panel)
        $unassigned = \App\Models\Schedule::where('status', 'active')
            ->whereDate('schedule_date', '>=', $today)
            ->where(fn($q) => $q->whereNull('driver_id')->orWhereNull('conductor_id'))
            ->with(['route', 'bus'])
            ->orderBy('schedule_date')
            ->orderBy('departure_time')
            ->take(10)
            ->get();

        return view('timekeeper.dashboard', [
            'todaySchedules' => \App\Models\Schedule::where('status', 'active')
                ->whereDate('schedule_date', $today)->count(),
            'weekSchedules' => \App\Models\Schedule::where('status', 'active')
                ->whereBetween('schedule_date', [$today, $weekEnd])->count(),
            'unassignedCount' => $unassigned->count(),
            'unassignedSchedules' => $unassigned,
            'totalRosters' => \App\Models\DutyRoster::whereDate('duty_date', $today)->count(),
        ]);
    })->name('dashboard');
});

// ══════════════════════════════════════════════════════════════
// DUTY ROSTERS (timekeeper + admin + officer)
// ══════════════════════════════════════════════════════════════

Route::middleware(['auth', 'role:admin,executive_officer,timekeeper'])->group(function () {
    Route::resource('rosters', App\Http\Controllers\DutyRosterController::class)
        ->except(['edit', 'update', 'show']);
    Route::get('rosters/pdf', [App\Http\Controllers\DutyRosterController::class, 'downloadPdf'])
        ->name('rosters.pdf');
});

// ══════════════════════════════════════════════════════════════
// INVENTORY (storekeeper + admin + officer)
// ══════════════════════════════════════════════════════════════

Route::middleware(['auth', 'role:admin,executive_officer,storekeeper'])->group(function () {

    Route::resource('inventory', App\Http\Controllers\InventoryController::class)
        ->except(['destroy']);

    Route::post('inventory/{inventory}/release', [App\Http\Controllers\InventoryController::class, 'release'])
        ->name('inventory.release');

    Route::post('inventory/{inventory}/restock', [App\Http\Controllers\InventoryController::class, 'restock'])
        ->name('inventory.restock');

    Route::get('inventory-report/pdf', [App\Http\Controllers\InventoryController::class, 'downloadReport'])
        ->name('inventory.report');
});

// ══════════════════════════════════════════════════════════════
// ANALYTICS DATA ENDPOINTS (JSON — used by Chart.js)
// ══════════════════════════════════════════════════════════════

Route::middleware('auth')->prefix('analytics')->name('analytics.')->group(function () {
    Route::get('/admin', [App\Http\Controllers\AnalyticsController::class, 'adminData'])
        ->middleware('role:admin')->name('admin');
    Route::get('/officer', [App\Http\Controllers\AnalyticsController::class, 'officerData'])
        ->middleware('role:executive_officer')->name('officer');
    Route::get('/timekeeper', [App\Http\Controllers\AnalyticsController::class, 'timekeeperData'])
        ->middleware('role:timekeeper')->name('timekeeper');
    Route::get('/storekeeper', [App\Http\Controllers\AnalyticsController::class, 'storekeeperData'])
        ->middleware('role:storekeeper')->name('storekeeper');
});

// ── Audit log (admin only) ────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/activity-log', [App\Http\Controllers\ActivityLogController::class, 'index'])
        ->name('activity.log');
});

require __DIR__ . '/auth.php';