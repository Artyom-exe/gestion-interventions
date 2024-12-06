<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterventionImages extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
        'intervention_id',
    ];

    // Relation: Une image appartient à une intervention
    public function intervention()
    {
        return $this->belongsTo(Interventions::class);
    }
}
