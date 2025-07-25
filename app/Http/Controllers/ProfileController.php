<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $profile = $user->profile ?? $user->profile()->create();
        
        return view('profile.edit', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $profile = $user->profile ?? $user->profile()->create();

        // Empêcher la modification du nom/email de l'admin principal
        if ($user->isMainAdmin() && ($request->has('name') || $request->has('email'))) {
            \Log::warning('Tentative de modification du nom/email de l\'admin principal par l\'utilisateur ID: ' . $user->id);
            return back()->with('error', 'Modification du nom ou de l\'email de l\'administrateur principal interdite.');
        }
        
        // Mettre à jour les informations utilisateur de base
        $user->fill($request->only(['name', 'email']));
        
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        
        $user->save();

        // Mettre à jour le profil
        $profileData = $request->except(['name', 'email', 'password', 'password_confirmation']);
        
        // Gérer l'upload d'avatar
        if ($request->hasFile('avatar')) {
            if ($profile->avatar) {
                Storage::disk('public')->delete($profile->avatar);
            }
            $profileData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Gérer l'upload de bannière
        if ($request->hasFile('banner')) {
            if ($profile->banner) {
                Storage::disk('public')->delete($profile->banner);
            }
            $profileData['banner'] = $request->file('banner')->store('banners', 'public');
        }

        $profile->fill($profileData);
        $profile->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Display user's public profile.
     */
    public function show($id): View
    {
        $user = \App\Models\User::with('profile')->findOrFail($id);
        $profile = $user->profile;
        
        // Vérifier les permissions de visibilité
        if (!$profile || !$this->canViewProfile($profile)) {
            abort(403, 'Ce profil n\'est pas accessible.');
        }

        return view('profile.show', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    /**
     * Display current user's profile.
     */
    public function myProfile(): View
    {
        $user = Auth::user();
        $profile = $user->profile ?? $user->profile()->create();

        return view('profile.my-profile', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    /**
     * Update profile privacy settings.
     */
    public function updatePrivacy(Request $request): RedirectResponse
    {
        $request->validate([
            'privacy_level' => 'required|in:public,members,friends,private',
            'notification_preferences' => 'array',
        ]);

        $profile = $request->user()->profile ?? $request->user()->profile()->create();
        $profile->update($request->only(['privacy_level', 'notification_preferences']));

        return Redirect::route('profile.edit')->with('status', 'privacy-updated');
    }

    /**
     * Update theme preference.
     */
    public function updateTheme(Request $request): RedirectResponse
    {
        $request->validate([
            'theme' => 'required|in:light,dark,auto',
        ]);

        $profile = $request->user()->profile ?? $request->user()->profile()->create();
        $profile->update(['theme' => $request->theme]);

        return Redirect::back()->with('status', 'theme-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Empêcher la suppression de l'admin principal
        if ($user->isMainAdmin()) {
            \Log::warning('Tentative de suppression de l\'admin principal par l\'utilisateur ID: ' . $user->id);
            return back()->with('error', 'Suppression de l\'administrateur principal interdite.');
        }

        // Supprimer les fichiers uploadés
        if ($user->profile) {
            if ($user->profile->avatar) {
                Storage::disk('public')->delete($user->profile->avatar);
            }
            if ($user->profile->banner) {
                Storage::disk('public')->delete($user->profile->banner);
            }
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Check if current user can view the profile.
     */
    private function canViewProfile($profile): bool
    {
        $currentUser = Auth::user();
        
        if (!$currentUser) {
            return $profile->isPublic();
        }

        // L'utilisateur peut toujours voir son propre profil
        if ($currentUser->id === $profile->user_id) {
            return true;
        }

        // Les admins peuvent voir tous les profils
        if ($currentUser->isAdmin() || $currentUser->isPastor()) {
            return true;
        }

        // Vérifier le niveau de confidentialité
        switch ($profile->privacy_level) {
            case 'public':
                return true;
            case 'members':
                return $currentUser->isMember();
            case 'friends':
                return $currentUser->allFriends()->contains($profile->user_id);
            case 'private':
                return false;
            default:
                return false;
        }
    }
}
