<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'download']);
    }

    public function index(Request $request)
    {
        $query = Resource::public()->with(['category', 'user']);

        // Filtres
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $resources = $query->latest()->paginate(12);

        $categories = ResourceCategory::active()->ordered()->get();
        $featuredResources = Resource::public()->featured()->with(['category', 'user'])->limit(6)->get();

        return view('resources.index', compact('resources', 'categories', 'featuredResources'));
    }

    public function create()
    {
        // Vérifier que l'utilisateur est administrateur
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent ajouter des ressources.');
        }
        
        $categories = ResourceCategory::active()->ordered()->get();
        return view('resources.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Vérifier que l'utilisateur est administrateur
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent ajouter des ressources.');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:resource_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'type' => 'required|in:document,video,audio,link,image',
            'file' => 'nullable|file|max:102400', // 100MB max
            'external_url' => 'nullable|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string|max:500',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'language' => 'nullable|string|max:10',
            'duration' => 'nullable|integer|min:1',
            'is_public' => 'boolean',
        ]);

        $resource = new Resource($validated);
        $resource->user_id = Auth::id();

        // Gestion du fichier
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('resources', 'public');
            $resource->file_path = $path;
            $resource->file_size = $file->getSize();
            $resource->file_type = $file->getMimeType();
        }

        // Gestion de la thumbnail
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailPath = $thumbnail->store('resources/thumbnails', 'public');
            $resource->thumbnail_path = $thumbnailPath;
        }

        $resource->save();

        return redirect()->route('resources.show', $resource)
            ->with('success', 'Ressource ajoutée avec succès !');
    }

    public function show(Resource $resource)
    {
        $resource->incrementViews();
        $resource->load(['category', 'user']);

        // Ressources similaires
        $similarResources = Resource::public()
            ->where('category_id', $resource->category_id)
            ->where('id', '!=', $resource->id)
            ->with(['category', 'user'])
            ->limit(4)
            ->get();

        return view('resources.show', compact('resource', 'similarResources'));
    }

    public function edit(Resource $resource)
    {
        // Vérifier que l'utilisateur est administrateur
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent modifier des ressources.');
        }
        
        $categories = ResourceCategory::active()->ordered()->get();
        return view('resources.edit', compact('resource', 'categories'));
    }

    public function update(Request $request, Resource $resource)
    {
        // Vérifier que l'utilisateur est administrateur
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent modifier des ressources.');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:resource_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'type' => 'required|in:document,video,audio,link,image',
            'file' => 'nullable|file|max:102400',
            'external_url' => 'nullable|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string|max:500',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'language' => 'nullable|string|max:10',
            'duration' => 'nullable|integer|min:1',
            'is_public' => 'boolean',
        ]);

        // Gestion du nouveau fichier
        if ($request->hasFile('file')) {
            // Supprimer l'ancien fichier
            if ($resource->file_path) {
                Storage::disk('public')->delete($resource->file_path);
            }

            $file = $request->file('file');
            $path = $file->store('resources', 'public');
            $validated['file_path'] = $path;
            $validated['file_size'] = $file->getSize();
            $validated['file_type'] = $file->getMimeType();
        }

        // Gestion de la nouvelle thumbnail
        if ($request->hasFile('thumbnail')) {
            // Supprimer l'ancienne thumbnail
            if ($resource->thumbnail_path) {
                Storage::disk('public')->delete($resource->thumbnail_path);
            }

            $thumbnail = $request->file('thumbnail');
            $thumbnailPath = $thumbnail->store('resources/thumbnails', 'public');
            $validated['thumbnail_path'] = $thumbnailPath;
        }

        $resource->update($validated);

        return redirect()->route('resources.show', $resource)
            ->with('success', 'Ressource mise à jour avec succès !');
    }

    public function destroy(Resource $resource)
    {
        // Vérifier que l'utilisateur est administrateur
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent supprimer des ressources.');
        }

        // Supprimer les fichiers
        if ($resource->file_path) {
            Storage::disk('public')->delete($resource->file_path);
        }

        if ($resource->thumbnail_path) {
            Storage::disk('public')->delete($resource->thumbnail_path);
        }

        $resource->delete();

        return redirect()->route('resources.index')
            ->with('success', 'Ressource supprimée avec succès !');
    }

    public function download(Resource $resource)
    {
        if (!$resource->file_path) {
            return back()->with('error', 'Aucun fichier à télécharger.');
        }

        $resource->incrementDownloads();

        $path = storage_path('app/public/' . $resource->file_path);
        
        if (!file_exists($path)) {
            return back()->with('error', 'Fichier introuvable.');
        }

        return response()->download($path, $resource->title . '.' . pathinfo($path, PATHINFO_EXTENSION));
    }

    public function category(ResourceCategory $category)
    {
        $resources = $category->resources()
            ->public()
            ->with(['user'])
            ->latest()
            ->paginate(12);

        return view('resources.category', compact('category', 'resources'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return redirect()->route('resources.index');
        }

        $resources = Resource::public()
            ->search($query)
            ->with(['category', 'user'])
            ->latest()
            ->paginate(12);

        $categories = ResourceCategory::active()->ordered()->get();

        return view('resources.search', compact('resources', 'categories', 'query'));
    }
}
