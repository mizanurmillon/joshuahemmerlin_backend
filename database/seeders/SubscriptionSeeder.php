<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subscription::create([
            'name' => 'Standard Plan',
            'price' => 99.99,
            'type' => 'monthly',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
