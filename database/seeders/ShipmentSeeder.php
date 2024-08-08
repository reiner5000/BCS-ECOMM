<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShipmentSeeder extends Seeder
{
    public function run()
    {
        DB::table('shipment')->insert([
            [
                'nama_penerima' => 'John Doe 1',
                'phone_number' => '081234567890',
                'negara' => 'Indonesia',
                'provinsi' => 'Jawa Barat',
                'kota' => 'Bandung',
                'kecamatan' => 'Bandung Wetan',
                'kode_pos' => '40115',
                'informasi_tambahan' => 'Dekat masjid',
                'detail_informasi_tambahan' => 'Rumah warna biru',
                'is_default' => 0,
                'customer_id' => 1,
            ],
            [
                'nama_penerima' => 'John Doe 2',
                'phone_number' => '081234567890',
                'negara' => 'Indonesia',
                'provinsi' => 'Jawa Barat',
                'kota' => 'Bandung',
                'kecamatan' => 'Bandung Wetan',
                'kode_pos' => '40115',
                'informasi_tambahan' => 'Dekat masjid',
                'detail_informasi_tambahan' => 'Rumah warna biru',
                'is_default' => 1,
                'customer_id' => 1,
            ],
            [
                'nama_penerima' => 'John Doe 3',
                'phone_number' => '081234567890',
                'negara' => 'Indonesia',
                'provinsi' => 'Jawa Barat',
                'kota' => 'Bandung',
                'kecamatan' => 'Bandung Wetan',
                'kode_pos' => '40115',
                'informasi_tambahan' => 'Dekat masjid',
                'detail_informasi_tambahan' => 'Rumah warna biru',
                'is_default' => 0,
                'customer_id' => 1,
            ],
        ]);
    }
}
