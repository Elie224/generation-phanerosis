<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function notificationPreferences()
    {
        return $this->hasOne(NotificationPreference::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // Les amis où l'utilisateur est l'initiateur
    public function friendships()
    {
        return $this->hasMany(Friendship::class, 'user_id')->where('status', 'accepted');
    }

    // Les amis où l'utilisateur est le destinataire
    public function friendsOf()
    {
        return $this->hasMany(Friendship::class, 'friend_id')->where('status', 'accepted');
    }

    // Tous les amis (collection User)
    public function allFriends()
    {
        $ids = collect($this->friendships()->pluck('friend_id'))
            ->merge($this->friendsOf()->pluck('user_id'))
            ->unique()
            ->values();
        return User::whereIn('id', $ids)->get();
    }

    // Messages envoyés
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'from_id');
    }

    // Messages reçus
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'to_id');
    }

    // Tous les amis (l'utilisateur est user_id OU friend_id)
    public function friendsAll()
    {
        return User::where(function($query) {
                $query->whereIn('id', function($subQuery) {
                    $subQuery->select('friend_id')
                        ->from('friendships')
                        ->where('user_id', $this->id)
                        ->where('status', 'accepted');
                })
                ->orWhereIn('id', function($subQuery) {
                    $subQuery->select('user_id')
                        ->from('friendships')
                        ->where('friend_id', $this->id)
                        ->where('status', 'accepted');
                });
            });
    }

    public function isAdmin() { return $this->role === 'admin'; }
    public function isPastor() { return $this->role === 'pasteur'; }
    public function isLeader() { return $this->role === 'leader'; }
    public function isMember() { return $this->role === 'member'; }
    public function isMainAdmin() { 
        return $this->email === config('admin_security.main_admin.email'); 
    }
    
    public function hasRole($role) { return $this->role === $role; }
    public function hasAnyRole($roles) { return in_array($this->role, (array) $roles); }

    // Protection de l'administrateur principal - Empêcher toute modification
    protected static function boot()
    {
        parent::boot();

        // Avant de sauvegarder, vérifier qu'on ne modifie pas l'admin principal
        static::saving(function ($user) {
            if ($user->isMainAdmin()) {
                // Empêcher toute modification de l'email, du rôle et du statut
                $user->email = config('admin_security.main_admin.email');
                $user->role = config('admin_security.main_admin.forced_role');
                $user->is_active = config('admin_security.main_admin.forced_status');
                
                // Log de sécurité
                if (config('admin_security.logging.enabled')) {
                    \Log::log(
                        config('admin_security.logging.modification_level'),
                        'Tentative de modification de l\'administrateur principal',
                        [
                            'user_id' => $user->id,
                            'attempted_by' => auth()->id(),
                            'ip' => request()->ip(),
                            'user_agent' => request()->userAgent()
                        ]
                    );
                }
            }
        });

        // Empêcher la suppression de l'admin principal
        static::deleting(function ($user) {
            if ($user->isMainAdmin()) {
                // Log de sécurité
                if (config('admin_security.logging.enabled')) {
                    \Log::log(
                        config('admin_security.logging.deletion_level'),
                        'Tentative de suppression de l\'administrateur principal',
                        [
                            'user_id' => $user->id,
                            'attempted_by' => auth()->id(),
                            'ip' => request()->ip(),
                            'user_agent' => request()->userAgent()
                        ]
                    );
                }
                
                throw new \Exception('L\'administrateur principal ne peut pas être supprimé.');
            }
        });
    }

    // Méthode pour vérifier si l'utilisateur peut être modifié
    public function canBeModifiedBy($user)
    {
        // L'admin principal ne peut jamais être modifié
        if ($this->isMainAdmin()) {
            return false;
        }

        // Un utilisateur ne peut pas se modifier lui-même
        if ($this->id === $user->id) {
            return false;
        }

        // SEUL l'administrateur principal peut modifier des utilisateurs
        if (!$user->isMainAdmin()) {
            return false;
        }

        return true;
    }

    // Méthode pour vérifier si l'utilisateur peut être supprimé
    public function canBeDeletedBy($user)
    {
        // L'admin principal ne peut jamais être supprimé
        if ($this->isMainAdmin()) {
            return false;
        }

        // Un utilisateur ne peut pas se supprimer lui-même
        if ($this->id === $user->id) {
            return false;
        }

        // SEUL l'administrateur principal peut supprimer des utilisateurs
        if (!$user->isMainAdmin()) {
            return false;
        }

        return true;
    }
}
