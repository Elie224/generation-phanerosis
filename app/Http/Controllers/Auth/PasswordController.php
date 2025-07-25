<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        // Empêcher la modification du mot de passe de l'admin principal par un autre utilisateur
        if ($user->isMainAdmin() && $user->id !== auth()->id()) {
            \Log::warning('Tentative de modification du mot de passe de l\'admin principal par l\'utilisateur ID: ' . auth()->id());
            return back()->with('error', 'Modification du mot de passe de l\'administrateur principal interdite.');
        }

        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
