<x-dashboard-layout title="Add Inventory Item">
<div class="max-w-2xl">
    <a href="{{ route('inventory.index') }}"
       class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back to inventory</a>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-6">Add new inventory item</h2>

        <form method="POST" action="{{ route('inventory.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-2 gap-5">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Item name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="e.g. Engine Oil Filter, Brake Pad"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none
                                  @error('name') border-red-400 @enderror">
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Category <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="category" value="{{ old('category') }}"
                           placeholder="e.g. Engine Parts, Tyres, Fluids"
                           list="category-list"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none
                                  @error('category') border-red-400 @enderror">
                    <datalist id="category-list">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                    </datalist>
                    @error('category')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Unit of measure <span class="text-red-400">*</span>
                    </label>
                    <select name="unit"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                   focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @foreach(['pcs'=>'Pieces (pcs)','kg'=>'Kilograms (kg)','g'=>'Grams (g)',
                                  'litres'=>'Litres','ml'=>'Millilitres (ml)',
                                  'metres'=>'Metres','sets'=>'Sets'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('unit', 'pcs') === $val)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Opening quantity <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="quantity" value="{{ old('quantity', 0) }}"
                           min="0" step="0.01"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none
                                  @error('quantity') border-red-400 @enderror">
                    @error('quantity')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Low stock threshold <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', 5) }}"
                           min="0" step="0.01"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none
                                  @error('low_stock_threshold') border-red-400 @enderror">
                    <p class="text-xs text-gray-400 mt-1">Alert fires when quantity drops to this level.</p>
                    @error('low_stock_threshold')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit price (LKR)</label>
                    <input type="number" name="unit_price" value="{{ old('unit_price') }}"
                           min="0" step="0.01" placeholder="Optional"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                    <input type="text" name="supplier" value="{{ old('supplier') }}"
                           placeholder="Optional"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="2"
                          class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm
                                 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('notes') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('inventory.index') }}"
                   class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700">
                    Add to Inventory
                </button>
            </div>
        </form>
    </div>
</div>
</x-dashboard-layout>