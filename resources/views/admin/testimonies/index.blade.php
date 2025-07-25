@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Témoignages</h1>
        @if(auth()->user() && auth()->user()->isAdmin())
            <!-- Bouton Ajouter -->
            <a href="{{ route('admin.temoignages.create') }}" class="btn btn-primary">Ajouter un témoignage</a>
        @endif
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>Titre</th>
                    <th>Témoin</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($testimonies as $temoignage)
                    <tr>
                        <td class="fw-bold">{{ $temoignage->title }}</td>
                        <td>{{ $temoignage->witness_name }}</td>
                        <td><span class="badge bg-info text-dark">{{ ucfirst($temoignage->media_type) }}</span></td>
                        <td>{{ $temoignage->published_at ? \Illuminate\Support\Carbon::parse($temoignage->published_at)->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($temoignage->is_published)
                                <span class="badge bg-success">Publié</span>
                            @else
                                <span class="badge bg-secondary">Non publié</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('temoignages.show', $temoignage) }}" class="btn btn-sm btn-outline-secondary" title="Voir"><i class="fas fa-eye"></i></a>
                            @if(auth()->user() && auth()->user()->isAdmin())
                                <!-- Boutons Modifier/Supprimer -->
                                <a href="{{ route('admin.temoignages.edit', $temoignage->id) }}" class="btn btn-sm btn-warning" title="Modifier"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.temoignages.destroy', $temoignage->id) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Supprimer ce témoignage ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
        {{ $testimonies->links() }}
    </div>
</div>
@endsection 
 
 