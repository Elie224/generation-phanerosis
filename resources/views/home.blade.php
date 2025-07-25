@extends('layouts.app')
@section('content')
{{-- Bannière et logo supprimés --}}
<div class="py-12 mb-8">
    <div class="max-w-4xl mx-auto flex flex-col items-center text-center">
        <h1 class="text-4xl font-bold mb-2">Bienvenue sur Génération Phanérosis</h1>
        <p class="text-lg mb-4">Une Jeunesse Motivée Par Le Saint Esprit</p>
        @guest
        <div class="mt-6">
            <a href="{{ route('login') }}" class="bg-orange-600 text-white px-6 py-2 rounded shadow hover:bg-orange-700">Connexion</a>
            <a href="{{ route('register') }}" class="ml-2 bg-white text-orange-600 border border-orange-300 px-6 py-2 rounded shadow hover:bg-orange-50">Inscription</a>
        </div>
        @endguest
    </div>
</div>
<div class="max-w-6xl mx-auto px-4">
    <div class="grid md:grid-cols-2 gap-8 mb-12">
        <!-- Bloc actualités/annonces récentes -->
        <div>
            <h2 class="text-2xl font-bold mb-4 text-orange-700 flex items-center gap-2"><svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Actualités & Annonces</h2>
            @php $annonces = \App\Models\Announcement::latest()->take(3)->get(); @endphp
            @forelse($annonces as $ann)
                <div class="bg-white rounded shadow p-4 mb-3 border-l-4 border-orange-400">
                    @if($ann->attachment)
                        @php
                            $ext = strtolower(pathinfo($ann->attachment, PATHINFO_EXTENSION));
                        @endphp
                        @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                            <img src="{{ asset('storage/'.$ann->attachment) }}" alt="Image annonce" class="w-full max-h-48 object-cover rounded mb-2">
                        @endif
                    @endif
                    <div class="font-bold text-lg text-orange-700">{{ $ann->title }}</div>
                    <div class="text-gray-600 text-sm mb-2">{{ $ann->created_at->format('d/m/Y') }}</div>
                    <div class="text-gray-700">{!! Str::limit($ann->content, 100) !!}</div>
                    <a href="{{ route('announcements.show', $ann->id) }}" class="text-orange-600 hover:underline text-sm mt-2 inline-block">Voir l'annonce</a>
                </div>
            @empty
                <div class="text-gray-400">Aucune annonce pour le moment.</div>
            @endforelse
        </div>
        <!-- Bloc à la une -->
        <div>
            <h2 class="text-2xl font-bold mb-4 text-turquoise-700 flex items-center gap-2"><svg class="w-6 h-6 text-turquoise-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>À la une</h2>
            @php $talent = \App\Models\YoungTalent::latest()->first(); @endphp
            @if($talent)
                <div class="bg-white rounded shadow p-4 flex flex-col items-center border-l-4 border-turquoise-400">
                    @if($talent->photo_path)
                        <img src="{{ asset('storage/'.$talent->photo_path) }}" alt="Photo" class="w-20 h-20 object-cover rounded-full border mb-2">
                    @endif
                    <div class="font-bold text-lg text-turquoise-700">{{ $talent->name }}</div>
                    <div class="text-turquoise-600 text-sm mb-2">{{ $talent->domain }}</div>
                    <div class="text-gray-700 text-center mb-2">{!! Str::limit($talent->description, 80) !!}</div>
                    <a href="{{ route('opportunites-talents.talent', $talent->id) }}" class="text-turquoise-600 hover:underline text-sm">Voir le profil</a>
                </div>
            @else
                <div class="text-gray-400">Aucun talent à la une pour le moment.</div>
            @endif
        </div>
    </div>
    <!-- Présentation rapide -->
    <div class="bg-white rounded-xl shadow p-6 mb-12 text-center">
        <h2 class="text-2xl font-bold mb-2 text-orange-700">Qui sommes-nous ?</h2>
        <p class="text-gray-700 mb-2">Génération Phanérosis est une plateforme communautaire chrétienne dédiée à l’édification, au partage, à la solidarité et à la valorisation des talents des jeunes. Retrouvez enseignements, annonces, opportunités, témoignages et entraidez-vous dans la foi et la vie professionnelle !</p>
        <p class="text-gray-500">Notre mission : révéler, encourager, connecter et soutenir chaque membre.</p>
    </div>
</div>
<footer class="bg-orange-50 py-6 mt-12 border-t border-orange-200">
    <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between text-gray-600 text-sm px-4 gap-2">
        <div>&copy; {{ date('Y') }} Génération Phanérosis. Tous droits réservés.</div>
        <div class="flex gap-4">
            <a href="#" class="hover:underline">Contact</a>
            <a href="#" class="hover:underline">Mentions légales</a>
            <a href="#" class="hover:underline">Facebook</a>
        </div>
    </div>
</footer>
@endsection 