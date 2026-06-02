<x-dashboard-layout title="Timekeeper Dashboard">
    <x-charts />

{{-- Stats row --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Today's schedules</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $todaySchedules }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wide">This week</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $weekSchedules }}</p>
    </div>
    <div class="bg-white rounded-xl border {{ $unassignedCount > 0 ? 'border-amber-300 bg-amber-50' : 'border-gray-200' }} p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Unassigned schedules</p>
        <p class="text-3xl font-bold {{ $unassignedCount > 0 ? 'text-amber-600' : 'text-gray-800' }} mt-2">
            {{ $unassignedCount }}
        </p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-400 uppercase tracking-wide">Rosters today</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalRosters }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- FullCalendar --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-800">Schedule Calendar</h3>
            <a href="{{ route('schedules.create') }}"
               class="text-xs text-blue-600 hover:underline">+ Add schedule</a>
        </div>
        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet'/>
        <div id="schedule-calendar" style="font-size:0.82rem;"></div>
    </div>

    {{-- Right column --}}
    <div class="space-y-4">

        {{-- Unassigned alert panel --}}
        @if($unassignedCount > 0)
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667
                             1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34
                             16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <h3 class="text-sm font-semibold text-amber-800">Unassigned schedules</h3>
            </div>
            <div class="space-y-2">
                @foreach($unassignedSchedules as $s)
                <div class="bg-white border border-amber-200 rounded-lg px-3 py-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold text-gray-800">{{ $s->route->name }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $s->schedule_date->format('d M') }}
                                &middot;
                                {{ \Carbon\Carbon::parse($s->departure_time)->format('h:i A') }}
                                &middot;
                                <span class="font-mono text-blue-600">{{ $s->bus->depot_reg_no }}</span>
                            </p>
                        </div>
                        <a href="{{ route('schedules.edit', $s) }}"
                           class="text-xs text-amber-600 hover:underline flex-shrink-0 ml-2">Assign</a>
                    </div>
                    @if(!$s->driver_id)
                    <span class="inline-block mt-1 text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded">No driver</span>
                    @endif
                    @if(!$s->conductor_id)
                    <span class="inline-block mt-1 text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded">No conductor</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Quick actions --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-3">Quick actions</h3>
            <div class="space-y-2">
                <a href="{{ route('schedules.create') }}"
                   class="flex items-center gap-2 text-sm text-blue-600 hover:underline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create new schedule
                </a>
                <a href="{{ route('rosters.create') }}"
                   class="flex items-center gap-2 text-sm text-blue-600 hover:underline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Assign duty roster
                </a>
                <a href="{{ route('rosters.index') }}"
                   class="flex items-center gap-2 text-sm text-gray-600 hover:underline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    View all rosters
                </a>
                <a href="{{ route('schedules.index') }}"
                   class="flex items-center gap-2 text-sm text-gray-600 hover:underline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Manage schedules
                </a>
            </div>
        </div>

    </div>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('schedule-calendar');
    const calendar   = new FullCalendar.Calendar(calendarEl, {
        initialView:    'dayGridMonth',
        headerToolbar: {
            left:   'prev,next today',
            center: 'title',
            right:  'dayGridMonth,timeGridWeek,listWeek'
        },
        height:          'auto',
        events:          '{{ route("schedules.calendar-events") }}',
        eventColor:      '#2563EB',
        eventDisplay:    'block',
        eventClick: function (info) {
            window.location.href = info.event.url;
            info.jsEvent.preventDefault();
        },
        eventDidMount: function (info) {
            info.el.title = info.event.title;
        },
        loading: function (isLoading) {
            if (isLoading) {
                calendarEl.style.opacity = '0.6';
            } else {
                calendarEl.style.opacity = '1';
            }
        }
    });
    calendar.render();
});
</script>
{{-- Schedules per day bar chart --}}
<div class="bg-white rounded-xl border border-gray-200 p-5 mt-5">
    <h3 class="text-sm font-semibold text-gray-800 mb-4">Schedules — Next 14 Days</h3>
    <div style="height:180px;position:relative;">
        <canvas id="timekeeperScheduleChart"></canvas>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('{{ route("analytics.timekeeper") }}')
        .then(r => r.json())
        .then(data => {
            const sd = data.schedule_by_day;
            renderChart('timekeeperScheduleChart', {
                type: 'bar',
                data: {
                    labels: sd.map(d => d.date),
                    datasets: [{
                        label: 'Schedules',
                        data: sd.map(d => d.count),
                        backgroundColor: sd.map(d =>
                            d.date === '{{ today()->format("d M") }}'
                                ? PALETTE.blue.bg : 'rgba(37,99,235,0.4)'
                        ),
                        borderColor: PALETTE.blue.border,
                        borderRadius: 4,
                        borderWidth: 1,
                    }]
                },
                options: barOptions('Schedules')
            });
        });
});
</script>
</x-dashboard-layout>