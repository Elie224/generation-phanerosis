@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Modifier le talent</h1>
    <form action="{{ route('jeunes-talents.update', $talent) }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')
        <div>
            <label class="block font-semibold">Nom</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name', $talent->name) }}" required>
        </div>
        <div>
            <label class="block font-semibold">Domaine d'excellence</label>
            <input type="text" name="domain" class="w-full border rounded px-3 py-2" value="{{ old('domain', $talent->domain) }}" required>
        </div>
        <div>
            <label class="block font-semibold">Description / Bio</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" rows="4">{{ old('description', $talent->description) }}</textarea>
        </div>
        <div>
            <label class="block font-semibold">Photo</label>
            <input type="file" name="photo" class="w-full">
            @if($talent->photo_path)
                <a href="{{ asset('storage/'.$talent->photo_path) }}" target="_blank" class="text-purple-500 underline text-xs">Voir la photo actuelle</a>
            @endif
        </div>
        <div>
            <label class="block font-semibold">CV (PDF, doc...)</label>
            <input type="file" name="cv" class="w-full">
            @if($talent->cv_path)
                <a href="{{ asset('storage/'.$talent->cv_path) }}" target="_blank" class="text-purple-500 underline text-xs">Voir le CV actuel</a>
            @endif
        </div>
        <div>
            <label class="block font-semibold">Lien externe (portfolio, réseaux...)</label>
            <input type="url" name="external_link" class="w-full border rounded px-3 py-2" value="{{ old('external_link', $talent->external_link) }}">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection 
 
 