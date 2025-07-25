@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Modifier l'offre d'emploi</h1>
    <form action="{{ route('opportunites.update', $opportunity) }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')
        <div>
            <label class="block font-semibold">Intitulé du poste</label>
            <input type="text" name="title" class="w-full border rounded px-3 py-2" value="{{ old('title', $opportunity->title) }}" required>
        </div>
        <div>
            <label class="block font-semibold">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" rows="4" required>{{ old('description', $opportunity->description) }}</textarea>
        </div>
        <div>
            <label class="block font-semibold">Entreprise/Organisme</label>
            <input type="text" name="company" class="w-full border rounded px-3 py-2" value="{{ old('company', $opportunity->company) }}">
        </div>
        <div>
            <label class="block font-semibold">Lieu</label>
            <input type="text" name="location" class="w-full border rounded px-3 py-2" value="{{ old('location', $opportunity->location) }}">
        </div>
        <div>
            <label class="block font-semibold">Email de contact</label>
            <input type="email" name="contact_email" class="w-full border rounded px-3 py-2" value="{{ old('contact_email', $opportunity->contact_email) }}">
        </div>
        <div>
            <label class="block font-semibold">Lien externe</label>
            <input type="url" name="external_link" class="w-full border rounded px-3 py-2" value="{{ old('external_link', $opportunity->external_link) }}">
        </div>
        <div>
            <label class="block font-semibold">Pièce jointe (CV, fiche PDF...)</label>
            <input type="file" name="attachment" class="w-full">
            @if($opportunity->attachment_path)
                <a href="{{ asset('storage/'.$opportunity->attachment_path) }}" target="_blank" class="text-blue-500 underline text-xs">Voir le document actuel</a>
            @endif
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection 
 
 