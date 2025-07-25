@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Nouveau projet à soutenir</h1>
    <form action="{{ route('projets-talents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        <div>
            <label class="block font-semibold">Nom du projet</label>
            <input type="text" name="title" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block font-semibold">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" rows="4" required></textarea>
        </div>
        <div>
            <label class="block font-semibold">Nom du porteur</label>
            <input type="text" name="owner_name" class="w-full border rounded px-3 py-2" required>
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
            <label class="block font-semibold">Pièce jointe (image, doc...)</label>
            <input type="file" name="attachment" class="w-full">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded">Enregistrer</button>
        </div>
    </form>
</div>
@endsection 
 
 