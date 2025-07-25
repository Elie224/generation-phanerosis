@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto py-8">
    <a href="{{ route('opportunites-talents.public') }}" class="text-purple-500 hover:underline mb-4 inline-block">&larr; Retour aux opportunités</a>
    <div class="bg-white rounded-xl shadow p-6 border border-purple-100 flex flex-col items-center">
        @if($talent->photo_path)
            <img src="{{ asset('storage/'.$talent->photo_path) }}" alt="Photo" class="w-24 h-24 object-cover rounded-full border mb-4">
        @endif
        <h1 class="text-2xl font-bold text-purple-700 mb-2 text-center">{{ $talent->name }}</h1>
        <div class="text-purple-600 mb-2 text-center">{{ $talent->domain }}</div>
        <div class="mb-4 text-gray-800 text-center">{!! nl2br(e($talent->description)) !!}</div>
        <div class="flex gap-4 mt-2">
            @if($talent->cv_path)
                <a href="{{ asset('storage/'.$talent->cv_path) }}" target="_blank" class="text-purple-500 underline">Voir le CV</a>
            @endif
            @if($talent->external_link)
                <a href="{{ $talent->external_link }}" target="_blank" class="text-purple-600 underline">Lien externe</a>
            @endif
        </div>
    </div>
</div>
@endsection 
 
 