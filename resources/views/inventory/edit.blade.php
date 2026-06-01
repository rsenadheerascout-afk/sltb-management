<x-dashboard-layout title="Edit Inventory Item">
<div class="max-w-2xl">
    <a href="{{ route('inventory.show', $inventory) }}"
       class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back</a>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-6">Edit — {{ $inventory->name }}</h2>

        <form method="POST" action="{{ route('inventory.update', $inventory) }}" class="space-y-5">
            @csrf @method('PATCH')

            <div class="grid grid-cols-2 gap-5">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item name</label>
                    <input type="text" name="name" value="{{ old('name', $inventory->name) }}"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <input type="text" name="category" value="{{ old('category', $inventory->category) }}"
                           list="category-list"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <datalist id="category-list">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                    </datalist>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit of measure</label>
                    <select name="unit"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                   focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @foreach(['pcs'=>'Pieces (pcs)','kg'=>'Kilograms (kg)','g'=>'Grams (g)',
                                  'litres'=>'Litres','ml'=>'Millilitres (ml)',
                                  'metres'=>'Metres','sets'=>'Sets'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('unit', $inventory->unit) === $val)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current quantity</label>
                    <input type="number" name="quantity" value="{{ old('quantity', $inventory->quantity) }}"
                           min="0" step="0.01"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <p class="text-xs text-gray-400 mt-1">
                        Use the Release or Restock actions on the item page for tracked changes.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Low stock threshold</label>
                    <input type="number" name="low_stock_threshold"
                           value="{{ old('low_stock_threshold', $inventory->low_stock_threshold) }}"
                           min="0" step="0.01"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit price (LKR)</label>
                    <input type="number" name="unit_price"
                           value="{{ old('unit_price', $inventory->unit_price) }}"
                           min="0" step="0.01"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                    <input type="text" name="supplier" value="{{ old('supplier', $inventory->supplier) }}"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="2"
                          class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('notes', $inventory->notes) }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('inventory.show', $inventory) }}"
                   class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700">
                    Save changes
                </button>
            </div>
        </form>
    </div>
</div>
</x-dashboard-layout>