@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête de l'événement -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            @if($event->image_path)
            <div class="h-64 bg-cover bg-center" style="background-image: url('{{ asset('storage/'.$event->image_path) }}')"></div>
            @else
            <div class="h-64 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                <svg class="w-24 h-24 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            @endif
            
            <div class="p-8">
                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" style="background-color: {{ $event->color }}20; color: {{ $event->color }};">
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
                        @if($event->isFull)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                Événement complet
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                {{ $event->availableSpots }} places disponibles
                            </span>
                        @endif
                    @endif
                </div>
                
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $event->title }}</h1>
                
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <div>
                            <div class="font-medium">Début</div>
                            <div>{{ $event->start_date->format('d/m/Y à H:i') }}</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <div class="font-medium">Fin</div>
                            <div>{{ $event->end_date->format('d/m/Y à H:i') }}</div>
                        </div>
                    </div>
                    
                    @if($event->location)
                    <div class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div>
                            <div class="font-medium">Lieu</div>
                            <div>{{ $event->location }}</div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <div>
                            <div class="font-medium">Organisateur</div>
                            <div>{{ $event->organizer_name }}</div>
                        </div>
                    </div>
                </div>
                
                @auth
                    @if($event->requires_registration)
                        <div class="border-t pt-6">
                            @if($isParticipant)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-green-600">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Vous êtes inscrit à cet événement
                                    </div>
                                    <form action="{{ route('events.unregister', $event->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                                            Se désinscrire
                                        </button>
                                    </form>
                                </div>
                            @else
                                @if(!$event->isFull)
                                    <form action="{{ route('events.register', $event->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-medium">
                                            S'inscrire à cet événement
                                        </button>
                                    </form>
                                @else
                                    <div class="text-center text-gray-600">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        Cet événement est complet
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif
                @else
                    <div class="border-t pt-6 text-center">
                        <p class="text-gray-600 mb-4">Connectez-vous pour vous inscrire à cet événement</p>
                        <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            Se connecter
                        </a>
                    </div>
                @endauth
            </div>
        </div>
        
        <!-- Description détaillée -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Description</h2>
            <div class="prose max-w-none">
                {!! nl2br(e($event->description)) !!}
            </div>
        </div>
        
        <!-- Informations supplémentaires -->
        @if($event->additional_info)
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Informations supplémentaires</h2>
            <div class="prose max-w-none">
                {!! nl2br(e($event->additional_info)) !!}
            </div>
        </div>
        @endif
        
        <!-- Contact -->
        @if($event->contact_email || $event->contact_phone)
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Contact</h2>
            <div class="space-y-3">
                @if($event->contact_email)
                <div class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <a href="mailto:{{ $event->contact_email }}" class="text-blue-600 hover:text-blue-800">
                        {{ $event->contact_email }}
                    </a>
                </div>
                @endif
                
                @if($event->contact_phone)
                <div class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <a href="tel:{{ $event->contact_phone }}" class="text-blue-600 hover:text-blue-800">
                        {{ $event->contact_phone }}
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Actions -->
        <div class="flex justify-between items-center">
            <a href="{{ route('events.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                ← Retour aux événements
            </a>
        </div>
    </div>
</div>
@endsection 