@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $teaching->title }}</h1>
                    <p class="mt-1 text-sm text-gray-600">Détails de l'enseignement</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.teachings.edit', $teaching->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Modifier
                    </a>
                    <a href="{{ route('admin.teachings') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations de l'enseignement -->
    <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Titre</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $teaching->title }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Pasteur/Prédicateur</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $teaching->pastor }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date de l'enseignement</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $teaching->teaching_date->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $teaching->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dernière modification</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $teaching->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Médias</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Image de couverture</dt>
                            <dd class="mt-1">
                                @if($teaching->image_path)
                                    <img src="{{ Storage::url($teaching->image_path) }}" alt="Image de couverture" class="w-32 h-32 object-cover rounded">
                                @else
                                    <span class="text-sm text-gray-500">Aucune image</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fichier média</dt>
                            <dd class="mt-1">
                                @if($teaching->media_path)
                                    <a href="{{ Storage::url($teaching->media_path) }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                                        </svg>
                                        Écouter/Télécharger
                                    </a>
                                @else
                                    <span class="text-sm text-gray-500">Aucun fichier média</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Contenu de l'enseignement</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="prose max-w-none">
                        {!! $teaching->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 