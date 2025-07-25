@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Modifier le projet</h1>
    <form action="{{ route('projets-talents.update', $project) }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')
        <div>
            <label class="block font-semibold">Nom du projet</label>
            <input type="text" name="title" class="w-full border rounded px-3 py-2" value="{{ old('title', $project->title) }}" required>
        </div>
        <div>
            <label class="block font-semibold">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" rows="4" required>{{ old('description', $project->description) }}</textarea>
        </div>
        <div>
            <label class="block font-semibold">Nom du porteur</label>
            <input type="text" name="owner_name" class="w-full border rounded px-3 py-2" value="{{ old('owner_name', $project->owner_name) }}" required>
        </div>
        <div>
            <label class="block font-semibold">Email de contact</label>
            <input type="email" name="contact_email" class="w-full border rounded px-3 py-2" value="{{ old('contact_email', $project->contact_email) }}">
        </div>
        <div>
            <label class="block font-semibold">Lien externe</label>
            <input type="url" name="external_link" class="w-full border rounded px-3 py-2" value="{{ old('external_link', $project->external_link) }}">
        </div>
        <div>
            <label class="block font-semibold">Pièce jointe (image, doc...)</label>
            <input type="file" name="attachment" class="w-full">
            @if($project->attachment_path)
                <a href="{{ asset('storage/'.$project->attachment_path) }}" target="_blank" class="text-green-500 underline text-xs">Voir le document actuel</a>
            @endif
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection 
 
 