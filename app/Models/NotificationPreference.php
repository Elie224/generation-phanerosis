<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_enabled',
        'sms_enabled',
        'push_enabled',
        'whatsapp_enabled',
        'events_email',
        'events_sms',
        'events_push',
        'events_whatsapp',
        'prayer_requests_email',
        'prayer_requests_sms',
        'prayer_requests_push',
        'prayer_requests_whatsapp',
        'announcements_email',
        'announcements_sms',
        'announcements_push',
        'announcements_whatsapp',
        'messages_email',
        'messages_sms',
        'messages_push',
        'messages_whatsapp',
        'ministry_updates_email',
        'ministry_updates_sms',
        'ministry_updates_push',
        'ministry_updates_whatsapp',
        'birthday_reminders_email',
        'birthday_reminders_sms',
        'birthday_reminders_push',
        'birthday_reminders_whatsapp',
        'spiritual_reminders_email',
        'spiritual_reminders_sms',
        'spiritual_reminders_push',
        'spiritual_reminders_whatsapp',
        'whatsapp_number',
        'sms_number',
        'push_token',
        'email_frequency',
        'sms_frequency',
        'whatsapp_frequency',
        'quiet_hours_start',
        'quiet_hours_end',
        'respect_quiet_hours',
    ];

    protected $casts = [
        'email_enabled' => 'boolean',
        'sms_enabled' => 'boolean',
        'push_enabled' => 'boolean',
        'whatsapp_enabled' => 'boolean',
        'events_email' => 'boolean',
        'events_sms' => 'boolean',
        'events_push' => 'boolean',
        'events_whatsapp' => 'boolean',
        'prayer_requests_email' => 'boolean',
        'prayer_requests_sms' => 'boolean',
        'prayer_requests_push' => 'boolean',
        'prayer_requests_whatsapp' => 'boolean',
        'announcements_email' => 'boolean',
        'announcements_sms' => 'boolean',
        'announcements_push' => 'boolean',
        'announcements_whatsapp' => 'boolean',
        'messages_email' => 'boolean',
        'messages_sms' => 'boolean',
        'messages_push' => 'boolean',
        'messages_whatsapp' => 'boolean',
        'ministry_updates_email' => 'boolean',
        'ministry_updates_sms' => 'boolean',
        'ministry_updates_push' => 'boolean',
        'ministry_updates_whatsapp' => 'boolean',
        'birthday_reminders_email' => 'boolean',
        'birthday_reminders_sms' => 'boolean',
        'birthday_reminders_push' => 'boolean',
        'birthday_reminders_whatsapp' => 'boolean',
        'spiritual_reminders_email' => 'boolean',
        'spiritual_reminders_sms' => 'boolean',
        'spiritual_reminders_push' => 'boolean',
        'spiritual_reminders_whatsapp' => 'boolean',
        'respect_quiet_hours' => 'boolean',
        'quiet_hours_start' => 'datetime:H:i',
        'quiet_hours_end' => 'datetime:H:i',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vérifier si une notification est autorisée pour un type donné
     */
    public function isNotificationEnabled(string $type, string $channel): bool
    {
        $field = "{$type}_{$channel}";
        return $this->$field ?? false;
    }

    /**
     * Vérifier si les heures silencieuses sont respectées
     */
    public function isWithinQuietHours(): bool
    {
        if (!$this->respect_quiet_hours) {
            return false;
        }

        $now = now();
        $start = $this->quiet_hours_start;
        $end = $this->quiet_hours_end;

        if ($start <= $end) {
            return $now->format('H:i') >= $start->format('H:i') && $now->format('H:i') <= $end->format('H:i');
        } else {
            // Gestion du cas où les heures silencieuses passent minuit
            return $now->format('H:i') >= $start->format('H:i') || $now->format('H:i') <= $end->format('H:i');
        }
    }

    /**
     * Obtenir tous les canaux activés pour un type de notification
     */
    public function getEnabledChannels(string $type): array
    {
        $channels = [];
        
        if ($this->isNotificationEnabled($type, 'email') && $this->email_enabled) {
            $channels[] = 'email';
        }
        
        if ($this->isNotificationEnabled($type, 'sms') && $this->sms_enabled && $this->sms_number) {
            $channels[] = 'sms';
        }
        
        if ($this->isNotificationEnabled($type, 'push') && $this->push_enabled && $this->push_token) {
            $channels[] = 'push';
        }
        
        if ($this->isNotificationEnabled($type, 'whatsapp') && $this->whatsapp_enabled && $this->whatsapp_number) {
            $channels[] = 'whatsapp';
        }
        
        return $channels;
    }
}
