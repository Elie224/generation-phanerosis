@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <h2 class="text-xl font-bold mb-6">Tous les utilisateurs</h2>
    
    @if($users->isEmpty())
        <div class="text-center py-8">
            <p class="text-gray-500">Aucun autre utilisateur trouvé.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow">
            <ul class="divide-y divide-gray-200">
                @foreach($users as $user)
                    @php
                        $friendship = $friendships->get($user->id);
                        $isFriend = $friendship && $friendship->status === 'accepted';
                        $isPending = $friendship && $friendship->status === 'pending';
                        $isPendingFromMe = $isPending && $friendship->user_id === auth()->id();
                        $isPendingToMe = $isPending && $friendship->friend_id === auth()->id();
                    @endphp
                    <li class="p-4 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-gray-600 font-semibold">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            @if($isFriend)
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                    Ami
                                </span>
                                <a href="{{ route('messages.show', $user->id) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                    Message
                                </a>
                            @elseif($isPendingFromMe)
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                    Demande envoyée
                                </span>
                            @elseif($isPendingToMe)
                                <div class="flex space-x-2">
                                    <form action="{{ route('friends.accept-request', $friendship->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-sm font-medium transition-colors">
                                            Accepter
                                        </button>
                                    </form>
                                    <form action="{{ route('friends.reject-request', $friendship->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm font-medium transition-colors">
                                            Refuser
                                        </button>
                                    </form>
                                </div>
                            @else
                                <form action="{{ route('friends.send-request', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                        Ajouter comme ami
                                    </button>
                                </form>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection 