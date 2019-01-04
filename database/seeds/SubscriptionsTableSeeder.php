<?php

use App\Login;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $plans = [
            ['name' => 'Free', 'price' => 0, 'max_tours' => 3, 'subkey' => 'free'],
            ['name' => 'Bronze', 'price' => 24.99, 'max_tours' => 10, 'subkey' => 'bronze'],
            ['name' => 'Silver', 'price' => 54.99, 'max_tours' => 50, 'subkey' => 'silver'],
            ['name' => 'Gold', 'price' => 74.99, 'max_tours' => 99, 'subkey' => 'gold'],
            ['name' => 'Platinum', 'price' => 244.99, 'max_tours' => 999999999, 'subkey' => 'platinum'],
        ];


        foreach ($plans as $plans) {

            DB::table('plans')->insert([
                'name' => $plans['name'],
                'price' => $plans['price'],
                'max_tours' => $plans['max_tours'],
                'subkey' => $plans['subkey']
            ]);
        }

    }
}
