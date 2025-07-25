@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto py-8">
    <a href="{{ route('opportunites-talents.public') }}" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Retour</a>
    <div class="bg-white rounded-xl shadow p-6 border">
        <h1 class="text-2xl font-bold mb-6 text-center">Proposer une fiche</h1>
        <form action="#" method="POST" enctype="multipart/form-data" id="submit-form">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold mb-1">Type de fiche</label>
                <select name="type" id="type-select" class="w-full border rounded px-3 py-2" required>
                    <option value="">Choisir...</option>
                    <option value="emploi">Offre d'emploi</option>
                    <option value="projet">Projet à soutenir</option>
                    <option value="talent">Jeune talentueux</option>
                </select>
            </div>
            <div id="fields-emploi" class="type-fields hidden">
                <label class="block font-semibold mb-1">Intitulé du poste</label>
                <input type="text" name="title_emploi" class="w-full border rounded px-3 py-2 mb-2">
                <label class="block font-semibold mb-1">Entreprise/Organisme</label>
                <input type="text" name="company_emploi" class="w-full border rounded px-3 py-2 mb-2">
                <label class="block font-semibold mb-1">Lieu</label>
                <input type="text" name="location_emploi" class="w-full border rounded px-3 py-2 mb-2">
                <label class="block font-semibold mb-1">Description</label>
                <textarea name="description_emploi" class="w-full border rounded px-3 py-2 mb-2" rows="4"></textarea>
                <label class="block font-semibold mb-1">Email de contact</label>
                <input type="email" name="contact_email_emploi" class="w-full border rounded px-3 py-2 mb-2">
                <label class="block font-semibold mb-1">Lien externe</label>
                <input type="url" name="external_link_emploi" class="w-full border rounded px-3 py-2 mb-2">
                <label class="block font-semibold mb-1">Pièce jointe (CV, fiche PDF...)</label>
                <input type="file" name="attachment_emploi" class="w-full mb-2">
            </div>
            <div id="fields-projet" class="type-fields hidden">
                <label class="block font-semibold mb-1">Nom du projet</label>
                <input type="text" name="title_projet" class="w-full border rounded px-3 py-2 mb-2">
                <label class="block font-semibold mb-1">Nom du porteur</label>
                <input type="text" name="owner_name_projet" class="w-full border rounded px-3 py-2 mb-2">
                <label class="block font-semibold mb-1">Description</label>
                <textarea name="description_projet" class="w-full border rounded px-3 py-2 mb-2" rows="4"></textarea>
                <label class="block font-semibold mb-1">Email de contact</label>
                <input type="email" name="contact_email_projet" class="w-full border rounded px-3 py-2 mb-2">
                <label class="block font-semibold mb-1">Lien externe</label>
                <input type="url" name="external_link_projet" class="w-full border rounded px-3 py-2 mb-2">
                <label class="block font-semibold mb-1">Pièce jointe (image, doc...)</label>
                <input type="file" name="attachment_projet" class="w-full mb-2">
            </div>
            <div id="fields-talent" class="type-fields hidden">
                <label class="block font-semibold mb-1">Nom</label>
                <input type="text" name="name_talent" class="w-full border rounded px-3 py-2 mb-2">
                <label class="block font-semibold mb-1">Domaine d'excellence</label>
                <input type="text" name="domain_talent" class="w-full border rounded px-3 py-2 mb-2">
                <label class="block font-semibold mb-1">Description / Bio</label>
                <textarea name="description_talent" class="w-full border rounded px-3 py-2 mb-2" rows="4"></textarea>
                <label class="block font-semibold mb-1">Photo</label>
                <input type="file" name="photo_talent" class="w-full mb-2">
                <label class="block font-semibold mb-1">CV (PDF, doc...)</label>
                <input type="file" name="cv_talent" class="w-full mb-2">
                <label class="block font-semibold mb-1">Lien externe (portfolio, réseaux...)</label>
                <input type="url" name="external_link_talent" class="w-full border rounded px-3 py-2 mb-2">
            </div>
            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded">Envoyer la fiche</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.getElementById('type-select').addEventListener('change', function() {
        document.querySelectorAll('.type-fields').forEach(f => f.classList.add('hidden'));
        if(this.value === 'emploi') document.getElementById('fields-emploi').classList.remove('hidden');
        if(this.value === 'projet') document.getElementById('fields-projet').classList.remove('hidden');
        if(this.value === 'talent') document.getElementById('fields-talent').classList.remove('hidden');
    });
</script>
@endsection 
 
 