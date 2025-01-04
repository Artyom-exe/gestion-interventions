<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
  
    protected $table = 'utilisateurs'; // Nom de la table personnalisée

    protected $fillable = [
        'username',
        'password',
        'email',
        'phone_number',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relation : Un utilisateur peut être assigné à plusieurs tickets.
     */
    public function tickets()
    {
        return $this->hasMany(Tickets::class, 'assigned_to');
    }

    /**
     * Relation : Un utilisateur peut être assigné à plusieurs interventions.
     */
    public function interventions()
    {
        return $this->hasMany(Interventions::class, 'assigned_to');
    }

    /**
     * Relation : Historique des statuts modifiés par cet utilisateur.
     */
    public function statusHistory()
    {
        return $this->hasMany(StatusHistory::class, 'changed_by');
    }

    public function statusChanges()
    {
        return $this->hasMany(StatusHistory::class, 'changed_by');
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
