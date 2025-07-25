@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-2">
    <h1 class="text-3xl font-bold mb-10 text-center">Opportunités & Talents</h1>
    <form method="GET" action="" class="flex flex-col md:flex-row gap-4 items-center justify-between mb-8">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Recherche par mot-clé, nom, domaine..." class="w-full md:w-1/3 border rounded px-3 py-2" />
        <select name="type" class="border rounded px-3 py-2">
            <option value="">Tous types</option>
            <option value="emploi" @if(request('type')=='emploi') selected @endif>Emplois</option>
            <option value="projet" @if(request('type')=='projet') selected @endif>Projets</option>
            <option value="talent" @if(request('type')=='talent') selected @endif>Talents</option>
        </select>
        <select name="sort" class="border rounded px-3 py-2">
            <option value="recent" @if(request('sort')=='recent') selected @endif>Plus récents</option>
            <option value="old" @if(request('sort')=='old') selected @endif>Plus anciens</option>
            <option value="alpha" @if(request('sort')=='alpha') selected @endif>Ordre alphabétique</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Rechercher</button>
        <a href="{{ route('opportunites-talents.submit') }}" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Proposer une fiche</a>
    </form>
    <div class="grid md:grid-cols-3 gap-8">
        <!-- Offres d'emploi -->
        <div>
            <h2 class="text-xl font-semibold mb-4 text-blue-700 flex items-center gap-2"><svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-7V6a5 5 0 00-10 0v2a5 5 0 0010 0z" /></svg>Offres d'emploi</h2>
            <div class="space-y-4">
            @forelse($opportunities as $op)
                <div class="bg-white rounded-xl shadow p-4 flex flex-col gap-2 border border-blue-100 hover:shadow-lg transition">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-xl">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-7V6a5 5 0 00-10 0v2a5 5 0 0010 0z" /></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">{{ $op->title }}</h3>
                            <div class="text-xs text-gray-500">{{ $op->company }} @if($op->location) - {{ $op->location }}@endif</div>
                        </div>
                    </div>
                    <div class="text-gray-700 text-sm line-clamp-3">{!! nl2br(e(Str::limit($op->description, 120))) !!}</div>
                    <div class="flex gap-2 mt-2">
                        <a href="{{ route('opportunites-talents.opportunity', $op->id) }}" class="text-blue-600 font-semibold hover:underline">Voir plus</a>
                        @if($op->attachment_path)
                            <a href="{{ asset('storage/'.$op->attachment_path) }}" target="_blank" class="text-blue-400 underline text-xs">Document</a>
                        @endif
                        @if($op->external_link)
                            <a href="{{ $op->external_link }}" target="_blank" class="text-blue-400 underline text-xs">Lien</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-gray-400">Aucune offre pour le moment.</div>
            @endforelse
            </div>
        </div>
        <!-- Projets à soutenir -->
        <div>
            <h2 class="text-xl font-semibold mb-4 text-green-700 flex items-center gap-2"><svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 7v7m0 0H9m3 0h3" /></svg>Projets à soutenir</h2>
            <div class="space-y-4">
            @forelse($projects as $prj)
                <div class="bg-white rounded-xl shadow p-4 flex flex-col gap-2 border border-green-100 hover:shadow-lg transition">
                    <div class="flex items-center gap-3">
                        @if($prj->attachment_path)
                            <img src="{{ asset('storage/'.$prj->attachment_path) }}" alt="Projet" class="w-12 h-12 object-cover rounded-full border border-green-200">
                        @else
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 font-bold text-xl">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 7v7m0 0H9m3 0h3" /></svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-bold text-lg">{{ $prj->title }}</h3>
                            <div class="text-xs text-gray-500">Porté par : {{ $prj->owner_name }}</div>
                        </div>
                    </div>
                    <div class="text-gray-700 text-sm line-clamp-3">{!! nl2br(e(Str::limit($prj->description, 120))) !!}</div>
                    <div class="flex gap-2 mt-2">
                        <a href="{{ route('opportunites-talents.project', $prj->id) }}" class="text-green-600 font-semibold hover:underline">Voir plus</a>
                        @if($prj->external_link)
                            <a href="{{ $prj->external_link }}" target="_blank" class="text-green-400 underline text-xs">Lien</a>
                        @endif
                        @if($prj->contact_email)
                            <span class="text-xs text-gray-400">{{ $prj->contact_email }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-gray-400">Aucun projet pour le moment.</div>
            @endforelse
            </div>
        </div>
        <!-- Jeunes talentueux -->
        <div>
            <h2 class="text-xl font-semibold mb-4 text-purple-700 flex items-center gap-2"><svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6" /></svg>Jeunes talentueux</h2>
            <div class="space-y-4">
            @forelse($talents as $tal)
                <div class="bg-white rounded-xl shadow p-4 flex flex-col gap-2 border border-purple-100 hover:shadow-lg transition items-center">
                    @if($tal->photo_path)
                        <img src="{{ asset('storage/'.$tal->photo_path) }}" alt="Photo" class="w-16 h-16 object-cover rounded-full border border-purple-200 mb-2">
                    @else
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold text-xl mb-2">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6" /></svg>
                        </div>
                    @endif
                    <h3 class="font-bold text-lg text-center">{{ $tal->name }}</h3>
                    <div class="text-sm text-purple-600 mb-1">{{ $tal->domain }}</div>
                    <div class="mb-2 text-center text-gray-700 text-sm line-clamp-3">{!! nl2br(e(Str::limit($tal->description, 120))) !!}</div>
                    <div class="flex gap-2 mt-2">
                        <a href="{{ route('opportunites-talents.talent', $tal->id) }}" class="text-purple-600 font-semibold hover:underline">Voir plus</a>
                        @if($tal->cv_path)
                            <a href="{{ asset('storage/'.$tal->cv_path) }}" target="_blank" class="text-purple-400 underline text-xs">CV</a>
                        @endif
                        @if($tal->external_link)
                            <a href="{{ $tal->external_link }}" target="_blank" class="text-purple-400 underline text-xs">Lien</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-gray-400">Aucun talent affiché pour le moment.</div>
            @endforelse
            </div>
        </div>
    </div>
</div>
@endsection 