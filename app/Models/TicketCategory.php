<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'category', 'price', 'stock', 'booking_deadline',
    ];

    // Relasi many-to-one ke event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
