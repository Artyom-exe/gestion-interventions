<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    use HasFactory;

    protected $table = 'tickets'; // Nom de la table

    protected $fillable = [
        'description',
        'status',
        'priority',
        'assigned_to',
    ];

    protected $casts = [
        'status' => 'string',
        'priority' => 'string',
    ];

    /**
     * Relation : Un ticket appartient à un utilisateur (assigné).
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Relation : Un ticket peut avoir plusieurs images.
     */
    public function images()
    {
        return $this->hasMany(TicketImages::class);
    }

    /**
     * Relation : Historique des statuts liés au ticket.
     */
    public function statusHistory()
    {
        return $this->morphMany(StatusHistory::class, 'relatedTo');
    }
}
