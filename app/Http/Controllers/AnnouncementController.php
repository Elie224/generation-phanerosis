<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $announcements = Announcement::where('is_published', true)->latest()->paginate(10);
        return view('announcements.index', compact('announcements'));
    }

    public function show($id)
    {
        $annonce = Announcement::findOrFail($id);
        return view('announcements.show', compact('annonce'));
    }

    public function create()
    {
        // Vérifier que l'utilisateur est administrateur
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent ajouter des annonces.');
        }
        
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        // Vérifier que l'utilisateur est administrateur
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent ajouter des annonces.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'attachment' => 'nullable|file|max:10240', // 10MB max
            'is_published' => 'boolean',
        ]);

        $announcement = new Announcement($validated);
        $announcement->user_id = Auth::id();

        // Gestion du fichier joint
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('announcements', 'public');
            $announcement->attachment = $path;
        }

        // Gestion de la date de publication
        if ($request->boolean('is_published')) {
            $announcement->published_at = now();
        }

        $announcement->save();

        return redirect()->route('admin.announcements')
            ->with('success', 'Annonce ajoutée avec succès !');
    }

    public function edit(Announcement $announcement)
    {
        // Vérifier que l'utilisateur est administrateur
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent modifier des annonces.');
        }
        
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        // Vérifier que l'utilisateur est administrateur
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent modifier des annonces.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'attachment' => 'nullable|file|max:10240', // 10MB max
            'is_published' => 'boolean',
        ]);

        // Gestion du fichier joint
        if ($request->hasFile('attachment')) {
            // Supprimer l'ancien fichier s'il existe
            if ($announcement->attachment && Storage::disk('public')->exists($announcement->attachment)) {
                Storage::disk('public')->delete($announcement->attachment);
            }
            
            $file = $request->file('attachment');
            $path = $file->store('announcements', 'public');
            $validated['attachment'] = $path;
        }

        // Gestion de la date de publication
        if ($request->boolean('is_published') && !$announcement->is_published) {
            $validated['published_at'] = now();
        }

        $announcement->update($validated);

        return redirect()->route('admin.announcements')
            ->with('success', 'Annonce mise à jour avec succès !');
    }

    public function destroy(Announcement $announcement)
    {
        // Vérifier que l'utilisateur est administrateur
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent supprimer des annonces.');
        }

        // Supprimer le fichier joint s'il existe
        if ($announcement->attachment && Storage::disk('public')->exists($announcement->attachment)) {
            Storage::disk('public')->delete($announcement->attachment);
        }

        $announcement->delete();

        return redirect()->route('admin.announcements')
            ->with('success', 'Annonce supprimée avec succès !');
    }
}
