@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto py-8">
    <a href="{{ route('opportunites-talents.public') }}" class="text-green-500 hover:underline mb-4 inline-block">&larr; Retour aux opportunités</a>
    <div class="bg-white rounded-xl shadow p-6 border border-green-100">
        <h1 class="text-2xl font-bold text-green-700 mb-2">{{ $project->title }}</h1>
        <div class="text-gray-600 mb-2">Porté par : {{ $project->owner_name }}</div>
        @if($project->attachment_path)
            <img src="{{ asset('storage/'.$project->attachment_path) }}" alt="Projet" class="w-full max-h-64 object-cover rounded mb-4">
        @endif
        <div class="mb-4 text-gray-800">{!! nl2br(e($project->description)) !!}</div>
        @if($project->external_link)
            <a href="{{ $project->external_link }}" target="_blank" class="text-green-600 underline">Lien externe</a>
        @endif
        @if($project->contact_email)
            <div class="mt-4 text-sm text-gray-500">Contact : <a href="mailto:{{ $project->contact_email }}" class="underline">{{ $project->contact_email }}</a></div>
        @endif
    </div>
</div>
@endsection 
 
 