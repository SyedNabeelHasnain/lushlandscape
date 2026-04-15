<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['name' => 'Hamilton', 'region_name' => 'Hamilton-Wentworth', 'latitude' => 43.2557, 'longitude' => -79.8711, 'sort_order' => 1],
            ['name' => 'Burlington', 'region_name' => 'Halton Region', 'latitude' => 43.3255, 'longitude' => -79.7990, 'sort_order' => 2],
            ['name' => 'Oakville', 'region_name' => 'Halton Region', 'latitude' => 43.4675, 'longitude' => -79.6877, 'sort_order' => 3],
            ['name' => 'Mississauga', 'region_name' => 'Peel Region', 'latitude' => 43.5890, 'longitude' => -79.6441, 'sort_order' => 4],
            ['name' => 'Milton', 'region_name' => 'Halton Region', 'latitude' => 43.5083, 'longitude' => -79.8828, 'sort_order' => 5],
            ['name' => 'Toronto', 'region_name' => 'Toronto', 'latitude' => 43.6532, 'longitude' => -79.3832, 'sort_order' => 6],
            ['name' => 'Vaughan', 'region_name' => 'York Region', 'latitude' => 43.8361, 'longitude' => -79.4981, 'sort_order' => 7],
            ['name' => 'Richmond Hill', 'region_name' => 'York Region', 'latitude' => 43.8828, 'longitude' => -79.4403, 'sort_order' => 8],
            ['name' => 'Georgetown', 'region_name' => 'Halton Hills', 'latitude' => 43.6525, 'longitude' => -79.9197, 'sort_order' => 9],
            ['name' => 'Brampton', 'region_name' => 'Peel Region', 'latitude' => 43.7315, 'longitude' => -79.7624, 'sort_order' => 10],
        ];

        foreach ($cities as $cityData) {
            $cityData['province_name'] = 'Ontario';
            $cityData['status'] = 'published';

            City::updateOrCreate(
                ['name' => $cityData['name']],
                $cityData
            );
        }
    }
}
