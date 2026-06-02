<x-dashboard-layout title="Admin Dashboard">
<x-charts />

{{-- ── STAT CARDS ──────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Total Employees</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalEmployees }}</p>
        <a href="{{ route('employees.index') }}" class="text-xs text-blue-600 hover:underline mt-1 inline-block">Manage &rarr;</a>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Active Buses</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $activeBuses }}</p>
        <a href="{{ route('buses.index') }}" class="text-xs text-blue-600 hover:underline mt-1 inline-block">View fleet &rarr;</a>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Today's Schedules</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $todaySchedules }}</p>
        <a href="{{ route('schedules.index') }}" class="text-xs text-blue-600 hover:underline mt-1 inline-block">View &rarr;</a>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Today's Revenue</p>
        <p class="text-2xl font-bold text-gray-800 mt-2">LKR {{ number_format($todayRevenue, 0) }}</p>
        <a href="{{ route('bookings.index') }}" class="text-xs text-blue-600 hover:underline mt-1 inline-block">Bookings &rarr;</a>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Total Bookings</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalBookings }}</p>
    </div>
    <a href="{{ route('registrations.index') }}"
       class="bg-white rounded-xl border {{ $pendingRegistrations > 0 ? 'border-amber-300 bg-amber-50' : 'border-gray-200' }} p-5 block hover:shadow-sm transition">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Pending Applications</p>
        <p class="text-3xl font-bold {{ $pendingRegistrations > 0 ? 'text-amber-600' : 'text-gray-800' }} mt-2">{{ $pendingRegistrations }}</p>
    </a>
    <a href="{{ route('breakdowns.index') }}"
       class="bg-white rounded-xl border {{ $openBreakdowns > 0 ? 'border-red-200 bg-red-50' : 'border-gray-200' }} p-5 block hover:shadow-sm transition">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Open Breakdowns</p>
        <p class="text-3xl font-bold {{ $openBreakdowns > 0 ? 'text-red-600' : 'text-gray-800' }} mt-2">{{ $openBreakdowns }}</p>
    </a>
    <a href="{{ route('inventory.index', ['stock' => 'low']) }}"
       class="bg-white rounded-xl border {{ $lowStockItems > 0 ? 'border-amber-300 bg-amber-50' : 'border-gray-200' }} p-5 block hover:shadow-sm transition">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Low Stock Items</p>
        <p class="text-3xl font-bold {{ $lowStockItems > 0 ? 'text-amber-600' : 'text-gray-800' }} mt-2">{{ $lowStockItems }}</p>
    </a>
</div>

