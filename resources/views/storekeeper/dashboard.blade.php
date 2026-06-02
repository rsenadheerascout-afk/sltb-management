<x-dashboard-layout title="Storekeeper Dashboard">
<x-charts />

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
    <a href="{{ $sparesNeeded > 0 ? route('storekeeper.breakdowns') : '#' }}"
       class="bg-white rounded-xl border {{ $sparesNeeded > 0 ? 'border-amber-300 bg-amber-50' : 'border-gray-200' }}
              p-5 block hover:shadow-sm transition">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Spare Parts Jobs</p>
        <p class="text-3xl font-bold {{ $sparesNeeded > 0 ? 'text-amber-600' : 'text-gray-800' }} mt-2">
            {{ $sparesNeeded }}
        </p>
        @if($sparesNeeded > 0)
        <p class="text-xs text-amber-600 mt-1">Requires attention</p>
        @endif
    </a>
    <a href="{{ route('inventory.index') }}"
       class="bg-white rounded-xl border border-gray-200 p-5 block hover:shadow-sm transition">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Inventory Items</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalItems }}</p>
    </a>
    <a href="{{ route('inventory.index', ['stock' => 'low']) }}"
       class="bg-white rounded-xl border {{ $lowStockItems > 0 ? 'border-red-200 bg-red-50' : 'border-gray-200' }}
              p-5 block hover:shadow-sm transition">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Low Stock Alerts</p>
        <p class="text-3xl font-bold {{ $lowStockItems > 0 ? 'text-red-600' : 'text-gray-800' }} mt-2">
            {{ $lowStockItems }}
        </p>
        @if($lowStockItems > 0)
        <p class="text-xs text-red-600 mt-1">Needs restocking</p>
        @endif
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Recent releases --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-800">Recent releases</h3>
            <a href="{{ route('inventory.index') }}"
               class="text-xs text-blue-600 hover:underline">View inventory</a>
        </div>
        @if($recentReleases->isEmpty())
        <div class="px-5 py-8 text-center">
            <p class="text-sm text-gray-400">No stock releases yet.</p>
        </div>
        @else
        <div class="divide-y divide-gray-100">
            @foreach($recentReleases as $release)
            <div class="px-5 py-3 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $release->inventoryItem->name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $release->reason }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-red-600">
                        −{{ number_format($release->quantity_released, 2) }}
                        {{ $release->inventoryItem->unit }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $release->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Quick actions --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Quick actions</h3>
        <div class="space-y-2">
            <a href="{{ route('inventory.create') }}"
               class="flex items-center gap-2 text-sm text-blue-600 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add new inventory item
            </a>
            <a href="{{ route('inventory.index', ['stock' => 'low']) }}"
               class="flex items-center gap-2 text-sm text-amber-600 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667
                             1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34
                             16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                View low stock items
            </a>
            <a href="{{ route('inventory.report') }}"
               class="flex items-center gap-2 text-sm text-gray-600 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download inventory PDF
            </a>
            <a href="{{ route('storekeeper.breakdowns') }}"
               class="flex items-center gap-2 text-sm text-gray-600 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                View spare parts jobs
            </a>
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-2 text-sm text-gray-600 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                My profile
            </a>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mt-5">

    {{-- Stock by category bar --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Stock Levels by Category</h3>
        <div style="height:220px;position:relative;">
            <canvas id="categoryStockChart"></canvas>
        </div>
    </div>

    {{-- Monthly releases line --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Monthly Stock Releases</h3>
        <div style="height:220px;position:relative;">
            <canvas id="releasesChart"></canvas>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('{{ route("analytics.storekeeper") }}')
        .then(r => r.json())
        .then(data => {
            // Stock by category
            const cs = data.category_stock;
            renderChart('categoryStockChart', {
                type: 'bar',
                data: {
                    labels: cs.map(c => c.category),
                    datasets: [
                        {
                            label: 'Total qty',
                            data: cs.map(c => parseFloat(c.total_qty)),
                            backgroundColor: PALETTE.teal.bg,
                            borderColor: PALETTE.teal.border,
                            borderRadius: 4,
                            borderWidth: 1,
                        },
                        {
                            label: 'Low stock',
                            data: cs.map(c => c.low_count),
                            backgroundColor: PALETTE.amber.bg,
                            borderColor: PALETTE.amber.border,
                            borderRadius: 4,
                            borderWidth: 1,
                        }
                    ]
                },
                options: {
                    ...barOptions(),
                    plugins: {
                        legend: { display: true, position: 'top' },
                    }
                }
            });

            // Monthly releases
            const mr = data.monthly_releases;
            renderChart('releasesChart', {
                type: 'line',
                data: {
                    labels: mr.map(r => r.label),
                    datasets: [{
                        label: 'Units released',
                        data: mr.map(r => r.total),
                        backgroundColor: 'rgba(239,68,68,0.1)',
                        borderColor: PALETTE.red.border,
                        fill: true,
                    }]
                },
                options: lineOptions('Units released')
            });
        });
});
</script>

</x-dashboard-layout>