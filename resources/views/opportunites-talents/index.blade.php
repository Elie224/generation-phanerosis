@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Administration - Opportunités & Talents</h1>
    <div class="grid md:grid-cols-3 gap-8">
        <!-- Offres d'emploi -->
        <div>
            <h2 class="text-lg font-semibold mb-2 text-blue-700">Offres d'emploi</h2>
            <a href="{{ route('opportunites.create') }}" class="text-blue-500 underline text-sm mb-2 inline-block">+ Nouvelle offre</a>
            @foreach($opportunities as $op)
                <div class="bg-white rounded shadow p-3 mb-2">
                    <div class="font-bold">{{ $op->title }}</div>
                    <div class="text-xs text-gray-500">{{ $op->company }}</div>
                    <div class="flex gap-2 mt-2">
                        <a href="{{ route('opportunites.edit', $op) }}" class="text-blue-600 text-xs">Modifier</a>
                        <form action="{{ route('opportunites.destroy', $op) }}" method="POST" onsubmit="return confirm('Supprimer ?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 text-xs">Supprimer</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Projets à soutenir -->
        <div>
            <h2 class="text-lg font-semibold mb-2 text-green-700">Projets à soutenir</h2>
            <a href="{{ route('projets-talents.create') }}" class="text-green-500 underline text-sm mb-2 inline-block">+ Nouveau projet</a>
            @foreach($projects as $prj)
                <div class="bg-white rounded shadow p-3 mb-2">
                    <div class="font-bold">{{ $prj->title }}</div>
                    <div class="text-xs text-gray-500">{{ $prj->owner_name }}</div>
                    <div class="flex gap-2 mt-2">
                        <a href="{{ route('projets-talents.edit', $prj) }}" class="text-green-600 text-xs">Modifier</a>
                        <form action="{{ route('projets-talents.destroy', $prj) }}" method="POST" onsubmit="return confirm('Supprimer ?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 text-xs">Supprimer</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Jeunes talentueux -->
        <div>
            <h2 class="text-lg font-semibold mb-2 text-purple-700">Jeunes talentueux</h2>
            <a href="{{ route('jeunes-talents.create') }}" class="text-purple-500 underline text-sm mb-2 inline-block">+ Nouveau talent</a>
            @foreach($talents as $tal)
                <div class="bg-white rounded shadow p-3 mb-2">
                    <div class="font-bold">{{ $tal->name }}</div>
                    <div class="text-xs text-purple-600">{{ $tal->domain }}</div>
                    <div class="flex gap-2 mt-2">
                        <a href="{{ route('jeunes-talents.edit', $tal) }}" class="text-purple-600 text-xs">Modifier</a>
                        <form action="{{ route('jeunes-talents.destroy', $tal) }}" method="POST" onsubmit="return confirm('Supprimer ?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 text-xs">Supprimer</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection 
 
 