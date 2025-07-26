@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-8 relative overflow-hidden">
    <!-- Éléments décoratifs de fond -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-200/30 to-purple-200/30 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-br from-purple-200/30 to-pink-200/30 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-br from-blue-100/20 to-purple-100/20 rounded-full blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-4 max-w-4xl relative z-10">
        <!-- Header avec animation -->
        <div class="text-center mb-12">
            <div class="relative inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-full mb-6 shadow-2xl transform hover:scale-105 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent rounded-full"></div>
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-400 to-purple-600 rounded-full blur opacity-25"></div>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Demande de Prière</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Partagez votre cœur avec nos pasteurs. Nous sommes là pour prier avec vous et vous accompagner dans votre cheminement spirituel.
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

        <!-- Formulaire principal -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl p-8 mb-8 border border-white/20">
            <form action="{{ route('prayer.send') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Sélection du pasteur -->
                <div class="space-y-2 form-element" style="animation-delay: 0.1s;">
                    <label for="pastor_id" class="block text-sm font-semibold text-gray-700">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Choisir un pasteur
                        </span>
                    </label>
                    <select name="pastor_id" id="pastor_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/50 backdrop-blur-sm" required>
                        <option value="">-- Sélectionner un pasteur --</option>
                        @foreach($pasteurs as $pasteur)
                            <option value="{{ $pasteur->id }}" {{ old('pastor_id') == $pasteur->id ? 'selected' : '' }}>
                                {{ $pasteur->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('pastor_id')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Requête de prière -->
                <div class="space-y-2 form-element" style="animation-delay: 0.2s;">
                    <label for="content" class="block text-sm font-semibold text-gray-700">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            Votre requête de prière
                        </span>
                    </label>
                    <textarea 
                        name="content" 
                        id="content" 
                        rows="6" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 resize-none bg-white/50 backdrop-blur-sm"
                        placeholder="Partagez votre cœur, vos préoccupations, vos joies... Nous sommes là pour prier avec vous."
                        required
                    >{{ old('content') }}</textarea>
                    <div class="flex justify-between items-center text-sm text-gray-500">
                        <span>Votre message sera traité avec confidentialité</span>
                        <span id="char-count">0/5000</span>
                    </div>
                    @error('content')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fichiers (photos, vidéos, documents) -->
                <div class="space-y-3 form-element" style="animation-delay: 0.3s;">
                    <label class="block text-sm font-semibold text-gray-700">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            Ajouter des fichiers (optionnel)
                        </span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 transition-all duration-300 hover:bg-blue-50/50 backdrop-blur-sm">
                        <input type="file" name="files[]" id="files" class="hidden" accept="image/*,video/*,.pdf,.doc,.docx,.txt" multiple>
                        <label for="files" class="cursor-pointer">
                            <div class="flex flex-col items-center space-y-2">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Cliquez pour ajouter des fichiers</p>
                                    <p class="text-xs text-gray-500">Photos, vidéos, documents (PDF, DOC, TXT)</p>
                                    <p class="text-xs text-gray-500">Taille max: 10MB par fichier</p>
                                </div>
                            </div>
                        </label>
                    </div>
                    <div id="file-preview" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4" style="display: none;">
                        <!-- Les aperçus des fichiers seront affichés ici -->
                    </div>
                    @error('files.*')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Option anonyme -->
                <div class="flex items-center space-x-3 form-element" style="animation-delay: 0.4s;">
                    <input type="checkbox" name="is_anonymous" id="is_anonymous" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_anonymous" class="text-sm text-gray-700">
                        Envoyer de manière anonyme
                    </label>
                </div>

                <!-- Message vocal -->
                <div class="space-y-3 form-element" style="animation-delay: 0.5s;">
                    <label class="block text-sm font-semibold text-gray-700">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                            </svg>
                            Message vocal (optionnel)
                        </span>
                    </label>
                    <input type="file" name="audio" id="audio" class="hidden" accept="audio/*">
                    <div class="flex space-x-3">
                                            <button type="button" id="record-btn" class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200 flex items-center justify-center space-x-2 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                        </svg>
                        <span id="record-text">Enregistrer un message vocal</span>
                    </button>
                        <button type="button" id="clear-audio" class="px-4 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105" style="display: none;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="audio-preview" class="hidden">
                        <audio controls class="w-full rounded-lg">
                            <source src="" type="audio/webm">
                        </audio>
                    </div>
                </div>

                <!-- Bouton d'envoi -->
                <div class="pt-6 form-element" style="animation-delay: 0.6s;">
                    <button type="submit" class="w-full bg-purple-600 text-white py-4 px-6 rounded-xl font-semibold text-lg hover:bg-purple-700 transform hover:scale-105 transition-all duration-300 shadow-xl hover:shadow-2xl glow-effect">
                        <span class="flex items-center justify-center space-x-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <span>Envoyer ma demande de prière</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Section d'informations -->
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-xl text-center border border-white/20 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Confidentialité</h3>
                <p class="text-sm text-gray-600">Vos demandes sont traitées avec la plus grande confidentialité</p>
            </div>

            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-xl text-center border border-white/20 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Réponse rapide</h3>
                <p class="text-sm text-gray-600">Nos pasteurs répondent généralement dans les 24-48h</p>
            </div>

            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-xl text-center border border-white/20 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Accompagnement</h3>
                <p class="text-sm text-gray-600">Nous vous accompagnons dans votre cheminement spirituel</p>
            </div>
        </div>
    </div>
</div>

<style>
.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(-20px) scale(0.95); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}

.recording {
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0%, 100% { 
        transform: scale(1); 
        box-shadow: 0 10px 25px rgba(34, 197, 94, 0.3);
    }
    50% { 
        transform: scale(1.05); 
        box-shadow: 0 15px 35px rgba(34, 197, 94, 0.5);
    }
}

/* Animation d'entrée pour les éléments */
.form-element {
    animation: slideInUp 0.8s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Effet de brillance sur les boutons */
.glow-effect {
    position: relative;
    overflow: hidden;
}

.glow-effect::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.glow-effect:hover::before {
    left: 100%;
}
</style>

<script>
let mediaRecorder;
let audioChunks = [];
const recordBtn = document.getElementById('record-btn');
const recordText = document.getElementById('record-text');
const audioInput = document.getElementById('audio');
const audioPreview = document.getElementById('audio-preview');
const clearAudioBtn = document.getElementById('clear-audio');
const audioElement = audioPreview.querySelector('audio');
const contentTextarea = document.getElementById('content');
const charCount = document.getElementById('char-count');

// Compteur de caractères
contentTextarea.addEventListener('input', function() {
    const length = this.value.length;
    charCount.textContent = `${length}/5000`;
    if (length > 4500) {
        charCount.classList.add('text-red-500');
    } else {
        charCount.classList.remove('text-red-500');
    }
});

// Gestion des fichiers
const fileInput = document.getElementById('files');
const filePreview = document.getElementById('file-preview');

fileInput.addEventListener('change', function() {
    filePreview.innerHTML = '';
    
    if (this.files.length > 0) {
        filePreview.style.display = 'grid';
        
        Array.from(this.files).forEach((file, index) => {
            const previewDiv = document.createElement('div');
            previewDiv.className = 'relative bg-gray-100 rounded-lg p-3';
            
            if (file.type.startsWith('image/')) {
                // Aperçu pour les images
                const img = document.createElement('img');
                img.className = 'w-full h-24 object-cover rounded';
                img.src = URL.createObjectURL(file);
                previewDiv.appendChild(img);
            } else if (file.type.startsWith('video/')) {
                // Aperçu pour les vidéos
                const video = document.createElement('video');
                video.className = 'w-full h-24 object-cover rounded';
                video.src = URL.createObjectURL(file);
                video.controls = true;
                previewDiv.appendChild(video);
            } else {
                // Aperçu pour les documents
                const icon = document.createElement('div');
                icon.className = 'w-full h-24 bg-blue-100 rounded flex items-center justify-center';
                icon.innerHTML = `
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                `;
                previewDiv.appendChild(icon);
            }
            
            // Nom du fichier
            const fileName = document.createElement('p');
            fileName.className = 'text-xs text-gray-600 mt-2 truncate';
            fileName.textContent = file.name;
            previewDiv.appendChild(fileName);
            
            // Bouton supprimer
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600';
            removeBtn.innerHTML = '×';
            removeBtn.onclick = function() {
                previewDiv.remove();
                if (filePreview.children.length === 0) {
                    filePreview.style.display = 'none';
                }
            };
            previewDiv.appendChild(removeBtn);
            
            filePreview.appendChild(previewDiv);
        });
    } else {
        filePreview.style.display = 'none';
    }
});

// Enregistrement audio
recordBtn.addEventListener('click', async function() {
    if (mediaRecorder && mediaRecorder.state === 'recording') {
        mediaRecorder.stop();
        recordBtn.classList.remove('recording', 'bg-red-500');
        recordBtn.classList.add('bg-blue-600');
        recordText.textContent = 'Enregistrer un message vocal';
        return;
    }

    if (!navigator.mediaDevices) {
        alert('L\'enregistrement audio n\'est pas supporté sur ce navigateur.');
        return;
    }

    try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        audioChunks = [];

        mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
        mediaRecorder.onstop = e => {
            const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
            const file = new File([audioBlob], 'audio.webm', { type: 'audio/webm' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            audioInput.files = dataTransfer.files;
            audioElement.src = URL.createObjectURL(audioBlob);
            audioPreview.classList.remove('hidden');
            clearAudioBtn.style.display = 'block';
        };

        mediaRecorder.start();
        recordBtn.classList.add('recording', 'bg-red-500');
        recordBtn.classList.remove('bg-blue-600');
        recordText.textContent = 'Arrêter l\'enregistrement';
    } catch (error) {
        alert('Erreur lors de l\'accès au microphone: ' + error.message);
    }
});

// Effacer l'audio
clearAudioBtn.addEventListener('click', function() {
    audioInput.value = '';
    audioPreview.classList.add('hidden');
    clearAudioBtn.style.display = 'none';
    audioElement.src = '';
});
</script>
@endsection 
 
 