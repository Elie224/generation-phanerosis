@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width: 500px;">
    <h1 class="mb-4 text-center">Envoyer une requête de prière</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('prayer.send') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="pastor_id" class="form-label">Choisir un pasteur</label>
            <select name="pastor_id" id="pastor_id" class="form-control" required>
                <option value="">-- Sélectionner un pasteur --</option>
                @foreach($pasteurs as $pasteur)
                    <option value="{{ $pasteur->id }}">{{ $pasteur->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Votre requête de prière</label>
            <textarea name="content" id="content" class="form-control" rows="5" required>{{ old('content') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="photos" class="form-label">Ajouter des photos (optionnel)</label>
            <input type="file" name="photos[]" id="photos" class="form-control" accept="image/*" multiple>
        </div>
        <div class="mb-3">
            <label class="form-label">Message vocal (optionnel)</label>
            <input type="file" name="audio" id="audio" class="form-control d-none" accept="audio/*">
            <button type="button" class="btn btn-outline-primary w-100 mb-2" id="record-btn">Enregistrer un message vocal</button>
            <audio id="audio-preview" controls style="display:none;width:100%;margin-top:10px;"></audio>
        </div>
        <button type="submit" class="btn btn-primary w-100">Envoyer la requête</button>
    </form>
    <script>
    let mediaRecorder;
    let audioChunks = [];
    const recordBtn = document.getElementById('record-btn');
    const audioInput = document.getElementById('audio');
    const audioPreview = document.getElementById('audio-preview');

    recordBtn.addEventListener('click', async function() {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            mediaRecorder.stop();
            recordBtn.textContent = 'Enregistrer un message vocal';
            return;
        }
        if (!navigator.mediaDevices) {
            alert('L\'enregistrement audio n\'est pas supporté sur ce navigateur.');
            return;
        }
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
            audioPreview.src = URL.createObjectURL(audioBlob);
            audioPreview.style.display = 'block';
        };
        mediaRecorder.start();
        recordBtn.textContent = 'Arrêter l\'enregistrement';
    });
    </script>
</div>
@endsection 
 
 