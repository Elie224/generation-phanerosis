@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Annonces de l'église</h1>
    <div class="row g-4">
        @foreach($announcements as $annonce)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 hover-shadow position-relative overflow-hidden" style="transition:box-shadow .2s;">
                    @if($annonce->attachment)
                        @php
                            $ext = strtolower(pathinfo($annonce->attachment, PATHINFO_EXTENSION));
                        @endphp
                        @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                            <img src="{{ asset('storage/'.$annonce->attachment) }}" alt="Image annonce" class="card-img-top" style="max-height:220px;object-fit:cover;">
                        @elseif(in_array($ext, ['mp4','mov','avi','webm']))
                            <video controls class="card-img-top" style="max-height:220px;object-fit:cover;">
                                <source src="{{ asset('storage/'.$annonce->attachment) }}" type="video/{{ $ext == 'mp4' ? 'mp4' : ($ext == 'webm' ? 'webm' : 'ogg') }}">
                                Votre navigateur ne supporte pas la lecture vidéo.
                            </video>
                        @endif
                    @endif
                    <div class="card-body d-flex flex-column">
                        <div class="position-absolute top-0 start-0 bg-dark bg-opacity-50 text-white px-3 py-1 rounded-bottom-end small" style="z-index:2;">
                            {{ $annonce->published_at ? \Illuminate\Support\Carbon::parse($annonce->published_at)->format('d/m/Y') : '-' }}
                        </div>
                        <h5 class="card-title mt-2 mb-1 fw-bold" style="font-size:1.15em;">
                            <a href="{{ route('announcements.show', $annonce->id) }}" class="text-decoration-none text-dark">{{ $annonce->title }}</a>
                        </h5>
                        <p class="card-text flex-grow-1">{{ Str::limit($annonce->content, 100) }}</p>
                        <a href="{{ route('announcements.show', $annonce->id) }}" class="btn btn-sm btn-primary mt-auto align-self-start">Lire la suite</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $announcements->links() }}
    </div>
</div>
@endsection 
 
 