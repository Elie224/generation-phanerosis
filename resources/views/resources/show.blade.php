@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Fil d'Ariane -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('resources.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Ressources
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('resources.category', $resource->category) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                            {{ $resource->category->name }}
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $resource->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- En-tête de la ressource -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            <div class="md:flex">
                <!-- Thumbnail -->
                <div class="md:w-1/3">
                    <div class="h-64 md:h-full bg-gray-200 flex items-center justify-center">
                        @if($resource->thumbnail_path)
                            <img src="{{ asset('storage/'.$resource->thumbnail_path) }}" alt="Thumbnail" class="w-full h-full object-cover">
                        @else
                            <div class="text-gray-400 text-center">
                                @switch($resource->type)
                                    @case('video')
                                        <svg class="w-24 h-24 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        <p>Vidéo</p>
                                        @break
                                    @case('audio')
                                        <svg class="w-24 h-24 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                        </svg>
                                        <p>Audio</p>
                                        @break
                                    @case('document')
                                        <svg class="w-24 h-24 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p>Document</p>
                                        @break
                                    @default
                                        <svg class="w-24 h-24 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                        </svg>
                                        <p>Lien</p>
                                @endswitch
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Informations -->
                <div class="md:w-2/3 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($resource->type) }}
                        </span>
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <span>{{ $resource->views_count }} vues</span>
                            <span>•</span>
                            <span>{{ $resource->downloads_count }} téléchargements</span>
                        </div>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $resource->title }}</h1>
                    
                    <div class="flex items-center text-sm text-gray-600 mb-4">
                        <span>Par {{ $resource->user->name }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ $resource->created_at->format('d/m/Y') }}</span>
                        @if($resource->publication_date)
                            <span class="mx-2">•</span>
                            <span>Publié le {{ $resource->publication_date->format('d/m/Y') }}</span>
                        @endif
                    </div>

                    <p class="text-gray-700 mb-6">{{ $resource->description }}</p>

                    <!-- Métadonnées -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        @if($resource->author)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Auteur:</span>
                            <p class="text-sm text-gray-900">{{ $resource->author }}</p>
                        </div>
                        @endif
                        
                        @if($resource->publisher)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Éditeur:</span>
                            <p class="text-sm text-gray-900">{{ $resource->publisher }}</p>
                        </div>
                        @endif
                        
                        @if($resource->file_size_formatted != 'N/A')
                        <div>
                            <span class="text-sm font-medium text-gray-500">Taille:</span>
                            <p class="text-sm text-gray-900">{{ $resource->file_size_formatted }}</p>
                        </div>
                        @endif
                        
                        @if($resource->duration_formatted)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Durée:</span>
                            <p class="text-sm text-gray-900">{{ $resource->duration_formatted }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Tags -->
                    @if($resource->tags_array)
                    <div class="mb-6">
                        <span class="text-sm font-medium text-gray-500">Tags:</span>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($resource->tags_array as $tag)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ trim($tag) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex flex-wrap gap-3">
                        @if($resource->file_path)
                            <a href="{{ route('resources.download', $resource) }}" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Télécharger
                            </a>
                        @elseif($resource->external_url)
                            <a href="{{ $resource->external_url }}" target="_blank" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Voir le lien
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Ressources similaires -->
        @if($similarResources->count() > 0)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Ressources similaires</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($similarResources as $similar)
                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ ucfirst($similar->type) }}
                        </span>
                        <span class="text-xs text-gray-500">{{ $similar->file_size_formatted }}</span>
                    </div>
                    
                    <h3 class="font-semibold text-gray-900 mb-2">{{ Str::limit($similar->title, 50) }}</h3>
                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($similar->description, 80) }}</p>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500">{{ $similar->category->name }}</span>
                        <a href="{{ route('resources.show', $similar) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Voir
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection 