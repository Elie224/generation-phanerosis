<?php

namespace App\Http\Controllers;

use App\Models\Teaching;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeachingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Accès réservé aux administrateurs.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $teachings = Teaching::latest()->paginate(10);
        return view('teachings.index', compact('teachings'));
    }

    public function create()
    {
        return view('teachings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'pastor' => 'required|string|max:255',
            'teaching_date' => 'required|date',
            'media_file' => 'nullable|file|mimes:mp3,mp4,wav,avi,mov|max:102400', // 100MB max
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $teaching = new Teaching();
        $teaching->title = $request->title;
        $teaching->content = $request->content;
        $teaching->pastor = $request->pastor;
        $teaching->teaching_date = $request->teaching_date;

        // Gestion du fichier média
        if ($request->hasFile('media_file')) {
            $mediaPath = $request->file('media_file')->store('teachings/media', 'public');
            $teaching->media_path = $mediaPath;
        }

        // Gestion de l'image
        if ($request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('teachings/images', 'public');
            $teaching->image_path = $imagePath;
        }

        $teaching->save();

        return redirect()->route('admin.teachings')->with('success', 'Enseignement ajouté avec succès.');
    }

    public function show(Teaching $teaching)
    {
        return view('teachings.show', compact('teaching'));
    }

    public function edit(Teaching $teaching)
    {
        return view('teachings.edit', compact('teaching'));
    }

    public function update(Request $request, Teaching $teaching)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'pastor' => 'required|string|max:255',
            'teaching_date' => 'required|date',
            'media_file' => 'nullable|file|mimes:mp3,mp4,wav,avi,mov|max:102400',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $teaching->title = $request->title;
        $teaching->content = $request->content;
        $teaching->pastor = $request->pastor;
        $teaching->teaching_date = $request->teaching_date;

        // Gestion du fichier média
        if ($request->hasFile('media_file')) {
            // Supprimer l'ancien fichier s'il existe
            if ($teaching->media_path) {
                Storage::disk('public')->delete($teaching->media_path);
            }
            $mediaPath = $request->file('media_file')->store('teachings/media', 'public');
            $teaching->media_path = $mediaPath;
        }

        // Gestion de l'image
        if ($request->hasFile('image_file')) {
            // Supprimer l'ancienne image s'elle existe
            if ($teaching->image_path) {
                Storage::disk('public')->delete($teaching->image_path);
            }
            $imagePath = $request->file('image_file')->store('teachings/images', 'public');
            $teaching->image_path = $imagePath;
        }

        $teaching->save();

        return redirect()->route('admin.teachings')->with('success', 'Enseignement mis à jour avec succès.');
    }

    public function destroy(Teaching $teaching)
    {
        // Supprimer les fichiers associés
        if ($teaching->media_path) {
            Storage::disk('public')->delete($teaching->media_path);
        }
        if ($teaching->image_path) {
            Storage::disk('public')->delete($teaching->image_path);
        }

        $teaching->delete();

        return redirect()->route('admin.teachings')->with('success', 'Enseignement supprimé avec succès.');
    }
}
