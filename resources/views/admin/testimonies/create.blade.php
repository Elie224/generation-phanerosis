@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ajouter un témoignage</h1>
    <form action="{{ route('temoignages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" name="title" id="title" class="form-control" required value="{{ old('title') }}">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="witness_name" class="form-label">Nom du témoin</label>
            <input type="text" name="witness_name" id="witness_name" class="form-control" value="{{ old('witness_name') }}">
        </div>
        <div class="mb-3">
            <label for="media" class="form-label">Fichier audio ou vidéo</label>
            <input type="file" name="media" id="media" class="form-control" required accept="audio/*,video/*">
        </div>
        <div class="mb-3">
            <label for="media_type" class="form-label">Type de média</label>
            <select name="media_type" id="media_type" class="form-control" required>
                <option value="video">Vidéo</option>
                <option value="audio">Audio</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="published_at" class="form-label">Date de publication</label>
            <input type="date" name="published_at" id="published_at" class="form-control" value="{{ old('published_at') }}">
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
            <label class="form-check-label" for="is_published">Publié</label>
        </div>
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('temoignages.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection 
 
 