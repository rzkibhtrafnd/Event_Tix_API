<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
        'event_datetime',
        'image',
    ];

    public function ticketCategories()
    {
        return $this->hasMany(TicketCategory::class);
    }
}
