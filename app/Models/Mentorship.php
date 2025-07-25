<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mentorship extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_id',
        'mentee_id',
        'title',
        'description',
        'status',
        'type',
        'start_date',
        'end_date',
        'duration_weeks',
        'meeting_frequency',
        'goals',
        'expectations',
        'notes',
        'is_public',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_public' => 'boolean',
    ];

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function mentee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(MentorshipSession::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('mentor_id', $userId)
              ->orWhere('mentee_id', $userId);
        });
    }

    public function scopeAsMentor($query, $userId)
    {
        return $query->where('mentor_id', $userId);
    }

    public function scopeAsMentee($query, $userId)
    {
        return $query->where('mentee_id', $userId);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'En attente',
            'active' => 'Actif',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé',
            default => 'Inconnu'
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'spiritual' => 'Spirituel',
            'professional' => 'Professionnel',
            'personal' => 'Personnel',
            'academic' => 'Académique',
            default => 'Inconnu'
        };
    }

    public function getDurationAttribute(): string
    {
        if ($this->start_date && $this->end_date) {
            $days = $this->start_date->diffInDays($this->end_date);
            if ($days < 30) {
                return $days . ' jour(s)';
            } elseif ($days < 365) {
                $months = round($days / 30);
                return $months . ' mois';
            } else {
                $years = round($days / 365);
                return $years . ' an(s)';
            }
        }
        
        return 'Non défini';
    }

    public function getNextSessionAttribute()
    {
        return $this->sessions()
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at')
            ->first();
    }

    public function getCompletedSessionsCountAttribute(): int
    {
        return $this->sessions()->where('status', 'completed')->count();
    }

    public function getTotalSessionsCountAttribute(): int
    {
        return $this->sessions()->count();
    }

    public function getAverageRatingAttribute(): float
    {
        $sessions = $this->sessions()->whereNotNull('rating');
        if ($sessions->count() === 0) {
            return 0;
        }
        
        return round($sessions->avg('rating'), 1);
    }

    public function isUserInvolved($userId): bool
    {
        return $this->mentor_id === $userId || $this->mentee_id === $userId;
    }

    public function isMentor($userId): bool
    {
        return $this->mentor_id === $userId;
    }

    public function isMentee($userId): bool
    {
        return $this->mentee_id === $userId;
    }
}
