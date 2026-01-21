<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tickets = [
            [
                'event_id' => 1,
                'type' => 'premium',
                'price' => 1500000,
                'stock' => 100,
            ],
            [
                'event_id' => 1,
                'type' => 'regular',
                'price' => 500000,
                'stock' => 500,
            ],
            [
                'event_id' => 2,
                'type' => 'premium',
                'price' => 200000,
                'stock' => 300,
            ],
            [
                'event_id' => 3,
                'type' => 'premium',
                'price' => 300000,
                'stock' => 200,
            ],
        ];

        foreach ($tickets as $ticket) {
            Ticket::create($ticket);
        }
    }
}
