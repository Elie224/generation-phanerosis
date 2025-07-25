@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto py-8">
    <a href="{{ route('announcements.index') }}" class="text-yellow-600 hover:underline mb-4 inline-block">&larr; Retour aux annonces</a>
    <div class="bg-white rounded-xl shadow p-6 border border-yellow-100">
        @if($annonce->attachment)
            @php
                $ext = strtolower(pathinfo($annonce->attachment, PATHINFO_EXTENSION));
            @endphp
            @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                <img src="{{ asset('storage/'.$annonce->attachment) }}" alt="Image annonce" class="w-full max-h-72 object-cover rounded mb-4">
            @elseif(in_array($ext, ['mp4','mov','avi','webm']))
                <video controls class="w-full max-h-72 object-cover rounded mb-4">
                    <source src="{{ asset('storage/'.$annonce->attachment) }}" type="video/{{ $ext == 'mp4' ? 'mp4' : ($ext == 'webm' ? 'webm' : 'ogg') }}">
                    Votre navigateur ne supporte pas la lecture vidéo.
                </video>
            @endif
        @endif
        <h1 class="text-2xl font-bold text-yellow-700 mb-2">{{ $annonce->title }}</h1>
        <div class="text-gray-600 mb-2">{{ $annonce->created_at->format('d/m/Y') }}</div>
        <div class="mb-4 text-gray-800">{!! nl2br(e($annonce->content)) !!}</div>
        @if($annonce->attachment && !in_array($ext, ['jpg','jpeg','png','gif','webp','mp4','mov','avi','webm']))
            <a href="{{ asset('storage/'.$annonce->attachment) }}" target="_blank" class="text-yellow-500 underline">Voir le document</a>
        @endif
    </div>
</div>
@endsection 
 
 