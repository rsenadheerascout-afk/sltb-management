<x-dashboard-layout title="Item Details">
<div class="max-w-3xl">
    <a href="{{ route('inventory.index') }}"
       class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back to inventory</a>

    {{-- Item header --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
        <div class="flex items-start justify-between mb-5">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $inventory->name }}</h2>
                <p class="text-sm text-gray-400 mt-0.5">{{ $inventory->category }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs {{ $inventory->stock_badge_color }}">
                    {{ $inventory->stock_badge_label }}
                </span>
                <a href="{{ route('inventory.edit', $inventory) }}"
                   class="px-3 py-1.5 border border-gray-200 text-sm rounded-lg hover:bg-gray-50">
                    Edit
                </a>
            </div>
        </div>

        <dl class="grid grid-cols-3 gap-x-6 gap-y-4 text-sm">
            <div>
                <dt class="text-gray-400 text-xs">Current stock</dt>
                <dd class="text-2xl font-bold {{ $inventory->isLowStock() ? 'text-amber-600' : 'text-gray-800' }} mt-0.5">
                    {{ number_format($inventory->quantity, 2) }}
                    <span class="text-sm font-normal text-gray-400">{{ $inventory->unit }}</span>
                </dd>
            </div>
            <div>
                <dt class="text-gray-400 text-xs">Low stock threshold</dt>
                <dd class="font-medium text-gray-800 mt-0.5">
                    {{ number_format($inventory->low_stock_threshold, 2) }} {{ $inventory->unit }}
                </dd>
            </div>
            <div>
                <dt class="text-gray-400 text-xs">Unit price</dt>
                <dd class="font-medium text-gray-800 mt-0.5">
                    {{ $inventory->unit_price ? 'LKR ' . number_format($inventory->unit_price, 2) : '—' }}
                </dd>
            </div>
            @if($inventory->supplier)
            <div>
                <dt class="text-gray-400 text-xs">Supplier</dt>
                <dd class="text-gray-700 mt-0.5">{{ $inventory->supplier }}</dd>
            </div>
            @endif
            @if($inventory->notes)
            <div class="col-span-3">
                <dt class="text-gray-400 text-xs">Notes</dt>
                <dd class="text-gray-600 mt-0.5">{{ $inventory->notes }}</dd>
            </div>
            @endif
        </dl>
    </div>

    {{-- Release stock panel --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4" x-data="{ showRelease: false, showRestock: false }">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Stock actions</h3>
        <div class="flex gap-3">
            <button type="button" @click="showRelease = !showRelease; showRestock = false"
                    :class="showRelease ? 'bg-red-600 text-white border-red-600' : 'border-gray-200 text-gray-700 hover:bg-gray-50'"
                    class="px-4 py-2 border text-sm font-medium rounded-lg transition-colors">
                Release stock
            </button>
            <button type="button" @click="showRestock = !showRestock; showRelease = false"
                    :class="showRestock ? 'bg-green-600 text-white border-green-600' : 'border-gray-200 text-gray-700 hover:bg-gray-50'"
                    class="px-4 py-2 border text-sm font-medium rounded-lg transition-colors">
                Restock / add stock
            </button>
        </div>

        {{-- Release form --}}
        <div x-show="showRelease" x-transition class="mt-5 border-t border-gray-100 pt-5">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Release stock</h4>
            <form method="POST" action="{{ route('inventory.release', $inventory) }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Quantity to release <span class="text-red-400">*</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="quantity_released"
                                   value="{{ old('quantity_released') }}"
                                   min="0.01" step="0.01"
                                   max="{{ $inventory->quantity }}"
                                   placeholder="0.00"
                                   class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm
                                          focus:ring-2 focus:ring-blue-500 focus:outline-none
                                          @error('quantity_released') border-red-400 @enderror">
                            <span class="text-sm text-gray-500">{{ $inventory->unit }}</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">
                            Available: {{ number_format($inventory->quantity, 2) }} {{ $inventory->unit }}
                        </p>
                        @error('quantity_released')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Linked breakdown report
                        </label>
                        <select name="breakdown_report_id"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm
                                       focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">None</option>
                            @foreach(\App\Models\BreakdownReport::where('response_action','spare_parts')
                                ->whereIn('status',['approved','in_progress'])
                                ->with('bus')->get() as $br)
                                <option value="{{ $br->id }}">
                                    #{{ $br->id }} — Bus {{ $br->bus->depot_reg_no }}
                                    ({{ $br->created_at->format('d M Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Reason <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="reason" value="{{ old('reason') }}"
                           placeholder="e.g. Brake pad replacement for breakdown #12"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none
                                  @error('reason') border-red-400 @enderror">
                    @error('reason')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="2"
                              class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm
                                     focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('notes') }}</textarea>
                </div>
                <button type="submit"
                        class="px-5 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700">
                    Confirm Release
                </button>
            </form>
        </div>

        {{-- Restock form --}}
        <div x-show="showRestock" x-transition class="mt-5 border-t border-gray-100 pt-5">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Add stock</h4>
            <form method="POST" action="{{ route('inventory.restock', $inventory) }}" class="space-y-4">
                @csrf
                <div class="flex items-end gap-3">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Quantity to add <span class="text-red-400">*</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="quantity_added"
                                   min="0.01" step="0.01" placeholder="0.00"
                                   class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm
                                          focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <span class="text-sm text-gray-500">{{ $inventory->unit }}</span>
                        </div>
                    </div>
                    <button type="submit"
                            class="px-5 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700">
                        Add Stock
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Release history --}}
    @if($inventory->releases->count())
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">Release history</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs text-gray-500 font-medium">Date</th>
                    <th class="px-4 py-3 text-left text-xs text-gray-500 font-medium">Quantity</th>
                    <th class="px-4 py-3 text-left text-xs text-gray-500 font-medium">Reason</th>
                    <th class="px-4 py-3 text-left text-xs text-gray-500 font-medium">Breakdown</th>
                    <th class="px-4 py-3 text-left text-xs text-gray-500 font-medium">Released by</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($inventory->releases->sortByDesc('created_at') as $release)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        {{ $release->created_at->format('d M Y, h:i A') }}
                    </td>
                    <td class="px-4 py-3 font-semibold text-red-600">
                        −{{ number_format($release->quantity_released, 2) }} {{ $inventory->unit }}
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ $release->reason }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        @if($release->breakdownReport)
                            <a href="{{ route('breakdowns.show', $release->breakdownReport) }}"
                               class="text-blue-600 hover:underline">
                                Bus {{ $release->breakdownReport->bus->depot_reg_no }}
                            </a>
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $release->releasedBy->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
</x-dashboard-layout>