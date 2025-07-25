@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">Mes amis</h2>
        <a href="{{ route('friends.requests') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
            Demandes d'amis
        </a>
    </div>
    
    @if($friends->isEmpty())
        <div class="text-center py-8">
            <p class="text-gray-500 mb-4">Vous n'avez pas encore d'amis.</p>
            <p class="text-sm text-gray-400">Ajoutez des amis pour pouvoir échanger des messages avec eux.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow">
            <ul class="divide-y divide-gray-200">
                @foreach($friends as $friend)
                    <li class="p-4 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-gray-600 font-semibold">{{ substr($friend->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $friend->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $friend->email }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('messages.show', $friend->id) }}" 
                               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                Message
                            </a>
                            <form action="{{ route('friends.delete', $friend->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet ami ?')">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
</ul>
        </div>
    @endif
</div>
@endsection
