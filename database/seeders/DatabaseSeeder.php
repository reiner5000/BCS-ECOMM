<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleTableSeeder::class,
            UserTableSeeder::class,
            CustomerSeeder::class,
            ChoirSeeder::class,
            ShipmentSeeder::class,
            RajaOngkirSeeder::class,
        ]);
    }
}