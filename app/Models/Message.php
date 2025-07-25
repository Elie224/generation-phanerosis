<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_id',
        'to_id',
        'content',
        'attachment_path',
        'audio_path',
        'is_read',
        'type',
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_id');
    }

    public function deletions()
    {
        return $this->belongsToMany(User::class, 'message_user_deletions', 'message_id', 'user_id');
    }
}
