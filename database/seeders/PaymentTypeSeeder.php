<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payment_types = [
            ['name' => 'Gopay'],
            ['name' => 'OVO'],
            ['name' => 'ShopeePay'],
        ];

        foreach ($payment_types as $pt) {
            PaymentType::create(['name' => $pt['name']]);
        }
    }
}
