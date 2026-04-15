<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Neighborhood;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NeighborhoodSeeder extends Seeder
{
    public function run(): void
    {
        $neighborhoods = $this->getNeighborhoods();

        foreach ($neighborhoods as $cityName => $hoods) {
            $city = City::where('name', $cityName)->first();
            if (! $city) {
                continue;
            }

            foreach ($hoods as $i => $hood) {
                Neighborhood::updateOrCreate(
                    ['city_id' => $city->id, 'slug' => Str::slug($hood['name'])],
                    [
                        'name' => $hood['name'],
                        'latitude' => $hood['lat'],
                        'longitude' => $hood['lng'],
                        'summary' => $hood['summary'],
                        'status' => 'published',
                        'sort_order' => $i + 1,
                    ]
                );
            }
        }
    }

    private function getNeighborhoods(): array
    {
        return [

            // Hamilton (city center: 43.2557, -79.8711)
            'Hamilton' => [
                ['name' => 'Westdale',      'lat' => 43.2620, 'lng' => -79.9040, 'summary' => 'Tree-lined streets near McMaster University with 1920s-1940s homes and mature lot landscaping.'],
                ['name' => 'Dundas',         'lat' => 43.2667, 'lng' => -79.9540, 'summary' => 'Historic valley community at the base of the Niagara Escarpment with heritage streetscapes.'],
                ['name' => 'Ancaster',       'lat' => 43.2220, 'lng' => -79.9870, 'summary' => 'Established escarpment community with executive homes, large lots, and clay-heavy soils.'],
                ['name' => 'Stoney Creek',   'lat' => 43.2174, 'lng' => -79.7670, 'summary' => 'Lake Ontario-adjacent community spanning from lakeshore bungalows to escarpment-top subdivisions.'],
                ['name' => 'Waterdown',      'lat' => 43.3340, 'lng' => -79.8950, 'summary' => 'Growing community on the escarpment brow with mix of new developments and established homes.'],
                ['name' => 'Durand',         'lat' => 43.2540, 'lng' => -79.8730, 'summary' => 'Victorian-era neighbourhood with heritage mansions and mature urban canopy requiring careful landscaping.'],
                ['name' => 'Kirkendall',     'lat' => 43.2510, 'lng' => -79.8850, 'summary' => 'Lower-city neighbourhood with early 20th century homes and narrow lot frontages.'],
                ['name' => 'Locke Street',   'lat' => 43.2480, 'lng' => -79.8890, 'summary' => 'Vibrant south-end corridor with mixed residential stock and heritage character.'],
                ['name' => 'Binbrook',       'lat' => 43.1230, 'lng' => -79.8100, 'summary' => 'Rapidly growing rural-suburban community with modern subdivisions on former agricultural land.'],
                ['name' => 'Mount Hope',     'lat' => 43.1670, 'lng' => -79.8640, 'summary' => 'Semi-rural community south of the escarpment with larger rural-residential properties.'],
                ['name' => 'Flamborough',    'lat' => 43.3210, 'lng' => -79.9760, 'summary' => 'Rural township with estate properties, hobby farms, and escarpment-edge lots.'],
                ['name' => 'Winona',         'lat' => 43.2150, 'lng' => -79.7130, 'summary' => 'Lake-effect zone community along the QEW corridor with orchard-belt heritage properties.'],
            ],

            // Burlington (city center: 43.3255, -79.7990)
            'Burlington' => [
                ['name' => 'Aldershot',      'lat' => 43.3130, 'lng' => -79.8460, 'summary' => 'West Burlington waterfront area with post-war bungalows and proximity to RBG and Cootes Paradise.'],
                ['name' => 'Tyandaga',       'lat' => 43.3170, 'lng' => -79.7980, 'summary' => 'South-central neighbourhood with mature trees and mid-century homes on generous lots.'],
                ['name' => 'Roseland',       'lat' => 43.3210, 'lng' => -79.8090, 'summary' => 'Established south Burlington neighbourhood with wide lots and mature landscaping.'],
                ['name' => 'Millcroft',      'lat' => 43.3650, 'lng' => -79.8170, 'summary' => 'Executive-home community on the escarpment bench with large lots and premium properties.'],
                ['name' => 'Headon Forest',  'lat' => 43.3580, 'lng' => -79.7790, 'summary' => 'Family neighbourhood in north Burlington with modern subdivisions and diverse housing stock.'],
                ['name' => 'Orchard',        'lat' => 43.3420, 'lng' => -79.7740, 'summary' => 'Former orchard land now hosting well-kept family homes with good-sized lots.'],
                ['name' => 'LaSalle',        'lat' => 43.3280, 'lng' => -79.7940, 'summary' => 'Central Burlington neighbourhood with walkable streets and mature tree canopy.'],
                ['name' => 'Shoreacres',     'lat' => 43.3070, 'lng' => -79.7800, 'summary' => 'Lakefront neighbourhood with salt-exposure considerations for hardscaping materials.'],
                ['name' => 'Palmer',         'lat' => 43.3150, 'lng' => -79.7710, 'summary' => 'South Burlington area with mix of bungalows and modern infill construction.'],
                ['name' => 'Brant Hills',    'lat' => 43.3470, 'lng' => -79.8230, 'summary' => 'Established family neighbourhood on the escarpment slopes with variable terrain.'],
            ],

            // Oakville (city center: 43.4675, -79.6877)
            'Oakville' => [
                ['name' => 'Old Oakville',   'lat' => 43.4420, 'lng' => -79.6720, 'summary' => 'Heritage lakefront district with strict heritage conservation design requirements.'],
                ['name' => 'Bronte',         'lat' => 43.4310, 'lng' => -79.7090, 'summary' => 'Harbour village character with heritage homes and waterfront properties.'],
                ['name' => 'Glen Abbey',     'lat' => 43.4680, 'lng' => -79.7210, 'summary' => 'Prestigious golf community with executive homes and manicured streetscapes.'],
                ['name' => 'River Oaks',     'lat' => 43.4780, 'lng' => -79.6820, 'summary' => 'Large-lot subdivision near Sixteen Mile Creek with executive-scale properties.'],
                ['name' => 'Joshua Creek',   'lat' => 43.4530, 'lng' => -79.6530, 'summary' => 'Modern executive community in southeast Oakville with diverse housing styles.'],
                ['name' => 'Clearview',      'lat' => 43.4660, 'lng' => -79.6950, 'summary' => 'Family neighbourhood in central Oakville with well-maintained properties.'],
                ['name' => 'Falgarwood',     'lat' => 43.4580, 'lng' => -79.7020, 'summary' => 'Mid-century neighbourhood with generous lot sizes and mature landscaping.'],
                ['name' => 'College Park',   'lat' => 43.4500, 'lng' => -79.6870, 'summary' => 'Established south Oakville area near Sheridan College with mix of housing types.'],
                ['name' => 'Palermo',        'lat' => 43.4950, 'lng' => -79.7260, 'summary' => 'North Oakville community with newer developments and rural-edge character.'],
                ['name' => 'Iroquois Ridge', 'lat' => 43.4830, 'lng' => -79.6940, 'summary' => 'Central-north Oakville with modern family homes and good lot proportions.'],
                ['name' => 'West Oak Trails', 'lat' => 43.4910, 'lng' => -79.7370, 'summary' => 'Northwest Oakville community with contemporary homes and environmental features.'],
                ['name' => 'Eastlake',       'lat' => 43.4440, 'lng' => -79.6580, 'summary' => 'Southeast Oakville neighbourhood with lakeside proximity and diverse housing.'],
            ],

            // Mississauga (city center: 43.5890, -79.6441)
            'Mississauga' => [
                ['name' => 'Port Credit',         'lat' => 43.5510, 'lng' => -79.5870, 'summary' => 'Waterfront village with heritage cottages, townhomes, and vibrant commercial streetscape.'],
                ['name' => 'Lorne Park',          'lat' => 43.5380, 'lng' => -79.6140, 'summary' => 'Premium residential enclave with mature tree canopy, large lots, and estate-quality properties.'],
                ['name' => 'Mineola',             'lat' => 43.5570, 'lng' => -79.5980, 'summary' => 'Established south Mississauga neighbourhood with heritage bungalows and mature landscaping.'],
                ['name' => 'Clarkson',            'lat' => 43.5250, 'lng' => -79.6360, 'summary' => 'Southwest Mississauga community near Lake Ontario with diverse residential housing stock.'],
                ['name' => 'Erin Mills',          'lat' => 43.5640, 'lng' => -79.6830, 'summary' => 'Large planned community in west Mississauga with family homes and varied lot sizes.'],
                ['name' => 'Meadowvale',          'lat' => 43.6120, 'lng' => -79.7170, 'summary' => 'North Mississauga community with established subdivisions and proximity to conservation areas.'],
                ['name' => 'Churchill Meadows',   'lat' => 43.5430, 'lng' => -79.7020, 'summary' => 'Modern southwest subdivision with newer construction and CVC-regulated features.'],
                ['name' => 'Streetsville',        'lat' => 43.5850, 'lng' => -79.7110, 'summary' => 'Historic village core with heritage character, Credit River proximity, and charming streetscapes.'],
                ['name' => 'Lisgar',              'lat' => 43.5690, 'lng' => -79.7310, 'summary' => 'Growing west Mississauga community with modern family homes and new infrastructure.'],
                ['name' => 'Cooksville',          'lat' => 43.5780, 'lng' => -79.6160, 'summary' => 'Central Mississauga neighbourhood undergoing revitalization with transit-oriented development.'],
                ['name' => 'Mississauga Valleys', 'lat' => 43.5710, 'lng' => -79.6470, 'summary' => 'Established central area with mature properties and proximity to the Credit River valley.'],
                ['name' => 'Erindale',            'lat' => 43.5530, 'lng' => -79.6620, 'summary' => 'Family community along the Credit River with varied housing and natural features.'],
            ],

            // Milton (city center: 43.5083, -79.8828)
            'Milton' => [
                ['name' => 'Old Milton',       'lat' => 43.5100, 'lng' => -79.8830, 'summary' => 'Historic town core with heritage properties and mature streetscapes near the escarpment.'],
                ['name' => 'Timberlea',        'lat' => 43.4920, 'lng' => -79.8730, 'summary' => 'Large modern subdivision in south Milton with diverse housing types.'],
                ['name' => 'Harrison',         'lat' => 43.5160, 'lng' => -79.8630, 'summary' => 'Growing east Milton community with contemporary family homes.'],
                ['name' => 'Clarke',           'lat' => 43.5250, 'lng' => -79.8570, 'summary' => 'Northeast Milton neighbourhood with modern construction and new infrastructure.'],
                ['name' => 'Bristol',          'lat' => 43.4980, 'lng' => -79.8550, 'summary' => 'Southeast subdivision built on former agricultural clay land requiring robust drainage.'],
                ['name' => 'Scott',            'lat' => 43.5010, 'lng' => -79.8820, 'summary' => 'Central-south Milton community with a mix of housing types and price points.'],
                ['name' => 'Willmott',         'lat' => 43.4970, 'lng' => -79.9010, 'summary' => 'Southwest Milton area with newer developments and proximity to conservation lands.'],
                ['name' => 'Dempsey',          'lat' => 43.5050, 'lng' => -79.9080, 'summary' => 'New growth area in west Milton with contemporary suburban development.'],
                ['name' => 'Mountainview',     'lat' => 43.5280, 'lng' => -79.8950, 'summary' => 'Escarpment-edge community with elevation changes and scenic lot positions.'],
                ['name' => 'Bronte Meadows',   'lat' => 43.5210, 'lng' => -79.9070, 'summary' => 'Northwest Milton neighbourhood with newer homes and natural heritage features.'],
            ],

            // Toronto (city center: 43.6532, -79.3832)
            'Toronto' => [
                ['name' => 'Etobicoke',      'lat' => 43.6205, 'lng' => -79.5132, 'summary' => 'West Toronto district with diverse housing from lakefront condos to suburban bungalows.'],
                ['name' => 'North York',     'lat' => 43.7615, 'lng' => -79.4111, 'summary' => 'Central-north Toronto with wide range of residential types and clay-heavy soils.'],
                ['name' => 'Scarborough',    'lat' => 43.7731, 'lng' => -79.2578, 'summary' => 'East Toronto with Bluffs-area sandy soils and diverse residential neighbourhoods.'],
                ['name' => 'Forest Hill',    'lat' => 43.6870, 'lng' => -79.4120, 'summary' => 'Affluent midtown neighbourhood with heritage estates and mature tree canopy.'],
                ['name' => 'Lawrence Park',  'lat' => 43.7240, 'lng' => -79.4010, 'summary' => 'Premium north-midtown neighbourhood with large lots and heritage landscape character.'],
                ['name' => 'The Kingsway',   'lat' => 43.6440, 'lng' => -79.5030, 'summary' => 'Western Etobicoke estate neighbourhood with Tudor-style homes and curving streetscapes.'],
                ['name' => 'Leaside',        'lat' => 43.7070, 'lng' => -79.3670, 'summary' => 'East York community with post-war housing, ravine-edge properties, and mature landscapes.'],
                ['name' => 'Danforth',       'lat' => 43.6846, 'lng' => -79.3490, 'summary' => 'East-end corridor with Victorian and Edwardian homes and urban lot constraints.'],
                ['name' => 'High Park',      'lat' => 43.6486, 'lng' => -79.4630, 'summary' => 'West-end neighbourhood adjacent to High Park with Victorian homes and ravine proximity.'],
                ['name' => 'Rosedale',       'lat' => 43.6790, 'lng' => -79.3780, 'summary' => 'Prestigious central neighbourhood with heritage estates and ravine protection bylaws.'],
                ['name' => 'Midtown',        'lat' => 43.6970, 'lng' => -79.3950, 'summary' => 'Central Toronto residential area with mix of housing types and urban density considerations.'],
                ['name' => 'Willowdale',     'lat' => 43.7640, 'lng' => -79.4140, 'summary' => 'North York community with post-war bungalows undergoing significant redevelopment.'],
            ],

            // Vaughan (city center: 43.8361, -79.4981)
            'Vaughan' => [
                ['name' => 'Woodbridge',      'lat' => 43.7940, 'lng' => -79.5310, 'summary' => 'Established Vaughan community with strong Italian-Canadian heritage and premium hardscaping demand.'],
                ['name' => 'Kleinburg',       'lat' => 43.8360, 'lng' => -79.6270, 'summary' => 'Heritage village with estate properties, artist studios, and rural-edge character.'],
                ['name' => 'Maple',           'lat' => 43.8530, 'lng' => -79.5040, 'summary' => 'Growing community in east Vaughan with mix of new and established housing.'],
                ['name' => 'Thornhill',       'lat' => 43.8080, 'lng' => -79.4510, 'summary' => 'Southern Vaughan community straddling the Markham border with diverse housing stock.'],
                ['name' => 'Concord',         'lat' => 43.7970, 'lng' => -79.4880, 'summary' => 'Central Vaughan area with industrial-residential mix and established homes.'],
                ['name' => 'Vellore Village', 'lat' => 43.8590, 'lng' => -79.5570, 'summary' => 'Modern northwest Vaughan subdivision with contemporary family homes.'],
                ['name' => 'Patterson',       'lat' => 43.8230, 'lng' => -79.5450, 'summary' => 'West Vaughan community near the Humber River with newer residential development.'],
                ['name' => 'Sonoma Heights',  'lat' => 43.8060, 'lng' => -79.5540, 'summary' => 'Premium Woodbridge enclave with large custom-built homes and manicured properties.'],
                ['name' => 'Islington Woods', 'lat' => 43.7870, 'lng' => -79.5620, 'summary' => 'Southwest Vaughan estate area with large lots and mature tree cover.'],
                ['name' => 'Elder Mills',     'lat' => 43.8420, 'lng' => -79.5390, 'summary' => 'Central Vaughan neighbourhood with family homes and proximity to Boyd Conservation Area.'],
            ],

            // Richmond Hill (city center: 43.8828, -79.4403)
            'Richmond Hill' => [
                ['name' => 'South Richvale',  'lat' => 43.8560, 'lng' => -79.4370, 'summary' => 'Southern Richmond Hill with mature homes, wide lots, and established landscaping.'],
                ['name' => 'Oak Ridges',      'lat' => 43.9260, 'lng' => -79.4540, 'summary' => 'Northern community on the Oak Ridges Moraine with sandy soils and environmental protections.'],
                ['name' => 'Bayview Hill',    'lat' => 43.8750, 'lng' => -79.4170, 'summary' => 'Premium southeast neighbourhood with large executive homes and diverse demographics.'],
                ['name' => 'Jefferson',       'lat' => 43.8810, 'lng' => -79.4250, 'summary' => 'Central-east Richmond Hill with established family homes and good transit access.'],
                ['name' => 'Westbrook',       'lat' => 43.8880, 'lng' => -79.4620, 'summary' => 'West Richmond Hill neighbourhood with newer construction and family-oriented community.'],
                ['name' => 'Mill Pond',       'lat' => 43.8730, 'lng' => -79.4400, 'summary' => 'Historic village area near the Mill Pond with heritage character and mature landscapes.'],
                ['name' => 'Crosby',          'lat' => 43.8850, 'lng' => -79.4480, 'summary' => 'Central Richmond Hill with diverse housing types and proximity to major corridors.'],
                ['name' => 'Harding',         'lat' => 43.8900, 'lng' => -79.4310, 'summary' => 'East-central area with mix of housing styles and well-established streetscapes.'],
                ['name' => 'Langstaff',       'lat' => 43.8470, 'lng' => -79.4350, 'summary' => 'Southern edge community near Highway 7 with intensification and transit development.'],
                ['name' => 'Observatory',     'lat' => 43.9100, 'lng' => -79.4200, 'summary' => 'Northeast Richmond Hill with estate-scale lots and proximity to David Dunlap Observatory lands.'],
            ],

            // Georgetown (city center: 43.6525, -79.9197)
            'Georgetown' => [
                ['name' => 'Georgetown Downtown', 'lat' => 43.6520, 'lng' => -79.9200, 'summary' => 'Historic commercial and residential core with heritage buildings and walkable streetscape.'],
                ['name' => 'Georgetown South',    'lat' => 43.6410, 'lng' => -79.9160, 'summary' => 'Modern subdivision area with newer housing stock and former agricultural soils.'],
                ['name' => 'Hungry Hollow',       'lat' => 43.6540, 'lng' => -79.9280, 'summary' => 'Natural heritage area along the Credit River with environmentally sensitive properties.'],
                ['name' => 'Glen Williams',       'lat' => 43.6640, 'lng' => -79.9380, 'summary' => 'Heritage hamlet west of Georgetown with rural character and escarpment-edge properties.'],
                ['name' => 'Silver Creek',        'lat' => 43.6580, 'lng' => -79.9080, 'summary' => 'Community near Silver Creek tributary with CVC-regulated properties and natural features.'],
                ['name' => 'Cedarvale',           'lat' => 43.6470, 'lng' => -79.9240, 'summary' => 'Established residential area with mature homes and good-sized lots.'],
                ['name' => 'Georgetown North',    'lat' => 43.6630, 'lng' => -79.9150, 'summary' => 'Northern residential area with mix of housing types and proximity to conservation lands.'],
                ['name' => 'Stewarttown',         'lat' => 43.6500, 'lng' => -79.8930, 'summary' => 'Small rural-residential community east of Georgetown with larger properties.'],
                ['name' => 'Limehouse',           'lat' => 43.6340, 'lng' => -79.9510, 'summary' => 'Heritage hamlet on the Niagara Escarpment with scenic properties and escarpment regulations.'],
                ['name' => 'Norval',              'lat' => 43.6270, 'lng' => -79.8780, 'summary' => 'Rural community at the Credit River and Silver Creek confluence with flood-plain considerations.'],
            ],

            // Brampton (city center: 43.7315, -79.7624)
            'Brampton' => [
                ['name' => 'Heart Lake',      'lat' => 43.7640, 'lng' => -79.7150, 'summary' => 'Established northeast Brampton community near Heart Lake Conservation Area with mature lots.'],
                ['name' => 'Mount Pleasant',  'lat' => 43.7810, 'lng' => -79.7870, 'summary' => 'Large modern subdivision in northwest Brampton with newer homes on clay-heavy soils.'],
                ['name' => 'Castlemore',      'lat' => 43.7880, 'lng' => -79.7250, 'summary' => 'Northeast Brampton area with estate-scale properties and custom-built homes.'],
                ['name' => 'Brampton North',  'lat' => 43.7950, 'lng' => -79.7620, 'summary' => 'Growing northern corridor with new subdivisions and expansion development.'],
                ['name' => 'Springdale',      'lat' => 43.7430, 'lng' => -79.7940, 'summary' => 'Central-west Brampton community with diverse housing and established infrastructure.'],
                ['name' => 'Sandalwood',      'lat' => 43.7560, 'lng' => -79.7530, 'summary' => 'Central-north area with family homes and proximity to parks and trails.'],
                ['name' => 'Fletchers Creek', 'lat' => 43.7200, 'lng' => -79.8050, 'summary' => 'West Brampton community near Fletchers Creek with CVC-regulated properties.'],
                ['name' => 'Bram West',       'lat' => 43.7060, 'lng' => -79.8110, 'summary' => 'Southwest Brampton with newer development and proximity to conservation lands.'],
                ['name' => 'Bramalea',        'lat' => 43.7280, 'lng' => -79.7330, 'summary' => 'Established central Brampton community with post-1960s housing and mature landscapes.'],
                ['name' => 'Central Brampton', 'lat' => 43.7310, 'lng' => -79.7620, 'summary' => 'Downtown Brampton area with heritage core and diverse residential stock.'],
                ['name' => 'Gore Meadows',    'lat' => 43.7780, 'lng' => -79.7070, 'summary' => 'Northeast Brampton community near Gore Meadows Park with newer homes.'],
                ['name' => 'Credit Valley',   'lat' => 43.7100, 'lng' => -79.7910, 'summary' => 'Southwest Brampton near the Credit River with CVC-regulated lots and natural features.'],
            ],

        ];
    }
}
