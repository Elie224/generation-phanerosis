<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Friendship;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Afficher tous les utilisateurs (sauf soi-même)
    public function index()
    {
        $currentUser = Auth::user();
        
        // Récupérer tous les utilisateurs sauf l'utilisateur connecté
        $users = User::where('id', '!=', $currentUser->id)->get();
        
        // Récupérer les amitiés existantes
        $friendships = Friendship::where('user_id', $currentUser->id)
            ->orWhere('friend_id', $currentUser->id)
            ->get()
            ->keyBy(function($friendship) use ($currentUser) {
                return $friendship->user_id == $currentUser->id ? $friendship->friend_id : $friendship->user_id;
            });

        return view('users.index', compact('users', 'friendships'));
    }
}
