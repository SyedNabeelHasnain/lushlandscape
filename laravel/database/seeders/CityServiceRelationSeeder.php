<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class CityServiceRelationSeeder extends Seeder
{
    public function run(): void
    {
        $cities = City::where('status', 'published')->get();
        $categories = ServiceCategory::where('status', 'published')->get();
        $services = Service::where('status', 'published')->get();

        // Link every published category to every published city
        foreach ($cities as $city) {
            $city->serviceCategories()->syncWithoutDetaching(
                $categories->pluck('id')->toArray()
            );
        }

        // Link every published service to every published city
        foreach ($cities as $city) {
            $city->services()->syncWithoutDetaching(
                $services->pluck('id')->toArray()
            );
        }
    }
}
