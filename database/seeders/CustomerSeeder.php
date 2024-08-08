<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        DB::table('customer')->insert(
            [
                'name' => 'Customer 1',
                'gender' => 'Male',
                'phone_number' => '081234567891',
                'email' => 'customer@example.com',
                'password' => bcrypt('password'),
                'photo_profile' => 'uploads/customer/1708671387.png',
            ],
            [
                'name' => 'Customer 2',
                'gender' => 'Male',
                'phone_number' => '081234567891',
                'email' => 'customer2@example.com',
                'password' => bcrypt('12345678'),
                'photo_profile' => 'uploads/customer/1708671387.png',
            ]
        );
    }
}
