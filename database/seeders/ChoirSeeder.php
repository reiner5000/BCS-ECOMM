<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChoirSeeder extends Seeder
{
    public function run()
    {
        DB::table('choir')->insert([
            [
                'name' => 'Choir A',
                'address' => 'Alamat Choir A',
                'conductor' => 'Conductor A',
                'is_default' => 0,
                'customer_id' => 1,
            ],
            [
                'name' => 'Choir B',
                'address' => 'Alamat Choir A',
                'conductor' => 'Conductor A',
                'is_default' => 1,
                'customer_id' => 1,
            ],
            [
                'name' => 'Choir C',
                'address' => 'Alamat Choir A',
                'conductor' => 'Conductor A',
                'is_default' => 0,
                'customer_id' => 1,
            ],
        ]);
    }
}
