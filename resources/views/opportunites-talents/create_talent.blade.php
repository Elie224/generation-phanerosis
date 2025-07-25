@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Nouveau jeune talentueux</h1>
    <form action="{{ route('jeunes-talents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        <div>
            <label class="block font-semibold">Nom</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block font-semibold">Domaine d'excellence</label>
            <input type="text" name="domain" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block font-semibold">Description / Bio</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" rows="4"></textarea>
        </div>
        <div>
            <label class="block font-semibold">Photo</label>
            <input type="file" name="photo" class="w-full">
        </div>
        <div>
            <label class="block font-semibold">CV (PDF, doc...)</label>
            <input type="file" name="cv" class="w-full">
        </div>
        <div>
            <label class="block font-semibold">Lien externe (portfolio, réseaux...)</label>
            <input type="url" name="external_link" class="w-full border rounded px-3 py-2">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded">Enregistrer</button>
        </div>
    </form>
</div>
@endsection 
 
 