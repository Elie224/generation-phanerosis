@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Nouvelle offre d'emploi</h1>
    <form action="{{ route('opportunites.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        <div>
            <label class="block font-semibold">Intitulé du poste</label>
            <input type="text" name="title" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block font-semibold">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" rows="4" required></textarea>
        </div>
        <div>
            <label class="block font-semibold">Entreprise/Organisme</label>
            <input type="text" name="company" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block font-semibold">Lieu</label>
            <input type="text" name="location" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block font-semibold">Email de contact</label>
            <input type="email" name="contact_email" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block font-semibold">Lien externe</label>
            <input type="url" name="external_link" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block font-semibold">Pièce jointe (CV, fiche PDF...)</label>
            <input type="file" name="attachment" class="w-full">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Enregistrer</button>
        </div>
    </form>
</div>
@endsection 
 
 