<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class RajaOngkirSeeder extends Seeder
{
    public function run()
    {
        $apiKey = '60cb01b32268ac56be57abf7bd2afe72';

        // Mengambil data negara
        $countryResponse = Http::withHeaders(['key' => $apiKey])
            ->get('https://pro.rajaongkir.com/api/v2/internationalDestination');
        $countries = $countryResponse->json()['rajaongkir']['results'];

        foreach ($countries as $country) {
            DB::table('countries')->insert([
                'country_id' => $country['country_id'],
                'country_name' => $country['country_name'],
            ]);
        }

        DB::table('countries')->insert([
            'country_id' => 236,
            'country_name' => 'Indonesia',
        ]);

        // Mengambil data provinsi
        $provinceResponse = Http::withHeaders(['key' => $apiKey])
            ->get('https://pro.rajaongkir.com/api/province');
        $provinces = $provinceResponse->json()['rajaongkir']['results'];

        foreach ($provinces as $province) {
            DB::table('provinces')->insert([
                'country_id' => 236,
                'province_id' => $province['province_id'],
                'province' => $province['province'],
            ]);
        }

        // Mengambil data kota
        $cityResponse = Http::withHeaders(['key' => $apiKey])
            ->get('https://pro.rajaongkir.com/api/city');
        $cities = $cityResponse->json()['rajaongkir']['results'];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_id' => $city['city_id'],
                'province_id' => $city['province_id'],
                'city_name' => $city['city_name'],
                'postal_code' => $city['postal_code'],
            ]);
        }
    }
}
