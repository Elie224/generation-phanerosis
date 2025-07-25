@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Calendrier d'événements</h1>
                <p class="mt-2 text-gray-600">Découvrez les événements à venir de notre communauté</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('events.calendar') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Vue calendrier
                </a>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex flex-wrap gap-4">
                <select id="type-filter" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les types</option>
                    <option value="culte">Culte</option>
                    <option value="formation">Formation</option>
                    <option value="reunion">Réunion</option>
                    <option value="priere">Prière</option>
                    <option value="general">Général</option>
                </select>
                <input type="date" id="date-filter" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button onclick="clearFilters()" class="text-gray-600 hover:text-gray-800">Effacer les filtres</button>
            </div>
        </div>

        <!-- Liste des événements -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($events as $event)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                @if($event->image_path)
                <div class="h-48 bg-cover bg-center" style="background-image: url('{{ asset('storage/'.$event->image_path) }}')"></div>
                @else
                <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                    <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                @endif
                
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: {{ $event->color }}20; color: {{ $event->color }};">
                            @switch($event->type)
                                @case('culte')
                                    Culte
                                    @break
                                @case('formation')
                                    Formation
                                    @break
                                @case('reunion')
                                    Réunion
                                    @break
                                @case('priere')
                                    Prière
                                    @break
                                @default
                                    Général
                            @endswitch
                        </span>
                        @if($event->requires_registration)
                        <span class="text-xs text-gray-500">
                            @if($event->isFull)
                                <span class="text-red-600">Complet</span>
                            @else
                                {{ $event->availableSpots }} places disponibles
                            @endif
                        </span>
                        @endif
                    </div>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                    
                    <div class="flex items-center text-gray-600 text-sm mb-3">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ $event->start_date->format('d/m/Y H:i') }}
                    </div>
                    
                    @if($event->location)
                    <div class="flex items-center text-gray-600 text-sm mb-3">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $event->location }}
                    </div>
                    @endif
                    
                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($event->description, 100) }}</p>
                    
                    <div class="flex justify-between items-center">
                        <a href="{{ route('events.show', $event->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            Voir les détails
                        </a>
                        @auth
                            @if($event->requires_registration && !$event->isFull)
                                <form action="{{ route('events.register', $event->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                        S'inscrire
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun événement à venir</h3>
                <p class="text-gray-600">Revenez plus tard pour découvrir les nouveaux événements.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $events->links() }}
        </div>
    </div>
</div>

<script>
function clearFilters() {
    document.getElementById('type-filter').value = '';
    document.getElementById('date-filter').value = '';
    // Ici vous pouvez ajouter la logique pour recharger les événements
}
</script>
@endsection 