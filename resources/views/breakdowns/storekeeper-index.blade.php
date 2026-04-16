<x-dashboard-layout title="Spare Parts Jobs">
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-semibold text-gray-800">Spare Parts Breakdown Jobs</h2>
        <p class="text-sm text-gray-500">Breakdown reports requiring spare parts from inventory.</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Bus</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Reported by</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Description</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Time</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Status</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">View</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($reports as $report)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono font-semibold text-blue-700">
                    {{ $report->bus->depot_reg_no }}
                </td>
                <td class="px-4 py-3 text-gray-700">{{ $report->reportedBy->name }}</td>
                <td class="px-4 py-3 text-gray-600 max-w-xs truncate">{{ $report->description }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $report->created_at->diffForHumans() }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded text-xs {{ $report->status_color }}">
                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <a href="{{ route('breakdowns.show', $report) }}"
                       class="text-xs text-blue-600 hover:underline">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">
                    No spare parts jobs at the moment.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $reports->links() }}</div>
</x-dashboard-layout>