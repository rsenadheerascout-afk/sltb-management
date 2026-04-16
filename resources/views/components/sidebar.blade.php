@php
$role = auth()->user()->role;
$nav = [
    'admin' => [
        ['label' => 'Dashboard',       'route' => 'admin.dashboard'],
        ['label' => 'Employees',       'route' => 'employees.index'],
        ['label' => 'Registrations',   'route' => 'registrations.index'],
        ['label' => 'Buses',           'route' => 'buses.index'],
        ['label' => 'Routes',          'route' => 'routes.index'],
        ['label' => 'Schedules',       'route' => 'schedules.index'],
        ['label' => 'Bookings',        'route' => 'bookings.index'],
        ['label' => 'Breakdowns',      'route' => 'breakdowns.index'],
        ['label' => 'Inventory',       'route' => 'inventory.index'],
        ['label' => 'Messages',        'route' => 'chatify'],
    ],
    'executive_officer' => [
        ['label' => 'Dashboard',       'route' => 'officer.dashboard'],
        ['label' => 'Employees',       'route' => 'employees.index'],
        ['label' => 'Registrations',   'route' => 'registrations.index'],
        ['label' => 'Buses',           'route' => 'buses.index'],
        ['label' => 'Routes',          'route' => 'routes.index'],
        ['label' => 'Schedules',       'route' => 'schedules.index'],
        ['label' => 'Bookings',        'route' => 'bookings.index'],
        ['label' => 'Breakdowns',      'route' => 'breakdowns.index'],
        ['label' => 'Messages',        'route' => 'chatify'],
    ],
    'timekeeper' => [
        ['label' => 'Dashboard',       'route' => 'timekeeper.dashboard'],
        ['label' => 'Schedules',       'route' => 'schedules.index'],
        ['label' => 'Duty Rosters',    'route' => 'rosters.index'],
        ['label' => 'Messages',        'route' => 'chatify'],
    ],
    'storekeeper' => [
        ['label' => 'Dashboard',       'route' => 'storekeeper.dashboard'],
        ['label' => 'Inventory',       'route' => 'inventory.index'],
        ['label' => 'Breakdowns',      'route' => 'breakdowns.index'],
        ['label' => 'Spare Parts Jobs', 'route' => 'storekeeper.breakdowns'],
        ['label' => 'My Profile',      'route' => 'profile.edit'],
        ['label' => 'Messages',        'route' => 'chatify'],
    ],
    'driver' => [
        ['label' => 'Dashboard',       'route' => 'driver.dashboard'],
        ['label' => 'My Schedule',     'route' => 'driver.schedule'],
        ['label' => 'Report Breakdown','route' => 'breakdowns.create'],
        ['label' => 'Messages',        'route' => 'chatify'],
    ],
    'conductor' => [
        ['label' => 'Dashboard',       'route' => 'driver.dashboard'],
        ['label' => 'My Schedule',     'route' => 'driver.schedule'],
        ['label' => 'Report Breakdown','route' => 'breakdowns.create'],
        ['label' => 'Messages',        'route' => 'chatify'],
    ],
    'employee' => [
        ['label' => 'Dashboard',       'route' => 'employee.dashboard'],
        ['label' => 'My Profile',      'route' => 'profile.edit'],
        ['label' => 'Messages',        'route' => 'chatify'],
    ],
];
$links = $nav[$role] ?? $nav['employee'];
@endphp

@foreach($links as $link)
    @if(\Illuminate\Support\Facades\Route::has($link['route']))
        @php
            $routeParts = explode('.', $link['route']);
            $isActive = request()->routeIs($routeParts[0] . '.*') || request()->routeIs($link['route']);
        @endphp
        <a href="{{ route($link['route']) }}"
           class="flex items-center px-3 py-2 text-sm rounded-lg mb-0.5 transition-colors
                  {{ $isActive
                     ? 'bg-blue-50 text-blue-700 font-medium'
                     : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            {{ $link['label'] }}
        </a>
    @endif
@endforeach