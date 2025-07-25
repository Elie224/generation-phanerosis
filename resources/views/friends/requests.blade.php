@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <h2 class="text-xl font-bold mb-6">Demandes d'amitié reçues</h2>
    
    @if($requests->isEmpty())
        <div class="text-center py-8">
            <p class="text-gray-500">Aucune demande d'amitié reçue.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow">
            <ul class="divide-y divide-gray-200">
                @foreach($requests as $req)
                    <li class="p-4 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-gray-600 font-semibold">{{ substr($req->user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $req->user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $req->user->email }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <form action="{{ route('friends.accept-request', $req->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                    Accepter
                                </button>
                            </form>
                            <form action="{{ route('friends.reject-request', $req->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                    Refuser
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
