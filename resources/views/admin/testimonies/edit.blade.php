@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier le témoignage</h1>
    <form action="{{ route('temoignages.update', $temoignage) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" name="title" id="title" class="form-control" required value="{{ old('title', $temoignage->title) }}">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $temoignage->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="witness_name" class="form-label">Nom du témoin</label>
            <input type="text" name="witness_name" id="witness_name" class="form-control" value="{{ old('witness_name', $temoignage->witness_name) }}">
        </div>
        <div class="mb-3">
            <label for="media" class="form-label">Fichier audio ou vidéo (laisser vide pour conserver l'actuel)</label>
            <input type="file" name="media" id="media" class="form-control" accept="audio/*,video/*">
            @if($temoignage->media_path)
                <div class="mt-2">
                    <a href="{{ asset('storage/'.$temoignage->media_path) }}" target="_blank">Voir le média actuel</a>
                </div>
            @endif
        </div>
        <div class="mb-3">
            <label for="media_type" class="form-label">Type de média</label>
            <select name="media_type" id="media_type" class="form-control" required>
                <option value="video" @if(old('media_type', $temoignage->media_type)=='video') selected @endif>Vidéo</option>
                <option value="audio" @if(old('media_type', $temoignage->media_type)=='audio') selected @endif>Audio</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="published_at" class="form-label">Date de publication</label>
            <input type="date" name="published_at" id="published_at" class="form-control" value="{{ old('published_at', $temoignage->published_at ? \Illuminate\Support\Carbon::parse($temoignage->published_at)->format('Y-m-d') : '') }}">
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $temoignage->is_published) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_published">Publié</label>
        </div>
        <button type="submit" class="btn btn-success">Mettre à jour</button>
        <a href="{{ route('temoignages.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection 
 
 