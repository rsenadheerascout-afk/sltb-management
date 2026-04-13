<x-dashboard-layout title="Notifications">
    <div class="max-w-2xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Notifications</h2>
                @php $unread = $notifications->where('is_read', false)->count(); @endphp
                <p class="text-sm text-gray-500">{{ $unread > 0 ? $unread . ' unread' : 'All caught up' }}</p>
            </div>
            @if($unread > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50">
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>
        <div class="space-y-2">
            @forelse($notifications as $n)
                <div
                    class="bg-white rounded-xl border {{ $n->is_read ? 'border-gray-200' : 'border-blue-200 bg-blue-50/30' }} p-4 flex gap-4">
                    @php $dots = ['info' => 'bg-blue-500', 'success' => 'bg-green-500', 'warning' => 'bg-amber-500', 'danger' => 'bg-red-500']; @endphp
                    <div class="mt-1.5 w-2 h-2 rounded-full flex-shrink-0 {{ $dots[$n->type] ?? 'bg-gray-400' }}"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800">{{ $n->title }}</p>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $n->message }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $n->created_at->diffForHumans() }}</p>
                    </div>
                    @if(!$n->is_read)
                        <form method="POST" action="{{ route('notifications.read', $n) }}" class="flex-shrink-0">
                            @csrf
                            <button type="submit" class="text-xs text-blue-600 hover:underline">Mark read</button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-200 p-10 text-center">
                    <p class="text-gray-400 text-sm">No notifications yet.</p>
                </div>
            @endforelse
        </div>
        <div class="mt-4">{{ $notifications->links() }}</div>
    </div>
</x-dashboard-layout>