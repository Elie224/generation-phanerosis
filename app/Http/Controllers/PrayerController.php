<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PrayerController extends Controller
{
    public function index()
    {
        // Récupérer les pasteurs (utilisateurs avec le rôle pasteur)
        $pasteurs = User::where('role', 'pasteur')->orWhere('role', 'admin')->get();
        
        return view('prayer.index', compact('pasteurs'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'pastor_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'audio' => 'nullable|file|mimes:mp3,wav,webm|max:10240',
        ]);

        // Ici vous pouvez ajouter la logique pour sauvegarder la demande de prière
        // Par exemple, l'envoyer par email ou la sauvegarder en base de données

        return back()->with('success', 'Votre demande de prière a été envoyée avec succès. Nous prions pour vous.');
    }
}
