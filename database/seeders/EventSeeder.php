<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Illuminate\Support\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'name' => 'Konser Musik Rock 2025',
                'description' => 'Konser musik rock dengan band ternama nasional.',
                'location' => 'Stadion Utama Gelora Bung Karno',
                'event_datetime' => Carbon::parse('2025-06-15 19:00:00'),
                'image' => 'events/rock2025.jpg',
            ],
            [
                'name' => 'Festival Kuliner Nusantara',
                'description' => 'Nikmati berbagai hidangan khas dari seluruh Indonesia.',
                'location' => 'Lapangan Banteng, Jakarta',
                'event_datetime' => Carbon::parse('2025-05-20 10:00:00'),
                'image' => 'events/kuliner2025.jpg',
            ],
            [
                'name' => 'Tech Conference Indonesia',
                'description' => 'Diskusi dan workshop teknologi terbaru.',
                'location' => 'ICE BSD, Tangerang',
                'event_datetime' => Carbon::parse('2025-08-10 09:00:00'),
                'image' => 'events/techconf.jpg',
            ],
            [
                'name' => 'Job Fair Nasional',
                'description' => 'Bursa kerja dengan berbagai perusahaan ternama.',
                'location' => 'JCC Senayan',
                'event_datetime' => Carbon::parse('2025-04-25 08:00:00'),
                'image' => 'events/jobfair.jpg',
            ],
            [
                'name' => 'Jakarta Fashion Week',
                'description' => 'Peragaan busana oleh desainer lokal dan internasional.',
                'location' => 'Plaza Senayan',
                'event_datetime' => Carbon::parse('2025-09-01 14:00:00'),
                'image' => 'events/fashionweek.jpg',
            ],
            [
                'name' => 'Festival Film Indie',
                'description' => 'Pemutaran film indie dari sineas muda Indonesia.',
                'location' => 'CGV Grand Indonesia',
                'event_datetime' => Carbon::parse('2025-07-12 18:30:00'),
                'image' => 'events/filmindie.jpg',
            ],
            [
                'name' => 'Workshop UI/UX Design',
                'description' => 'Pelatihan langsung bersama praktisi UI/UX.',
                'location' => 'Hacktiv8, Jakarta Selatan',
                'event_datetime' => Carbon::parse('2025-06-05 10:00:00'),
                'image' => 'events/uiux.jpg',
            ],
            [
                'name' => 'Pentas Seni SMA Se-Jakarta',
                'description' => 'Penampilan seni dari berbagai sekolah.',
                'location' => 'Taman Ismail Marzuki',
                'event_datetime' => Carbon::parse('2025-10-10 15:00:00'),
                'image' => 'events/pensijakarta.jpg',
            ],
            [
                'name' => 'Festival Startup Indonesia',
                'description' => 'Ajang presentasi dan networking startup lokal.',
                'location' => 'Block71 Jakarta',
                'event_datetime' => Carbon::parse('2025-11-20 09:00:00'),
                'image' => 'events/startupfest.jpg',
            ],
            [
                'name' => 'Seminar Kesehatan Mental',
                'description' => 'Diskusi dan edukasi mengenai pentingnya kesehatan mental.',
                'location' => 'Universitas Indonesia',
                'event_datetime' => Carbon::parse('2025-05-10 13:00:00'),
                'image' => 'events/sehatmental.jpg',
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}