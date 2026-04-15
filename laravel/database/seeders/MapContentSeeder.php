<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceCityPage;
use App\Models\PageBlock;
use Illuminate\Database\Seeder;

class MapContentSeeder extends Seeder
{
    private function appendBlockIfMissing(string $pageType, mixed $pageId, string $blockType, array $content, bool $isEnabled = true): void
    {
        $existsQuery = PageBlock::where('page_type', $pageType)
            ->where('block_type', $blockType);
        if ($pageId === null) {
            $existsQuery->whereNull('page_id');
        } else {
            $existsQuery->where('page_id', $pageId);
        }
        $exists = $existsQuery->exists();

        if ($exists) {
            return;
        }

        $sortOrderQuery = PageBlock::where('page_type', $pageType)
            ->whereNull('parent_id');
        if ($pageId === null) {
            $sortOrderQuery->whereNull('page_id');
        } else {
            $sortOrderQuery->where('page_id', $pageId);
        }
        $sortOrder = (int) $sortOrderQuery->max('sort_order');

        $category = (string) (config('blocks.types.'.$blockType.'.category') ?? 'content');

        PageBlock::create([
            'page_type' => $pageType,
            'page_id' => $pageId,
            'block_type' => $blockType,
            'category' => $category,
            'sort_order' => $sortOrder + 1,
            'is_enabled' => $isEnabled,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => $content,
        ]);
    }

    public function run(): void
    {
        $this->seedCityMaps();
        $this->seedServiceCityMaps();
        $this->seedServicesHubMap();
        $this->seedLocationsHubMap();
        $this->seedServiceCategoryMaps();
        $this->seedServiceDetailMaps();
    }

    /**
     * Add an interactive map block to each city page showing its neighbourhoods.
     */
    private function seedCityMaps(): void
    {
        $cities = City::where('status', 'published')->orderBy('sort_order')->get();

        foreach ($cities as $city) {
            $desc = $this->cityMapDescription($city->name);

            $this->appendBlockIfMissing('city', $city->id, 'interactive_map', [
                'heading' => "Neighbourhoods We Serve in {$city->name}",
                'description' => $desc,
                'map_mode' => 'single_city',
                'city_slug' => $city->slug_final,
                'center_lat' => (string) $city->latitude,
                'center_lng' => (string) $city->longitude,
                'zoom' => '12',
                'height' => '480',
                'show_chips' => true,
                'marker_color' => 'forest',
                'popup_cta_text' => 'Book a Consultation',
                'schema_type' => 'LocalBusiness',
                'markers' => [],
            ]);
        }
    }

    /**
     * Add an interactive map block to each service-city page (single city mode).
     */
    private function seedServiceCityMaps(): void
    {
        $pages = ServiceCityPage::where('is_active', true)
            ->with(['city', 'service'])
            ->get();

        foreach ($pages as $page) {
            if (! $page->city || ! $page->service) {
                continue;
            }

            $city = $page->city;
            $service = $page->service->name;
            $desc = $this->serviceCityMapDescription($service, $city->name);

            $this->appendBlockIfMissing('service_city_page', $page->id, 'interactive_map', [
                'heading' => "{$service} service areas in {$city->name}",
                'description' => $desc,
                'map_mode' => 'single_city',
                'city_slug' => $city->slug_final,
                'center_lat' => (string) $city->latitude,
                'center_lng' => (string) $city->longitude,
                'zoom' => '12',
                'height' => '420',
                'show_chips' => true,
                'marker_color' => 'forest',
                'popup_cta_text' => 'Book a Consultation',
                'schema_type' => 'LocalBusiness',
                'markers' => [],
            ]);
        }
    }

    /**
     * Add an all-cities map block to the services hub page.
     */
    private function seedServicesHubMap(): void
    {
        $this->appendBlockIfMissing('services_hub', null, 'interactive_map', [
            'heading' => 'Where we provide our services',
            'description' => 'Explore the cities we serve across Southern Ontario and plan your consultation.',
            'map_mode' => 'all_cities',
            'city_slug' => '',
            'center_lat' => '43.55',
            'center_lng' => '-79.65',
            'zoom' => '9',
            'height' => '500',
            'show_chips' => true,
            'marker_color' => 'forest',
            'popup_cta_text' => 'Book a Consultation',
            'schema_type' => 'LocalBusiness',
            'markers' => [],
        ]);
    }

