@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-purple-500 to-blue-600 rounded-full mb-6 shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Mes Demandes de Prière</h1>
            <p class="text-lg text-gray-600">
                Gérez les demandes de prière qui vous ont été adressées
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

        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex flex-wrap gap-4">
                <button class="filter-btn active px-4 py-2 rounded-lg bg-blue-500 text-white" data-filter="all">
                    Toutes ({{ $prayers->total() }})
                </button>
                <button class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300" data-filter="pending">
                    En attente
                </button>
                <button class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300" data-filter="in_progress">
                    En cours
                </button>
                <button class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300" data-filter="answered">
                    Répondues
                </button>
            </div>
        </div>

        <!-- Liste des demandes -->
        <div class="space-y-6">
            @forelse($prayers as $prayer)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden prayer-card" data-status="{{ $prayer->status }}">
                    <div class="p-6">
                        <!-- En-tête de la demande -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">
                                        {{ $prayer->is_anonymous ? 'A' : substr($prayer->user->name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">
                                        {{ $prayer->is_anonymous ? 'Demande anonyme' : $prayer->user->name }}
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
                            <p class="text-gray-700 leading-relaxed">{{ $prayer->content }}</p>
                        </div>

                        <!-- Audio si présent -->
                        @if($prayer->audio_path)
                            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-2 mb-2">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Message vocal</span>
                                </div>
                                <audio controls class="w-full">
                                    <source src="{{ Storage::url($prayer->audio_path) }}" type="audio/webm">
                                </audio>
                            </div>
                        @endif

                        <!-- Fichiers si présents -->
                        @if($prayer->files_paths && count($prayer->files_paths) > 0)
                            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-2 mb-3">
                                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Fichiers joints</span>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($prayer->files_paths as $file)
                                        <div class="bg-white rounded-lg p-3 border">
                                            @if(str_starts_with($file['type'], 'image/'))
                                                <img src="{{ Storage::url($file['path']) }}" alt="{{ $file['name'] }}" class="w-full h-20 object-cover rounded mb-2">
                                            @elseif(str_starts_with($file['type'], 'video/'))
                                                <video src="{{ Storage::url($file['path']) }}" class="w-full h-20 object-cover rounded mb-2" controls></video>
                                            @else
                                                <div class="w-full h-20 bg-blue-100 rounded flex items-center justify-center mb-2">
                                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <p class="text-xs text-gray-600 truncate">{{ $file['name'] }}</p>
                                            <a href="{{ Storage::url($file['path']) }}" target="_blank" class="text-xs text-blue-600 hover:underline">Télécharger</a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Réponse du pasteur -->
                        @if($prayer->pastor_response)
                            <div class="mb-4 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                                <h4 class="font-semibold text-blue-800 mb-2">Votre réponse :</h4>
                                <p class="text-blue-700">{{ $prayer->pastor_response }}</p>
                                @if($prayer->answered_at)
                                    <p class="text-xs text-blue-600 mt-2">
                                        Répondu le {{ $prayer->answered_at->format('d/m/Y à H:i') }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex justify-end space-x-3">
                            @if($prayer->status === 'pending')
                                <button onclick="openResponseModal({{ $prayer->id }})" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                    Répondre
                                </button>
                                <button onclick="updateStatus({{ $prayer->id }}, 'in_progress')" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                                    En cours de prière
                                </button>
                            @elseif($prayer->status === 'in_progress')
                                <button onclick="openResponseModal({{ $prayer->id }})" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                    Marquer comme répondu
                                </button>
                            @endif
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
                    <p class="text-gray-600">Vous n'avez pas encore reçu de demandes de prière.</p>
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

<!-- Modal de réponse -->
<div id="responseModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Répondre à la demande de prière</h3>
                <form id="responseForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="response" class="block text-sm font-medium text-gray-700 mb-2">
                            Votre réponse
                        </label>
                        <textarea 
                            id="response" 
                            name="response" 
                            rows="4" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Partagez votre réponse, vos encouragements, vos prières..."
                            required
                        ></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeResponseModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Envoyer la réponse
                        </button>
                    </div>
                </form>
            </div>
        </div>
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

.filter-btn.active {
    background-color: #3b82f6;
    color: white;
}
</style>

<script>
// Filtrage des demandes
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const filter = this.dataset.filter;
        
        // Mettre à jour les boutons
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        // Filtrer les cartes
        document.querySelectorAll('.prayer-card').forEach(card => {
            if (filter === 'all' || card.dataset.status === filter) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});

// Modal de réponse
function openResponseModal(prayerId) {
    const modal = document.getElementById('responseModal');
    const form = document.getElementById('responseForm');
    form.action = `/prayer/${prayerId}/respond`;
    modal.classList.remove('hidden');
}

function closeResponseModal() {
    const modal = document.getElementById('responseModal');
    modal.classList.add('hidden');
    document.getElementById('response').value = '';
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('responseModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeResponseModal();
    }
});

// Mettre à jour le statut
function updateStatus(prayerId, status) {
    if (confirm('Voulez-vous marquer cette demande comme "En cours de prière" ?')) {
        fetch(`/prayer/${prayerId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}
</script>
@endsection 