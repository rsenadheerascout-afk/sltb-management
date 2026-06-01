<x-dashboard-layout title="Inventory">
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-semibold text-gray-800">Inventory</h2>
        <p class="text-sm text-gray-500">
            {{ $items->total() }} items
            @if($lowCount > 0)
            &middot;
            <span class="text-amber-600 font-medium">{{ $lowCount }} low stock</span>
            @endif
        </p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('inventory.report') }}"
           class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            PDF Report
        </a>
        <a href="{{ route('inventory.create') }}"
           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
            + Add Item
        </a>
    </div>
</div>

{{-- Low stock alert banner --}}
@if($lowCount > 0)
<div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 mb-5 flex items-center gap-3">
    <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667
                 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34
                 16c-.77 1.333.192 3 1.732 3z"/>
    </svg>
    <div>
        <p class="text-sm font-semibold text-amber-800">{{ $lowCount }} item{{ $lowCount > 1 ? 's' : '' }} need restocking</p>
        <p class="text-xs text-amber-600 mt-0.5">These items are at or below their low stock threshold.</p>
    </div>
    <a href="{{ request()->fullUrlWithQuery(['stock' => 'low']) }}"
       class="ml-auto text-xs text-amber-700 font-medium hover:underline flex-shrink-0">
        Show only low stock &rarr;
    </a>
</div>
@endif

{{-- Filters --}}
<form method="GET" class="flex gap-3 mb-5 flex-wrap">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Search name or category..."
           class="flex-1 min-w-48 px-3 py-2 border border-gray-200 rounded-lg text-sm
                  focus:ring-2 focus:ring-blue-500 focus:outline-none">

    <select name="category" class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
        <option value="">All categories</option>
        @foreach($categories as $cat)
            <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
        @endforeach
    </select>

    <select name="stock" class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
        <option value="">All stock levels</option>
        <option value="low" @selected(request('stock')==='low')>Low / Critical</option>
        <option value="out" @selected(request('stock')==='out')>Out of stock</option>
    </select>

    <button type="submit"
            class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg">
        Filter
    </button>
    <a href="{{ route('inventory.index') }}"
       class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50">
        Reset
    </a>
</form>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Item</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Category</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">In stock</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Unit</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Threshold</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Unit price</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Status</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($items as $item)
            <tr class="hover:bg-gray-50 {{ $item->isLowStock() ? 'bg-amber-50/40' : '' }}">
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-800">{{ $item->name }}</p>
                    @if($item->supplier)
                    <p class="text-xs text-gray-400">{{ $item->supplier }}</p>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs">
                        {{ $item->category }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <span class="font-semibold {{ $item->isLowStock() ? 'text-amber-700' : 'text-gray-800' }}">
                        {{ number_format($item->quantity, 2) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $item->unit }}</td>
                <td class="px-4 py-3 text-gray-500">{{ number_format($item->low_stock_threshold, 2) }}</td>
                <td class="px-4 py-3 text-gray-600">
                    {{ $item->unit_price ? 'LKR ' . number_format($item->unit_price, 2) : '—' }}
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded text-xs {{ $item->stock_badge_color }}">
                        {{ $item->stock_badge_label }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-3">
                        <a href="{{ route('inventory.show', $item) }}"
                           class="text-xs text-blue-600 hover:underline">View</a>
                        <a href="{{ route('inventory.edit', $item) }}"
                           class="text-xs text-gray-600 hover:underline">Edit</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-4 py-12 text-center text-gray-400 text-sm">
                    No inventory items yet.
                    <a href="{{ route('inventory.create') }}" class="text-blue-600 hover:underline ml-1">
                        Add the first item
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $items->links() }}</div>
</x-dashboard-layout>