<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Prayer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PrayerController extends Controller
{
    public function index()
    {
        // Récupérer uniquement les pasteurs (pas les admins)
        $pasteurs = User::where('role', 'pasteur')->where('is_active', true)->get();
        
        return view('prayer.index', compact('pasteurs'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'pastor_id' => 'required|exists:users,id',
            'content' => 'required|string|max:5000',
            'audio' => 'nullable|file|mimes:mp3,wav,webm,ogg|max:10240', // 10MB max
            'files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,avi,mov,pdf,doc,docx,txt|max:10240', // 10MB max par fichier
            'is_anonymous' => 'boolean'
        ]);

        try {
            $audioPath = null;
            $filesPaths = [];
            
            // Gérer l'upload audio si présent
            if ($request->hasFile('audio')) {
                $audioFile = $request->file('audio');
                $audioPath = $audioFile->store('prayers/audio', 'public');
            }

            // Gérer l'upload des fichiers si présents
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $filePath = $file->store('prayers/files', 'public');
                    $filesPaths[] = [
                        'path' => $filePath,
                        'name' => $file->getClientOriginalName(),
                        'type' => $file->getMimeType(),
                        'size' => $file->getSize()
                    ];
                }
            }

            // Créer la demande de prière
            $prayer = Prayer::create([
                'user_id' => Auth::id(),
                'pastor_id' => $request->pastor_id,
                'content' => $request->content,
                'is_anonymous' => $request->boolean('is_anonymous'),
                'audio_path' => $audioPath,
                'files_paths' => $filesPaths,
                'status' => 'pending'
            ]);

            // Ici vous pourriez ajouter l'envoi d'email au pasteur
            // Mail::to($prayer->pastor->email)->send(new NewPrayerRequest($prayer));

            return back()->with('success', 'Votre demande de prière a été envoyée avec succès. Nous prions pour vous et vous répondrons dans les plus brefs délais.');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Une erreur est survenue lors de l\'envoi de votre demande. Veuillez réessayer.']);
        }
    }

    // Méthode pour que les pasteurs voient leurs demandes de prière
    public function myPrayers()
    {
        // Vérifier que l'utilisateur est bien un pasteur
        if (Auth::user()->role !== 'pasteur') {
            abort(403, 'Accès non autorisé. Seuls les pasteurs peuvent voir les demandes de prière.');
        }

        $prayers = Prayer::where('pastor_id', Auth::id())
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('prayer.my-prayers', compact('prayers'));
    }

    // Méthode pour répondre à une demande de prière
    public function respond(Request $request, Prayer $prayer)
    {
        // Vérifier que l'utilisateur est bien le pasteur destinataire
        if (Auth::id() !== $prayer->pastor_id || Auth::user()->role !== 'pasteur') {
            abort(403, 'Accès non autorisé. Vous ne pouvez répondre qu\'aux demandes qui vous sont adressées.');
        }

        $request->validate([
            'response' => 'required|string|max:1000'
        ]);

        $prayer->update([
            'pastor_response' => $request->response,
            'status' => 'answered',
            'answered_at' => now()
        ]);

        return back()->with('success', 'Votre réponse a été envoyée avec succès.');
    }

    // Méthode pour que les utilisateurs voient leurs demandes
    public function myRequests()
    {
        $prayers = Prayer::where('user_id', Auth::id())
            ->with(['pastor'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('prayer.my-requests', compact('prayers'));
    }

    // Méthode pour mettre à jour le statut d'une demande
    public function updateStatus(Request $request, Prayer $prayer)
    {
        // Vérifier que l'utilisateur est bien le pasteur destinataire
        if (Auth::id() !== $prayer->pastor_id || Auth::user()->role !== 'pasteur') {
            abort(403, 'Accès non autorisé. Vous ne pouvez modifier que les demandes qui vous sont adressées.');
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,answered,closed'
        ]);

        $prayer->update([
            'status' => $request->status,
            'answered_at' => $request->status === 'answered' ? now() : null
        ]);

        return response()->json(['success' => true]);
    }
}
