<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Friendship;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewFriendRequest;

class FriendController extends Controller
{
    // Envoyer une demande d’amitié
    public function sendRequest($id)
    {
        $friend = User::findOrFail($id);

        // Empêcher de s’ajouter soi-même
        if ($friend->id == Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas vous ajouter vous-même !');
        }

        // Vérifier si la demande existe déjà
        $existing = Friendship::where(function($q) use($friend) {
            $q->where('user_id', Auth::id())->where('friend_id', $friend->id);
        })->orWhere(function($q) use($friend) {
            $q->where('user_id', $friend->id)->where('friend_id', Auth::id());
        })->first();

        if ($existing) {
            return back()->with('info', 'Demande déjà envoyée ou vous êtes déjà amis.');
        }

        Friendship::create([
            'user_id' => Auth::id(),
            'friend_id' => $friend->id,
            'status' => 'pending',
        ]);

        // Notification
        $friend->notify(new NewFriendRequest(Auth::user()));

        return back()->with('success', 'Demande d\'amitié envoyée !');
    }

    // Accepter une demande d’amitié
    public function acceptRequest($id)
    {
        $friendship = Friendship::findOrFail($id);

        // Vérification d'ownership
        if ($friendship->friend_id !== Auth::id() || $friendship->status !== 'pending') {
            abort(403, 'Non autorisé.');
        }

        $friendship->status = 'accepted';
        $friendship->save();

        return back()->with('success', 'Demande d\'amitié acceptée !');
    }

    // Refuser ou supprimer une demande
    public function deleteRequest($id)
    {
        $friendship = Friendship::where(function($q) use ($id) {
            $q->where('user_id', Auth::id())->where('friend_id', $id);
        })->orWhere(function($q) use ($id) {
            $q->where('user_id', $id)->where('friend_id', Auth::id());
        })->first();

        // Vérification d'ownership
        if ($friendship && $friendship->friend_id !== Auth::id() && $friendship->user_id !== Auth::id()) {
            abort(403, 'Non autorisé.');
        }

        if ($friendship) {
            $friendship->delete();
            return back()->with('success', 'Demande ou amitié supprimée.');
        }
        return back()->with('error', 'Aucune demande trouvée.');
    }

    // Refuser une demande d'amitié (DELETE)
    public function rejectRequest($id)
    {
        $friendship = \App\Models\Friendship::findOrFail($id);
        // Vérification d'ownership
        if ($friendship->friend_id !== Auth::id()) {
            abort(403, 'Non autorisé.');
        }
        $friendship->delete();
        return back()->with('success', 'Demande d\'ami refusée.');
    }

    // Liste de mes amis
    public function myFriends()
    {
        $user = Auth::user();
        $friends = $user->friendsAll()->get();

        return view('friends.index', compact('friends'));
    }

    // Liste de mes demandes reçues
    public function myRequests()
    {
        $user = Auth::user();
        $requests = Friendship::where('friend_id', $user->id)
            ->where('status', 'pending')
            ->with('user')
            ->get();

        return view('friends.requests', compact('requests'));
    }

    // Afficher la liste des amis (route /amis)
    public function index()
    {
        $user = Auth::user();
        $friends = $user->friendsAll()->get();
        return view('friends.index', compact('friends'));
    }

    // Supprimer un ami
    public function deleteFriend($id)
    {
        $friendship = Friendship::where(function($q) use ($id) {
            $q->where('user_id', Auth::id())->where('friend_id', $id);
        })->orWhere(function($q) use ($id) {
            $q->where('user_id', $id)->where('friend_id', Auth::id());
        })->first();

        if (!$friendship) {
            return back()->with('error', 'Aucune relation d\'amitié trouvée.');
        }

        // Vérification d'ownership
        if ($friendship->friend_id !== Auth::id() && $friendship->user_id !== Auth::id()) {
            abort(403, 'Non autorisé.');
        }

        $friendship->delete();
        return back()->with('success', 'Ami supprimé avec succès.');
    }
}
