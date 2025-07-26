@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-green-500 to-blue-600 rounded-full mb-6 shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Mes Demandes de Prière</h1>
            <p class="text-lg text-gray-600">
                Suivez l'état de vos demandes de prière et les réponses de nos pasteurs
            </p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-8 animate-fade-in">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Statistiques -->
        <div class="grid md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-lg text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $prayers->total() }}</div>
                <div class="text-sm text-gray-600">Total</div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-lg text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $prayers->where('status', 'pending')->count() }}</div>
                <div class="text-sm text-gray-600">En attente</div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-lg text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $prayers->where('status', 'in_progress')->count() }}</div>
                <div class="text-sm text-gray-600">En cours</div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-lg text-center">
                <div class="text-2xl font-bold text-green-600">{{ $prayers->where('status', 'answered')->count() }}</div>
                <div class="text-sm text-gray-600">Répondues</div>
            </div>
        </div>

        <!-- Liste des demandes -->
        <div class="space-y-6">
            @forelse($prayers as $prayer)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <!-- En-tête de la demande -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-blue-600 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">
                                        Pasteur {{ $prayer->pastor->name }}
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $prayer->created_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-{{ $prayer->status_color }}-100 text-{{ $prayer->status_color }}-800">
                                    {{ $prayer->status_label }}
                                </span>
                            </div>
                        </div>

                        <!-- Contenu de la demande -->
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-800 mb-2">Votre demande :</h4>
                            <p class="text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-lg">{{ $prayer->content }}</p>
                        </div>

                        <!-- Audio si présent -->
                        @if($prayer->audio_path)
                            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-2 mb-2">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Votre message vocal</span>
                                </div>
                                <audio controls class="w-full">
                                    <source src="{{ Storage::url($prayer->audio_path) }}" type="audio/webm">
                                </audio>
                            </div>
                        @endif

                        <!-- Réponse du pasteur -->
                        @if($prayer->pastor_response)
                            <div class="mb-4 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                                <h4 class="font-semibold text-blue-800 mb-2">Réponse du pasteur :</h4>
                                <p class="text-blue-700 leading-relaxed">{{ $prayer->pastor_response }}</p>
                                @if($prayer->answered_at)
                                    <p class="text-xs text-blue-600 mt-2">
                                        Répondu le {{ $prayer->answered_at->format('d/m/Y à H:i') }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                            <div class="text-sm text-gray-500">
                                @if($prayer->is_anonymous)
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        Envoyé anonymement
                                    </span>
                                @endif
                            </div>
                            <div class="flex space-x-2">
                                @if($prayer->status === 'pending')
                                    <span class="text-sm text-yellow-600 font-medium">
                                        En attente de réponse
                                    </span>
                                @elseif($prayer->status === 'in_progress')
                                    <span class="text-sm text-blue-600 font-medium">
                                        Le pasteur prie pour vous
                                    </span>
                                @elseif($prayer->status === 'answered')
                                    <span class="text-sm text-green-600 font-medium">
                                        Répondu
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Aucune demande de prière</h3>
                    <p class="text-gray-600 mb-6">Vous n'avez pas encore envoyé de demandes de prière.</p>
                    <a href="{{ route('prayer.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Envoyer une demande
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($prayers->hasPages())
            <div class="mt-8">
                {{ $prayers->links() }}
            </div>
        @endif
    </div>
</div>

<style>
.animate-fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection 