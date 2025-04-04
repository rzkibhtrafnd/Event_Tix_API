<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\TicketCategory;
use Illuminate\Support\Carbon;

class TicketCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'category' => 'VIP',
                'price' => 500000,
                'stock' => 50,
            ],
            [
                'category' => 'Regular',
                'price' => 200000,
                'stock' => 150,
            ],
            [
                'category' => 'Student',
                'price' => 100000,
                'stock' => 100,
            ],
        ];

        $events = Event::all();

        foreach ($events as $event) {
            foreach ($categories as $cat) {
                TicketCategory::create([
                    'event_id' => $event->id,
                    'category' => $cat['category'],
                    'price' => $cat['price'],
                    'stock' => $cat['stock'],
                    'booking_deadline' => Carbon::parse($event->event_datetime)->subDays(3),
                ]);
            }
        }
    }
}