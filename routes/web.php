<?php

use Illuminate\Support\Facades\Route;

// ══════════════════════════════════════════════════════════════
// PUBLIC ROUTES — accessible to everyone (passengers + guests)
// ══════════════════════════════════════════════════════════════

// Homepage — passenger-friendly landing page
Route::get('/', function () {
    return view('public.home');
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
    return match(auth()->user()->role) {
        'admin'              => redirect()->route('admin.dashboard'),
        'executive_officer'  => redirect()->route('officer.dashboard'),
        'timekeeper'         => redirect()->route('timekeeper.dashboard'),
        'storekeeper'        => redirect()->route('storekeeper.dashboard'),
        'driver', 'conductor'=> redirect()->route('driver.dashboard'),
        default              => redirect()->route('employee.dashboard'),
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

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard', [
            'totalEmployees'       => \App\Models\User::where('role', '!=', 'admin')->count(),
            'activeBuses'          => 0,
            'todaySchedules'       => 0,
            'pendingRegistrations' => \App\Models\EmployeeRegistration::where('status', 'pending')->count(),
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
        ]);
    })->name('dashboard');
});

// ══════════════════════════════════════════════════════════════
// TIMEKEEPER DASHBOARD
// ══════════════════════════════════════════════════════════════

Route::middleware(['auth', 'role:timekeeper'])->prefix('timekeeper')->name('timekeeper.')->group(function () {
    Route::get('/dashboard', fn() => view('timekeeper.dashboard'))->name('dashboard');
});

// ══════════════════════════════════════════════════════════════
// STOREKEEPER DASHBOARD
// ══════════════════════════════════════════════════════════════

Route::middleware(['auth', 'role:storekeeper'])->prefix('storekeeper')->name('storekeeper.')->group(function () {
    Route::get('/dashboard', fn() => view('storekeeper.dashboard'))->name('dashboard');
});

// ══════════════════════════════════════════════════════════════
// DRIVER / CONDUCTOR DASHBOARD
// ══════════════════════════════════════════════════════════════

Route::middleware(['auth', 'role:driver,conductor'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/dashboard', fn() => view('driver.dashboard'))->name('dashboard');
    Route::get('/schedule', fn() => view('driver.schedule'))->name('schedule');
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

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard', [
            'totalEmployees'    => \App\Models\User::where('role', '!=', 'admin')->count(),
            'activeBuses'       => \App\Models\Bus::where('status', 'active')->count(),
            'todaySchedules'    => \App\Models\Schedule::whereDate('schedule_date', today())->where('status', 'active')->count(),
            'pendingRegistrations' => \App\Models\EmployeeRegistration::where('status', 'pending')->count(),
        ]);
    })->name('dashboard');
});

require __DIR__.'/auth.php';