{{-- ── CHARTS ROW 1 ────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">

    {{-- Monthly revenue bar chart --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Monthly Revenue</h3>
                <p class="text-xs text-gray-400">{{ now()->year }} — paid bookings</p>
            </div>
            <span id="annual-total" class="text-xs font-semibold text-blue-700 bg-blue-50 px-2 py-1 rounded-lg"></span>
        </div>
        <div style="height:220px; position:relative;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Breakdown status doughnut --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Breakdowns by Status</h3>
        <div style="height:220px; position:relative;">
            <canvas id="breakdownChart"></canvas>
        </div>
    </div>
</div>

{{-- ── CHARTS ROW 2 ────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">

    {{-- Monthly booking count line chart --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Monthly Booking Volume</h3>
        <div style="height:200px; position:relative;">
            <canvas id="bookingCountChart"></canvas>
        </div>
    </div>

    {{-- Bus status doughnut --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Fleet Status</h3>
        <div style="height:200px; position:relative;">
            <canvas id="busStatusChart"></canvas>
        </div>
    </div>
</div>

{{-- ── TABLES ROW ───────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Recent bookings --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-800">Recent Bookings</h3>
            <a href="{{ route('bookings.index') }}" class="text-xs text-blue-600 hover:underline">View all</a>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-2 text-left text-xs text-gray-500 font-medium">Ref</th>
                <th class="px-4 py-2 text-left text-xs text-gray-500 font-medium">Passenger</th>
                <th class="px-4 py-2 text-left text-xs text-gray-500 font-medium">Route</th>
                <th class="px-4 py-2 text-left text-xs text-gray-500 font-medium">Amount</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentBookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 font-mono text-xs text-blue-700">{{ $booking->booking_ref }}</td>
                    <td class="px-4 py-2 text-gray-700 text-xs">{{ $booking->passenger_name }}</td>
                    <td class="px-4 py-2 text-gray-600 text-xs">{{ $booking->schedule->route->name ?? '—' }}</td>
                    <td class="px-4 py-2 text-gray-700 text-xs font-medium">LKR {{ number_format($booking->amount, 0) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400 text-xs">No bookings yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Recent breakdowns --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-800">Recent Breakdowns</h3>
            <a href="{{ route('breakdowns.index') }}" class="text-xs text-blue-600 hover:underline">View all</a>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-2 text-left text-xs text-gray-500 font-medium">Bus</th>
                <th class="px-4 py-2 text-left text-xs text-gray-500 font-medium">Reported by</th>
                <th class="px-4 py-2 text-left text-xs text-gray-500 font-medium">Time</th>
                <th class="px-4 py-2 text-left text-xs text-gray-500 font-medium">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentBreakdowns as $bd)
                <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('breakdowns.show', $bd) }}'">
                    <td class="px-4 py-2 font-mono text-xs font-semibold text-blue-700">{{ $bd->bus->depot_reg_no }}</td>
                    <td class="px-4 py-2 text-gray-700 text-xs">{{ $bd->reportedBy->name }}</td>
                    <td class="px-4 py-2 text-gray-500 text-xs">{{ $bd->created_at->diffForHumans() }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-0.5 rounded text-xs {{ $bd->status_color }}">
                            {{ ucfirst(str_replace('_',' ',$bd->status)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400 text-xs">No breakdowns reported.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('{{ route("analytics.admin") }}')
        .then(r => r.json())
        .then(data => {

            // Monthly revenue bar chart
            const rev = data.monthly_revenue;
            renderChart('revenueChart', {
                type: 'bar',
                data: {
                    labels: rev.map(r => r.month),
                    datasets: [{
                        label: 'Revenue (LKR)',
                        data: rev.map(r => r.revenue),
                        backgroundColor: PALETTE.blue.bg,
                        borderColor: PALETTE.blue.border,
                        borderWidth: 1,
                        borderRadius: 5,
                    }]
                },
                options: barOptions('LKR', true)
            });

            // Annual total badge
            const annualTotal = rev.reduce((s, r) => s + r.revenue, 0);
            document.getElementById('annual-total').textContent =
                'Total: LKR ' + annualTotal.toLocaleString();

            // Booking count line chart
            const bc = data.booking_counts;
            renderChart('bookingCountChart', {
                type: 'line',
                data: {
                    labels: bc.map(r => r.month),
                    datasets: [{
                        label: 'Bookings',
                        data: bc.map(r => r.count),
                        backgroundColor: 'rgba(37,99,235,0.1)',
                        borderColor: PALETTE.blue.border,
                        fill: true,
                    }]
                },
                options: lineOptions('Bookings')
            });

            // Breakdown status doughnut
            const bdStatus = data.breakdown_status;
            const bdLabels  = Object.keys(bdStatus).map(s => s.replace('_',' '));
            const bdColors  = {
                pending:     PALETTE.amber,
                approved:    PALETTE.blue,
                rejected:    PALETTE.gray,
                in_progress: PALETTE.purple,
                resolved:    PALETTE.green,
            };
            renderChart('breakdownChart', {
                type: 'doughnut',
                data: {
                    labels: bdLabels,
                    datasets: [{
                        data: Object.values(bdStatus),
                        backgroundColor: Object.keys(bdStatus).map(s => bdColors[s]?.bg ?? PALETTE.gray.bg),
                        borderColor: Object.keys(bdStatus).map(s => bdColors[s]?.border ?? PALETTE.gray.border),
                        borderWidth: 1,
                    }]
                },
                options: doughnutOptions('bottom')
            });

            // Bus status doughnut
            const bStatus  = data.bus_status;
            const bColors  = {
                active:       PALETTE.green,
                needs_repair: PALETTE.amber,
                under_repair: PALETTE.orange,
                maintenance:  PALETTE.blue,
                condemned:    PALETTE.red,
            };
            renderChart('busStatusChart', {
                type: 'doughnut',
                data: {
                    labels: Object.keys(bStatus).map(s => s.replace('_',' ')),
                    datasets: [{
                        data: Object.values(bStatus),
                        backgroundColor: Object.keys(bStatus).map(s => bColors[s]?.bg ?? PALETTE.gray.bg),
                        borderColor: Object.keys(bStatus).map(s => bColors[s]?.border ?? PALETTE.gray.border),
                        borderWidth: 1,
                    }]
                },
                options: doughnutOptions('bottom')
            });
        })
        .catch(e => console.error('Analytics fetch failed:', e));
});
</script>
</x-dashboard-layout>