@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <a href="{{ route('teachings.index') }}" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Retour à la liste</a>
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-2">{{ $teaching->title }}</h1>
        <div class="text-gray-600 text-sm mb-4">Par <span class="font-bold">{{ $teaching->pastor }}</span> — {{ \Carbon\Carbon::parse($teaching->teaching_date)->format('d/m/Y') }}</div>
        @if($teaching->image_path)
            <img src="{{ asset('storage/' . $teaching->image_path) }}" alt="Image" class="w-full max-h-64 object-cover rounded mb-4">
        @endif
        <div class="prose max-w-none mb-4">{!! nl2br(e($teaching->content)) !!}</div>
        @if($teaching->media_path)
            @php
                $ext = pathinfo($teaching->media_path, PATHINFO_EXTENSION);
            @endphp
            @if(in_array(strtolower($ext), ['mp3','wav','ogg']))
                <div class="mb-4">
                    <audio controls class="w-full">
                        <source src="{{ asset('storage/' . $teaching->media_path) }}" type="audio/{{ $ext }}">
                        Votre navigateur ne supporte pas l'audio.
                    </audio>
                </div>
            @elseif(in_array(strtolower($ext), ['mp4','webm']))
                <div class="mb-4">
                    <video controls class="w-full max-h-96">
                        <source src="{{ asset('storage/' . $teaching->media_path) }}" type="video/{{ $ext }}">
                        Votre navigateur ne supporte pas la vidéo.
                    </video>
                </div>
            @else
                <a href="{{ asset('storage/' . $teaching->media_path) }}" download class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">Télécharger le fichier</a>
            @endif
        @endif
    </div>
</div>
@endsection 
 
 