    /**
     * Add an all-cities map block to the locations hub page.
     */
    private function seedLocationsHubMap(): void
    {
        $this->appendBlockIfMissing('locations_hub', null, 'interactive_map', [
            'heading' => 'Our service coverage across Ontario',
            'description' => 'Select a city to explore local services and plan your consultation.',
            'map_mode' => 'all_cities',
            'city_slug' => '',
            'center_lat' => '43.55',
            'center_lng' => '-79.65',
            'zoom' => '9',
            'height' => '500',
            'show_chips' => true,
            'marker_color' => 'forest',
            'popup_cta_text' => 'Book a Consultation',
            'schema_type' => 'LocalBusiness',
            'markers' => [],
        ]);
    }

    /**
     * Add an all-cities map block to each service category page.
     */
    private function seedServiceCategoryMaps(): void
    {
        $categories = ServiceCategory::where('status', 'published')->get();

        foreach ($categories as $cat) {
            $this->appendBlockIfMissing('service_category', $cat->id, 'interactive_map', [
                'heading' => "{$cat->name} service areas",
                'description' => "Explore city-level availability for {$cat->name} and plan your consultation.",
                'map_mode' => 'all_cities',
                'city_slug' => '',
                'center_lat' => '43.55',
                'center_lng' => '-79.65',
                'zoom' => '9',
                'height' => '460',
                'show_chips' => true,
                'marker_color' => 'forest',
                'popup_cta_text' => 'Book a Consultation',
                'schema_type' => 'LocalBusiness',
                'markers' => [],
            ]);
        }
    }

    /**
     * Add an all-cities map block to each service detail page.
     */
    private function seedServiceDetailMaps(): void
    {
        $services = Service::where('status', 'published')->get();

        foreach ($services as $svc) {
            $this->appendBlockIfMissing('service', $svc->id, 'interactive_map', [
                'heading' => "Cities where we offer {$svc->name}",
                'description' => "Explore city-level availability for {$svc->name} and plan your consultation.",
                'map_mode' => 'all_cities',
                'city_slug' => '',
                'center_lat' => '43.55',
                'center_lng' => '-79.65',
                'zoom' => '9',
                'height' => '460',
                'show_chips' => true,
                'marker_color' => 'forest',
                'popup_cta_text' => 'Book a Consultation',
                'schema_type' => 'LocalBusiness',
                'markers' => [],
            ]);
        }
    }

    private function cityMapDescription(string $city): string
    {
        $descs = [
            'Hamilton' => 'Our Hamilton crews cover every corner of the city, from the heritage streets of Dundas to the growing subdivisions of Binbrook. Select a neighbourhood below to learn about our landscaping services in your area.',
            'Burlington' => 'We serve Burlington homeowners from the lakefront properties of Shoreacres to the escarpment homes of Millcroft. Tap any neighbourhood marker to see how we can enhance your outdoor space.',
            'Oakville' => 'From the heritage streetscapes of Old Oakville to the executive homes of Glen Abbey, our Oakville team delivers premium landscaping across every neighbourhood. Explore the map to find your area.',
            'Mississauga' => 'Our Mississauga operations cover the entire city, from the waterfront village of Port Credit to the established communities of Meadowvale. Select your neighbourhood to get started.',
            'Milton' => 'We are actively completing projects throughout Milton, from the historic town core to the newer subdivisions of Timberlea and Harrison. Find your neighbourhood on the map below.',
            'Toronto' => 'Our Toronto division serves homeowners across the city, from The Kingsway in Etobicoke to the Bluffs in Scarborough. Select your area to see our local service availability.',
            'Vaughan' => 'From the established streets of Woodbridge to the estate properties of Kleinburg, our Vaughan team brings premium craftsmanship to every neighbourhood. Explore our coverage below.',
            'Richmond Hill' => 'We serve Richmond Hill from the southern edge near Highway 7 to the Oak Ridges Moraine in the north. Select your neighbourhood marker for local project details.',
            'Georgetown' => 'Our Georgetown crews cover the entire Halton Hills area, from the historic downtown core to the rural hamlets of Glen Williams and Limehouse. Explore our service coverage.',
            'Brampton' => 'We serve Brampton homeowners in every corner of the city, from the mature lots of Heart Lake to the growing communities of Mount Pleasant. Find your neighbourhood below.',
        ];

        return $descs[$city] ?? "Explore the neighbourhoods we serve in {$city}. Select a marker to learn about our local landscaping services and begin your project inquiry.";
    }

    private function serviceCityMapDescription(string $service, string $city): string
    {
        return "We provide professional {$service} services across {$city} and its surrounding neighbourhoods. Select a location marker below to see the areas where our {$service} crews are currently booking projects, and request an on-site consultation.";
    }
}
