@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <h2 class="text-xl font-bold mb-6">Mes conversations</h2>
    
    @if($conversations->isEmpty())
        <div class="text-center py-8">
            <p class="text-gray-500 mb-4">Vous n'avez aucune conversation.</p>
            @if($friends->isNotEmpty())
                <p class="text-sm text-gray-400 mb-6">Commencez une conversation avec un de vos amis :</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($friends as $friend)
                        <div class="bg-white p-4 rounded-lg shadow border">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                    <span class="text-gray-600 font-semibold">{{ substr($friend->name, 0, 1) }}</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $friend->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $friend->email }}</p>
                                </div>
                                <a href="{{ route('messages.show', $friend->id) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                    Message
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400">Ajoutez des amis pour pouvoir échanger des messages.</p>
                <a href="{{ route('friends.index') }}" class="inline-block mt-4 bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md font-medium transition-colors">
                    Voir mes amis
                </a>
            @endif
        </div>
    @else
        <div class="bg-white rounded-lg shadow">
            <ul class="divide-y divide-gray-200">
                @foreach($conversations as $userId => $messages)
                    @php
                        $last = $messages->last();
                        if ($last) {
                            $user = $last->from_id == auth()->id() ? $last->toUser : $last->fromUser;
                        } else {
                            continue;
                        }
                    @endphp
                    <li class="p-4">
                        <a href="{{ route('messages.show', $user->id) }}" class="flex items-center space-x-3 hover:bg-gray-50 p-2 rounded transition-colors">
                            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-gray-600 font-semibold">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-gray-900">{{ $user->name }}</span>
                                    <span class="text-gray-500 text-sm">{{ $last->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="text-gray-600 text-sm">{{ Str::limit($last->content, 60) }}</div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="mt-4">
                {{ $conversations->links() }}
            </div>
        </div>
        
        @if($friends->isNotEmpty())
            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4">Nouvelle conversation</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php
                        $conversationUserIds = $conversations->keys();
                    @endphp
                    @foreach($friends as $friend)
                        @if(!$conversationUserIds->contains($friend->id))
                            <div class="bg-white p-4 rounded-lg shadow border">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                        <span class="text-gray-600 font-semibold">{{ substr($friend->name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900">{{ $friend->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $friend->email }}</p>
                                    </div>
                                    <a href="{{ route('messages.show', $friend->id) }}" 
                                       class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                        Nouveau
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
