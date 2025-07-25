<?php

namespace App\Http\Controllers;

use App\Models\NotificationPreference;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotificationPreferenceController extends Controller
{
    /**
     * Afficher les préférences de notification
     */
    public function index()
    {
        $preferences = auth()->user()->notificationPreferences;
        
        if (!$preferences) {
            $preferences = auth()->user()->notificationPreferences()->create([
                'email_enabled' => true,
                'sms_enabled' => false,
                'push_enabled' => true,
                'whatsapp_enabled' => false,
            ]);
        }

        return view('profile.notifications.index', compact('preferences'));
    }

    /**
     * Mettre à jour les préférences de notification
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            // Canaux principaux
            'email_enabled' => 'boolean',
            'sms_enabled' => 'boolean',
            'push_enabled' => 'boolean',
            'whatsapp_enabled' => 'boolean',
            
            // Événements
            'events_email' => 'boolean',
            'events_sms' => 'boolean',
            'events_push' => 'boolean',
            'events_whatsapp' => 'boolean',
            
            // Demandes de prière
            'prayer_requests_email' => 'boolean',
            'prayer_requests_sms' => 'boolean',
            'prayer_requests_push' => 'boolean',
            'prayer_requests_whatsapp' => 'boolean',
            
            // Annonces
            'announcements_email' => 'boolean',
            'announcements_sms' => 'boolean',
            'announcements_push' => 'boolean',
            'announcements_whatsapp' => 'boolean',
            
            // Messages
            'messages_email' => 'boolean',
            'messages_sms' => 'boolean',
            'messages_push' => 'boolean',
            'messages_whatsapp' => 'boolean',
            
            // Mises à jour de ministère
            'ministry_updates_email' => 'boolean',
            'ministry_updates_sms' => 'boolean',
            'ministry_updates_push' => 'boolean',
            'ministry_updates_whatsapp' => 'boolean',
            
            // Rappels d'anniversaire
            'birthday_reminders_email' => 'boolean',
            'birthday_reminders_sms' => 'boolean',
            'birthday_reminders_push' => 'boolean',
            'birthday_reminders_whatsapp' => 'boolean',
            
            // Rappels spirituels
            'spiritual_reminders_email' => 'boolean',
            'spiritual_reminders_sms' => 'boolean',
            'spiritual_reminders_push' => 'boolean',
            'spiritual_reminders_whatsapp' => 'boolean',
            
            // Informations de contact
            'whatsapp_number' => 'nullable|string|max:20',
            'sms_number' => 'nullable|string|max:20',
            'push_token' => 'nullable|string|max:255',
            
            // Fréquence
            'email_frequency' => ['nullable', Rule::in(['immediate', 'daily', 'weekly'])],
            'sms_frequency' => ['nullable', Rule::in(['immediate', 'daily', 'weekly'])],
            'whatsapp_frequency' => ['nullable', Rule::in(['immediate', 'daily', 'weekly'])],
            
            // Heures silencieuses
            'quiet_hours_start' => 'nullable|date_format:H:i',
            'quiet_hours_end' => 'nullable|date_format:H:i',
            'respect_quiet_hours' => 'boolean',
        ]);

        // Créer ou mettre à jour les préférences
        $preferences = $user->notificationPreferences()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return redirect()->route('profile.notifications.index')
            ->with('status', 'Préférences de notification mises à jour avec succès !');
    }

    /**
     * Tester les notifications
     */
    public function test(Request $request)
    {
        $request->validate([
            'channel' => ['required', Rule::in(['email', 'sms', 'push', 'whatsapp'])],
        ]);

        $channel = $request->channel;
        $user = auth()->user();
        $preferences = $user->notificationPreferences;

        if (!$preferences || !$preferences->isNotificationEnabled('events', $channel)) {
            return back()->withErrors(['test' => "Le canal {$channel} n'est pas activé pour les événements."]);
        }

        // Ici, vous pouvez ajouter la logique pour envoyer une notification de test
        // Pour l'instant, on simule juste le succès
        
        return back()->with('status', "Notification de test envoyée via {$channel} !");
    }

    /**
     * Activer/désactiver tous les canaux pour un type
     */
    public function toggleType(Request $request)
    {
        $request->validate([
            'type' => ['required', Rule::in(['events', 'prayer_requests', 'announcements', 'messages', 'ministry_updates', 'birthday_reminders', 'spiritual_reminders'])],
            'enabled' => 'required|boolean',
        ]);

        $user = auth()->user();
        $preferences = $user->notificationPreferences;
        $type = $request->type;
        $enabled = $request->enabled;

        if ($preferences) {
            $preferences->update([
                "{$type}_email" => $enabled,
                "{$type}_sms" => $enabled,
                "{$type}_push" => $enabled,
                "{$type}_whatsapp" => $enabled,
            ]);
        }

        return back()->with('status', "Notifications {$type} " . ($enabled ? 'activées' : 'désactivées') . " pour tous les canaux !");
    }
}
