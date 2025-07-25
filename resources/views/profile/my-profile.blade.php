<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mon Profil') }}
            </h2>
            <a href="{{ route('profile.edit') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Modifier') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Bannière et Avatar -->
            <div class="relative mb-8">
                @if($profile->banner_url)
                    <div class="w-full h-64 rounded-lg overflow-hidden bg-gray-200">
                        <img src="{{ $profile->banner_url }}" alt="Bannière" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="w-full h-64 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                        <p class="text-white text-lg">{{ __('Ajoutez une bannière à votre profil') }}</p>
                    </div>
                @endif
                
                <div class="absolute -bottom-16 left-8">
                    <div class="w-32 h-32 rounded-full border-4 border-white overflow-hidden bg-gray-200 shadow-lg">
                        <img src="{{ $profile->avatar_url }}" alt="Avatar de {{ $user->name }}" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <!-- Informations principales -->
            <div class="ml-40 mb-8">
                <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                @if($profile->ministry_role)
                    <p class="text-lg text-blue-600 font-medium">{{ $profile->ministry_role }}</p>
                @endif
                @if($profile->age_group)
                    <span class="inline-block bg-gray-100 text-gray-800 text-sm px-3 py-1 rounded-full mt-2">
                        {{ ucfirst($profile->age_group) }}
                    </span>
                @endif
                <div class="mt-2">
                    <span class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                        {{ ucfirst($profile->privacy_level) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Colonne principale -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Biographie -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('À propos') }}</h3>
                            @if($profile->bio)
                                <p class="text-gray-700 leading-relaxed">{{ $profile->bio }}</p>
                            @else
                                <p class="text-gray-500 italic">{{ __('Ajoutez une biographie à votre profil...') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Témoignage -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Témoignage') }}</h3>
                            @if($profile->testimony)
                                <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                                    <p class="text-gray-700 italic leading-relaxed">"{{ $profile->testimony }}"</p>
                                </div>
                            @else
                                <p class="text-gray-500 italic">{{ __('Partagez votre témoignage de conversion...') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Informations spirituelles -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Parcours Spirituel') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">{{ __('Conversion') }}</span>
                                    <p class="text-gray-900">
                                        @if($profile->conversion_date)
                                            {{ $profile->conversion_date->format('d/m/Y') }}
                                        @else
                                            <span class="text-gray-400">{{ __('Non renseigné') }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">{{ __('Baptême') }}</span>
                                    <p class="text-gray-900">
                                        @if($profile->baptism_date)
                                            {{ $profile->baptism_date->format('d/m/Y') }}
                                        @else
                                            <span class="text-gray-400">{{ __('Non renseigné') }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">{{ __('Membre depuis') }}</span>
                                    <p class="text-gray-900">
                                        @if($profile->member_since)
                                            {{ $profile->member_since->format('d/m/Y') }}
                                        @else
                                            <span class="text-gray-400">{{ __('Non renseigné') }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">{{ __('Âge') }}</span>
                                    <p class="text-gray-900">
                                        @if($profile->age)
                                            {{ $profile->age }} {{ __('ans') }}
                                        @else
                                            <span class="text-gray-400">{{ __('Non renseigné') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ministères -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Ministères') }}</h3>
                            @if($profile->ministries && count($profile->ministries) > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach($profile->ministries as $ministry)
                                        <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                                            {{ ucfirst($ministry) }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 italic">{{ __('Ajoutez vos ministères de participation...') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Dons spirituels -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Dons Spirituels') }}</h3>
                            @if($profile->spiritual_gifts)
                                <p class="text-gray-700">{{ $profile->spiritual_gifts }}</p>
                            @else
                                <p class="text-gray-500 italic">{{ __('Ajoutez vos dons spirituels...') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    
                    <!-- Informations de contact -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Contact') }}</h3>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-gray-700">{{ $user->email }}</span>
                                </div>
                                @if($profile->phone)
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <span class="text-gray-700">{{ $profile->phone }}</span>
                                    </div>
                                @endif
                                @if($profile->full_address)
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="text-gray-700">{{ $profile->full_address }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Groupe de maison -->
                    @if($profile->small_group)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Groupe de Maison') }}</h3>
                                <p class="text-gray-700">{{ $profile->small_group }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Réseaux sociaux -->
                    @if($profile->facebook_url || $profile->instagram_url || $profile->twitter_url || $profile->linkedin_url || $profile->youtube_url)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Réseaux Sociaux') }}</h3>
                                <div class="flex space-x-3">
                                    @if($profile->facebook_url)
                                        <a href="{{ $profile->facebook_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    @if($profile->instagram_url)
                                        <a href="{{ $profile->instagram_url }}" target="_blank" class="text-pink-600 hover:text-pink-800">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297C4.198 14.895 3.708 13.744 3.708 12.447s.49-2.448 1.297-3.323c.875-.807 2.026-1.297 3.323-1.297s2.448.49 3.323 1.297c.807.875 1.297 2.026 1.297 3.323s-.49 2.448-1.297 3.323c-.875.807-2.026 1.297-3.323 1.297zm7.718-1.297c-.875.807-2.026 1.297-3.323 1.297s-2.448-.49-3.323-1.297c-.807-.875-1.297-2.026-1.297-3.323s.49-2.448 1.297-3.323c.875-.807 2.026-1.297 3.323-1.297s2.448.49 3.323 1.297c.807.875 1.297 2.026 1.297 3.323s-.49 2.448-1.297 3.323z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    @if($profile->twitter_url)
                                        <a href="{{ $profile->twitter_url }}" target="_blank" class="text-blue-400 hover:text-blue-600">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    @if($profile->linkedin_url)
                                        <a href="{{ $profile->linkedin_url }}" target="_blank" class="text-blue-700 hover:text-blue-900">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    @if($profile->youtube_url)
                                        <a href="{{ $profile->youtube_url }}" target="_blank" class="text-red-600 hover:text-red-800">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Statut en ligne -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Statut') }}</h3>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full {{ $profile->is_online ? 'bg-green-500' : 'bg-gray-400' }} mr-3"></div>
                                <span class="text-gray-700">
                                    {{ $profile->is_online ? __('En ligne') : __('Hors ligne') }}
                                </span>
                            </div>
                            @if($profile->last_seen_at)
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ __('Dernière connexion') }}: {{ $profile->last_seen_at->diffForHumans() }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 