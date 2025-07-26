<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pastor_id',
        'content',
        'status',
        'pastor_response',
        'answered_at',
        'is_anonymous',
        'audio_path',
        'files_paths'
    ];

    protected $casts = [
        'answered_at' => 'datetime',
        'is_anonymous' => 'boolean',
        'files_paths' => 'array',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pastor()
    {
        return $this->belongsTo(User::class, 'pastor_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeAnswered($query)
    {
        return $query->where('status', 'answered');
    }

    // Accesseurs
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'En attente',
            'in_progress' => 'En cours de prière',
            'answered' => 'Répondu',
            'closed' => 'Fermé',
            default => 'Inconnu'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'in_progress' => 'info',
            'answered' => 'success',
            'closed' => 'secondary',
            default => 'light'
        };
    }
} 