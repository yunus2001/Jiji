<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Buyer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::factory()->hasAddress(['user_id' => 1])->create();
        User::factory()->hasAddress(['user_id' => 2])->create();
        Buyer::factory()->hasAddress(['user_id' => 1])->create();
        Buyer::factory()->hasAddress(['user_id' => 2])->create();
        Buyer::factory()->hasAddress(['user_id' => 3])->create();
        Buyer::factory()->hasAddress(['user_id' => 4])->create();
    }
}