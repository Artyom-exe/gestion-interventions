<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interventions extends Model
{
    use HasFactory;

    protected $fillable = [
        'details',
        'status',
        'category',
        'ticket_id',
        'assigned_to',
        'date',
    ];

    // Relation: Une intervention appartient à un ticket
    public function ticket()
    {
        return $this->belongsTo(Tickets::class);
    }

    // Relation: Une intervention appartient à un utilisateur
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Relation: Une intervention peut avoir plusieurs images
    public function images()
    {
        return $this->hasMany(InterventionImages::class);
    }

    // Relation polymorphique: Historique des statuts
    public function statusHistories()
    {
        return $this->morphMany(StatusHistory::class, 'related_to');
    }
}

