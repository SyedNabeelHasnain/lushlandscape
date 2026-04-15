<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\MediaAsset;
use App\Models\PortfolioCategory;
use App\Models\PortfolioProject;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Services\BlockBuilderService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $catMap = $this->seedCategories();
        $services = Service::with('category')->get()->keyBy('name');
        $cities = City::where('status', 'published')->get()->keyBy('name');

        foreach ($this->getProjects() as $order => $p) {
            $service = $services[$p['service']] ?? null;
            $city = $cities[$p['city']] ?? null;
            if (! $service || ! $city) {
                continue;
            }

            $portfolioCatId = $catMap[$service->category->name ?? ''] ?? null;
            $slug = Str::limit(Str::slug($p['title']), 250, '');

            $mediaId = $this->ensureMediaPlaceholder($p['title'], $p['service'], $p['city'], $p['neighborhood']);

            $project = PortfolioProject::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $portfolioCatId,
                    'title' => $p['title'],
                    'description' => $p['description'],
                    'body' => $p['body'],
                    'project_type' => $p['project_type'],
                    'city_id' => $city->id,
                    'service_id' => $service->id,
                    'neighborhood' => $p['neighborhood'],
                    'hero_media_id' => $mediaId,
                    'project_value_range' => $p['value'],
                    'project_duration' => $p['duration'],
                    'meta_title' => Str::limit($p['title'].' | Lush Landscape Service', 70, ''),
                    'meta_description' => Str::limit($p['description'], 155, ''),
                    'is_featured' => $p['featured'] ?? false,
                    'status' => 'published',
                    'completion_date' => $p['completed'],
                    'sort_order' => $order + 1,
                ]
            );

            $this->createProjectBlocks($project, $p, $order);
        }
    }

    private function seedCategories(): array
    {
        $map = [];
        $cats = [
            ['name' => 'Interlock & Paving Projects',         'slug' => 'interlock-paving-projects',         'icon' => 'grid-3x3', 'sort_order' => 1],
            ['name' => 'Concrete Projects',                   'slug' => 'concrete-projects',                 'icon' => 'square',   'sort_order' => 2],
            ['name' => 'Structural Hardscape Projects',       'slug' => 'structural-hardscape-projects',     'icon' => 'brick-wall', 'sort_order' => 3],
            ['name' => 'Softscaping & Outdoor Living Projects', 'slug' => 'softscaping-outdoor-living-projects', 'icon' => 'trees',  'sort_order' => 4],
        ];

        $serviceCatMap = [
            'Interlock & Specialty Paving' => 'interlock-paving-projects',
            'Concrete Services' => 'concrete-projects',
            'Structural Hardscape & Repair' => 'structural-hardscape-projects',
            'Softscaping & Lifestyle Enhancements' => 'softscaping-outdoor-living-projects',
        ];

        foreach ($cats as $cat) {
            PortfolioCategory::updateOrCreate(
                ['slug' => $cat['slug']],
                array_merge($cat, [
                    'status' => 'published',
                    'short_description' => 'Completed '.strtolower($cat['name']).' by Lush Landscape Service across Ontario.',
                ])
            );
        }

        $serviceCategories = ServiceCategory::all();
        foreach ($serviceCategories as $sc) {
            $pSlug = $serviceCatMap[$sc->name] ?? null;
            if ($pSlug) {
                $pc = PortfolioCategory::where('slug', $pSlug)->first();
                if ($pc) {
                    $map[$sc->name] = $pc->id;
                }
            }
        }

        return $map;
    }

    private function ensureMediaPlaceholder(string $title, string $service, string $city, string $neighborhood): ?int
    {
        $filename = Str::slug($title).'-hero.jpg';

        $asset = MediaAsset::updateOrCreate(
            ['canonical_filename' => $filename],
            [
                'internal_title' => $title.' - Hero Image',
                'disk' => 'public',
                'path' => 'portfolio/'.Str::slug($service).'/'.Str::slug($city).'/'.Str::slug($title).'.jpg',
                'media_type' => 'image',
                'mime_type' => 'image/jpeg',
                'extension' => 'jpg',
                'description' => "Completed {$service} project in {$neighborhood}, {$city}, Ontario by Lush Landscape Service.",
                'default_alt_text' => "{$service} project in {$neighborhood}, {$city} by Lush Landscape Service",
                'default_caption' => "{$title} - {$city}, Ontario",
                'credit' => 'Lush Landscape Service',
                'image_purpose' => 'informative',
                'location_city' => $city,
                'social_preview_eligible' => true,
                'schema_eligible' => true,
                'status' => 'draft',
            ]
        );

        return $asset->id;
    }

    // ─── 42 Projects ────────────────────────────────────────────────────────

    private function getProjects(): array
    {
        return [

            // ── MISSISSAUGA (6) ─────────────────────────────────────────

            [
                'title' => 'Herringbone Paver Driveway Transformation in Port Credit',
                'service' => 'Interlocking Driveways',
                'city' => 'Mississauga',
                'neighborhood' => 'Port Credit',
                'project_type' => 'Interlock & Specialty Paving',
                'value' => '$22,000 - $28,000',
                'duration' => '5 days',
                'completed' => '2025-09-15',
                'featured' => true,
                'description' => 'Complete driveway replacement with Unilock Artline pavers in a classic herringbone pattern for a Port Credit lakefront home. The project included demolition of the existing asphalt surface, full granular base installation, and decorative soldier course borders.',
                'body' => "This Port Credit homeowner approached us after their 20-year-old asphalt driveway had deteriorated beyond repair. The driveway had extensive cracking from repeated freeze-thaw cycles and poor original drainage. They wanted a premium interlocking paver solution that would complement their lakefront property's architectural character.\n\nWe excavated to 18 inches below grade to accommodate Mississauga's clay-over-sand soil profile common in the Port Credit waterfront area. The base consists of compacted Granular A aggregate topped with a 1-inch HPB levelling course. We installed Unilock Artline pavers in a 45-degree herringbone pattern with charcoal soldier course borders and a contrasting accent band at the garage threshold. The drainage was re-engineered to direct runoff away from the home's foundation toward the municipal storm system.\n\nThe completed driveway accommodates two vehicles with a dedicated pedestrian walkway along the south edge. Polymeric sand joints and a professional-grade sealer provide long-term protection against Mississauga's salt exposure and seasonal weather. The homeowners reported immediate improvement in curb appeal and received compliments from neighbours within the first week.",
            ],

            [
                'title' => 'Multi-Level Backyard Patio with Fire Pit in Lorne Park',
                'service' => 'Interlocking Patios & Backyard Living',
                'city' => 'Mississauga',
                'neighborhood' => 'Lorne Park',
                'project_type' => 'Interlock & Specialty Paving',
                'value' => '$25,000 - $32,000',
                'duration' => '7 days',
                'completed' => '2025-08-22',
                'featured' => false,
                'description' => 'Tiered patio design featuring Techo-Bloc Blu 60mm pavers with a built-in natural gas fire pit, curved seating wall, and privacy screen plantings in a Lorne Park backyard.',
                'body' => "The owners of this established Lorne Park property wanted to transform their underused backyard into a true outdoor living space. The existing patio was a small concrete pad that offered no connection to the surrounding garden and lacked any entertaining capacity. Their vision was a multi-level design that would accommodate dining, lounging, and a fire feature.\n\nWe designed a two-tier patio system stepping down from the rear sliding doors. The upper tier serves as the dining area with direct kitchen access, while the lower tier features a circular natural gas fire pit surrounded by a curved Techo-Bloc Mini-Creta seating wall. The 6-inch elevation change between levels is handled with a natural stone step band that visually separates the two zones. We selected Techo-Bloc Blu 60mm pavers in Champlain Grey for their contemporary texture and slip resistance.\n\nAlong the south and west property lines, we installed a privacy screening of columnar cedars backed by ornamental grasses, creating a sheltered atmosphere without blocking afternoon sunlight. The fire pit runs on a dedicated natural gas line, eliminating the need for propane tanks. Low-voltage LED step lights illuminate the transition between levels for safe evening use.",
            ],

            [
                'title' => 'Ashlar Slate Stamped Concrete Patio in Erin Mills',
                'service' => 'Stamped Concrete',
                'city' => 'Mississauga',
                'neighborhood' => 'Erin Mills',
                'project_type' => 'Concrete Services',
                'value' => '$12,000 - $18,000',
                'duration' => '4 days',
                'completed' => '2025-07-10',
                'featured' => false,
                'description' => 'Large backyard patio finished in ashlar slate stamp pattern with acid-stained borders and integral sandstone colour. Designed for an Erin Mills family home with emphasis on durability and low maintenance.',
                'body' => "This Erin Mills family needed a durable, low-maintenance backyard surface that could handle heavy foot traffic from three active children while still looking elegant for adult entertaining. The previous lawn area behind their home was perpetually muddy due to poor drainage on the heavy Halton Till clay common in this part of Mississauga.\n\nWe poured a 4-inch reinforced concrete slab over a compacted granular base with integrated slope toward the rear property line for drainage. The ashlar slate stamp pattern was selected for its natural stone appearance at a fraction of the cost. We used integral sandstone colour throughout the slab with a walnut release agent that settles into the stamp texture, creating realistic depth and shadow. The border features a contrasting smooth band with acid-stained charcoal edging.\n\nThe finished patio spans 450 square feet, providing ample space for a dining set, play area, and lounge furniture. A professional acrylic sealer protects the surface from UV fading and makes cleanup simple. The air-entrained concrete mix ensures long-term durability through Mississauga's freeze-thaw cycles.",
            ],

            [
                'title' => 'Terraced Retaining Wall System in Meadowvale',
                'service' => 'Retaining Walls',
                'city' => 'Mississauga',
                'neighborhood' => 'Meadowvale',
                'project_type' => 'Structural Hardscape & Repair',
                'value' => '$18,000 - $25,000',
                'duration' => '6 days',
                'completed' => '2025-06-05',
                'featured' => false,
                'description' => 'Three-tier engineered retaining wall system addressing a 6-foot grade change in a Meadowvale backyard. Constructed with Permacon Ridgefield wall units and integrated planting terraces.',
                'body' => "This Meadowvale property had a steep 6-foot grade change from the rear patio down to the lower yard, making nearly half the backyard unusable and creating ongoing erosion issues. The heavy clay soil in this area of Mississauga compounded the drainage problems, with water pooling against the home's foundation after every rain.\n\nWe designed a three-tier retaining wall system using Permacon Ridgefield wall units in Sierra Grey. Each wall stands approximately 2 feet in exposed height, with planted terraces between levels that soften the visual impact and add garden interest. The walls are engineered with geogrid reinforcement, compacted granular backfill, and a perforated drain pipe behind each tier that connects to a discharge point at the lower yard. This drainage system ensures hydrostatic pressure never builds behind the wall face.\n\nThe completed system transformed the steep slope into three usable terrace levels planted with drought-tolerant perennials and ornamental grasses suited to the clay soil conditions. The lower yard is now fully accessible via integrated natural stone steps connecting each terrace level.",
            ],

            [
                'title' => 'Complete Foundation Waterproofing and Grading in Streetsville',
                'service' => 'Foundation Waterproofing & Grading',
                'city' => 'Mississauga',
                'neighborhood' => 'Streetsville',
                'project_type' => 'Structural Hardscape & Repair',
                'value' => '$12,000 - $18,000',
                'duration' => '5 days',
                'completed' => '2025-05-20',
                'featured' => false,
                'description' => 'Full perimeter foundation waterproofing with exterior membrane, weeping tile replacement, and lot re-grading for a heritage-era Streetsville home experiencing basement moisture issues.',
                'body' => "The owners of this 1960s Streetsville home had dealt with persistent basement moisture for years. Previous interior solutions (sealant paint, dehumidifiers) had failed to address the root cause: deteriorated original weeping tile and negative grading that directed surface water toward the foundation. The heavy clay soil in Streetsville retained moisture against the foundation walls, worsening the problem during spring thaw and heavy rain events.\n\nWe excavated around the full perimeter of the home down to the foundation footing, exposing the original concrete block walls. After cleaning and repairing minor cracks, we applied a two-layer waterproofing system: a rubberized asphalt membrane bonded directly to the foundation wall, followed by a dimpled drainage board that channels water downward. We replaced the original clay weeping tile with modern 4-inch perforated PVC pipe bedded in clear stone, connected to a new sump pit.\n\nThe final phase involved re-grading the surrounding soil to achieve the Ontario Building Code minimum 10% slope away from the foundation for the first 6 feet. Window wells were rebuilt with proper drainage. The homeowner reported a completely dry basement through the following spring, even during heavy April rainfall.",
            ],

            [
                'title' => 'Japanese-Inspired Garden Retreat in Clarkson',
                'service' => 'Garden Design & Softscaping',
                'city' => 'Mississauga',
                'neighborhood' => 'Clarkson',
                'project_type' => 'Softscaping & Lifestyle Enhancements',
                'value' => '$15,000 - $22,000',
                'duration' => '7 days',
                'completed' => '2025-10-01',
                'featured' => false,
                'description' => 'Zen-inspired garden design featuring natural stone pathways, a recirculating water feature, ornamental maples, and native shade-tolerant plantings in a mature Clarkson lot.',
                'body' => "This Clarkson homeowner had a large, shaded backyard dominated by mature silver maples that made lawn maintenance nearly impossible. Rather than fighting the conditions, they wanted a low-maintenance garden design that embraced the shade and created a peaceful retreat. Their inspiration was traditional Japanese garden aesthetics adapted for the Ontario climate.\n\nWe designed a naturalistic layout centred on a recirculating stone basin water feature surrounded by moss-covered boulders. Winding pathways of natural flagstone connect the rear deck to a cedar meditation bench positioned beneath the existing canopy. Plant selections focus on shade-tolerant native and adapted species: Canadian wild ginger, ferns, hostas, astilbe, and Japanese forest grass. Three specimen Japanese maples (Acer palmatum varieties hardy to Zone 6a) provide seasonal colour interest from spring through fall.\n\nThe ground plane combines river stone mulch beds with stepping stone paths, eliminating the need for lawn mowing entirely. A drip irrigation system ensures consistent moisture for the plantings during dry periods. The design creates four-season interest with evergreen structure from dwarf conifers and the textural beauty of bare branches in winter.",
            ],

            // ── HAMILTON (4) ────────────────────────────────────────────

            [
                'title' => 'Curved Interlocking Walkway and Front Entry in Dundas',
                'service' => 'Interlocking Walkways & Entryways',
                'city' => 'Hamilton',
                'neighborhood' => 'Dundas',
                'project_type' => 'Interlock & Specialty Paving',
                'value' => '$8,000 - $12,000',
                'duration' => '3 days',
                'completed' => '2025-09-28',
                'featured' => false,
                'description' => 'Sweeping curved interlocking walkway from the municipal sidewalk to the front door, with an enlarged landing pad and decorative address pillar, designed for a Dundas heritage neighbourhood.',
                'body' => "This Dundas homeowner wanted to replace their narrow, cracked concrete walkway with a design that better suited the character of their Edwardian-era home. The existing straight walkway was only 3 feet wide and had heaved significantly due to the clay-heavy soil common in the Dundas valley area.\n\nWe designed a sweeping S-curve walkway using Unilock Thornbury pavers in Bavarian Blend, which complement the warm brick tones of the home. The walkway widens from 4 feet at the sidewalk to 5 feet at the front porch landing, creating a welcoming sense of arrival. The landing pad features a contrasting herringbone centre panel bordered by a soldier course. We excavated to 16 inches to accommodate the Hamilton frost depth and installed a compacted Granular A base with proper cross-slope for drainage.\n\nA decorative stone address pillar with integrated LED house number lighting marks the walkway entrance from the street. The curved design flows naturally around an existing mature hydrangea that the homeowner wanted to preserve, demonstrating how thoughtful hardscaping can work with established plantings rather than replacing them.",
            ],

            [
                'title' => 'Escarpment-Edge Retaining Wall in Ancaster',
                'service' => 'Retaining Walls',
                'city' => 'Hamilton',
                'neighborhood' => 'Ancaster',
                'project_type' => 'Structural Hardscape & Repair',
                'value' => '$22,000 - $30,000',
                'duration' => '7 days',
                'completed' => '2025-06-18',
                'featured' => true,
                'description' => 'Engineered retaining wall on Niagara Escarpment slope in Ancaster, featuring natural stone veneer, integrated LED lighting, and terraced planting beds with native species.',
                'body' => "This Ancaster property sits near the Niagara Escarpment brow, with a dramatic 8-foot grade change across the rear yard that had been slowly eroding for years. The existing timber retaining wall had rotted and begun to lean, creating a safety hazard. The homeowner needed a permanent, engineered solution that could handle the escarpment's unique geological challenges.\n\nWorking with a licensed structural engineer, we designed a two-tier wall system using reinforced concrete block cores with natural limestone veneer that echoes the exposed Queenston shale found throughout the Hamilton escarpment. Each tier incorporates geogrid reinforcement extending 6 feet behind the wall face into compacted granular backfill. The drainage system includes a French drain behind each tier connected to a daylight outlet at the lower yard. Given the escarpment edge location, we coordinated with the Hamilton Conservation Authority to ensure compliance with all environmental regulations.\n\nIntegrated LED strip lighting along the wall cap illuminates the terraced planting beds, which feature native escarpment species including columbine, wild bergamot, and little bluestem grass. The completed wall system provides full structural stability with a design life exceeding 50 years, backed by our 10-year workmanship warranty.",
            ],

            [
                'title' => 'Natural Stone Front Entry Steps in Stoney Creek',
                'service' => 'Steps & Staircases',
                'city' => 'Hamilton',
                'neighborhood' => 'Stoney Creek',
                'project_type' => 'Structural Hardscape & Repair',
                'value' => '$8,000 - $14,000',
                'duration' => '4 days',
                'completed' => '2025-08-12',
                'featured' => false,
                'description' => 'Custom flagstone-capped concrete steps with wrought iron railings and integrated LED risers for a Stoney Creek split-level home. Ontario Building Code compliant design with proper rise and run ratios.',
                'body' => "This Stoney Creek split-level home had original precast concrete steps that had heaved and cracked after 25 years of Hamilton's freeze-thaw cycles. The steps had become a safety concern with uneven treads and no railings. The homeowner wanted a rebuilt entry that improved both safety and curb appeal while meeting current Ontario Building Code requirements.\n\nWe demolished the existing steps and poured new reinforced concrete stair structures with proper frost footings extending 48 inches below grade. Each tread was capped with natural Eramosa limestone, a locally quarried stone that provides excellent grip and durability. The riser height and tread depth were designed to meet OBC requirements (maximum 200mm rise, minimum 250mm run) for comfortable, safe use. Custom wrought iron railings with a powder-coated matte black finish were fabricated and installed on both sides.\n\nLED strip lighting recessed beneath each tread nose provides subtle illumination for safe nighttime use. The surrounding landing was finished in matching limestone with a brushed non-slip surface. The completed entry steps transformed the home's street presence and eliminated the safety hazard that concerned the homeowner.",
            ],

            [
                'title' => 'Complete Outdoor Kitchen and Dining Space in Westdale',
                'service' => 'Outdoor Kitchens & Fire Features',
                'city' => 'Hamilton',
                'neighborhood' => 'Westdale',
                'project_type' => 'Softscaping & Lifestyle Enhancements',
                'value' => '$28,000 - $40,000',
                'duration' => '10 days',
                'completed' => '2025-07-25',
                'featured' => false,
                'description' => 'Custom outdoor kitchen with built-in Napoleon gas grill, granite countertops, bar seating, and a cedar pergola over a dedicated dining area in a Westdale backyard.',
                'body' => "This Westdale family are avid entertainers who wanted to extend their living space outdoors. Their mature, tree-lined backyard had the space for a full outdoor kitchen but lacked any infrastructure. They envisioned a complete cooking, dining, and socializing area that would serve as the hub for summer gatherings.\n\nWe constructed an L-shaped outdoor kitchen island using concrete block with natural stone veneer matching the home's foundation. The island houses a built-in Napoleon Prestige Pro 665 gas grill, a stainless steel refrigerator, storage drawers, and a prep sink with running water. The countertop is Jet Mist granite, chosen for its durability and resistance to Hamilton's weather extremes. A TSSA-licensed gas contractor ran a dedicated natural gas line from the home's meter. The bar-height seating side accommodates four guests with a granite overhang.\n\nA 12-by-14-foot cedar pergola with stainless steel cables and climbing wisteria covers the adjacent dining area, which sits on a Techo-Bloc Blu patio surface. String lights woven through the pergola structure provide ambient evening lighting. The complete installation allows the family to cook, dine, and entertain outdoors from May through October.",
            ],

            // ── BURLINGTON (4) ──────────────────────────────────────────

            [
                'title' => 'Lakefront Patio with Panoramic Seating Wall in Aldershot',
                'service' => 'Interlocking Patios & Backyard Living',
                'city' => 'Burlington',
                'neighborhood' => 'Aldershot',
                'project_type' => 'Interlock & Specialty Paving',
                'value' => '$18,000 - $25,000',
                'duration' => '6 days',
                'completed' => '2025-08-05',
                'featured' => false,
                'description' => 'Expansive backyard patio with large-format pavers, a curved seating wall overlooking Lake Ontario, and wind-resistant design details for an Aldershot lakefront property.',
                'body' => "Located just blocks from Burlington's Lake Ontario shoreline, this Aldershot property is exposed to persistent lake-effect winds that made traditional outdoor furniture impractical. The homeowner wanted a built-in patio solution that could withstand the elements while maximizing their partial lake view.\n\nWe designed a 500-square-foot patio using Techo-Bloc Industria large-format slabs in Smooth Greyed Nickel, providing a contemporary, clean-line aesthetic. The highlight is a curved freestanding seating wall positioned at the southwest corner, oriented to capture sunset views toward the lake. The wall is constructed from Permacon wall units with a natural stone cap wide enough to serve as both seating and a surface for drinks. The patio grade incorporates a subtle 2% slope away from the home for drainage.\n\nBecause of the lake-effect wind exposure, we used enhanced polymeric sand rated for high-wind environments and installed the pavers on a deeper-than-standard base to prevent any lateral movement. The sandy loam soil typical of Burlington's lakefront area actually provided better drainage conditions than the clay soils found further inland, reducing the risk of frost heave.",
            ],

            [
                'title' => 'Premium Pool Deck and Cabana Surround in Millcroft',
                'service' => 'Interlocking Pool Decks',
                'city' => 'Burlington',
                'neighborhood' => 'Millcroft',
                'project_type' => 'Interlock & Specialty Paving',
                'value' => '$22,000 - $35,000',
                'duration' => '7 days',
                'completed' => '2025-07-14',
                'featured' => true,
                'description' => 'Non-slip textured pool deck installation with bullnose coping stones, integrated drainage channels, and a cabana foundation pad for a Millcroft executive home.',
                'body' => "This Millcroft homeowner had recently installed an in-ground pool but was dissatisfied with the basic concrete deck poured by the pool contractor. The surface was slippery when wet, lacked visual appeal, and had already begun cracking at the expansion joints after one winter. They wanted a premium pool deck that matched the quality of their home.\n\nWe removed the existing concrete and installed Unilock Beacon Hill Smooth pavers with a textured surface rated for pool deck applications. The non-slip finish provides safe footing even when wet, meeting or exceeding the coefficient of friction standards recommended for pool surrounds. Custom bullnose coping stones in a contrasting charcoal tone define the pool edge with a finished, elegant look. We integrated slot drainage channels at the pool perimeter to capture splash-out water and direct it to the storm system, preventing standing water on the deck surface.\n\nThe project included preparing a reinforced foundation pad for a future cabana structure at the far end of the pool area. The total deck area of 650 square feet provides generous lounging space around all four sides of the pool. The installation is backed by our 10-year workmanship warranty and Unilock's lifetime product warranty.",
            ],

            [
                'title' => 'Exposed Aggregate Driveway and Walkway in Tyandaga',
                'service' => 'Exposed Aggregate Concrete',
                'city' => 'Burlington',
                'neighborhood' => 'Tyandaga',
                'project_type' => 'Concrete Services',
                'value' => '$10,000 - $16,000',
                'duration' => '4 days',
                'completed' => '2025-09-08',
                'featured' => false,
                'description' => 'Exposed aggregate concrete driveway and coordinating front walkway in a Tyandaga established neighbourhood, featuring earth-tone stone aggregate with stamped borders.',
                'body' => "The homeowner in this established Tyandaga neighbourhood wanted to replace their aging asphalt driveway with something more visually interesting than standard concrete but more cost-effective than interlocking pavers. Exposed aggregate concrete offered the perfect middle ground, providing natural stone beauty with the structural simplicity of a monolithic concrete slab.\n\nWe poured a 5-inch reinforced concrete driveway with an exposed aggregate finish revealing natural earth-tone river stone. The aggregate blend was selected to complement the home's brick and stone facade. Smooth-finished borders 8 inches wide frame the driveway on both sides, providing a clean visual transition to the adjacent lawn. Control joints were saw-cut in a diamond pattern to manage natural cracking while adding a geometric design element. A coordinating exposed aggregate walkway connects the driveway to the front entry.\n\nThe concrete mix includes air entrainment for freeze-thaw durability, essential for Burlington's Zone 6a climate. The exposed aggregate surface provides excellent traction in wet and winter conditions. After 28 days of curing, we applied a penetrating sealer that enhances the stone colour and protects against staining and de-icer damage.",
            ],

            [
                'title' => 'Complete LED Landscape Lighting Package in Roseland',
                'service' => 'Landscape Lighting',
                'city' => 'Burlington',
                'neighborhood' => 'Roseland',
                'project_type' => 'Softscaping & Lifestyle Enhancements',
                'value' => '$8,000 - $14,000',
                'duration' => '3 days',
                'completed' => '2025-10-15',
                'featured' => false,
                'description' => 'Comprehensive low-voltage LED landscape lighting system including path lights, architectural uplighting, tree canopy illumination, and facade wash lighting for a Roseland property.',
                'body' => "This Roseland homeowner had invested significantly in their landscape over the years but felt it disappeared after dark. They wanted a professional lighting system that would extend the enjoyment of their outdoor spaces into the evening hours while improving security and curb appeal.\n\nWe designed a comprehensive low-voltage LED system with five distinct lighting zones, each on independent dimmer control. The front yard features path lights along the walkway (warm white, 2700K), uplights on the facade highlighting architectural details, and tree canopy wash lights illuminating two mature oaks. The backyard includes patio step lights, accent spots on garden focal points, and perimeter security lighting along the fence line. All fixtures are solid brass construction rated for Canadian weather extremes.\n\nThe system runs on a multi-tap transformer with a smart timer that automatically adjusts on/off times with seasonal daylight changes. Total power consumption is under 300 watts for the entire property, making it extremely energy-efficient. The LED fixtures carry a 15-year manufacturer warranty, and the entire installation is covered by our workmanship guarantee. The homeowner noted that the lighting dramatically transformed their property's evening presence and neighbours have since requested similar installations.",
            ],

            // ── OAKVILLE (4) ────────────────────────────────────────────

            [
                'title' => 'Grand Circular Driveway with Pillar Entry in Glen Abbey',
                'service' => 'Interlocking Driveways',
                'city' => 'Oakville',
                'neighborhood' => 'Glen Abbey',
                'project_type' => 'Interlock & Specialty Paving',
                'value' => '$28,000 - $38,000',
                'duration' => '6 days',
                'completed' => '2025-05-30',
                'featured' => true,
                'description' => 'Grand circular driveway installation with decorative stone entry pillars, Techo-Bloc Blu pavers, and integrated coach lighting for a Glen Abbey executive home.',
                'body' => "This Glen Abbey executive home sits on a generous lot with a long frontage, making it ideal for a circular driveway that the homeowner had always envisioned. The existing double-wide straight driveway was functional but lacked the presence befitting the home's scale. The goal was a grand arrival experience that would set the property apart.\n\nWe designed an elliptical driveway with a central landscaped island featuring a specimen ornamental tree and low boxwood hedging. Techo-Bloc Blu 60mm pavers in Shale Grey were installed in a running bond pattern with a double soldier course border in Onyx Black. Two custom stone pillars flanking the entry point are topped with coach-style LED lanterns that activate at dusk. The driveway accommodates comfortable two-way traffic flow with dedicated guest parking areas.\n\nThe excavation revealed the heavy clay soil typical of inland Oakville, requiring an enhanced 18-inch granular base with geotextile fabric separation. The total paved area exceeds 1,200 square feet, making it one of the larger residential driveway projects we have completed in the Glen Abbey area. The installation maintains full compliance with Oakville's lot coverage bylaws.",
            ],

            [
                'title' => 'Heritage-Style Flagstone Walkway in Old Oakville',
                'service' => 'Interlocking Walkways & Entryways',
                'city' => 'Oakville',
                'neighborhood' => 'Old Oakville',
                'project_type' => 'Interlock & Specialty Paving',
                'value' => '$6,000 - $10,000',
                'duration' => '3 days',
                'completed' => '2025-06-22',
                'featured' => false,
                'description' => 'Natural flagstone walkway designed to complement the Old Oakville Heritage Conservation District guidelines, with mortared joints and garden-edge planting beds.',
                'body' => "Properties within Old Oakville's Heritage Conservation District must respect design guidelines that preserve the neighbourhood's historic character. This homeowner needed a new front walkway that would satisfy the heritage committee while providing a safe, level walking surface to replace their crumbling original concrete path.\n\nWe selected natural Muskoka flagstone in warm grey and brown tones that complement the home's early-1900s brick facade. The walkway was installed on a compacted granular base with mortared joints using a colour-matched morite, giving a traditional appearance while preventing weed growth and maintaining structural integrity. The walkway edges transition into low garden planting beds with English lavender, catmint, and creeping thyme that soften the stone borders.\n\nThe Heritage Oakville Advisory Committee approved the design prior to construction. The finished walkway is 4 feet wide with a gentle curve that follows the original path alignment, respecting the mature sugar maple whose root zone we carefully protected during excavation.",
            ],

            [
                'title' => 'Multi-Tier Garden Retaining Wall in River Oaks',
                'service' => 'Retaining Walls',
                'city' => 'Oakville',
                'neighborhood' => 'River Oaks',
                'project_type' => 'Structural Hardscape & Repair',
                'value' => '$15,000 - $22,000',
                'duration' => '5 days',
                'completed' => '2025-07-30',
                'featured' => false,
                'description' => 'Three-level terraced retaining wall with integrated planting beds and LED cap lighting, addressing a rear grade change in a River Oaks property.',
                'body' => "This River Oaks property had a 5-foot grade transition from the patio level to the lower lawn that was handled by an unstable grass slope prone to erosion during heavy rain. The homeowner wanted usable terrace levels for gardening and a permanent structural solution.\n\nWe constructed three retaining wall tiers using Unilock Pisa2 wall units in Sierra, each approximately 20 inches in exposed height. The walls step back from each other, creating planting terraces 3 feet deep between levels. Each tier is engineered with geogrid reinforcement and granular backfill, with a continuous perforated drain pipe system behind all three walls. The wall caps are finished with contrasting natural stone that doubles as comfortable seating edges.\n\nIntegrated LED lighting beneath the top cap of each wall tier provides subtle accent illumination in the evening, highlighting the plant textures in each terrace. The planting design features a mix of ornamental grasses, flowering perennials, and evergreen shrubs that provide year-round structure. The completed project added usable outdoor space and eliminated the erosion problems that had been undermining the lower lawn.",
            ],

            [
                'title' => 'Full Front and Rear Lawn Renovation in Bronte',
                'service' => 'Sod Installation & Lawn Care',
                'city' => 'Oakville',
                'neighborhood' => 'Bronte',
                'project_type' => 'Softscaping & Lifestyle Enhancements',
                'value' => '$5,000 - $9,000',
                'duration' => '2 days',
                'completed' => '2025-05-15',
                'featured' => false,
                'description' => 'Complete lawn renovation including topsoil grading, premium Kentucky bluegrass sod installation, and starter fertilization for a Bronte family home.',
                'body' => "This Bronte homeowner had struggled with a patchy, weed-infested lawn for years. Previous overseeding attempts produced inconsistent results, and the underlying issue was poor topsoil quality and uneven grading that left standing water in several areas. They decided a full renovation was the most cost-effective long-term solution.\n\nWe stripped the existing lawn and removed the old sod and weed mat. The exposed subsoil was re-graded to establish proper drainage slopes away from the foundation and eliminate the low spots where water had been pooling. We spread 3 inches of screened topsoil and triple mix across the entire front and rear lawn area, totalling approximately 3,500 square feet. The topsoil was lightly compacted and raked to a smooth, even finish.\n\nPremium Kentucky bluegrass sod was installed the same day it was harvested from the sod farm to ensure maximum viability. The sod was rolled and watered immediately after placement. We provided the homeowner with a detailed 30-day watering schedule and applied a starter fertilizer to promote rapid root establishment. The lawn was fully rooted and mowable within three weeks of installation.",
            ],

            // ── MILTON (4) ──────────────────────────────────────────────

            [
                'title' => 'Concrete Garage Pad and Side Walkway in Timberlea',
                'service' => 'Plain & Broom-Finish Concrete',
                'city' => 'Milton',
                'neighborhood' => 'Timberlea',
                'project_type' => 'Concrete Services',
                'value' => '$6,000 - $10,000',
                'duration' => '3 days',
                'completed' => '2025-08-18',
                'featured' => false,
                'description' => 'Broom-finish concrete garage extension pad with side yard walkway and proper drainage grading for a Timberlea newer-build home.',
                'body' => "This Timberlea homeowner needed a concrete garage extension pad for workshop and storage use, along with a proper side-yard walkway to replace the muddy path they had been using to access the backyard. The builder-grade grading had left the side yard with standing water issues on the heavy clay fill common in Milton's newer subdivisions.\n\nWe poured a 10-by-12-foot reinforced concrete pad adjacent to the existing garage slab, with matching elevation and a broom-finish surface for traction. Expansion joints connect the new pad to the existing slab, allowing independent movement during freeze-thaw cycles. The side-yard walkway runs 40 feet from the garage to the backyard gate, poured at 3 feet wide with a continuous slope toward the rear property line.\n\nThe entire project included re-grading the side yard to eliminate the standing water problem. We installed a French drain along the foundation line to capture any water that pools between the home and the fence. The air-entrained concrete mix is rated for Milton's Zone 5b to 6a conditions with approximately 130 cm of annual snowfall.",
            ],

            [
                'title' => 'Precast Concrete Front Steps with Landing in Old Milton',
                'service' => 'Steps & Staircases',
                'city' => 'Milton',
                'neighborhood' => 'Old Milton',
                'project_type' => 'Structural Hardscape & Repair',
                'value' => '$7,000 - $12,000',
                'duration' => '4 days',
                'completed' => '2025-09-05',
                'featured' => false,
                'description' => 'Custom precast front steps with natural stone veneer cladding, interlocking paver landing pad, and LED address lighting for an Old Milton character home.',
                'body' => "This Old Milton character home had original concrete steps that had settled unevenly and cracked after decades of exposure to the escarpment-edge climate. The homeowner wanted steps that respected the home's traditional architecture while incorporating modern safety and lighting features.\n\nWe poured new reinforced concrete step structures on frost footings extending 48 inches below grade to prevent future heaving. The steps were clad in natural stone veneer in a Charcoal Blend that complements the home's fieldstone foundation. Each tread is finished with a natural stone cap providing a non-slip walking surface. The landing pad at the top was expanded to 5-by-6 feet and finished with Unilock pavers in a matching colour palette.\n\nIntegrated LED riser lights provide safe nighttime illumination, and a backlit address plaque was mounted to the adjacent column. Wrought iron railings with a satin black finish were custom fabricated to match the home's period details. The completed entry exceeds current Ontario Building Code requirements for residential stairs and transforms the home's street presence.",
            ],

            [
                'title' => 'New Build Foundation Waterproofing in Harrison',
                'service' => 'Foundation Waterproofing & Grading',
                'city' => 'Milton',
                'neighborhood' => 'Harrison',
                'project_type' => 'Structural Hardscape & Repair',
                'value' => '$10,000 - $16,000',
                'duration' => '5 days',
                'completed' => '2025-06-28',
                'featured' => false,
                'description' => 'Post-construction foundation waterproofing with drainage tile, exterior membrane, and lot grading correction for a Harrison new-build home experiencing early moisture issues.',
                'body' => "This Harrison new-build owner discovered basement moisture within the first year of occupancy, a problem more common than many homeowners expect in Milton's newer subdivisions. The builder's original waterproofing was a single coat of dampproofing (not true waterproofing), and the lot grading had settled, creating negative drainage toward the foundation.\n\nWe excavated around the full perimeter to the foundation footing, carefully working around new landscaping and utilities. The existing dampproofing was supplemented with a self-adhering rubberized asphalt membrane providing a true waterproofing barrier. A drainage board was installed over the membrane to protect it from backfill damage and provide a drainage channel. New 4-inch perforated PVC weeping tile was installed in clear stone at the footing level, connected to the existing sump system.\n\nThe lot was re-graded with imported topsoil to re-establish proper drainage slopes. Given Milton's heavy clay subsoil, we ensured a minimum 5% slope for the first 8 feet from the foundation. Window wells were rebuilt with clear stone backfill and new covers. The homeowner has experienced zero moisture issues since the remediation, even through the following spring thaw.",
            ],

            [
                'title' => 'Builder-Grade to Premium Lawn Upgrade in Bristol',
                'service' => 'Sod Installation & Lawn Care',
                'city' => 'Milton',
                'neighborhood' => 'Bristol',
                'project_type' => 'Softscaping & Lifestyle Enhancements',
                'value' => '$4,000 - $7,000',
                'duration' => '2 days',
                'completed' => '2025-05-25',
                'featured' => false,
                'description' => 'Complete lawn upgrade from builder-grade sod to premium blend, including soil amendment, grading correction, and drainage improvement for a Bristol new subdivision home.',
                'body' => "Like many homes in Milton's Bristol subdivision, this property received a basic builder lawn that quickly deteriorated. The thin topsoil layer over compacted clay fill left the lawn struggling with poor drainage, bare patches, and persistent weed invasion within the first growing season.\n\nWe stripped the failing lawn and rototilled the top 4 inches, incorporating 2 inches of compost and peat moss to improve the soil structure. The grade was corrected to eliminate three low spots where water had been pooling after rain. We added 2 inches of screened topsoil and triple mix over the amended base, bringing the total growing medium to a healthy 6-inch depth. This is significantly more than the 2-inch builder standard that had contributed to the original lawn failure.\n\nPremium sod blended for the Milton climate (Kentucky bluegrass with perennial ryegrass for quick establishment) was installed across the front and rear yards. The homeowner received a customized care calendar with seasonal fertilization, aeration, and overseeding recommendations specific to the clay-heavy Bristol soil conditions.",
            ],

            // ── TORONTO (4) ─────────────────────────────────────────────

            [
                'title' => 'Executive Backyard Patio and Outdoor Living in The Kingsway',
                'service' => 'Interlocking Patios & Backyard Living',
                'city' => 'Toronto',
                'neighborhood' => 'The Kingsway',
                'project_type' => 'Interlock & Specialty Paving',
                'value' => '$30,000 - $42,000',
                'duration' => '8 days',
                'completed' => '2025-08-30',
                'featured' => true,
                'description' => 'Large-format slab patio with an outdoor natural gas fireplace, built-in stone seating, and privacy planting for an executive Kingsway estate backyard.',
                'body' => "This Kingsway estate home had a spacious backyard shaded by mature trees, but the outdoor space was entirely lawn with no defined entertaining area. The homeowners, who frequently host dinner parties, wanted a sophisticated outdoor living room that would complement their home's Tudor Revival architecture.\n\nWe designed a 700-square-foot patio using Techo-Bloc Industria slabs in Greyed Nickel, providing a contemporary contrast to the home's traditional stonework. The centrepiece is a custom-built natural gas fireplace with a Brampton Brick veneer surround that echoes the home's facade. Flanking the fireplace, two L-shaped stone seating walls with natural limestone caps create an intimate conversation area. The narrow side-yard access, typical of established Toronto neighbourhoods, required us to transport all materials by compact equipment and hand-carry pavers to the backyard.\n\nPrivacy was achieved through strategic plantings of columnar hornbeam along the east property line and a cedar screen on the west. Low-voltage accent lighting highlights the fireplace, the tree canopy, and the walkway transitions. The patio includes a dedicated zone for a dining table and a separate lounge area, each defined by the paving pattern direction.",
            ],

            [
                'title' => 'Stamped Concrete Pool Surround in Lawrence Park',
                'service' => 'Stamped Concrete',
                'city' => 'Toronto',
                'neighborhood' => 'Lawrence Park',
                'project_type' => 'Concrete Services',
                'value' => '$14,000 - $20,000',
                'duration' => '4 days',
                'completed' => '2025-07-05',
                'featured' => false,
                'description' => 'York stone pattern stamped concrete pool surround with non-slip finish and integral colour for a Lawrence Park estate home. Includes custom coping detail and expansion joint design.',
                'body' => "This Lawrence Park homeowner had a beautiful in-ground pool surrounded by aging poured concrete that had cracked and become slippery with algae growth. They wanted a premium finish that would provide safe footing, withstand pool chemical exposure, and complement the home's Georgian-style architecture.\n\nWe removed the existing pool deck and poured a new 5-inch reinforced concrete slab with a York stone stamp pattern, one of the most popular designs for its natural limestone appearance. The integral colour is Sandstone with a Charcoal release agent that settles into the stamp impressions, creating realistic depth. A non-slip texture additive was broadcast onto the surface before sealing, providing safe wet-footing traction exceeding industry standards for pool surrounds.\n\nCustom bullnose coping was formed along the pool edge for a finished, safe grip point. Expansion joints were planned to follow the natural pattern lines of the stamp design, making them nearly invisible. The air-entrained concrete mix is rated for Toronto's freeze-thaw cycles, and the acrylic sealer provides UV stability and chemical resistance against chlorinated splash water.",
            ],

            [
                'title' => 'Victorian Home Foundation Repair and Waterproofing in Etobicoke',
                'service' => 'Foundation Waterproofing & Grading',
                'city' => 'Toronto',
                'neighborhood' => 'Etobicoke',
                'project_type' => 'Structural Hardscape & Repair',
                'value' => '$14,000 - $22,000',
                'duration' => '6 days',
                'completed' => '2025-04-28',
                'featured' => false,
                'description' => 'Comprehensive foundation waterproofing for a 1920s Etobicoke home, including rubberized membrane application, weeping tile replacement, window well reconstruction, and perimeter grading correction.',
                'body' => "This 1920s Etobicoke home had a stone-and-mortar foundation that had been leaking progressively worse over the past decade. The original clay weeping tile had collapsed, and years of landscaping changes had created negative grading that directed rainwater and snowmelt directly toward the foundation walls. The basement showed active water infiltration along the north and west walls.\n\nThe century-old foundation required careful excavation with hand digging near the corners to avoid undermining the footing. After exposing the stone walls, we repointed deteriorated mortar joints with hydraulic cement, then applied a parge coat to create a smooth surface for membrane adhesion. A self-adhering rubberized asphalt membrane was applied over the parge coat, followed by a drainage board for protection. New 4-inch perforated PVC weeping tile replaced the failed clay system, bedded in clear stone and connected to a new interior sump pump.\n\nWindow wells were rebuilt with galvanized steel forms and clear stone drainage fill. The perimeter was re-graded with imported topsoil to achieve positive drainage slopes meeting Ontario Building Code requirements. The home now stays dry through spring thaw and heavy summer storms.",
            ],

            [
                'title' => 'Luxury Outdoor Kitchen with Pizza Oven in Forest Hill',
                'service' => 'Outdoor Kitchens & Fire Features',
                'city' => 'Toronto',
                'neighborhood' => 'Forest Hill',
                'project_type' => 'Softscaping & Lifestyle Enhancements',
                'value' => '$35,000 - $50,000',
                'duration' => '12 days',
                'completed' => '2025-06-15',
                'featured' => true,
                'description' => 'Custom natural stone outdoor kitchen with a wood-fired pizza oven, professional-grade gas grill, granite countertops, and integrated refrigeration for a Forest Hill estate.',
                'body' => "This Forest Hill homeowner is a culinary enthusiast who wanted a fully equipped outdoor kitchen that could rival an indoor setup. Their expansive backyard, screened by mature evergreens, provided the perfect setting for an ambitious outdoor cooking and entertaining space. The design needed to complement the home's classic stone and brick architecture.\n\nWe constructed a U-shaped kitchen island using concrete block cores with natural Indiana limestone veneer. The island houses a 42-inch Napoleon gas grill, a custom-built wood-fired pizza oven with a copper-clad chimney, an under-counter refrigerator, a two-bowl stainless steel sink with running water, and extensive storage. The countertops are 3cm Absolute Black granite with a leathered finish for durability and heat resistance. All gas and water connections were completed by licensed subtrades per Toronto building and TSSA requirements.\n\nThe surrounding patio was finished in natural flagstone to maintain the estate's classic character. A dedicated pizza prep counter with flour storage sits adjacent to the oven. String lights suspended from stainless cable between the home and a custom steel post create an intimate canopy over the dining area. The complete installation allows year-round outdoor cooking, with the pizza oven functioning as a heat source during cooler months.",
            ],

            // ── VAUGHAN (4) ─────────────────────────────────────────────

            [
                'title' => 'European-Style Double Driveway in Woodbridge',
                'service' => 'Interlocking Driveways',
                'city' => 'Vaughan',
                'neighborhood' => 'Woodbridge',
                'project_type' => 'Interlock & Specialty Paving',
                'value' => '$32,000 - $42,000',
                'duration' => '6 days',
                'completed' => '2025-09-20',
                'featured' => true,
                'description' => 'Wide double interlocking driveway with tumbled pavers, decorative borders, and stone address pillars for a Woodbridge executive home. European-inspired design with contrasting accent bands.',
                'body' => "This Woodbridge executive home required a driveway that matched the grand European-inspired architecture common in the area. The homeowner wanted a wide double driveway with premium pavers, decorative borders, and entry pillars that would make a statement. The existing asphalt surface had been in place since the home was built 15 years earlier and had deteriorated from the heavy Newmarket Till clay soil that dominates Vaughan.\n\nWe excavated to 20 inches below grade due to the dense clay conditions and installed a robust granular base with geotextile fabric separation. The main driveway field uses Unilock Thornbury tumbled pavers in Sierra, providing the old-world European texture the homeowner desired. Three contrasting accent bands in Onyx Black Brussels Block cross the driveway at intervals, creating visual rhythm along the 60-foot length. Double soldier course borders in matching Onyx frame the entire perimeter.\n\nTwo custom stone pillars at the street entry are constructed from matching paver block with natural stone caps and integrated LED coach lanterns. The pillars include a low wall extension that defines the property boundary and prevents vehicles from cutting the lawn corner. The total paved area exceeds 1,400 square feet, making this one of the most substantial residential driveway projects in the Woodbridge area.",
            ],

            [
                'title' => 'Resort-Style Pool Deck and Spa Surround in Kleinburg',
                'service' => 'Interlocking Pool Decks',
                'city' => 'Vaughan',
                'neighborhood' => 'Kleinburg',
                'project_type' => 'Interlock & Specialty Paving',
                'value' => '$28,000 - $40,000',
                'duration' => '8 days',
                'completed' => '2025-07-20',
                'featured' => false,
                'description' => 'Expansive pool deck with integrated spa surround, natural stone coping, an outdoor shower station, and a cabana entertainment area for a Kleinburg rural estate.',
                'body' => "This Kleinburg estate sits on over an acre of land with a custom pool and attached spa. The original pool deck was aging poured concrete that had cracked and no longer complemented the property's premium character. The homeowner wanted a resort-quality pool environment that leveraged the estate's generous space.\n\nWe installed over 1,000 square feet of Techo-Bloc Blu 60mm pavers in Shale Grey around the pool and spa, with natural Eramosa limestone coping providing a warm contrast at the water's edge. The coping is bullnosed for a safe, comfortable grip point. A dedicated outdoor shower station with a cedar privacy screen sits at the pool entry point, built on its own drainage pad. The transition from the deck to the surrounding lawn is handled by a natural stone border band.\n\nThe cabana area at the far end of the pool includes a built-in bar counter with granite top, under-counter storage, and a prep sink. The deck incorporates slot drainage at the spa overflow edge and pool perimeter to manage splash water. Integrated LED lights along the deck coping and within the seating wall provide safe, ambient evening lighting. The completed installation transforms the backyard into a private resort environment befitting the Kleinburg estate setting.",
            ],

            [
                'title' => 'Four-Season Garden Design with Pergola in Maple',
                'service' => 'Garden Design & Softscaping',
                'city' => 'Vaughan',
                'neighborhood' => 'Maple',
                'project_type' => 'Softscaping & Lifestyle Enhancements',
                'value' => '$18,000 - $28,000',
                'duration' => '8 days',
                'completed' => '2025-10-05',
                'featured' => false,
                'description' => 'Comprehensive four-season garden design featuring perennial borders, ornamental grasses, a cedar pergola, and a recirculating water feature for a Maple suburban home.',
                'body' => "This Maple homeowner wanted to transform their builder-grade backyard into a garden retreat that would provide visual interest throughout all four Ontario seasons. The existing yard was a simple lawn with basic builder plantings along the fence line, offering no privacy, structure, or seasonal variety.\n\nWe designed the garden around a central cedar pergola positioned off the rear patio, creating a shaded sitting area draped with climbing hydrangea. The surrounding garden beds are layered in three depth zones: tall background plantings of miscanthus grass and native serviceberry along the fence, mid-height perennials including echinacea, black-eyed Susan, and Russian sage in the middle zone, and low groundcover plants including creeping thyme and sedum at the bed edges. Each zone was selected to provide colour and texture in different seasons.\n\nA recirculating boulder water feature near the pergola provides calming sound and attracts birds throughout the growing season. Winter interest comes from the ornamental grass seed heads, red-twig dogwood stems, and the structural branching of three specimen Japanese maples chosen for Zone 5b hardiness. A drip irrigation system with smart controller manages watering across all beds, adjusted for Vaughan's clay soil moisture retention.",
            ],

            [
                'title' => 'Modern Outdoor Kitchen and Lounge in Thornhill',
                'service' => 'Outdoor Kitchens & Fire Features',
                'city' => 'Vaughan',
                'neighborhood' => 'Thornhill',
                'project_type' => 'Softscaping & Lifestyle Enhancements',
                'value' => '$25,000 - $38,000',
                'duration' => '9 days',
                'completed' => '2025-08-08',
                'featured' => false,
                'description' => 'Minimalist outdoor kitchen with a linear gas fire table, built-in grill and bar counter, and LED accent lighting for a modern Thornhill backyard.',
                'body' => "This Thornhill homeowner's contemporary home called for an outdoor kitchen design with clean modern lines, minimal ornamentation, and a monochromatic colour palette. They wanted a functional cooking and entertaining space that felt like a natural extension of their interior living room.\n\nWe designed a straight-line kitchen island finished in smooth-faced charcoal porcelain veneer panels that match the home's exterior cladding. The island houses a Napoleon Built-In gas grill, a beverage cooler, and storage drawers beneath a Caesarstone quartz countertop in Piatra Grey. The bar-height end accommodates three stools for casual dining. A separate 6-foot linear fire table in matching veneer sits 8 feet from the kitchen island, creating a lounge focal point.\n\nThe surrounding patio is finished in large-format Techo-Bloc Industria slabs in Greyed Nickel, continuing the minimalist aesthetic. Recessed LED strip lighting along the base of both the kitchen island and fire table creates a floating effect in the evening. The natural gas connections for both the grill and fire table were installed by a TSSA-licensed contractor. The overall design achieves the contemporary, uncluttered look the homeowner envisioned while providing full outdoor cooking functionality.",
            ],

            // ── RICHMOND HILL (4) ───────────────────────────────────────

            [
                'title' => 'Exposed Aggregate Patio and Walkway in Bayview Hill',
                'service' => 'Exposed Aggregate Concrete',
                'city' => 'Richmond Hill',
                'neighborhood' => 'Bayview Hill',
                'project_type' => 'Concrete Services',
                'value' => '$12,000 - $18,000',
                'duration' => '4 days',
                'completed' => '2025-08-15',
                'featured' => false,
                'description' => 'Decorative exposed aggregate concrete patio with stamped border bands and integrated steps, connecting the rear deck to a lower garden level in Bayview Hill.',
                'body' => "This Bayview Hill property had a 3-foot grade change between the rear deck and the lower lawn that was handled by a deteriorating timber staircase and a small concrete pad. The homeowner wanted a unified surface that would create a generous entertaining area while gracefully transitioning between the two levels.\n\nWe poured a 400-square-foot exposed aggregate patio at the upper deck level, connected by two formed concrete steps to a 200-square-foot lower patio. The aggregate blend features warm-tone natural stones that complement the home's brick exterior. Smooth-finished border bands 6 inches wide frame each patio section and the step treads, providing visual definition and a contrasting texture. Control joints follow a geometric grid that integrates with the overall design.\n\nThe heavy clay soil in the Bayview Hill area required a 6-inch compacted granular base beneath the concrete slabs. The step risers are faced with the same exposed aggregate finish for visual continuity. A penetrating sealer applied after 28 days of curing enhances the stone colours and provides freeze-thaw protection through Richmond Hill's Zone 5b winters.",
            ],

            [
                'title' => 'Side Yard Utility Concrete and Drainage Solution in Jefferson',
                'service' => 'Plain & Broom-Finish Concrete',
                'city' => 'Richmond Hill',
                'neighborhood' => 'Jefferson',
                'project_type' => 'Concrete Services',
                'value' => '$5,000 - $8,000',
                'duration' => '2 days',
                'completed' => '2025-09-12',
                'featured' => false,
                'description' => 'Side yard utility concrete pad and walkway with integrated channel drain and proper slope grading to resolve persistent water pooling in a Jefferson home.',
                'body' => "This Jefferson homeowner had a side yard that became impassable after every rain. The narrow space between their home and the fence had become a muddy corridor that tracked into the garage and made accessing the backyard unpleasant. Standing water against the foundation was also causing concern about potential basement moisture.\n\nWe installed a 3-foot-wide broom-finish concrete walkway running the full 35-foot length of the side yard, poured on a compacted granular base with proper slope away from the foundation. At the midpoint, where water had been pooling the worst, we installed a linear channel drain with a grated top that captures surface water and directs it to the rear yard via underground pipe. The walkway includes a widened pad area at the gate location for comfortable access.\n\nThe broom finish provides excellent non-slip traction in wet and winter conditions. The concrete mix includes air entrainment for freeze-thaw durability appropriate to Richmond Hill's climate. The completed project eliminated the standing water problem, protected the foundation, and gave the homeowner a clean, dry path from the driveway to the backyard year-round.",
            ],

            [
                'title' => 'English Cottage Garden Design in South Richvale',
                'service' => 'Garden Design & Softscaping',
                'city' => 'Richmond Hill',
                'neighborhood' => 'South Richvale',
                'project_type' => 'Softscaping & Lifestyle Enhancements',
                'value' => '$14,000 - $22,000',
                'duration' => '7 days',
                'completed' => '2025-06-10',
                'featured' => false,
                'description' => 'Formal English cottage garden design with boxwood hedging, natural stone edging, perennial borders, and a recirculating birdbath fountain for a South Richvale character home.',
                'body' => "This South Richvale character home with its traditional brick facade and arched front entry was surrounded by a generic suburban landscape that did nothing to complement its architectural charm. The homeowner, an avid gardener, wanted a front garden that reflected English cottage garden traditions while being practical for Richmond Hill's climate.\n\nWe designed a symmetrical front garden with low boxwood hedging defining formal beds flanking the walkway. Within each bed, a profusion of cottage perennials creates a lush, layered effect: David Austin roses, foxglove, delphinium, catmint, and lady's mantle provide colour from May through October. Natural limestone edging separates the garden beds from the lawn and walkway. A recirculating birdbath fountain serves as the garden's central focal point, positioned at the intersection of the front walkway and a new cross path.\n\nThe rear garden continues the cottage theme with more relaxed planting in deeper beds, including a cutting garden for fresh flowers and a small herb section near the kitchen door. All plantings were selected for Zone 5b hardiness and the heavy clay soil conditions common in South Richvale. A drip irrigation system with separate zones for sun and shade beds ensures consistent moisture without overwatering.",
            ],

            [
                'title' => 'Estate Landscape Lighting System in Oak Ridges',
                'service' => 'Landscape Lighting',
                'city' => 'Richmond Hill',
                'neighborhood' => 'Oak Ridges',
                'project_type' => 'Softscaping & Lifestyle Enhancements',
                'value' => '$10,000 - $18,000',
                'duration' => '4 days',
                'completed' => '2025-10-20',
                'featured' => false,
                'description' => 'Comprehensive estate-scale LED lighting system including driveway bollard lights, garden accent spots, facade uplighting, and security-grade perimeter lighting for an Oak Ridges property.',
                'body' => "This Oak Ridges estate sits on a large lot with mature trees, extensive gardens, and a long driveway. The homeowner wanted a professional lighting system that would serve three purposes: enhance the property's evening beauty, improve safety along walkways and the driveway, and provide security illumination around the home's perimeter.\n\nWe designed a multi-zone system with 28 individual fixtures across the property. The driveway features 8 low bollard path lights spaced evenly along both sides, providing safe navigation without glare. The home's facade is illuminated by 6 uplights that highlight the stone and architectural details. The front garden receives 4 accent spots on specimen plantings, and 2 tree canopy lights illuminate the mature oaks from below. The rear yard includes 4 step lights on the patio, 2 pergola downlights, and 2 security floods on motion sensors at the garage corners.\n\nAll fixtures are solid copper construction that develops a natural patina over time, complementing the estate's established character. The system runs on two multi-tap transformers with a smart controller that adjusts schedules seasonally and integrates with the home's WiFi for smartphone control. Total power consumption for all 28 fixtures is under 400 watts, demonstrating the efficiency of modern LED landscape lighting.",
            ],

            // ── GEORGETOWN (4) ──────────────────────────────────────────

            [
                'title' => 'Family Pool Deck and Play Area in Silver Creek',
                'service' => 'Interlocking Pool Decks',
                'city' => 'Georgetown',
                'neighborhood' => 'Silver Creek',
                'project_type' => 'Interlock & Specialty Paving',
                'value' => '$18,000 - $28,000',
                'duration' => '6 days',
                'completed' => '2025-07-28',
                'featured' => false,
                'description' => 'Family-friendly pool deck with non-slip pavers, a splash pad zone for younger children, permeable paver border, and safety-compliant fencing integration in Silver Creek.',
                'body' => "This Silver Creek family with young children needed a pool deck designed primarily for safety and durability. Their new in-ground pool had a basic concrete deck that was already showing cracks after one winter on Georgetown's clay-and-cobble soil. The homeowner also wanted a dedicated splash area for their toddler separate from the main pool.\n\nWe removed the failing concrete and installed Unilock Beacon Hill Flagstone pavers with a textured non-slip surface across the entire pool surround. The splash pad zone, located in the shallow end corner, features a slightly depressed paver area with a rubber pad underlay that drains through a dedicated catch basin. Natural stone coping with a rounded bullnose profile provides a safe, comfortable grip along the pool edge. Along the property boundary, a 3-foot border of permeable pavers allows stormwater to infiltrate rather than running off, important for compliance in the Credit Valley Conservation watershed area.\n\nThe pool fence was integrated with the deck design, with fence posts set in concrete footings outside the paver field to avoid disrupting the surface. The completed deck provides a safe, durable, and attractive pool environment that will serve this growing family for years to come.",
            ],

            [
                'title' => 'Rustic Exposed Aggregate Walkway in Glen Williams',
                'service' => 'Exposed Aggregate Concrete',
                'city' => 'Georgetown',
                'neighborhood' => 'Glen Williams',
                'project_type' => 'Concrete Services',
                'value' => '$7,000 - $11,000',
                'duration' => '3 days',
                'completed' => '2025-09-18',
                'featured' => false,
                'description' => 'Rustic exposed aggregate concrete walkway with warm-tone stone aggregate and natural fieldstone accents, designed to complement the village character of Glen Williams.',
                'body' => "Glen Williams is a charming village community along the Credit River known for its rural character and heritage properties. This homeowner wanted a front walkway that felt authentic to the village setting rather than the suburban paver style common in nearby subdivisions. Exposed aggregate concrete with a warm, natural stone appearance was the perfect fit.\n\nWe poured a gently curving 4-foot-wide walkway from the street to the front porch using concrete with a custom aggregate blend of warm-tone Credit River stone. The exposed finish reveals the natural beauty of the stone while providing excellent traction in wet conditions. Where the walkway meets the porch steps, we set natural fieldstone boulders as accent features that tie the concrete to the surrounding landscape. Smooth-finished concrete borders define the walkway edges.\n\nGiven the silty clay loam with glacial cobble typical of Georgetown's soil, we installed a 6-inch granular base to ensure stability and proper drainage. The walkway grade follows the natural terrain contour rather than cutting a straight line, preserving the organic feel the homeowner wanted. The completed project blends seamlessly with Glen Williams' rural village character.",
            ],

            [
                'title' => 'Heritage Front Entry Staircase Rebuild in Georgetown',
                'service' => 'Steps & Staircases',
                'city' => 'Georgetown',
                'neighborhood' => 'Georgetown Downtown',
                'project_type' => 'Structural Hardscape & Repair',
                'value' => '$9,000 - $15,000',
                'duration' => '4 days',
                'completed' => '2025-08-25',
                'featured' => false,
                'description' => 'Period-appropriate front entry staircase rebuild with natural stone treads, wrought iron railings, and integrated landing for a Georgetown Downtown heritage home.',
                'body' => "This Georgetown Downtown heritage home had original limestone steps that had heaved and cracked from over a century of freeze-thaw cycles on the local clay and cobble soil. The homeowner wanted a faithful rebuild that respected the home's historical character while meeting modern safety standards. The project required sensitivity to the Georgetown Heritage Conservation District guidelines.\n\nWe demolished the failed steps and poured new reinforced concrete stair structures on frost footings extending 48 inches below grade. Each tread was finished with natural Eramosa limestone slabs sourced from a nearby quarry, matching the warm grey tone of the original stone. The tread depth and riser height comply with current Ontario Building Code requirements while maintaining proportions authentic to the home's era. Custom wrought iron railings were fabricated to replicate a traditional design appropriate to the circa-1890 architecture.\n\nThe landing at the top was expanded slightly to accommodate modern accessibility standards. A contrasting limestone threshold marks the transition from the steps to the covered porch. The completed staircase restores the home's original elegance while providing decades of structural performance through Georgetown's challenging climate conditions.",
            ],

            [
                'title' => 'Pathway and Garden Lighting Package in Hungry Hollow',
                'service' => 'Landscape Lighting',
                'city' => 'Georgetown',
                'neighborhood' => 'Hungry Hollow',
                'project_type' => 'Softscaping & Lifestyle Enhancements',
                'value' => '$6,000 - $10,000',
                'duration' => '3 days',
                'completed' => '2025-10-10',
                'featured' => false,
                'description' => 'LED pathway and garden accent lighting system with porch lighting, walkway markers, and garden spot lights for a Hungry Hollow family home.',
                'body' => "This Hungry Hollow family had a well-maintained landscape that they could not enjoy after dark. The only exterior lighting was a single porch light that left the walkway, driveway, and garden completely unlit. Safety was a primary concern, particularly for evening visitors navigating the front steps.\n\nWe designed a balanced lighting system with 14 fixtures across the front and rear yards. The front walkway features 4 brass path lights in warm white (2700K) that guide visitors from the driveway to the front steps. Two uplights illuminate the home's facade, highlighting the front gable and entry arch. The garden beds receive 3 accent spots positioned to illuminate the ornamental trees and evergreen shrubs the homeowner has cultivated over the years. In the rear, 3 step lights on the deck stairs and 2 garden spots on the patio plantings extend the living space into the evening.\n\nThe system runs on a single 300-watt multi-tap transformer with a photocell that automatically activates the lights at dusk and turns them off at midnight. All wiring is buried in conduit for protection. The total monthly operating cost is under $5, making it an extremely economical enhancement that dramatically improves both the home's evening presence and the family's outdoor enjoyment after dark.",
            ],

            // ── BRAMPTON (4) ────────────────────────────────────────────

            [
                'title' => 'Curved Front Walkway and Planter Wall in Heart Lake',
                'service' => 'Interlocking Walkways & Entryways',
                'city' => 'Brampton',
                'neighborhood' => 'Heart Lake',
                'project_type' => 'Interlock & Specialty Paving',
                'value' => '$7,000 - $11,000',
                'duration' => '3 days',
                'completed' => '2025-09-25',
                'featured' => false,
                'description' => 'Sweeping curved interlocking walkway with a low decorative planter wall and integrated address lighting for a Heart Lake home. Designed to overcome heavy clay soil challenges.',
                'body' => "This Heart Lake homeowner wanted to replace their straight builder-grade concrete walkway with a design that added curb appeal and personality to their home's front entrance. The existing walkway had cracked and heaved significantly due to Brampton's heavy Halton Till clay soil, one of the most challenging soils in the GTA for hardscaping.\n\nWe designed a gently curving walkway using Techo-Bloc Raffinato pavers in Shale Grey, widening from 4 feet at the sidewalk to 5 feet at the front porch. A low decorative planter wall (18 inches high) curves along one side of the walkway near the entry, constructed from matching wall units and capped with natural stone. The planter is filled with hardy perennials including lavender, salvia, and ornamental grasses that provide three-season colour and fragrance near the front door.\n\nGiven the extreme shrink-swell characteristics of Brampton's clay, we excavated to 20 inches and installed geotextile fabric over the subgrade before placing the compacted granular base. This additional preparation prevents clay infiltration into the base material, which is the primary cause of walkway failure in this area. An integrated LED address light illuminates the house number at the walkway entrance.",
            ],

            [
                'title' => 'Grand Stamped Concrete Driveway in Castlemore',
                'service' => 'Stamped Concrete',
                'city' => 'Brampton',
                'neighborhood' => 'Castlemore',
                'project_type' => 'Concrete Services',
                'value' => '$18,000 - $28,000',
                'duration' => '5 days',
                'completed' => '2025-06-25',
                'featured' => false,
                'description' => 'Large-scale stamped concrete driveway with cobblestone pattern, integral colour, and decorative borders for a Castlemore estate home.',
                'body' => "This Castlemore estate home sits on one of the larger lots in the neighbourhood with a wide frontage that allowed for a generous driveway design. The homeowner wanted a stamped concrete driveway that would provide the look of natural cobblestone at a more practical price point while handling the heavy vehicles (including an RV) that regularly use the surface.\n\nWe poured a 6-inch reinforced concrete driveway (thicker than standard due to the RV weight requirement) with European fan cobblestone stamp pattern in Charcoal integral colour. The driveway spans approximately 800 square feet, accommodating three vehicles plus the RV pad. Contrasting smooth-finish borders in a lighter sandstone tone frame the entire driveway, creating a picture-frame effect. Expansion joints were positioned to follow the natural pattern lines of the cobblestone stamps.\n\nThe heavy Halton Till clay soil in Castlemore required an 8-inch compacted granular base to prevent settlement under the RV load. The air-entrained concrete mix is rated for severe freeze-thaw exposure, and the applied acrylic sealer provides UV stability and salt resistance. The homeowner was impressed by how closely the finished surface resembles genuine Belgian cobblestone at a fraction of the cost.",
            ],

            [
                'title' => 'Backyard Concrete Pad and Dog Run in Springdale',
                'service' => 'Plain & Broom-Finish Concrete',
                'city' => 'Brampton',
                'neighborhood' => 'Springdale',
                'project_type' => 'Concrete Services',
                'value' => '$5,000 - $8,000',
                'duration' => '2 days',
                'completed' => '2025-08-02',
                'featured' => false,
                'description' => 'Backyard utility concrete pad and dedicated dog run with broom-finish surface, raised borders, and integrated drainage for a Springdale family home with large dogs.',
                'body' => "This Springdale family with two large dogs had a backyard that had been completely destroyed by foot traffic and digging. The muddy conditions made the yard unusable for the children and tracked dirt into the home constantly. They needed a durable, easy-to-clean outdoor surface for the dogs without sacrificing the entire backyard.\n\nWe divided the rear yard into two zones. A 12-by-20-foot broom-finish concrete pad adjacent to the rear sliding door serves as the family's outdoor living area, providing a clean, dry surface for furniture, a BBQ, and children's play. Along the south fence line, we constructed a dedicated 4-by-30-foot dog run with a broom-finish concrete surface, raised concrete borders to contain drainage, and a gentle slope toward a catch basin that prevents puddle formation.\n\nThe remaining lawn area was re-graded and re-sodded after the concrete work was completed. A simple gate separates the dog run from the main yard, allowing the family to control access. The broom-finish surface provides good traction for the dogs while being easy to hose clean. The air-entrained concrete withstands Brampton's freeze-thaw cycles, and the integrated drainage prevents the standing water that had been a persistent problem on the heavy clay soil.",
            ],

            [
                'title' => 'Complete Lawn Installation and Grading in Mount Pleasant',
                'service' => 'Sod Installation & Lawn Care',
                'city' => 'Brampton',
                'neighborhood' => 'Mount Pleasant',
                'project_type' => 'Softscaping & Lifestyle Enhancements',
                'value' => '$4,500 - $8,000',
                'duration' => '2 days',
                'completed' => '2025-05-28',
                'featured' => false,
                'description' => 'Complete new-build lawn preparation with topsoil delivery, fine grading, premium sod installation, and drainage correction for a Mount Pleasant home.',
                'body' => "This Mount Pleasant homeowner purchased a new-build home and was disappointed with the builder's lawn installation. Within the first season, the lawn had developed extensive bare patches, and water was pooling against the foundation in several locations due to improper grading. The thin topsoil over compacted clay fill was the root cause of both issues.\n\nWe stripped the failing sod and performed a complete re-grade of both front and rear yards. The critical correction was re-establishing positive drainage slopes away from the foundation, achieving the Ontario Building Code minimum of 10% slope for the first 6 feet. We imported screened topsoil and triple mix, spreading 4 inches over the amended subsoil to create a healthy growing medium significantly deeper than the builder's original 1 to 2 inch layer.\n\nPremium Kentucky bluegrass sod was installed across 4,200 square feet of front and rear lawn, rolled immediately after placement and given a thorough initial watering. We provided the homeowner with a complete first-year maintenance guide tailored to Brampton's heavy clay conditions, including watering schedules, mowing heights, and seasonal fertilization timing. The corrected grading and improved soil depth have produced a thick, healthy lawn that handles Brampton's heavy rainfall without pooling.",
            ],
        ];
    }

    // ─── Content Blocks ────────────────────────────────────────────────────

    private function createProjectBlocks(PortfolioProject $project, array $data, int $idx): void
    {
        $svc = $data['service'];
        $city = $data['city'];
        $hood = $data['neighborhood'];

        BlockBuilderService::saveUnifiedBlocks('portfolio_project', $project->id, [
            [
                'block_type' => 'feature_list',
                'sort_order' => 1,
                'is_enabled' => true,
                'content' => $this->featureListContent($svc, $city, $hood, $data),
            ],
            [
                'block_type' => 'steps_process',
                'sort_order' => 2,
                'is_enabled' => true,
                'content' => $this->stepsContent($svc, $city, $hood),
            ],
            [
                'block_type' => 'testimonial_card',
                'sort_order' => 3,
                'is_enabled' => true,
                'content' => $this->testimonialContent($svc, $city, $hood, $idx),
            ],
        ]);
    }

    private function featureListContent(string $svc, string $city, string $hood, array $data): array
    {
        $features = match ($svc) {
            'Interlocking Driveways' => [
                ['icon' => 'grid-3x3', 'title' => 'Premium Paver Selection', 'description' => "Hand-selected interlocking pavers chosen specifically for the architectural style and climate conditions in {$hood}, {$city}."],
                ['icon' => 'layers', 'title' => 'Engineered Base System', 'description' => "Full-depth excavation with compacted Granular A aggregate base designed for {$city}'s local soil and frost depth requirements."],
                ['icon' => 'droplets', 'title' => 'Integrated Drainage', 'description' => 'Re-engineered surface water management directing runoff away from the foundation and toward the municipal storm system.'],
                ['icon' => 'shield', 'title' => 'Polymeric Sand & Sealer', 'description' => 'Professional-grade jointing compound and sealant providing long-term protection against salt, weeds, and seasonal freeze-thaw exposure.'],
                ['icon' => 'sparkles', 'title' => 'Decorative Border Detail', 'description' => "Contrasting soldier course borders and accent bands adding visual distinction to this {$hood} driveway."],
            ],
            'Interlocking Patios & Backyard Living' => [
                ['icon' => 'layout', 'title' => 'Multi-Zone Patio Design', 'description' => "Distinct zones for dining, lounging, and entertaining, tailored to the property layout in {$hood}, {$city}."],
                ['icon' => 'flame', 'title' => 'Fire Feature Integration', 'description' => 'Built-in fire pit or fireplace with dedicated gas line, creating a year-round focal point for outdoor gatherings.'],
                ['icon' => 'armchair', 'title' => 'Built-In Seating', 'description' => 'Custom stone seating walls with natural cap stones, providing permanent, weather-resistant seating without the need for bulky furniture.'],
                ['icon' => 'eye-off', 'title' => 'Privacy Screening', 'description' => "Strategic plantings and screen elements selected for {$city}'s growing zone, creating a sheltered outdoor room."],
                ['icon' => 'lightbulb', 'title' => 'LED Accent Lighting', 'description' => 'Low-voltage step lights and accent spots extending the usable hours of the outdoor space into the evening.'],
            ],
            'Interlocking Walkways & Entryways' => [
                ['icon' => 'move-diagonal', 'title' => 'Curved Design Flow', 'description' => "A naturally curving path that creates a welcoming sense of arrival, designed to complement the {$hood} streetscape."],
                ['icon' => 'maximize', 'title' => 'Widened Entry Landing', 'description' => 'The walkway widens at the front door to create a generous landing pad for comfortable arrival and departure.'],
                ['icon' => 'brick-wall', 'title' => 'Premium Paver Material', 'description' => "Colour-matched pavers selected to complement the home's facade and the neighbourhood character in {$hood}, {$city}."],
                ['icon' => 'flower', 'title' => 'Planting Integration', 'description' => 'Garden bed edges along the walkway with seasonal plantings that soften the hardscape and add colour.'],
                ['icon' => 'lamp', 'title' => 'Illuminated Address Feature', 'description' => 'LED-lit address pillar or marker at the walkway entrance for visibility and curb appeal.'],
            ],
            'Stamped Concrete' => [
                ['icon' => 'stamp', 'title' => 'Custom Stamp Pattern', 'description' => "Premium stamp pattern selected to replicate natural stone at a fraction of the cost, chosen for this {$hood} property."],
                ['icon' => 'palette', 'title' => 'Integral Colour System', 'description' => 'Through-body colour with accent release agent creating realistic depth and shadow in the stamp texture.'],
                ['icon' => 'shield', 'title' => 'Air-Entrained Mix', 'description' => "Freeze-thaw rated concrete formulation specifically designed for {$city}'s climate and seasonal temperature swings."],
                ['icon' => 'sparkles', 'title' => 'UV-Stable Sealer', 'description' => 'Professional acrylic sealer providing colour protection, stain resistance, and enhanced surface durability.'],
                ['icon' => 'ruler', 'title' => 'Reinforced Slab Design', 'description' => 'Steel-reinforced concrete with proper control joints planned along natural pattern lines for invisible crack management.'],
            ],
            'Plain & Broom-Finish Concrete' => [
                ['icon' => 'square', 'title' => 'Broom-Finish Surface', 'description' => "Non-slip textured finish providing excellent traction in all weather conditions common to {$city}."],
                ['icon' => 'ruler', 'title' => 'Proper Slab Thickness', 'description' => "Reinforced slab poured to the correct thickness for the intended load, with air-entrained mix for {$city}'s freeze-thaw cycles."],
                ['icon' => 'droplets', 'title' => 'Integrated Drainage', 'description' => "Graded surface slope directing water away from the foundation and resolving standing water issues in {$hood}."],
                ['icon' => 'puzzle', 'title' => 'Expansion Joint Design', 'description' => 'Properly placed control and expansion joints to manage natural concrete movement and prevent random cracking.'],
                ['icon' => 'mountain', 'title' => 'Compacted Granular Base', 'description' => "Full granular base preparation over {$city}'s local clay subsoil, ensuring long-term slab stability."],
            ],
            'Exposed Aggregate Concrete' => [
                ['icon' => 'circle-dot', 'title' => 'Natural Stone Aggregate', 'description' => "Selected stone aggregate blend chosen to complement the home's architecture in {$hood}, {$city}."],
                ['icon' => 'frame', 'title' => 'Smooth Border Bands', 'description' => 'Contrasting smooth-finished borders framing the exposed aggregate field for a polished, defined appearance.'],
                ['icon' => 'shield', 'title' => 'Penetrating Sealer', 'description' => 'Professional-grade sealer enhancing the natural stone colours while protecting against freeze-thaw and staining.'],
                ['icon' => 'footprints', 'title' => 'Non-Slip Texture', 'description' => 'The naturally textured aggregate surface provides excellent wet-weather traction without additional treatment.'],
                ['icon' => 'puzzle', 'title' => 'Geometric Joint Pattern', 'description' => 'Control joints cut in a design-integrated pattern that manages cracking while adding visual interest.'],
            ],
            'Retaining Walls' => [
                ['icon' => 'brick-wall', 'title' => 'Engineered Wall System', 'description' => "Structurally engineered wall design addressing the specific grade change and soil conditions in {$hood}, {$city}."],
                ['icon' => 'layers', 'title' => 'Geogrid Reinforcement', 'description' => 'Geosynthetic reinforcement extending behind the wall face into compacted granular backfill for structural stability.'],
                ['icon' => 'droplets', 'title' => 'Drainage Integration', 'description' => 'Perforated drain pipe and clear stone backfill behind each tier, preventing hydrostatic pressure buildup.'],
                ['icon' => 'flower', 'title' => 'Terraced Planting Beds', 'description' => 'Usable garden terraces between wall tiers, planted with species suited to the local soil and light conditions.'],
                ['icon' => 'lightbulb', 'title' => 'LED Cap Lighting', 'description' => 'Integrated lighting beneath the wall cap providing subtle accent illumination for evening enjoyment.'],
            ],
            'Foundation Waterproofing & Grading' => [
                ['icon' => 'shield-check', 'title' => 'Rubberized Membrane', 'description' => "Self-adhering waterproofing membrane bonded directly to the foundation wall, rated for {$city}'s soil moisture conditions."],
                ['icon' => 'waves', 'title' => 'Weeping Tile Replacement', 'description' => 'New 4-inch perforated PVC pipe bedded in clear stone, replacing the original failed drainage system.'],
                ['icon' => 'trending-up', 'title' => 'Positive Lot Grading', 'description' => 'Re-graded perimeter achieving Ontario Building Code minimum slope away from the foundation for the first 6 feet.'],
                ['icon' => 'layers', 'title' => 'Drainage Board Protection', 'description' => 'Dimpled drainage board over the membrane channelling water downward and protecting the waterproofing from backfill damage.'],
                ['icon' => 'home', 'title' => 'Window Well Rebuild', 'description' => 'New window wells with proper clear stone drainage fill and protective covers, eliminating water entry points.'],
            ],
            'Steps & Staircases' => [
                ['icon' => 'brick-wall', 'title' => 'Natural Stone Treads', 'description' => "Durable natural stone cap on each tread providing a non-slip surface built for {$city}'s freeze-thaw climate."],
                ['icon' => 'ruler', 'title' => 'OBC-Compliant Dimensions', 'description' => 'Rise and run dimensions meeting current Ontario Building Code requirements for safe, comfortable residential stairs.'],
                ['icon' => 'shield', 'title' => 'Frost Footing Foundation', 'description' => "Reinforced concrete footings extending 48 inches below grade, below {$city}'s frost depth for permanent stability."],
                ['icon' => 'lightbulb', 'title' => 'LED Riser Lighting', 'description' => 'Recessed lighting beneath each tread nose for safe nighttime visibility and enhanced curb appeal.'],
                ['icon' => 'fence', 'title' => 'Custom Wrought Iron Railings', 'description' => 'Powder-coated railings fabricated to complement the home architecture and meet current safety standards.'],
            ],
            'Outdoor Kitchens & Fire Features' => [
                ['icon' => 'chef-hat', 'title' => 'Professional-Grade Equipment', 'description' => "Built-in gas grill, refrigeration, and prep surfaces rated for outdoor use in {$city}'s four-season climate."],
                ['icon' => 'flame', 'title' => 'TSSA-Certified Gas Line', 'description' => 'Dedicated natural gas connection installed by a licensed contractor for safe, propane-free operation.'],
                ['icon' => 'gem', 'title' => 'Premium Countertops', 'description' => "Durable granite or quartz countertop material chosen for heat resistance, weather durability, and the aesthetic of this {$hood} property."],
                ['icon' => 'brick-wall', 'title' => 'Natural Stone Veneer', 'description' => "Stone veneer finish matching the home's existing masonry for a cohesive, built-in appearance."],
                ['icon' => 'lamp', 'title' => 'Ambient Lighting Design', 'description' => 'Task and accent lighting throughout the kitchen and dining area for safe, atmospheric evening entertaining.'],
            ],
            'Garden Design & Softscaping' => [
                ['icon' => 'flower', 'title' => 'Climate-Adapted Plantings', 'description' => "All species selected for {$city}'s USDA hardiness zone and local soil conditions, ensuring long-term viability."],
                ['icon' => 'droplets', 'title' => 'Drip Irrigation System', 'description' => 'Smart-controlled irrigation with separate zones for sun and shade beds, optimizing water use and plant health.'],
                ['icon' => 'sun', 'title' => 'Four-Season Interest', 'description' => 'Layered planting design providing colour, texture, and structure from spring bloom through winter bark and seed heads.'],
                ['icon' => 'mountain', 'title' => 'Hardscape Integration', 'description' => "Natural stone pathways and garden structures connecting the landscape to the home's outdoor living areas."],
                ['icon' => 'bird', 'title' => 'Wildlife-Friendly Design', 'description' => 'Native and adapted species that attract pollinators and songbirds while maintaining a manicured appearance.'],
            ],
            'Sod Installation & Lawn Care' => [
                ['icon' => 'layers', 'title' => 'Deep Topsoil Preparation', 'description' => "Amended soil depth exceeding builder standards, correcting the thin topsoil typical of {$city}'s newer subdivisions."],
                ['icon' => 'sprout', 'title' => 'Premium Sod Blend', 'description' => "Kentucky bluegrass sod blend selected for {$city}'s climate, providing dense coverage and disease resistance."],
                ['icon' => 'trending-up', 'title' => 'Drainage Correction', 'description' => 'Re-graded property achieving positive drainage slopes away from the foundation, eliminating standing water.'],
                ['icon' => 'calendar', 'title' => 'Custom Care Calendar', 'description' => "Seasonal maintenance guide with fertilization, aeration, and mowing recommendations specific to {$city}'s soil type."],
                ['icon' => 'check-circle', 'title' => 'Same-Day Installation', 'description' => 'Sod installed the same day it was harvested for maximum viability and rapid root establishment.'],
            ],
            'Interlocking Pool Decks' => [
                ['icon' => 'shield', 'title' => 'Non-Slip Surface Rating', 'description' => 'Textured pavers meeting or exceeding coefficient of friction standards for pool deck safety when wet.'],
                ['icon' => 'waves', 'title' => 'Custom Bullnose Coping', 'description' => 'Natural stone coping with rounded bullnose profile providing a safe, finished grip at the pool edge.'],
                ['icon' => 'droplets', 'title' => 'Integrated Deck Drainage', 'description' => 'Slot drainage channels at the pool perimeter capturing splash-out water and preventing standing puddles.'],
                ['icon' => 'palette', 'title' => 'Coordinated Material Palette', 'description' => "Paver and coping colours selected to complement the pool finish and the home's exterior in {$hood}, {$city}."],
                ['icon' => 'award', 'title' => 'Lifetime Product Warranty', 'description' => "Premium manufacturer warranty backed by Lush Landscape's 10-year workmanship guarantee."],
            ],
            'Landscape Lighting' => [
                ['icon' => 'lightbulb', 'title' => 'LED Efficiency', 'description' => "Total system power consumption under 400 watts, providing brilliant illumination at minimal operating cost in {$hood}."],
                ['icon' => 'sliders', 'title' => 'Independent Zone Control', 'description' => 'Multiple lighting zones on separate dimmers and timers for complete control over the evening atmosphere.'],
                ['icon' => 'shield', 'title' => 'All-Weather Fixtures', 'description' => "Solid brass or copper construction rated for {$city}'s full range of weather extremes with 15-year warranty."],
                ['icon' => 'clock', 'title' => 'Smart Timer Integration', 'description' => 'Astronomical timer automatically adjusting on/off schedules with seasonal daylight changes.'],
                ['icon' => 'eye', 'title' => 'Security Enhancement', 'description' => 'Strategic placement of path, perimeter, and motion-activated fixtures improving safety and deterring intrusion.'],
            ],
            default => [
                ['icon' => 'check', 'title' => 'Professional Craftsmanship', 'description' => "Every detail executed to the highest standards for this {$hood}, {$city} project."],
                ['icon' => 'shield', 'title' => 'Backed by Warranty', 'description' => "Full workmanship warranty ensuring long-term performance in {$city}'s climate."],
                ['icon' => 'users', 'title' => 'Local Expertise', 'description' => "Deep knowledge of {$city}'s soil conditions, bylaws, and building requirements."],
                ['icon' => 'star', 'title' => 'Premium Materials', 'description' => 'Only top-tier materials sourced from trusted Canadian suppliers.'],
            ],
        };

        return [
            'heading' => 'Project Highlights',
            'columns' => '2',
            'features' => $features,
        ];
    }

    private function stepsContent(string $svc, string $city, string $hood): array
    {
        $type = match (true) {
            str_contains($svc, 'Interlocking') => 'interlock',
            str_contains($svc, 'Concrete') || str_contains($svc, 'Stamped') || str_contains($svc, 'Aggregate') => 'concrete',
            in_array($svc, ['Retaining Walls', 'Foundation Waterproofing & Grading', 'Steps & Staircases']) => 'structural',
            default => 'softscape',
        };

        $steps = match ($type) {
            'interlock' => [
                ['icon' => 'clipboard-list', 'title' => 'Design Consultation', 'description' => "We visited the {$hood} property to assess the existing conditions, take precise measurements, discuss material preferences, and present a detailed proposal with 3D renderings."],
                ['icon' => 'shovel', 'title' => 'Excavation & Base Preparation', 'description' => "Excavated to the required depth for {$city}'s frost conditions and installed a compacted Granular A base with geotextile fabric separation over the local clay subsoil."],
                ['icon' => 'grid-3x3', 'title' => 'Paver Installation & Pattern Layout', 'description' => 'Each paver was placed by hand following the approved pattern layout, with edge restraints, border courses, and all cuts made with diamond-blade saws for precision.'],
                ['icon' => 'check-circle', 'title' => 'Jointing, Sealing & Handover', 'description' => 'Polymeric sand was swept into all joints, the surface was compacted a final time, and sealant was applied. We provided care instructions and warranty documentation.'],
            ],
            'concrete' => [
                ['icon' => 'clipboard-list', 'title' => 'Site Measurement & Design', 'description' => "On-site consultation at the {$hood} property to determine slab dimensions, finish preferences, colour selections, and joint placement strategy."],
                ['icon' => 'hard-hat', 'title' => 'Formwork & Reinforcement', 'description' => "Precision formwork set to grade with steel reinforcement mesh, compacted granular base, and proper sub-drainage for {$city}'s soil conditions."],
                ['icon' => 'waves', 'title' => 'Pouring & Finishing', 'description' => 'Concrete poured, screeded, and finished in a single continuous pour. Stamp patterns, aggregate exposure, or broom texture applied while the concrete is at the optimal workability stage.'],
                ['icon' => 'check-circle', 'title' => 'Curing, Sealing & Cleanup', 'description' => 'Proper curing period observed, followed by sealer application, form removal, backfill grading, and a thorough site cleanup with final walkthrough.'],
            ],
            'structural' => [
                ['icon' => 'search', 'title' => 'Assessment & Engineering', 'description' => "Thorough site assessment in {$hood}, {$city} evaluating grade changes, soil conditions, drainage patterns, and structural requirements before design finalization."],
                ['icon' => 'shovel', 'title' => 'Excavation & Foundation', 'description' => 'Careful excavation to the required depth, with frost footings, drainage tile installation, and granular backfill preparation per the engineered design.'],
                ['icon' => 'brick-wall', 'title' => 'Construction & Drainage', 'description' => 'Systematic wall construction, step forming, or membrane application with integrated drainage systems designed for the local water table and soil permeability.'],
                ['icon' => 'check-circle', 'title' => 'Finishing & Inspection', 'description' => 'Final grading, cap stone installation, railing fabrication, and a comprehensive final inspection with care instructions and warranty documentation.'],
            ],
            'softscape' => [
                ['icon' => 'trees', 'title' => 'Landscape Consultation', 'description' => "On-site design consultation at the {$hood} property assessing light conditions, soil type, existing features, and the homeowner's lifestyle and aesthetic goals."],
                ['icon' => 'shovel', 'title' => 'Site Preparation', 'description' => "Soil amendment, grading correction, and infrastructure installation (irrigation, lighting conduit, hardscape foundations) tailored to {$city}'s conditions."],
                ['icon' => 'flower', 'title' => 'Planting & Installation', 'description' => 'Every plant, fixture, and feature installed according to the approved design, with proper planting depth, spacing, and staking for establishment.'],
                ['icon' => 'check-circle', 'title' => 'Handover & Maintenance Guide', 'description' => "Complete walkthrough with a customized maintenance calendar for {$city}'s growing season, including watering, fertilization, and seasonal care instructions."],
            ],
        };

        return [
            'heading' => 'How We Delivered This Project',
            'layout' => 'vertical',
            'steps' => $steps,
        ];
    }

    private function testimonialContent(string $svc, string $city, string $hood, int $idx): array
    {
        $names = [
            'Michael R.', 'Sarah T.', 'David K.', 'Jennifer L.', 'Robert M.', 'Amanda P.',
            'James W.', 'Lisa H.', 'Christopher B.', 'Nicole D.', 'Andrew S.', 'Karen F.',
            'Daniel G.', 'Michelle C.', 'Steven A.', 'Rebecca N.', 'Mark E.', 'Laura J.',
            'Brian V.', 'Stephanie O.', 'Kevin I.', 'Angela Q.', 'Thomas Z.', 'Heather U.',
            'Jason Y.', 'Patricia X.', 'Richard W.', 'Donna B.', 'Jeffrey H.', 'Christine M.',
            'Matthew L.', 'Elizabeth K.', 'Paul D.', 'Sandra G.', 'Ryan F.', 'Catherine S.',
            'Timothy A.', 'Maria R.', 'Eric J.', 'Deborah N.', 'Gregory P.', 'Carolyn T.',
        ];

        $quotes = [
            'We could not be happier with our new driveway. The crew was professional from start to finish, and the herringbone pattern they recommended looks incredible. Neighbours stop to compliment it regularly.',
            'The patio has completely transformed how we use our backyard. We went from never sitting outside to hosting dinner parties every weekend. The fire pit is our favourite feature by far.',
            'From the very first meeting, the Lush team understood exactly what we wanted. The finished walkway is stunning and fits the character of our neighbourhood perfectly. Worth every penny.',
            'The stamped concrete looks so much like real stone that visitors always ask if it is natural flagstone. The finish quality and attention to detail exceeded our expectations.',
            'Simple, functional, and done right the first time. The concrete pad and walkway solved our drainage problem completely, and the crew left the site spotless. Highly recommend.',
            'The aggregate finish on our driveway gets compliments constantly. It blends beautifully with our home and the texture provides great grip in winter. Excellent workmanship.',
            'Our retaining wall not only solved the erosion problem but actually added usable garden space. The terraced design was brilliant, and the plantings they suggested are thriving.',
            'After years of basement moisture, we finally have a dry basement. The Lush team explained every step, worked clean, and the result speaks for itself. Should have called them sooner.',
            "The new front steps are the centrepiece of our home's curb appeal. The stone treads and iron railings look like they belong on a heritage estate. Quality craftsmanship.",
            'Our outdoor kitchen has extended our living season by at least four months. The stone veneer matches our home perfectly and the pizza oven was the best decision we made.',
            'The garden is a completely different space now. Every season brings something new to enjoy, and the drip irrigation means we barely have to think about maintenance.',
            'We went from the worst lawn on the street to the best in two days. The sod was lush and green from day one, and the care guide they provided kept it looking perfect.',
            'The pool deck is exactly what we needed. No more slippery concrete, and the coping stones give the pool a resort-quality look. Our kids feel much safer around the pool now.',
            'The lighting transformed our property after dark. We actually sit outside in the evenings now, and the security benefit gives us real peace of mind. Beautifully done.',
            'Professional, punctual, and the quality of work is outstanding. The crew handled everything from permits to cleanup. Our only regret is not doing this sooner.',
            'The design process was collaborative and the team truly listened to what we wanted. The result is a backyard that feels like a vacation every time we step outside.',
            'What impressed us most was the attention to drainage and base preparation. They explained why each layer matters, and the finished surface is perfectly level after two full winters.',
            'The fire feature has become the gathering point for our family and friends. Even in October, we are still outside enjoying the warmth. It was the perfect addition to our patio.',
            'We had three quotes and chose Lush because they were the most thorough in their assessment. They identified issues the other companies missed, and the result proved them right.',
            'The walkway curve flows so naturally that it looks like it has always been there. They even worked around our favourite garden plants without damaging a single one.',
            'Exceptional from consultation to completion. The crew arrived on time every day, communicated clearly, and the final product is even better than the 3D rendering they showed us.',
            'Our neighbours hired a different company and spent the same amount. The difference in quality is night and day. We are so glad we went with Lush Landscape.',
            'The terraced wall system they built solved a problem we had been dealing with for over a decade. No more erosion, no more wasted space. Just beautiful, functional landscaping.',
            'We love how they matched the stonework to our existing facade. It looks like it was always part of the original design rather than an addition.',
            'The lighting design is subtle but makes a huge impact. Our home looks completely different at night, in the best possible way. The smart timer is a great feature too.',
            'Every time it rains heavily, we think about how grateful we are for the waterproofing work. Our basement stays bone dry now. Worth every cent of the investment.',
            'The outdoor kitchen has become the heart of our summer entertaining. The granite counter and built-in grill make cooking outside a genuine pleasure rather than a chore.',
            'Our kids can finally play in the backyard without tracking mud through the house. The sod went down beautifully and the lawn looks like a golf course. Amazing transformation.',
            'We appreciated their knowledge of local soil conditions and frost depths. They did not cut corners on the base preparation, and after three winters the surface is still perfect.',
            'The pool deck pavers stay cool underfoot even on the hottest summer days. The non-slip texture gives us confidence when the grandchildren are visiting.',
            'Their garden design turned our boring builder yard into something out of a magazine. We get compliments from everyone who visits, and the low maintenance is a real bonus.',
            'The step lighting was a brilliant addition. We feel so much safer coming home in the dark, and the warm glow gives the whole front entry a welcoming feel.',
            "We interviewed several companies and Lush was the only one that took the time to understand our property's specific drainage challenges. The solution they designed works flawlessly.",
            'The aggregate walkway fits the character of our village neighbourhood perfectly. It looks natural and rustic but is completely level and easy to maintain. Exactly what we wanted.',
            'From the initial consultation to the final walkthrough, the experience was seamless. The team kept us informed at every stage and the timeline was respected to the day.',
            'Our new front entry makes our home look like a completely different house. The natural stone and iron railings have a timeless quality that will look great for decades.',
            'We were nervous about the cost, but the Lush team worked with our budget and still delivered a result that exceeded our expectations. True professionals.',
            'The garden water feature is our favourite thing about the renovation. The sound of running water is so calming, and the birds it attracts are a joy to watch.',
            'The fire table and kitchen island have turned our backyard into a true outdoor living room. We barely use the indoor kitchen from May through September anymore.',
            'We appreciate that they used native plants suited to our local conditions. Three years later, everything is thriving with minimal maintenance. A thoughtful, professional design.',
            'The driveway project was completed on time and on budget. The European-style pavers look absolutely stunning and handle our Canadian winters without any issues.',
            'Our landscape lighting was the single best investment we have made in our property. The curb appeal at night is dramatic, and we use the backyard so much more in the evenings.',
        ];

        return [
            'quote' => $quotes[$idx % count($quotes)],
            'author' => $names[$idx % count($names)],
            'role' => "Homeowner, {$hood}, {$city}",
            'rating' => '5',
            'style' => 'card',
        ];
    }
}
