@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Enseignements des pasteurs</h2>
    @if($teachings->isEmpty())
        <div class="text-center text-gray-500 py-12">Aucun enseignement pour le moment.</div>
    @else
        <div class="grid gap-6">
            @foreach($teachings as $teaching)
                <div class="bg-white rounded-lg shadow flex flex-col sm:flex-row items-center p-4 gap-4">
                    @if($teaching->image_path)
                        <img src="{{ asset('storage/' . $teaching->image_path) }}" alt="Image" class="w-32 h-32 object-cover rounded-lg border">
                    @endif
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold mb-1">{{ $teaching->title }}</h3>
                        <div class="text-gray-600 text-sm mb-2">Par <span class="font-bold">{{ $teaching->pastor }}</span> — {{ \Carbon\Carbon::parse($teaching->teaching_date)->format('d/m/Y') }}</div>
                        <p class="text-gray-700 line-clamp-2">{{ Str::limit(strip_tags($teaching->content), 120) }}</p>
                        <a href="{{ route('teachings.show', $teaching->id) }}" class="inline-block mt-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">Voir</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $teachings->links() }}</div>
    @endif
</div>
@endsection 
 
 