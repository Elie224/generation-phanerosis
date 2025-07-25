<?php

namespace App\Services;

use App\Models\User;
use App\Models\NotificationPreference;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Envoyer une notification à un utilisateur
     */
    public function sendNotification(User $user, string $type, string $title, string $message, array $data = [])
    {
        $preferences = $user->notificationPreferences;
        
        if (!$preferences) {
            Log::warning("Aucune préférence de notification trouvée pour l'utilisateur {$user->id}");
            return false;
        }

        // Vérifier les heures silencieuses
        if ($preferences->isWithinQuietHours()) {
            Log::info("Notification non envoyée - heures silencieuses pour l'utilisateur {$user->id}");
            return false;
        }

        $enabledChannels = $preferences->getEnabledChannels($type);
        $sent = false;

        foreach ($enabledChannels as $channel) {
            try {
                switch ($channel) {
                    case 'email':
                        $this->sendEmail($user, $title, $message, $data);
                        $sent = true;
                        break;
                        
                    case 'sms':
                        $this->sendSMS($user, $message);
                        $sent = true;
                        break;
                        
                    case 'push':
                        $this->sendPush($user, $title, $message, $data);
                        $sent = true;
                        break;
                        
                    case 'whatsapp':
                        $this->sendWhatsApp($user, $message);
                        $sent = true;
                        break;
                }
            } catch (\Exception $e) {
                Log::error("Erreur lors de l'envoi de notification {$channel} à l'utilisateur {$user->id}: " . $e->getMessage());
            }
        }

        return $sent;
    }

    /**
     * Envoyer une notification par email
     */
    protected function sendEmail(User $user, string $title, string $message, array $data = [])
    {
        // Ici, vous pouvez utiliser Laravel Mail pour envoyer des emails
        // Pour l'instant, on simule l'envoi
        Log::info("Email envoyé à {$user->email}: {$title} - {$message}");
        
        // Exemple avec Mail::send() :
        // Mail::send('emails.notification', compact('title', 'message', 'data'), function($mail) use ($user, $title) {
        //     $mail->to($user->email)->subject($title);
        // });
    }

    /**
     * Envoyer une notification par SMS
     */
    protected function sendSMS(User $user, string $message)
    {
        $preferences = $user->notificationPreferences;
        
        if (!$preferences || !$preferences->sms_number) {
            Log::warning("Numéro SMS non configuré pour l'utilisateur {$user->id}");
            return false;
        }

        // Ici, vous pouvez intégrer un service SMS comme Twilio
        Log::info("SMS envoyé à {$preferences->sms_number}: {$message}");
        
        // Exemple avec Twilio :
        // $twilio = new Client($accountSid, $authToken);
        // $twilio->messages->create(
        //     $preferences->sms_number,
        //     ['from' => $twilioNumber, 'body' => $message]
        // );
    }

    /**
     * Envoyer une notification push
     */
    protected function sendPush(User $user, string $title, string $message, array $data = [])
    {
        $preferences = $user->notificationPreferences;
        
        if (!$preferences || !$preferences->push_token) {
            Log::warning("Token push non configuré pour l'utilisateur {$user->id}");
            return false;
        }

        // Ici, vous pouvez intégrer Firebase Cloud Messaging ou un autre service push
        Log::info("Push notification envoyé à {$preferences->push_token}: {$title} - {$message}");
        
        // Exemple avec Firebase :
        // $firebase = new Firebase\FirebaseLib($url, $token);
        // $firebase->push('/notifications', [
        //     'token' => $preferences->push_token,
        //     'title' => $title,
        //     'message' => $message,
        //     'data' => $data
        // ]);
    }

    /**
     * Envoyer une notification par WhatsApp
     */
    protected function sendWhatsApp(User $user, string $message)
    {
        $preferences = $user->notificationPreferences;
        
        if (!$preferences || !$preferences->whatsapp_number) {
            Log::warning("Numéro WhatsApp non configuré pour l'utilisateur {$user->id}");
            return false;
        }

        // Ici, vous pouvez intégrer l'API WhatsApp Business
        Log::info("WhatsApp envoyé à {$preferences->whatsapp_number}: {$message}");
        
        // Exemple avec WhatsApp Business API :
        // $whatsapp = new WhatsAppAPI($accessToken);
        // $whatsapp->sendMessage($preferences->whatsapp_number, $message);
    }

    /**
     * Envoyer une notification de test
     */
    public function sendTestNotification(User $user, string $channel)
    {
        $title = "Notification de test";
        $message = "Ceci est une notification de test via {$channel}.";
        
        return $this->sendNotification($user, 'events', $title, $message);
    }

    /**
     * Envoyer une notification d'événement
     */
    public function sendEventNotification(User $user, string $eventTitle, string $eventDate, string $eventLocation)
    {
        $title = "Nouvel événement : {$eventTitle}";
        $message = "Un nouvel événement a été ajouté : {$eventTitle} le {$eventDate} à {$eventLocation}.";
        
        return $this->sendNotification($user, 'events', $title, $message, [
            'event_title' => $eventTitle,
            'event_date' => $eventDate,
            'event_location' => $eventLocation
        ]);
    }

    /**
     * Envoyer une notification de demande de prière
     */
    public function sendPrayerRequestNotification(User $user, string $requesterName, string $request)
    {
        $title = "Nouvelle demande de prière";
        $message = "{$requesterName} a partagé une demande de prière : {$request}";
        
        return $this->sendNotification($user, 'prayer_requests', $title, $message, [
            'requester_name' => $requesterName,
            'request' => $request
        ]);
    }

    /**
     * Envoyer une notification d'annonce
     */
    public function sendAnnouncementNotification(User $user, string $announcementTitle, string $announcementContent)
    {
        $title = "Nouvelle annonce : {$announcementTitle}";
        $message = $announcementContent;
        
        return $this->sendNotification($user, 'announcements', $title, $message, [
            'announcement_title' => $announcementTitle,
            'announcement_content' => $announcementContent
        ]);
    }

    /**
     * Envoyer une notification de message privé
     */
    public function sendMessageNotification(User $user, string $senderName, string $messagePreview)
    {
        $title = "Nouveau message de {$senderName}";
        $message = $messagePreview;
        
        return $this->sendNotification($user, 'messages', $title, $message, [
            'sender_name' => $senderName,
            'message_preview' => $messagePreview
        ]);
    }
} 