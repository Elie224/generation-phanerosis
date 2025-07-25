<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    // Protège les colonnes pour l’assignation de masse
    protected $fillable = [
        'user_id',
        'friend_id',
        'status', // si tu utilises un statut (accepted, pending, etc.)
    ];

    // Relation vers l’utilisateur qui a envoyé la demande
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation vers l’ami
    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}
