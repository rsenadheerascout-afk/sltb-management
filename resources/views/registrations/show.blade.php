<x-dashboard-layout title="Review Application">
    <div class="max-w-2xl">
        <a href="{{ route('registrations.index') }}"
            class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Back</a>
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-semibold text-gray-800">{{ $registration->name }}</h2>
                @php $sc = ['pending' => 'bg-amber-50 text-amber-700', 'approved' => 'bg-green-50 text-green-700', 'rejected' => 'bg-red-50 text-red-600']; @endphp
                <span
                    class="px-3 py-1 rounded-full text-xs {{ $sc[$registration->status] }}">{{ ucfirst($registration->status) }}</span>
            </div>
            <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                <div>
                    <dt class="text-gray-400">Email</dt>
                    <dd class="text-gray-800 mt-0.5">{{ $registration->email }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Phone</dt>
                    <dd class="text-gray-800 mt-0.5">{{ $registration->phone }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">NIC</dt>
                    <dd class="text-gray-800 mt-0.5">{{ $registration->nic }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Applied role</dt>
                    <dd class="text-gray-800 mt-0.5">{{ ucfirst(str_replace('_', ' ', $registration->applied_role)) }}
                    </dd>
                </div>
                <div class="col-span-2">
                    <dt class="text-gray-400">Address</dt>
                    <dd class="text-gray-800 mt-0.5">{{ $registration->address }}</dd>
                </div>
                @if($registration->reviewer)
                    <div>
                        <dt class="text-gray-400">Reviewed by</dt>
                        <dd class="text-gray-800 mt-0.5">{{ $registration->reviewer->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Review notes</dt>
                        <dd class="text-gray-800 mt-0.5">{{ $registration->review_notes }}</dd>
                    </div>
                @endif
            </dl>
        </div>
        @if($registration->isPending())
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Decision</h3>
                <div class="grid grid-cols-2 gap-4">
                    <form method="POST" action="{{ route('registrations.approve', $registration) }}">
                        @csrf
                        <textarea name="notes" placeholder="Approval notes (optional)" rows="2"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                        <button type="submit"
                            class="w-full py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                            Approve &amp; Create Account
                        </button>
                    </form>
                    <form method="POST" action="{{ route('registrations.reject', $registration) }}">
                        @csrf
                        <textarea name="notes" placeholder="Reason for rejection (required)" rows="2"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-red-500 @error('notes') border-red-400 @enderror"></textarea>
                        @error('notes')<p class="text-xs text-red-500 -mt-2 mb-2">{{ $message }}</p>@enderror
                        <button type="submit"
                            class="w-full py-2 bg-red-50 text-red-600 text-sm rounded-lg hover:bg-red-100 border border-red-200">
                            Reject Application
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-dashboard-layout>