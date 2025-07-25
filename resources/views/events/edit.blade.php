@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Modifier l'événement</h1>
            <p class="mt-2 text-gray-600">Modifiez les informations de votre événement</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Titre -->
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre de l'événement *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('title')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type d'événement -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type d'événement *</label>
                        <select name="type" id="type" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Sélectionner un type</option>
                            <option value="culte" {{ old('type', $event->type) == 'culte' ? 'selected' : '' }}>Culte</option>
                            <option value="formation" {{ old('type', $event->type) == 'formation' ? 'selected' : '' }}>Formation</option>
                            <option value="reunion" {{ old('type', $event->type) == 'reunion' ? 'selected' : '' }}>Réunion</option>
                            <option value="priere" {{ old('type', $event->type) == 'priere' ? 'selected' : '' }}>Prière</option>
                            <option value="general" {{ old('type', $event->type) == 'general' ? 'selected' : '' }}>Général</option>
                        </select>
                        @error('type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Couleur -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-2">Couleur</label>
                        <input type="color" name="color" id="color" value="{{ old('color', $event->color) }}"
                               class="w-full h-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('color')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date et heure de début -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Date et heure de début *</label>
                        <input type="datetime-local" name="start_date" id="start_date" 
                               value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('start_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date et heure de fin -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Date et heure de fin *</label>
                        <input type="datetime-local" name="end_date" id="end_date" 
                               value="{{ old('end_date', $event->end_date->format('Y-m-d\TH:i')) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('end_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lieu -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Lieu</label>
                        <input type="text" name="location" id="location" value="{{ old('location', $event->location) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Adresse ou lieu de l'événement">
                        @error('location')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nombre maximum de participants -->
                    <div>
                        <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-2">Nombre maximum de participants</label>
                        <input type="number" name="max_participants" id="max_participants" value="{{ old('max_participants', $event->max_participants) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Laissez vide si illimité">
                        @error('max_participants')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Organisateur -->
                    <div>
                        <label for="organizer_name" class="block text-sm font-medium text-gray-700 mb-2">Nom de l'organisateur *</label>
                        <input type="text" name="organizer_name" id="organizer_name" value="{{ old('organizer_name', $event->organizer_name) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('organizer_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email de contact -->
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Email de contact</label>
                        <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $event->contact_email) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('contact_email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone de contact -->
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone de contact</label>
                        <input type="tel" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $event->contact_phone) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('contact_phone')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image -->
                    <div class="md:col-span-2">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Image de l'événement</label>
                        @if($event->image_path)
                        <div class="mb-4">
                            <img src="{{ asset('storage/'.$event->image_path) }}" alt="Image actuelle" class="w-32 h-32 object-cover rounded-lg">
                            <p class="text-sm text-gray-500 mt-1">Image actuelle</p>
                        </div>
                        @endif
                        <input type="file" name="image" id="image" accept="image/*"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-sm text-gray-500 mt-1">Formats acceptés : JPEG, PNG, JPG, GIF (max 2MB). Laissez vide pour conserver l'image actuelle.</p>
                        @error('image')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea name="description" id="description" rows="6" required
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Décrivez votre événement...">{{ old('description', $event->description) }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informations supplémentaires -->
                    <div class="md:col-span-2">
                        <label for="additional_info" class="block text-sm font-medium text-gray-700 mb-2">Informations supplémentaires</label>
                        <textarea name="additional_info" id="additional_info" rows="4"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Informations complémentaires, instructions spéciales...">{{ old('additional_info', $event->additional_info) }}</textarea>
                        @error('additional_info')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Options -->
                    <div class="md:col-span-2">
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_public" id="is_public" value="1" 
                                       {{ old('is_public', $event->is_public) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_public" class="ml-2 block text-sm text-gray-700">
                                    Événement public (visible par tous)
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="requires_registration" id="requires_registration" value="1" 
                                       {{ old('requires_registration', $event->requires_registration) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="requires_registration" class="ml-2 block text-sm text-gray-700">
                                    Nécessite une inscription
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end gap-4 mt-8 pt-6 border-t">
                    <a href="{{ route('events.show', $event->id) }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">
                        Annuler
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Validation côté client pour les dates
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = this.value;
    const endDateInput = document.getElementById('end_date');
    
    if (startDate && endDateInput.value && endDateInput.value <= startDate) {
        endDateInput.value = '';
        alert('La date de fin doit être postérieure à la date de début.');
    }
});

document.getElementById('end_date').addEventListener('change', function() {
    const endDate = this.value;
    const startDate = document.getElementById('start_date').value;
    
    if (endDate && startDate && endDate <= startDate) {
        alert('La date de fin doit être postérieure à la date de début.');
        this.value = '';
    }
});
</script>
@endsection 