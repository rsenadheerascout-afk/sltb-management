<x-dashboard-layout title="Executive Officer Dashboard">
<x-charts />

{{-- Stat cards --}}
<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
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
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Today's Schedules</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $todaySchedules }}</p>
    </div>
    <a href="{{ route('schedules.index') }}"
       class="bg-white rounded-xl border {{ $unassignedSchedules > 0 ? 'border-amber-300 bg-amber-50' : 'border-gray-200' }} p-5 block hover:shadow-sm transition">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Unassigned Schedules</p>
        <p class="text-3xl font-bold {{ $unassignedSchedules > 0 ? 'text-amber-600' : 'text-gray-800' }} mt-2">{{ $unassignedSchedules }}</p>
    </a>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Month Revenue</p>
        <p class="text-xl font-bold text-gray-800 mt-2">LKR {{ number_format($monthRevenue, 0) }}</p>
    </div>
    <a href="{{ route('bookings.index') }}"
       class="bg-white rounded-xl border border-gray-200 p-5 block hover:shadow-sm transition">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Total Bookings</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalBookings }}</p>
    </a>
</div>

{{-- Charts --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Monthly Revenue</h3>
        <div style="height:220px;position:relative;">
            <canvas id="officerRevenueChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Breakdowns by Status</h3>
        <div style="height:220px;position:relative;">
            <canvas id="officerBreakdownChart"></canvas>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('{{ route("analytics.officer") }}')
        .then(r => r.json())
        .then(data => {
            const rev = data.monthly_revenue;
            renderChart('officerRevenueChart', {
                type: 'bar',
                data: {
                    labels: rev.map(r => r.month),
                    datasets: [{
                        data: rev.map(r => r.revenue),
                        backgroundColor: PALETTE.teal.bg,
                        borderColor: PALETTE.teal.border,
                        borderRadius: 5,
                        borderWidth: 1,
                    }]
                },
                options: barOptions('LKR', true)
            });

            const bd = data.breakdown_status;
            const bdColors = {
                pending:'#F59E0B',approved:'#3B82F6',rejected:'#9CA3AF',
                in_progress:'#8B5CF6',resolved:'#22C55E'
            };
            renderChart('officerBreakdownChart', {
                type: 'doughnut',
                data: {
                    labels: Object.keys(bd).map(s => s.replace('_',' ')),
                    datasets: [{
                        data: Object.values(bd),
                        backgroundColor: Object.keys(bd).map(s => bdColors[s] ?? '#9CA3AF'),
                        borderWidth: 1,
                    }]
                },
                options: doughnutOptions('bottom')
            });
        });
});
</script>
</x-dashboard-layout>