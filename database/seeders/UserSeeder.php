<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        User::factory()->hasAttached(Item::factory()
                       ->state(['user_id' => 1 ])
                       ->count(3), ['item_id' => 2, 'quantity' => 5, 'seller_id' => 2])
                       ->create();

        User::factory()->hasAttached(Item::factory()
                       ->state(['user_id' => 2 ])
                       ->count(3), ['item_id' => 1, 'quantity' => 3, 'seller_id' => 1])
                       ->create();
    }
}

