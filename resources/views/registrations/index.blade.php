<x-dashboard-layout title="Registration Applications">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Registration Applications</h2>
            <p class="text-sm text-gray-500">{{ $registrations->total() }} total</p>
        </div>
    </div>
    <form method="GET" class="flex gap-3 mb-6">
        <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
            <option value="">All statuses</option>
            <option value="pending" @selected(request('status') === 'pending')>Pending</option>
            <option value="approved" @selected(request('status') === 'approved')>Approved</option>
            <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg">Filter</button>
    </form>
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Applicant</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Applied role</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">NIC</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Submitted</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Status</th>
                    <th class="px-4 py-3 text-left text-gray-500 font-medium">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($registrations as $reg)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $reg->name }}</p>
                            <p class="text-xs text-gray-400">{{ $reg->email }}</p>
                        </td>
                        <td class="px-4 py-3"><span
                                class="px-2 py-0.5 bg-purple-50 text-purple-700 rounded text-xs">{{ ucfirst(str_replace('_', ' ', $reg->applied_role)) }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $reg->nic }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $reg->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            @php $sc = ['pending' => 'bg-amber-50 text-amber-700', 'approved' => 'bg-green-50 text-green-700', 'rejected' => 'bg-red-50 text-red-600']; @endphp
                            <span
                                class="px-2 py-0.5 rounded text-xs {{ $sc[$reg->status] }}">{{ ucfirst($reg->status) }}</span>
                        </td>
                        <td class="px-4 py-3"><a href="{{ route('registrations.show', $reg) }}"
                                class="text-xs text-blue-600 hover:underline">Review</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-gray-400 text-sm">No applications found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $registrations->links() }}</div>
</x-dashboard-layout>