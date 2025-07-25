<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
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
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $currentUser = auth()->user();
        
        // Vérifier les permissions avec la nouvelle méthode
        if (!$user->canBeModifiedBy($currentUser)) {
            // Log de sécurité
            \Log::warning('Tentative d\'accès à la page de modification d\'utilisateur non autorisée', [
                'target_user_id' => $user->id,
                'target_user_email' => $user->email,
                'attempted_by' => $currentUser->id,
                'attempted_by_email' => $currentUser->email,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return redirect()->route('admin.users')->with('error', 'Vous n\'avez pas les permissions pour modifier cet utilisateur.');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $currentUser = auth()->user();
        
        // Vérifier les permissions avec la nouvelle méthode
        if (!$user->canBeModifiedBy($currentUser)) {
            // Log de sécurité
            \Log::warning('Tentative de modification d\'utilisateur non autorisée', [
                'target_user_id' => $user->id,
                'target_user_email' => $user->email,
                'attempted_by' => $currentUser->id,
                'attempted_by_email' => $currentUser->email,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'request_data' => $request->except(['password', 'password_confirmation'])
            ]);
            
            return redirect()->route('admin.users')->with('error', 'Vous n\'avez pas les permissions pour modifier cet utilisateur.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => [
                'required',
                Rule::in(['admin', 'pasteur', 'leader', 'member']),
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Vérifications supplémentaires de sécurité
        if ($validated['role'] === 'admin' && !$currentUser->isMainAdmin()) {
            \Log::warning('Tentative de promotion d\'utilisateur au rang d\'administrateur non autorisée', [
                'target_user_id' => $user->id,
                'attempted_by' => $currentUser->id,
                'ip' => request()->ip()
            ]);
            return redirect()->back()->with('error', 'Seul l\'administrateur principal peut promouvoir un utilisateur au rang d\'administrateur.');
        }

        try {
            // Mettre à jour les données de base
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->role = $validated['role'];

            // Mettre à jour le mot de passe si fourni
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            // Log de succès
            \Log::info('Utilisateur modifié avec succès', [
                'target_user_id' => $user->id,
                'modified_by' => $currentUser->id,
                'changes' => $validated
            ]);

            return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la modification d\'utilisateur', [
                'target_user_id' => $user->id,
                'attempted_by' => $currentUser->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la modification de l\'utilisateur.');
        }
    }

    public function destroy(User $user)
    {
        $currentUser = auth()->user();
        
        // Vérifier les permissions avec la nouvelle méthode
        if (!$user->canBeDeletedBy($currentUser)) {
            // Log de sécurité
            \Log::critical('Tentative de suppression d\'utilisateur non autorisée', [
                'target_user_id' => $user->id,
                'target_user_email' => $user->email,
                'attempted_by' => $currentUser->id,
                'attempted_by_email' => $currentUser->email,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return redirect()->route('admin.users')->with('error', 'Vous n\'avez pas les permissions pour supprimer cet utilisateur.');
        }

        try {
            // Supprimer l'utilisateur
            $user->delete();

            // Log de succès
            \Log::info('Utilisateur supprimé avec succès', [
                'target_user_id' => $user->id,
                'deleted_by' => $currentUser->id
            ]);

            return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression d\'utilisateur', [
                'target_user_id' => $user->id,
                'attempted_by' => $currentUser->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.users')->with('error', 'Une erreur est survenue lors de la suppression de l\'utilisateur.');
        }
    }

    public function toggleStatus(User $user)
    {
        $currentUser = auth()->user();
        
        // Vérifier les permissions avec la nouvelle méthode
        if (!$user->canBeModifiedBy($currentUser)) {
            // Log de sécurité
            \Log::warning('Tentative de changement de statut d\'utilisateur non autorisée', [
                'target_user_id' => $user->id,
                'target_user_email' => $user->email,
                'attempted_by' => $currentUser->id,
                'attempted_by_email' => $currentUser->email,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return redirect()->route('admin.users')->with('error', 'Vous n\'avez pas les permissions pour modifier le statut de cet utilisateur.');
        }

        try {
            // Basculer le statut
            $user->is_active = !$user->is_active;
            $user->save();

            $status = $user->is_active ? 'activé' : 'désactivé';
            
            // Log de succès
            \Log::info('Statut d\'utilisateur modifié avec succès', [
                'target_user_id' => $user->id,
                'new_status' => $user->is_active,
                'modified_by' => $currentUser->id
            ]);
            
            return redirect()->route('admin.users')->with('success', "Utilisateur {$status} avec succès.");
        } catch (\Exception $e) {
            \Log::error('Erreur lors du changement de statut d\'utilisateur', [
                'target_user_id' => $user->id,
                'attempted_by' => $currentUser->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.users')->with('error', 'Une erreur est survenue lors du changement de statut de l\'utilisateur.');
        }
    }
} 