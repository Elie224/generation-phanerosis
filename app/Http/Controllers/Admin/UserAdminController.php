<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAdminController extends Controller
{
    public function index()
    {
        if (!auth()->user() || (!auth()->user()->isAdmin() && !auth()->user()->isMainAdmin())) {
            abort(403, 'Accès réservé aux administrateurs.');
        }
        $users = User::orderBy('id')->get();
        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        $currentUser = auth()->user();
        if (!$currentUser || (!$currentUser->isAdmin() && !$currentUser->isMainAdmin())) {
            abort(403, 'Accès réservé aux administrateurs.');
        }
        $user = User::findOrFail($id);
        // L'admin principal peut tout faire
        if (!$currentUser->isMainAdmin()) {
            // Empêcher de modifier le rôle de l'admin principal
            if ($user->isMainAdmin()) {
                return back()->with('error', 'Impossible de modifier le rôle de l\'administrateur principal.');
            }
            // Empêcher un admin de rétrograder un autre admin (sauf principal)
            if ($user->isAdmin() && $currentUser->id !== $user->id) {
                return back()->with('error', 'Impossible de modifier le rôle d\'un autre administrateur.');
            }
        }
        // Empêcher un admin de se retirer lui-même le rôle admin
        if ($user->id === $currentUser->id && $request->role !== 'admin') {
            return back()->with('error', 'Vous ne pouvez pas vous retirer vous-même le rôle administrateur.');
        }
        $request->validate(['role' => 'required|in:admin,pasteur,user']);
        $user->role = $request->role;
        $user->save();
        return back()->with('success', 'Rôle mis à jour.');
    }

    public function destroy($id)
    {
        $currentUser = auth()->user();
        if (!$currentUser || (!$currentUser->isAdmin() && !$currentUser->isMainAdmin())) {
            abort(403, 'Accès réservé aux administrateurs.');
        }
        $user = User::findOrFail($id);
        // L'admin principal peut tout faire
        if (!$currentUser->isMainAdmin()) {
            // Empêcher de supprimer l'admin principal
            if ($user->isMainAdmin()) {
                return back()->with('error', 'Impossible de supprimer l\'administrateur principal.');
            }
            // Empêcher un admin de supprimer un autre admin (sauf principal)
            if ($user->isAdmin() && $currentUser->id !== $user->id) {
                return back()->with('error', 'Impossible de supprimer un autre administrateur.');
            }
        }
        // Empêcher un admin de se supprimer lui-même
        if ($user->id === $currentUser->id) {
            return back()->with('error', 'Vous ne pouvez pas vous supprimer vous-même.');
        }
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }
}
