<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teaching;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeachingAdminController extends Controller
{
    public function index()
    {
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isPastor() && !auth()->user()->isMainAdmin())) {
            abort(403, 'Accès réservé aux administrateurs/pasteurs.');
        }
        $teachings = Teaching::orderBy('teaching_date', 'desc')->paginate(15);
        return view('admin.teachings.index', compact('teachings'));
    }

    public function create()
    {
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isPastor() && !auth()->user()->isMainAdmin())) {
            abort(403, 'Accès réservé aux administrateurs/pasteurs.');
        }
        return view('admin.teachings.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isPastor() && !auth()->user()->isMainAdmin())) {
            abort(403, 'Accès réservé aux administrateurs/pasteurs.');
        }
        $data = $request->validate([
            'type' => 'required|in:enseignement,predication',
            'title' => 'required|string|max:255',
            'content' => 'required',
            'pastor' => 'required|string|max:255',
            'teaching_date' => 'required|date',
            'image' => 'nullable|image|max:2097152', // 2 Go
            'media' => 'nullable|file|max:2097152', // 2 Go
        ]);
        $data['image_path'] = $request->file('image') ? $request->file('image')->store('teachings/images', 'public') : null;
        $data['media_path'] = $request->file('media') ? $request->file('media')->store('teachings/media', 'public') : null;
        Teaching::create($data);
        return redirect()->route('admin.enseignements.index')->with('success', 'Ajouté avec succès.');
    }

    public function edit($id)
    {
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isPastor() && !auth()->user()->isMainAdmin())) {
            abort(403, 'Accès réservé aux administrateurs/pasteurs.');
        }
        $teaching = Teaching::findOrFail($id);
        return view('admin.teachings.edit', compact('teaching'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isPastor() && !auth()->user()->isMainAdmin())) {
            abort(403, 'Accès réservé aux administrateurs/pasteurs.');
        }
        $teaching = Teaching::findOrFail($id);
        $data = $request->validate([
            'type' => 'required|in:enseignement,predication',
            'title' => 'required|string|max:255',
            'content' => 'required',
            'pastor' => 'required|string|max:255',
            'teaching_date' => 'required|date',
            'image' => 'nullable|image|max:2097152', // 2 Go
            'media' => 'nullable|file|max:2097152', // 2 Go
        ]);
        if ($request->file('image')) {
            $data['image_path'] = $request->file('image')->store('teachings/images', 'public');
        }
        if ($request->file('media')) {
            $data['media_path'] = $request->file('media')->store('teachings/media', 'public');
        }
        $teaching->update($data);
        return redirect()->route('admin.enseignements.index')->with('success', 'Modifié avec succès.');
    }

    public function destroy($id)
    {
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isPastor() && !auth()->user()->isMainAdmin())) {
            abort(403, 'Accès réservé aux administrateurs/pasteurs.');
        }
        $teaching = Teaching::findOrFail($id);
        $teaching->delete();
        return back()->with('success', 'Supprimé avec succès.');
    }
}
