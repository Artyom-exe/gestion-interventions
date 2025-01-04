<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketImages extends Model
{
    use HasFactory;

    protected $table = 'ticket_images'; // Nom de la table

    protected $fillable = [
        'image_path',
        'ticket_id',
    ];

    /**
     * Relation : Une image appartient à un ticket.
     */
    public function ticket()
    {
        return $this->belongsTo(Tickets::class, 'ticket_id'); // Correcte clé étrangère
    }
}
