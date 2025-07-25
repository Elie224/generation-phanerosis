<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentorshipSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentorship_id',
        'title',
        'description',
        'scheduled_at',
        'started_at',
        'ended_at',
        'status',
        'format',
        'location',
        'agenda',
        'notes',
        'homework',
        'rating',
        'feedback',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function mentorship(): BelongsTo
    {
        return $this->belongsTo(Mentorship::class);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>', now());
    }

    public function scopePast($query)
    {
        return $query->where('scheduled_at', '<', now());
    }

    public function scopeByFormat($query, $format)
    {
        return $query->where('format', $format);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'scheduled' => 'Programmée',
            'in_progress' => 'En cours',
            'completed' => 'Terminée',
            'cancelled' => 'Annulée',
            'no_show' => 'Absence',
            default => 'Inconnu'
        };
    }

    public function getFormatLabelAttribute(): string
    {
        return match($this->format) {
            'in_person' => 'En personne',
            'video_call' => 'Appel vidéo',
            'phone_call' => 'Appel téléphonique',
            'chat' => 'Chat',
            default => 'Inconnu'
        };
    }

    public function getDurationAttribute(): string
    {
        if ($this->started_at && $this->ended_at) {
            $minutes = $this->started_at->diffInMinutes($this->ended_at);
            if ($minutes < 60) {
                return $minutes . ' min';
            } else {
                $hours = floor($minutes / 60);
                $remainingMinutes = $minutes % 60;
                return $hours . 'h' . ($remainingMinutes > 0 ? ' ' . $remainingMinutes . 'min' : '');
            }
        }
        
        return 'Non défini';
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'scheduled' && $this->scheduled_at < now();
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->status === 'scheduled' && $this->scheduled_at > now();
    }

    public function getIsTodayAttribute(): bool
    {
        return $this->scheduled_at->isToday();
    }

    public function getIsTomorrowAttribute(): bool
    {
        return $this->scheduled_at->isTomorrow();
    }

    public function getTimeUntilAttribute(): string
    {
        if ($this->scheduled_at <= now()) {
            return 'Maintenant';
        }

        $diff = now()->diff($this->scheduled_at);
        
        if ($diff->days > 0) {
            return $diff->days . ' jour(s)';
        } elseif ($diff->h > 0) {
            return $diff->h . ' heure(s)';
        } else {
            return $diff->i . ' minute(s)';
        }
    }

    public function start()
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'ended_at' => now(),
        ]);
    }

    public function cancel()
    {
        $this->update([
            'status' => 'cancelled',
        ]);
    }

    public function markAsNoShow()
    {
        $this->update([
            'status' => 'no_show',
        ]);
    }
}
