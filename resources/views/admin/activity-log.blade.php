<x-dashboard-layout title="Audit Log">
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-semibold text-gray-800">Activity Audit Log</h2>
        <p class="text-sm text-gray-500">{{ $logs->total() }} total records</p>
    </div>
</div>

{{-- Filters --}}
<form method="GET" class="flex gap-3 mb-6 flex-wrap">
    <select name="user_id" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
        <option value="">All employees</option>
        @foreach($users as $u)
            <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>
                {{ $u->name }} ({{ $u->employee_id }})
            </option>
        @endforeach
    </select>

    <select name="action" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
        <option value="">All actions</option>
        @foreach($actions as $a)
            <option value="{{ $a }}" @selected(request('action') === $a)>{{ ucfirst($a) }}</option>
        @endforeach
    </select>

    <select name="model" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
        <option value="">All models</option>
        @foreach($models as $m)
            <option value="{{ $m }}" @selected(request('model') === $m)>{{ $m }}</option>
        @endforeach
    </select>

    <input type="date" name="date" value="{{ request('date') }}"
           class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

    <button type="submit"
            class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700">
        Filter
    </button>
    <a href="{{ route('activity.log') }}"
       class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50">
        Reset
    </a>
</form>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Time</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Employee</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Action</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Model</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Description</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">IP</th>
                <th class="px-4 py-3 text-left text-gray-500 font-medium">Changes</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($logs as $log)
            <tr class="hover:bg-gray-50" x-data="{ open: false }">
                <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">
                    {{ $log->created_at->format('d M Y') }}<br>
                    <span class="text-gray-400">{{ $log->created_at->format('h:i A') }}</span>
                </td>
                <td class="px-4 py-3">
                    @if($log->user)
                    <p class="font-medium text-gray-800 text-xs">{{ $log->user->name }}</p>
                    <p class="text-gray-400 text-xs">{{ $log->user->employee_id }}</p>
                    @else
                    <span class="text-gray-400 text-xs">System</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    @php
                        $actionColors = [
                            'created'       => 'bg-green-50 text-green-700',
                            'updated'       => 'bg-blue-50 text-blue-700',
                            'deleted'       => 'bg-red-50 text-red-600',
                            'disabled'      => 'bg-amber-50 text-amber-700',
                            'enabled'       => 'bg-green-50 text-green-700',
                            'approved'      => 'bg-green-50 text-green-700',
                            'rejected'      => 'bg-red-50 text-red-600',
                            'resolved'      => 'bg-teal-50 text-teal-700',
                            'status_changed'=> 'bg-purple-50 text-purple-700',
                            'released'      => 'bg-orange-50 text-orange-700',
                            'restocked'     => 'bg-teal-50 text-teal-700',
                        ];
                    @endphp
                    <span class="px-2 py-0.5 rounded text-xs {{ $actionColors[$log->action] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-gray-600 text-xs">
                    {{ $log->model_type }}
                    @if($log->model_id)
                    <span class="text-gray-400">#{{ $log->model_id }}</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-600 text-xs max-w-xs">
                    {{ $log->description }}
                </td>
                <td class="px-4 py-3 text-gray-400 text-xs font-mono">
                    {{ $log->ip_address }}
                </td>
                <td class="px-4 py-3">
                    @if($log->old_values || $log->new_values)
                    <button @click="open = !open"
                            class="text-xs text-blue-600 hover:underline">
                        <span x-text="open ? 'Hide' : 'View'"></span>
                    </button>
                    <div x-show="open" x-transition
                         class="mt-2 space-y-2">
                        @if($log->old_values)
                        <div class="bg-red-50 rounded p-2">
                            <p class="text-xs font-semibold text-red-700 mb-1">Before</p>
                            @foreach($log->old_values as $key => $val)
                            <p class="text-xs text-red-600">
                                <span class="font-medium">{{ $key }}:</span>
                                {{ is_array($val) ? json_encode($val) : $val }}
                            </p>
                            @endforeach
                        </div>
                        @endif
                        @if($log->new_values)
                        <div class="bg-green-50 rounded p-2">
                            <p class="text-xs font-semibold text-green-700 mb-1">After</p>
                            @foreach($log->new_values as $key => $val)
                            <p class="text-xs text-green-600">
                                <span class="font-medium">{{ $key }}:</span>
                                {{ is_array($val) ? json_encode($val) : $val }}
                            </p>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @else
                    <span class="text-xs text-gray-300">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-12 text-center text-gray-400 text-sm">
                    No activity recorded yet. Actions taken in the system will appear here.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $logs->links() }}</div>
</x-dashboard-layout>