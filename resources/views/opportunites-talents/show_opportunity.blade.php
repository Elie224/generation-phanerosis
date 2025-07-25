@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto py-8">
    <a href="{{ route('opportunites-talents.public') }}" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Retour aux opportunités</a>
    <div class="bg-white rounded-xl shadow p-6 border border-blue-100">
        <h1 class="text-2xl font-bold text-blue-700 mb-2">{{ $opportunity->title }}</h1>
        <div class="text-gray-600 mb-2">{{ $opportunity->company }} @if($opportunity->location) - {{ $opportunity->location }}@endif</div>
        <div class="mb-4 text-gray-800">{!! nl2br(e($opportunity->description)) !!}</div>
        @if($opportunity->attachment_path)
            <a href="{{ asset('storage/'.$opportunity->attachment_path) }}" target="_blank" class="text-blue-500 underline">Voir le document</a>
        @endif
        @if($opportunity->external_link)
            <a href="{{ $opportunity->external_link }}" target="_blank" class="ml-4 text-blue-600 underline">Lien externe</a>
        @endif
        @if($opportunity->contact_email)
            <div class="mt-4 text-sm text-gray-500">Contact : <a href="mailto:{{ $opportunity->contact_email }}" class="underline">{{ $opportunity->contact_email }}</a></div>
        @endif
    </div>
</div>
@endsection 
 
 