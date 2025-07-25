@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Bibliothèque de ressources</h1>
                <p class="mt-2 text-gray-600">Documents, vidéos, podcasts et liens utiles pour votre édification</p>
            </div>
        </div>

        <!-- Barre de recherche -->
        <div class="bg-white rounded-lg shadow p-4 mb-8">
            <form action="{{ route('resources.search') }}" method="GET" class="flex gap-4">
                <input type="text" name="q" placeholder="Rechercher dans les ressources..." 
                       class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       value="{{ request('q') }}">
                <button type="submit" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">
                    Rechercher
                </button>
            </form>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow p-4 mb-8">
            <form method="GET" class="flex flex-wrap gap-4">
                <select name="category" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                
                <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les types</option>
                    <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Documents</option>
                    <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Vidéos</option>
                    <option value="audio" {{ request('type') == 'audio' ? 'selected' : '' }}>Audio</option>
                    <option value="link" {{ request('type') == 'link' ? 'selected' : '' }}>Liens</option>
                    <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Images</option>
                </select>
                
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Filtrer
                </button>
                
                @if(request('category') || request('type'))
                    <a href="{{ route('resources.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                        Effacer
                    </a>
                @endif
            </form>
        </div>

        <!-- Ressources en vedette -->
        @if($featuredResources->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Ressources en vedette</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredResources as $resource)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        @if($resource->thumbnail_path)
                            <img src="{{ asset('storage/'.$resource->thumbnail_path) }}" alt="Thumbnail" class="w-full h-full object-cover">
                        @else
                            <div class="text-gray-400">
                                @switch($resource->type)
                                    @case('video')
                                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        @break
                                    @case('audio')
                                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                        </svg>
                                        @break
                                    @case('document')
                                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        @break
                                    @default
                                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                        </svg>
                                @endswitch
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($resource->type) }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $resource->file_size_formatted }}</span>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $resource->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($resource->description, 100) }}</p>
                        
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                            <span>{{ $resource->category->name }}</span>
                            <div class="flex items-center space-x-2">
                                <span>{{ $resource->views_count }} vues</span>
                                <span>{{ $resource->downloads_count }} téléchargements</span>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <a href="{{ route('resources.show', $resource) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                Voir les détails
                            </a>
                            @if($resource->file_path)
                                <a href="{{ route('resources.download', $resource) }}" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                    Télécharger
                                </a>
                            @elseif($resource->external_url)
                                <a href="{{ $resource->external_url }}" target="_blank" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                    Voir le lien
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Toutes les ressources -->
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Toutes les ressources</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($resources as $resource)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        @if($resource->thumbnail_path)
                            <img src="{{ asset('storage/'.$resource->thumbnail_path) }}" alt="Thumbnail" class="w-full h-full object-cover">
                        @else
                            <div class="text-gray-400">
                                @switch($resource->type)
                                    @case('video')
                                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        @break
                                    @case('audio')
                                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                        </svg>
                                        @break
                                    @case('document')
                                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        @break
                                    @default
                                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                        </svg>
                                @endswitch
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ ucfirst($resource->type) }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $resource->file_size_formatted }}</span>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $resource->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($resource->description, 100) }}</p>
                        
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                            <span>{{ $resource->category->name }}</span>
                            <div class="flex items-center space-x-2">
                                <span>{{ $resource->views_count }} vues</span>
                                <span>{{ $resource->downloads_count }} téléchargements</span>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <a href="{{ route('resources.show', $resource) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                Voir les détails
                            </a>
                            @if($resource->file_path)
                                <a href="{{ route('resources.download', $resource) }}" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                    Télécharger
                                </a>
                            @elseif($resource->external_url)
                                <a href="{{ $resource->external_url }}" target="_blank" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                    Voir le lien
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune ressource trouvée</h3>
                    <p class="text-gray-600">Aucune ressource ne correspond à vos critères de recherche.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $resources->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 