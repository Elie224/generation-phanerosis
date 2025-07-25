<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'avatar',
        'banner',
        'bio',
        'phone',
        'city',
        'country',
        'address',
        'birth_date',
        'gender',
        'conversion_date',
        'baptism_date',
        'ministry_role',
        'spiritual_gifts',
        'testimony',
        'member_since',
        'age_group',
        'ministries',
        'small_group',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'linkedin_url',
        'youtube_url',
        'notification_preferences',
        'privacy_level',
        'language',
        'theme',
        'is_online',
        'last_seen_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'conversion_date' => 'date',
        'baptism_date' => 'date',
        'member_since' => 'date',
        'ministries' => 'array',
        'notification_preferences' => 'array',
        'is_online' => 'boolean',
        'last_seen_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accesseurs
    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([$this->address, $this->city, $this->country]);
        return implode(', ', $parts);
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return asset('default-avatar.png');
    }

    public function getBannerUrlAttribute()
    {
        if ($this->banner) {
            return asset('storage/' . $this->banner);
        }
        return null;
    }

    // Méthodes utilitaires
    public function isPublic()
    {
        return $this->privacy_level === 'public';
    }

    public function isVisibleToMembers()
    {
        return in_array($this->privacy_level, ['public', 'members']);
    }

    public function isVisibleToFriends()
    {
        return in_array($this->privacy_level, ['public', 'members', 'friends']);
    }

    public function updateLastSeen()
    {
        $this->update([
            'last_seen_at' => now(),
            'is_online' => true
        ]);
    }

    public function setOffline()
    {
        $this->update(['is_online' => false]);
    }
}
