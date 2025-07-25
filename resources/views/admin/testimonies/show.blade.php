@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">{{ $temoignage->title }}</h1>
            <h6 class="card-subtitle mb-2 text-muted">
                {{ $temoignage->published_at ? \Illuminate\Support\Carbon::parse($temoignage->published_at)->format('d/m/Y') : '-' }}
                @if($temoignage->is_published)
                    <span class="badge bg-success ms-2">Publié</span>
                @else
                    <span class="badge bg-secondary ms-2">Non publié</span>
                @endif
            </h6>
            <p class="card-text">{{ $temoignage->description }}</p>
            <p class="card-text"><strong>Témoin :</strong> {{ $temoignage->witness_name }}</p>
            @if($temoignage->media_path)
                @if($temoignage->media_type == 'video')
                    <video controls width="100%" class="mb-3" style="max-height:300px;object-fit:cover;">
                        <source src="{{ asset('storage/'.$temoignage->media_path) }}">
                        Votre navigateur ne supporte pas la lecture vidéo.
                    </video>
                @else
                    <audio controls class="w-100 mb-3">
                        <source src="{{ asset('storage/'.$temoignage->media_path) }}">
                        Votre navigateur ne supporte pas l'audio.
                    </audio>
                @endif
            @endif
            <div class="mt-3">
                <a href="{{ route('temoignages.index') }}" class="btn btn-secondary">Retour à la liste</a>
                <a href="{{ route('temoignages.edit', $temoignage) }}" class="btn btn-warning">Modifier</a>
                <form action="{{ route('temoignages.destroy', $temoignage) }}" method="POST" style="display:inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer ce témoignage ?')">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 
 
 