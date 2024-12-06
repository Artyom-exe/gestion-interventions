<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    use HasFactory;

    protected $table = 'status_history'; // Nom de la table

    protected $fillable = [
        'status',
        'changed_at',
        'changed_by',
        'related_to_id',
        'related_to_type',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    /**
     * Relation polymorphique : Récupérer l'entité liée (Ticket ou Intervention).
     */
    public function relatedTo()
    {
        return $this->morphTo();
    }

    /**
     * Relation : Récupérer l'utilisateur ayant effectué la modification.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
