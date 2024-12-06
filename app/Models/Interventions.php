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

    public function ticket()
    {
        return $this->belongsTo(Tickets::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
  
    public function images()
    {
        return $this->hasMany(InterventionImages::class);
    }
  
    public function statusHistories()
    {
        return $this->morphMany(StatusHistory::class, 'related_to');
    }
}

