@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold text-gray-900">Ajouter un enseignement</h1>
            <p class="mt-1 text-sm text-gray-600">Créez un nouvel enseignement ou une prédication.</p>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.teachings.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Titre de l'enseignement *</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div>
                        <label for="pastor" class="block text-sm font-medium text-gray-700">Pasteur/Prédicateur *</label>
                        <input type="text" name="pastor" id="pastor" value="{{ old('pastor') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                </div>

                <div class="mt-6">
                    <label for="teaching_date" class="block text-sm font-medium text-gray-700">Date de l'enseignement *</label>
                    <input type="date" name="teaching_date" id="teaching_date" value="{{ old('teaching_date') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div class="mt-6">
                    <label for="content" class="block text-sm font-medium text-gray-700">Contenu de l'enseignement *</label>
                    <textarea name="content" id="content" rows="10" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('content') }}</textarea>
                    <p class="mt-2 text-sm text-gray-500">Vous pouvez utiliser du HTML pour formater le contenu.</p>
                </div>

                <div class="grid grid-cols-1 gap-6 mt-6 sm:grid-cols-2">
                    <div>
                        <label for="media_file" class="block text-sm font-medium text-gray-700">Fichier média (audio/vidéo)</label>
                        <input type="file" name="media_file" id="media_file" accept=".mp3,.mp4,.wav,.avi,.mov" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-2 text-sm text-gray-500">Formats acceptés : MP3, MP4, WAV, AVI, MOV (max 100MB)</p>
                    </div>

                    <div>
                        <label for="image_file" class="block text-sm font-medium text-gray-700">Image de couverture</label>
                        <input type="file" name="image_file" id="image_file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-2 text-sm text-gray-500">Formats acceptés : JPEG, PNG, JPG, GIF (max 2MB)</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.teachings') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Créer l'enseignement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 
 
 