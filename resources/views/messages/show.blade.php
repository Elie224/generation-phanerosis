@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <h2 class="text-xl font-bold mb-6">Discussion avec {{ $other->name }}</h2>
    <div class="bg-white p-4 rounded shadow max-h-[50vh] overflow-y-auto mb-4" id="messages-area">
        @foreach($messages as $msg)
            <div class="mb-2 flex {{ $msg->from_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="relative inline-block px-4 py-2 rounded-lg 
                    {{ $msg->from_id == auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-black' }}">
                    @if($msg->type === 'text')
                        {{ $msg->content }}
                    @elseif($msg->type === 'image' && $msg->attachment_path)
                        <img src="{{ asset('storage/'.$msg->attachment_path) }}" alt="Image" class="max-w-xs max-h-48 rounded mb-1">
                    @elseif($msg->type === 'video' && $msg->attachment_path)
                        <video controls class="max-w-xs max-h-48 mb-1">
                            <source src="{{ asset('storage/'.$msg->attachment_path) }}">
                            Vidéo non supportée.
                        </video>
                    @elseif($msg->type === 'audio' && $msg->audio_path)
                        <audio controls class="w-48 mb-1">
                            <source src="{{ asset('storage/'.$msg->audio_path) }}">
                            Audio non supporté.
                        </audio>
                    @elseif($msg->type === 'file' && $msg->attachment_path)
                        <a href="{{ asset('storage/'.$msg->attachment_path) }}" target="_blank" class="underline text-blue-200">Fichier joint</a>
                    @endif
                    <span class="block text-xs text-right text-gray-400">{{ $msg->created_at->format('H:i') }}</span>
                    @if($msg->from_id == auth()->id())
                        <div class="flex gap-2 mt-1">
                            <form action="{{ route('messages.deleteForMe', $msg->id) }}" method="POST" onsubmit="return confirm('Supprimer ce message pour vous ?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-blue-500 hover:underline text-xs">Supprimer pour moi</button>
                            </form>
                            <form action="{{ route('messages.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Supprimer ce message pour tout le monde ?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs">Supprimer pour tout le monde</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @if($errors->any())
        <div class="mb-4 p-2 bg-red-100 text-red-700 rounded">
            <ul class="text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('messages.store', $other->id) }}" method="POST" enctype="multipart/form-data" class="flex gap-2 items-center" id="message-form">
        @csrf
        <input type="text" name="content" class="flex-1 border rounded px-2 py-1" placeholder="Écrire un message...">
        <label class="cursor-pointer bg-gray-200 hover:bg-gray-300 px-2 py-1 rounded flex items-center" title="Joindre une image, vidéo ou fichier">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.586-6.586a2 2 0 10-2.828-2.828z" /></svg>
            <span class="ml-1 text-xs text-gray-500 hidden sm:inline">Joindre un fichier</span>
            <input type="file" name="attachment" class="hidden" accept="image/*,video/*,application/pdf,.doc,.docx,.xls,.xlsx,.txt" id="attachment-input">
        </label>
        <button type="button" id="record-btn" class="bg-gray-200 hover:bg-gray-300 px-2 py-1 rounded flex items-center ml-1" title="Enregistrer un message vocal">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="6" fill="currentColor" /></svg>
            <span class="ml-1 text-xs text-gray-500 hidden sm:inline">Vocal</span>
        </button>
        <input type="file" name="audio" class="hidden" accept="audio/*" id="audio-input">
        <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">Envoyer</button>
    </form>
    <div id="preview-area" class="mt-2 relative"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const attachmentInput = document.getElementById('attachment-input');
            const previewArea = document.getElementById('preview-area');
            if (attachmentInput && previewArea) {
                attachmentInput.addEventListener('change', function(e) {
                    previewArea.innerHTML = '';
                    const file = e.target.files[0];
                    if (!file) return;
                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.style.maxWidth = '200px';
                        img.style.maxHeight = '120px';
                        previewArea.appendChild(img);
                    } else {
                        const info = document.createElement('div');
                        info.textContent = 'Fichier sélectionné : ' + file.name;
                        previewArea.appendChild(info);
                    }
                });
            }

            // --- ENREGISTREMENT VOCAL ---
            const recordBtn = document.getElementById('record-btn');
            const audioInput = document.getElementById('audio-input');
            let mediaRecorder = null;
            let audioChunks = [];
            let isRecording = false;
            let cancelBtn = null;

            function resetAudioInput() {
                audioInput.value = '';
                if (previewArea) previewArea.innerHTML = '';
            }

            if (recordBtn && audioInput) {
                recordBtn.addEventListener('click', async function() {
                    if (isRecording) {
                        // Arrêter l'enregistrement
                        mediaRecorder.stop();
                        recordBtn.textContent = 'Vocal';
                        isRecording = false;
                        if (cancelBtn) cancelBtn.remove();
                        return;
                    }
                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        alert('Votre navigateur ne supporte pas l\'enregistrement audio.');
                        return;
                    }
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        mediaRecorder = new MediaRecorder(stream);
                        audioChunks = [];
                        mediaRecorder.ondataavailable = e => {
                            if (e.data.size > 0) audioChunks.push(e.data);
                        };
                        mediaRecorder.onstop = () => {
                            const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                            if (audioBlob.size === 0) return;
                            const file = new File([audioBlob], 'audio_message.webm', { type: 'audio/webm' });
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            audioInput.files = dataTransfer.files;
                            // Prévisualisation
                            if (previewArea) {
                                previewArea.innerHTML = '';
                                const audio = document.createElement('audio');
                                audio.controls = true;
                                audio.src = URL.createObjectURL(audioBlob);
                                previewArea.appendChild(audio);
                            }
                        };
                        mediaRecorder.start();
                        isRecording = true;
                        recordBtn.textContent = 'Arrêter';
                        // Ajouter bouton annuler
                        if (!cancelBtn) {
                            cancelBtn = document.createElement('button');
                            cancelBtn.type = 'button';
                            cancelBtn.textContent = 'Annuler';
                            cancelBtn.className = 'ml-2 bg-gray-300 text-xs px-2 py-1 rounded';
                            cancelBtn.onclick = function() {
                                if (isRecording && mediaRecorder) {
                                    mediaRecorder.stop();
                                }
                                resetAudioInput();
                                recordBtn.textContent = 'Vocal';
                                isRecording = false;
                                cancelBtn.remove();
                            };
                            recordBtn.parentNode.insertBefore(cancelBtn, recordBtn.nextSibling);
                        }
                    } catch (err) {
                        alert('Erreur lors de l\'accès au micro : ' + err.message);
                    }
                });
            }
        });
    </script>
</div>
@endsection

