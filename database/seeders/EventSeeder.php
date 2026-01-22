<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'user_id' => 1,
                'name' => 'Konser Musik Rock',
                'description' => 'Nikmati malam penuh energi dengan band rock terkenal.',
                'date_time' => '2024-08-15 19:00:00',
                'location' => 'Stadion Utama',
                'category_id' => 1,
                'picture' => 'rock.jpg',
            ],
            [
                'user_id' => 1,
                'name' => 'Pameran Seni Kontemporer',
                'description' => 'Jelajahi karya seni modern dari seniman lokal dan internasional.',
                'date_time' => '2024-09-10 10:00:00',
                'location' => 'Galeri Seni Kota',
                'category_id' => 2,
                'picture' => 'art.jpg',
            ],
            [
                'user_id' => 1,
                'name' => 'Festival Makanan Internasional',
                'description' => 'Cicipi berbagai hidangan lezat dari seluruh dunia.',
                'date_time' => '2024-10-05 12:00:00',
                'location' => 'Taman Kota',
                'category_id' => 3,
                'picture' => 'food.jpg',
            ],
            [
                'user_id' => 1,
                'name' => 'Konser Rock Internasional',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
                'date_time' => '2024-10-05 12:00:00',
                'location' => 'Taman Kota',
                'category_id' => 3,
                'picture' => 'rock.jpg',
            ],
            [
                'user_id' => 1,
                'name' => 'Pertunjukan Teater',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
                'date_time' => '2024-10-05 12:00:00',
                'location' => 'Stadium Utama',
                'category_id' => 3,
                'picture' => 'teater.jpg',
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
