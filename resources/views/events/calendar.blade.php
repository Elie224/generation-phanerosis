@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Calendrier des événements</h1>
                <p class="mt-2 text-gray-600">Vue calendrier de tous les événements</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('events.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    Vue liste
                </a>
                @auth
                <a href="{{ route('events.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Créer un événement
                </a>
                @endauth
            </div>
        </div>

        <!-- Légende -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-3">Légende</h3>
            <div class="flex flex-wrap gap-4">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">Général</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">Culte</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">Formation</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">Réunion</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-purple-500 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">Prière</span>
                </div>
            </div>
        </div>

        <!-- Calendrier -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/main.min.css' rel='stylesheet' />
<link href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.10/main.min.css' rel='stylesheet' />
<link href='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.10/main.min.css' rel='stylesheet' />
<link href='https://cdn.jsdelivr.net/npm/@fullcalendar/list@6.1.10/main.min.css' rel='stylesheet' />

<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.10/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.10/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/list@6.1.10/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.10/main.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        buttonText: {
            today: 'Aujourd\'hui',
            month: 'Mois',
            week: 'Semaine',
            list: 'Liste'
        },
        height: 'auto',
        events: @json($events),
        eventClick: function(info) {
            if (info.event.url) {
                window.location.href = info.event.url;
                return false;
            }
        },
        eventDidMount: function(info) {
            // Ajouter des tooltips personnalisés
            var event = info.event;
            var element = info.el;
            
            element.title = event.title + '\n' + 
                           (event.extendedProps.location ? 'Lieu: ' + event.extendedProps.location + '\n' : '') +
                           'Type: ' + event.extendedProps.type;
        },
        dayMaxEvents: true,
        moreLinkClick: 'popover',
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        slotMinTime: '06:00:00',
        slotMaxTime: '22:00:00',
        allDaySlot: true,
        slotDuration: '00:30:00',
        slotLabelFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        }
    });
    
    calendar.render();
});
</script>

<style>
.fc-event {
    cursor: pointer;
    border-radius: 4px;
    font-size: 0.875rem;
    font-weight: 500;
}

.fc-event:hover {
    opacity: 0.8;
}

.fc-toolbar-title {
    font-size: 1.5rem !important;
    font-weight: 600 !important;
}

.fc-button {
    background-color: #3B82F6 !important;
    border-color: #3B82F6 !important;
    font-weight: 500 !important;
}

.fc-button:hover {
    background-color: #2563EB !important;
    border-color: #2563EB !important;
}

.fc-button-active {
    background-color: #1D4ED8 !important;
    border-color: #1D4ED8 !important;
}

.fc-daygrid-day-number {
    font-weight: 500;
}

.fc-col-header-cell {
    background-color: #F8FAFC;
    font-weight: 600;
}

.fc-day-today {
    background-color: #EFF6FF !important;
}

.fc-event-title {
    font-weight: 500;
}

.fc-event-time {
    font-weight: 400;
}

/* Responsive */
@media (max-width: 768px) {
    .fc-toolbar {
        flex-direction: column;
        gap: 1rem;
    }
    
    .fc-toolbar-chunk {
        display: flex;
        justify-content: center;
    }
    
    .fc-button {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
    }
}
</style>
@endsection 