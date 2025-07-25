@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Ajouter une ressource</h1>
            <p class="mt-2 text-gray-600">Partagez des documents, vidéos, audio ou liens utiles avec la communauté</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <form action="{{ route('resources.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Informations de base -->
                    <div class="md:col-span-2">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations de base</h2>
                    </div>

                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea name="description" id="description" rows="4" required
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Catégorie *</label>
                        <select name="category_id" id="category_id" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Sélectionner une catégorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                        <select name="type" id="type" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Sélectionner un type</option>
                            <option value="document" {{ old('type') == 'document' ? 'selected' : '' }}>Document</option>
                            <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Vidéo</option>
                            <option value="audio" {{ old('type') == 'audio' ? 'selected' : '' }}>Audio</option>
                            <option value="link" {{ old('type') == 'link' ? 'selected' : '' }}>Lien externe</option>
                            <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Image</option>
                        </select>
                        @error('type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fichier ou URL -->
                    <div class="md:col-span-2">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Contenu</h2>
                    </div>

                    <div class="md:col-span-2">
                        <div id="file-upload-section" class="hidden">
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-2">Fichier</label>
                            <input type="file" name="file" id="file" accept=".pdf,.doc,.docx,.txt,.mp4,.avi,.mov,.mp3,.wav,.jpg,.jpeg,.png,.gif"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-sm text-gray-500 mt-1">Taille maximale : 100MB</p>
                            @error('file')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="url-section" class="hidden">
                            <label for="external_url" class="block text-sm font-medium text-gray-700 mb-2">URL externe</label>
                            <input type="url" name="external_url" id="external_url" value="{{ old('external_url') }}"
                                   placeholder="https://example.com"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('external_url')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">Image de prévisualisation</label>
                        <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-sm text-gray-500 mt-1">Format : JPG, PNG, GIF - Taille maximale : 2MB</p>
                        @error('thumbnail')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Métadonnées -->
                    <div class="md:col-span-2">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Métadonnées</h2>
                    </div>

                    <div>
                        <label for="author" class="block text-sm font-medium text-gray-700 mb-2">Auteur</label>
                        <input type="text" name="author" id="author" value="{{ old('author') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('author')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="publisher" class="block text-sm font-medium text-gray-700 mb-2">Éditeur</label>
                        <input type="text" name="publisher" id="publisher" value="{{ old('publisher') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('publisher')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="publication_date" class="block text-sm font-medium text-gray-700 mb-2">Date de publication</label>
                        <input type="date" name="publication_date" id="publication_date" value="{{ old('publication_date') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('publication_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="language" class="block text-sm font-medium text-gray-700 mb-2">Langue</label>
                        <select name="language" id="language"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="fr" {{ old('language', 'fr') == 'fr' ? 'selected' : '' }}>Français</option>
                            <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>Anglais</option>
                            <option value="es" {{ old('language') == 'es' ? 'selected' : '' }}>Espagnol</option>
                            <option value="de" {{ old('language') == 'de' ? 'selected' : '' }}>Allemand</option>
                        </select>
                        @error('language')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="duration-section" class="hidden">
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Durée (en secondes)</label>
                        <input type="number" name="duration" id="duration" value="{{ old('duration') }}" min="1"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('duration')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                        <input type="text" name="tags" id="tags" value="{{ old('tags') }}"
                               placeholder="tag1, tag2, tag3"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-sm text-gray-500 mt-1">Séparez les tags par des virgules</p>
                        @error('tags')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Options -->
                    <div class="md:col-span-2">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Options</h2>
                    </div>

                    <div class="md:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_public" id="is_public" value="1" {{ old('is_public', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_public" class="ml-2 block text-sm text-gray-900">
                                Rendre cette ressource publique
                            </label>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Si désactivé, seuls les membres connectés pourront voir cette ressource</p>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex justify-end gap-4 mt-8 pt-6 border-t">
                    <a href="{{ route('resources.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">
                        Annuler
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        Ajouter la ressource
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const fileSection = document.getElementById('file-upload-section');
    const urlSection = document.getElementById('url-section');
    const durationSection = document.getElementById('duration-section');

    function toggleSections() {
        const selectedType = typeSelect.value;
        
        // Masquer toutes les sections
        fileSection.classList.add('hidden');
        urlSection.classList.add('hidden');
        durationSection.classList.add('hidden');
        
        // Afficher les sections appropriées
        if (['document', 'video', 'audio', 'image'].includes(selectedType)) {
            fileSection.classList.remove('hidden');
        }
        
        if (selectedType === 'link') {
            urlSection.classList.remove('hidden');
        }
        
        if (['video', 'audio'].includes(selectedType)) {
            durationSection.classList.remove('hidden');
        }
    }

    typeSelect.addEventListener('change', toggleSections);
    toggleSections(); // Exécuter au chargement de la page
});
</script>
@endsection 