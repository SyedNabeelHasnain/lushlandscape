<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Faq;
use App\Models\FaqAssignment;
use App\Models\FaqCategory;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceCityPage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FaqContentSeeder extends Seeder
{
    public function run(): void
    {
        $categories = $this->createFaqCategories();
        $cityData = $this->getCityData();
        $services = Service::with('category')->get();
        $cities = City::where('status', 'published')->get();

        foreach ($cities as $city) {
            $cd = $cityData[$city->name] ?? null;
            if (! $cd) {
                continue;
            }

            foreach ($services as $service) {
                $page = ServiceCityPage::where('service_id', $service->id)
                    ->where('city_id', $city->id)
                    ->where('is_active', true)
                    ->first();

                if (! $page) {
                    continue;
                }

                $faqCatId = $categories[$service->category_id] ?? $categories->first();
                $templates = $this->getFaqsForService($service->name, $city->name, $cd);

                foreach ($templates as $order => $tpl) {
                    $slug = Str::slug($tpl['question']).'-'.Str::slug($city->name);
                    // Ensure unique slug by truncating if needed
                    $slug = Str::limit($slug, 250, '');

                    $faq = Faq::updateOrCreate(
                        ['slug' => $slug],
                        [
                            'category_id' => $faqCatId,
                            'question' => $tpl['question'],
                            'short_answer' => $tpl['short_answer'],
                            'answer' => $tpl['answer'],
                            'answer_format' => 'text',
                            'faq_type' => 'service',
                            'audience_type' => 'customer',
                            'status' => 'published',
                            'display_order' => $order + 1,
                            'local_relevance' => true,
                            'city_relevance' => $city->name,
                            'region_relevance' => $cd['region'],
                            'schema_eligible' => true,
                            'published_at' => now(),
                        ]
                    );

                    FaqAssignment::updateOrCreate(
                        [
                            'faq_id' => $faq->id,
                            'assignable_type' => 'App\\Models\\ServiceCityPage',
                            'assignable_id' => $page->id,
                        ],
                        [
                            'local_display_order' => $order + 1,
                            'is_visible' => true,
                            'is_collapsed' => true,
                        ]
                    );
                }
            }
        }
    }

    // Create 4 FAQ categories matching service categories
    private function createFaqCategories(): array
    {
        $map = [];
        $cats = [
            ['name' => 'Interlock & Specialty Paving FAQs', 'slug' => 'interlock-specialty-paving-faqs', 'sort_order' => 1],
            ['name' => 'Concrete Services FAQs', 'slug' => 'concrete-services-faqs', 'sort_order' => 2],
            ['name' => 'Structural Hardscape & Repair FAQs', 'slug' => 'structural-hardscape-repair-faqs', 'sort_order' => 3],
            ['name' => 'Softscaping & Lifestyle FAQs', 'slug' => 'softscaping-lifestyle-faqs', 'sort_order' => 4],
        ];

        // Map service category names to FAQ category names
        $serviceCatMap = [
            'Interlock & Specialty Paving' => 'interlock-specialty-paving-faqs',
            'Concrete Services' => 'concrete-services-faqs',
            'Structural Hardscape & Repair' => 'structural-hardscape-repair-faqs',
            'Softscaping & Lifestyle Enhancements' => 'softscaping-lifestyle-faqs',
        ];

        foreach ($cats as $cat) {
            $faqCat = FaqCategory::updateOrCreate(
                ['slug' => $cat['slug']],
                array_merge($cat, ['status' => 'published'])
            );
            // Store by slug for lookup
            $map[$cat['slug']] = $faqCat->id;
        }

        // Build final map: service_category.id => faq_category.id
        $result = [];
        $serviceCategories = ServiceCategory::all();
        foreach ($serviceCategories as $sc) {
            $faqSlug = $serviceCatMap[$sc->name] ?? null;
            if ($faqSlug && isset($map[$faqSlug])) {
                $result[$sc->id] = $map[$faqSlug];
            }
        }

        return $result;
    }

    // City-specific data for FAQ answer localization
    private function getCityData(): array
    {
        return [
            'Hamilton' => [
                'region' => 'Hamilton-Wentworth',
                'soil' => 'clay-heavy glacial till over Queenston shale',
                'frost' => '48 inches',
                'zone' => '6a',
                'conservation' => 'Hamilton Conservation Authority (HCA)',
                'permit_auth' => 'City of Hamilton Planning & Economic Development',
                'snowfall' => '150 cm',
                'terrain' => 'Niagara Escarpment elevation changes',
                'neighborhoods' => 'Westdale, Dundas, Ancaster, and Stoney Creek',
            ],
            'Burlington' => [
                'region' => 'Halton Region',
                'soil' => 'sandy loam near lakeshore transitioning to silty clay inland',
                'frost' => '48 inches',
                'zone' => '6a',
                'conservation' => 'Conservation Halton',
                'permit_auth' => 'City of Burlington Building and By-Law Department',
                'snowfall' => '120 cm',
                'terrain' => 'lakefront to escarpment bench terrain',
                'neighborhoods' => 'Aldershot, Millcroft, Tyandaga, and Roseland',
            ],
            'Oakville' => [
                'region' => 'Halton Region',
                'soil' => 'sandy clay loam near lakeshore with heavy clay inland',
                'frost' => '48 inches',
                'zone' => '6a',
                'conservation' => 'Conservation Halton',
                'permit_auth' => 'Town of Oakville Building Services',
                'snowfall' => '110 cm',
                'terrain' => 'mature lakeshore and heritage communities',
                'neighborhoods' => 'Old Oakville, Bronte, Glen Abbey, and River Oaks',
            ],
            'Mississauga' => [
                'region' => 'Peel Region',
                'soil' => 'Halton Till clay with sandy pockets near Credit River',
                'frost' => '48 inches',
                'zone' => '6a',
                'conservation' => 'Credit Valley Conservation (CVC)',
                'permit_auth' => 'City of Mississauga Building Division',
                'snowfall' => '108 cm',
                'terrain' => 'Credit River watershed and suburban development',
                'neighborhoods' => 'Port Credit, Lorne Park, Erin Mills, and Meadowvale',
            ],
            'Milton' => [
                'region' => 'Halton Region',
                'soil' => 'clay loam on lowlands with thin soil over shale near escarpment',
                'frost' => '48 inches',
                'zone' => '5b to 6a',
                'conservation' => 'Conservation Halton / Hamilton Conservation Authority',
                'permit_auth' => 'Town of Milton Building Division',
                'snowfall' => '130 cm',
                'terrain' => 'escarpment edge and new suburban development',
                'neighborhoods' => 'Old Milton, Timberlea, Harrison, and Bristol',
            ],
            'Toronto' => [
                'region' => 'City of Toronto',
                'soil' => 'variable glacial deposits ranging from sandy to heavy clay till',
                'frost' => '48 inches',
                'zone' => '6a',
                'conservation' => 'Toronto and Region Conservation Authority (TRCA)',
                'permit_auth' => 'Toronto Building',
                'snowfall' => '108 cm',
                'terrain' => 'urban lots with ravine protection zones',
                'neighborhoods' => 'Etobicoke, Forest Hill, Lawrence Park, and The Kingsway',
            ],
            'Vaughan' => [
                'region' => 'York Region',
                'soil' => 'heavy Newmarket Till clay dominant across the city',
                'frost' => '48 inches',
                'zone' => '5b',
                'conservation' => 'Toronto and Region Conservation Authority (TRCA)',
                'permit_auth' => 'City of Vaughan Building Standards Department',
                'snowfall' => '130 cm',
                'terrain' => 'Humber River watershed and new subdivisions',
                'neighborhoods' => 'Woodbridge, Kleinburg, Maple, and Thornhill',
            ],
            'Richmond Hill' => [
                'region' => 'York Region',
                'soil' => 'heavy clay in the south with sandy glacial deposits on Oak Ridges Moraine',
                'frost' => '48 inches',
                'zone' => '5b',
                'conservation' => 'Toronto and Region Conservation Authority (TRCA)',
                'permit_auth' => 'City of Richmond Hill Planning and Building Services',
                'snowfall' => '135 cm',
                'terrain' => 'Oak Ridges Moraine and southern urban density',
                'neighborhoods' => 'South Richvale, Oak Ridges, Bayview Hill, and Jefferson',
            ],
            'Georgetown' => [
                'region' => 'Halton Hills',
                'soil' => 'silty clay loam with glacial cobble near Credit River',
                'frost' => '48 inches',
                'zone' => '5b',
                'conservation' => 'Credit Valley Conservation (CVC)',
                'permit_auth' => 'Town of Halton Hills Building Services',
                'snowfall' => '140 cm',
                'terrain' => 'Credit River valley and small-town community',
                'neighborhoods' => 'Georgetown Downtown, Hungry Hollow, Glen Williams, and Silver Creek',
            ],
            'Brampton' => [
                'region' => 'Peel Region',
                'soil' => 'heavy Halton Till clay with high shrink-swell potential',
                'frost' => '48 inches',
                'zone' => '5b',
                'conservation' => 'Credit Valley Conservation (CVC) / TRCA',
                'permit_auth' => 'City of Brampton Building Division',
                'snowfall' => '125 cm',
                'terrain' => 'rapidly expanding suburban development on clay soils',
                'neighborhoods' => 'Heart Lake, Mount Pleasant, Castlemore, and Springdale',
            ],
        ];
    }

    // Return 5 FAQ entries for a given service + city combination
    private function getFaqsForService(string $service, string $city, array $cd): array
    {
        $templates = $this->getServiceFaqTemplates();
        $serviceFaqs = $templates[$service] ?? [];

        // Replace placeholders in each FAQ
        $result = [];
        foreach ($serviceFaqs as $faq) {
            $result[] = [
                'question' => $this->replacePlaceholders($faq['question'], $city, $cd),
                'short_answer' => $this->replacePlaceholders($faq['short_answer'], $city, $cd),
                'answer' => $this->replacePlaceholders($faq['answer'], $city, $cd),
            ];
        }

        return $result;
    }

    private function replacePlaceholders(string $text, string $city, array $cd): string
    {
        return str_replace(
            ['{city}', '{soil}', '{frost}', '{zone}', '{conservation}', '{permit_auth}', '{snowfall}', '{terrain}', '{neighborhoods}', '{region}'],
            [$city, $cd['soil'], $cd['frost'], $cd['zone'], $cd['conservation'], $cd['permit_auth'], $cd['snowfall'], $cd['terrain'], $cd['neighborhoods'], $cd['region']],
            $text
        );
    }

    // 14 services x 5 FAQs each = 70 FAQ templates
    private function getServiceFaqTemplates(): array
    {
        return [

            'Interlocking Driveways' => [
                [
                    'question' => 'How much does an interlocking driveway cost in {city}?',
                    'short_answer' => 'Interlocking driveway costs in {city} typically range from $22 to $38 per square foot installed, depending on paver selection, pattern complexity, and site conditions. Properties with {soil} may require additional base preparation, which affects the total project cost. We provide detailed, itemized scope plans after an on-site assessment of your specific lot.',
                    'answer' => 'Interlocking driveway costs in {city} typically range from $22 to $38 per square foot installed, depending on paver selection, pattern complexity, and site conditions. Properties with {soil} may require additional base preparation, which affects the total project cost. We provide detailed, itemized scope plans after an on-site assessment of your specific lot. Most standard two-car driveways in {city} fall between $12,000 and $25,000 including excavation, base material, pavers, and polymeric sand.',
                ],
                [
                    'question' => 'Do I need a permit for an interlocking driveway in {city}?',
                    'short_answer' => 'In {city}, you typically need a driveway entrance permit from {permit_auth} if you are modifying the curb cut or changing the driveway width. Standard resurfacing within the existing footprint usually does not require a permit. Properties near regulated watercourses may also require approval from {conservation}. We handle permit coordination as part of our project scope.',
                    'answer' => 'In {city}, you typically need a driveway entrance permit from {permit_auth} if you are modifying the curb cut or changing the driveway width. Standard resurfacing within the existing footprint usually does not require a permit. Properties near regulated watercourses may also require approval from {conservation}. We handle permit coordination as part of our project scope.',
                ],
                [
                    'question' => 'How long does an interlocking driveway installation take in {city}?',
                    'short_answer' => 'A standard two-car interlocking driveway in {city} takes 3 to 5 working days from excavation to final compaction. This includes 1 day for demolition and excavation, 1 to 2 days for base preparation and compaction, and 1 to 2 days for paver installation and finishing. Weather delays and site complexity involving {terrain} may extend the timeline.',
                    'answer' => 'A standard two-car interlocking driveway in {city} takes 3 to 5 working days from excavation to final compaction. This includes 1 day for demolition and excavation, 1 to 2 days for base preparation and compaction, and 1 to 2 days for paver installation and finishing. Weather delays and site complexity involving {terrain} may extend the timeline.',
                ],
                [
                    'question' => 'What base depth do you use for driveways in {city}?',
                    'short_answer' => 'We install a minimum 16 to 18 inch excavation depth on {city} properties to account for the {frost} frost penetration depth in this Zone {zone} climate. The base consists of compacted Granular A aggregate topped with a 1-inch HPB levelling course. On lots with {soil}, we may increase base depth or add drainage provisions to prevent heaving.',
                    'answer' => 'We install a minimum 16 to 18 inch excavation depth on {city} properties to account for the {frost} frost penetration depth in this Zone {zone} climate. The base consists of compacted Granular A aggregate topped with a 1-inch HPB levelling course. On lots with {soil}, we may increase base depth or add drainage provisions to prevent heaving.',
                ],
                [
                    'question' => 'What warranty do you provide on interlocking driveways in {city}?',
                    'short_answer' => 'We provide a 10-year workmanship warranty on all interlocking driveway installations in {city}. This covers paver settlement, base failure, and joint stability under normal residential use. Paver manufacturers such as Unilock and Techo-Bloc also provide separate lifetime material warranties. Our warranty is backed by our local presence serving {neighborhoods} and surrounding areas.',
                    'answer' => 'We provide a 10-year workmanship warranty on all interlocking driveway installations in {city}. This covers paver settlement, base failure, and joint stability under normal residential use. Paver manufacturers such as Unilock and Techo-Bloc also provide separate lifetime material warranties. Our warranty is backed by our local presence serving {neighborhoods} and surrounding areas.',
                ],
            ],

            'Interlocking Patios & Backyard Living' => [
                [
                    'question' => 'How much does an interlocking patio cost in {city}?',
                    'short_answer' => 'Interlocking patio costs in {city} range from $20 to $35 per square foot for a standard installation. Complex projects with outdoor kitchens, fire pits, seating walls, and integrated lighting can range from $35 to $60+ per square foot. The {soil} on many {city} properties may require additional drainage engineering that affects the total budget.',
                    'answer' => 'Interlocking patio costs in {city} range from $20 to $35 per square foot for a standard installation. Complex projects with outdoor kitchens, fire pits, seating walls, and integrated lighting can range from $35 to $60+ per square foot. The {soil} on many {city} properties may require additional drainage engineering that affects the total budget.',
                ],
                [
                    'question' => 'Can you build an outdoor kitchen as part of my patio in {city}?',
                    'short_answer' => 'Yes. We design and build complete outdoor kitchens integrated into interlocking patio layouts for {city} homeowners. This includes BBQ islands, countertops, storage, and utility connections. We coordinate with licensed gas, electrical, and plumbing trades as needed. Gas line permits are handled through {permit_auth} as part of our project management.',
                    'answer' => 'Yes. We design and build complete outdoor kitchens integrated into interlocking patio layouts for {city} homeowners. This includes BBQ islands, countertops, storage, and utility connections. We coordinate with licensed gas, electrical, and plumbing trades as needed. Gas line permits are handled through {permit_auth} as part of our project management.',
                ],
                [
                    'question' => 'How do you handle drainage on patios in {city}?',
                    'short_answer' => 'Proper drainage is critical on {city} properties where {soil} creates water management challenges. We engineer positive grade away from your foundation at a minimum 2 percent slope and integrate catch basins where needed. On lots where clay impermeability causes ponding, we install open-graded base systems that allow water to drain through the paver surface rather than pooling on top.',
                    'answer' => 'Proper drainage is critical on {city} properties where {soil} creates water management challenges. We engineer positive grade away from your foundation at a minimum 2 percent slope and integrate catch basins where needed. On lots where clay impermeability causes ponding, we install open-graded base systems that allow water to drain through the paver surface rather than pooling on top.',
                ],
                [
                    'question' => 'How long does a patio project take in {city}?',
                    'short_answer' => 'A standard interlocking patio in {city} takes 4 to 7 working days. Multi-level patios with features like fire pits, seating walls, and lighting can take 2 to 3 weeks depending on scope. We provide a detailed timeline during your consultation that accounts for {city}-specific factors including {terrain} and soil conditions.',
                    'answer' => 'A standard interlocking patio in {city} takes 4 to 7 working days. Multi-level patios with features like fire pits, seating walls, and lighting can take 2 to 3 weeks depending on scope. We provide a detailed timeline during your consultation that accounts for {city}-specific factors including {terrain} and soil conditions.',
                ],
                [
                    'question' => 'What patio materials work best in {city}?',
                    'short_answer' => 'For {city} patios, we recommend pavers rated at 8,000+ PSI compressive strength with proven freeze-thaw durability for Zone {zone} conditions. Popular choices include Unilock Beacon Hill Flagstone, Techo-Bloc Blu 60, and Belgard Dimensions. The right material depends on your design preference, budget, and how the space will be used. We bring samples to your on-site consultation.',
                    'answer' => 'For {city} patios, we recommend pavers rated at 8,000+ PSI compressive strength with proven freeze-thaw durability for Zone {zone} conditions. Popular choices include Unilock Beacon Hill Flagstone, Techo-Bloc Blu 60, and Belgard Dimensions. The right material depends on your design preference, budget, and how the space will be used. We bring samples to your on-site consultation.',
                ],
            ],

            'Walkways & Steps' => [
                [
                    'question' => 'How much do interlocking walkways and steps cost in {city}?',
                    'short_answer' => 'Interlocking walkway costs in {city} typically range from $25 to $40 per square foot, depending on width, pattern, and material selection. Steps with reinforced concrete cores and paver or stone cladding add $800 to $2,500 per set depending on width and riser count. Properties with significant grade changes common in {terrain} may require additional structural work.',
                    'answer' => 'Interlocking walkway costs in {city} typically range from $25 to $40 per square foot, depending on width, pattern, and material selection. Steps with reinforced concrete cores and paver or stone cladding add $800 to $2,500 per set depending on width and riser count. Properties with significant grade changes common in {terrain} may require additional structural work.',
                ],
                [
                    'question' => 'Are your walkway steps OBC-compliant in {city}?',
                    'short_answer' => 'Yes. All step installations in {city} follow Ontario Building Code requirements for riser height (maximum 200mm), tread depth (minimum 235mm), and handrail placement where required. We install non-slip tread surfaces and landing pads at code-required intervals. Steps use reinforced concrete cores anchored to footings that extend below the {frost} frost line.',
                    'answer' => 'Yes. All step installations in {city} follow Ontario Building Code requirements for riser height (maximum 200mm), tread depth (minimum 235mm), and handrail placement where required. We install non-slip tread surfaces and landing pads at code-required intervals. Steps use reinforced concrete cores anchored to footings that extend below the {frost} frost line.',
                ],
                [
                    'question' => 'How do you prevent walkway heaving in {city} winters?',
                    'short_answer' => 'Walkway heaving in {city} is primarily caused by frost action in {soil}. We prevent it with a compacted aggregate base that extends below the frost line, proper drainage grading to prevent water accumulation under the surface, and polymeric sand joints that resist moisture infiltration. Edge restraint is anchored below grade to prevent lateral paver migration during freeze-thaw cycles.',
                    'answer' => 'Walkway heaving in {city} is primarily caused by frost action in {soil}. We prevent it with a compacted aggregate base that extends below the frost line, proper drainage grading to prevent water accumulation under the surface, and polymeric sand joints that resist moisture infiltration. Edge restraint is anchored below grade to prevent lateral paver migration during freeze-thaw cycles.',
                ],
                [
                    'question' => 'Can you match my existing driveway pavers for the walkway?',
                    'short_answer' => 'In most cases, yes. We work with all major paver manufacturers including Unilock, Techo-Bloc, and Belgard and can source matching or complementary colours and profiles. If your existing pavers have weathered significantly, we can also recommend accent materials that complement the aged tones. We bring samples from our {city} supply partners to your consultation for side-by-side comparison.',
                    'answer' => 'In most cases, yes. We work with all major paver manufacturers including Unilock, Techo-Bloc, and Belgard and can source matching or complementary colours and profiles. If your existing pavers have weathered significantly, we can also recommend accent materials that complement the aged tones. We bring samples from our {city} supply partners to your consultation for side-by-side comparison.',
                ],
                [
                    'question' => 'How long does a walkway installation take in {city}?',
                    'short_answer' => 'A typical front-entry walkway with steps in {city} takes 2 to 4 working days. This includes demolition of the existing surface, excavation and base preparation, step construction, paver installation, and finishing. Garden walkways without steps are usually completed in 1 to 2 days. We work efficiently to minimize disruption to your daily access.',
                    'answer' => 'A typical front-entry walkway with steps in {city} takes 2 to 4 working days. This includes demolition of the existing surface, excavation and base preparation, step construction, paver installation, and finishing. Garden walkways without steps are usually completed in 1 to 2 days. We work efficiently to minimize disruption to your daily access.',
                ],
            ],

            'Natural Stone & Flagstone' => [
                [
                    'question' => 'What types of natural stone do you install in {city}?',
                    'short_answer' => 'We work with Ontario-quarried Eramosa limestone, Algonquin flagstone, Muskoka granite, Owen Sound ledgerock, and Wiarton bluestone. We also install imported materials including Muskoka granite and premium European stone on request. Every stone is selected for colour consistency, thickness uniformity, and structural integrity before it reaches your {city} property.',
                    'answer' => 'We work with Ontario-quarried Eramosa limestone, Algonquin flagstone, Muskoka granite, Owen Sound ledgerock, and Wiarton bluestone. We also install imported materials including Muskoka granite and premium European stone on request. Every stone is selected for colour consistency, thickness uniformity, and structural integrity before it reaches your {city} property.',
                ],
                [
                    'question' => 'Is flagstone or interlocking better for my {city} patio?',
                    'short_answer' => 'Both perform well in {city} when installed correctly. Flagstone offers a natural, organic aesthetic that interlocking pavers cannot replicate, while interlocking provides more uniform surfaces and broader colour selection. Flagstone requires more maintenance over time as natural movement in {soil} can shift individual pieces. Interlocking is generally more cost-effective for large areas. We can help you choose during your consultation.',
                    'answer' => 'Both perform well in {city} when installed correctly. Flagstone offers a natural, organic aesthetic that interlocking pavers cannot replicate, while interlocking provides more uniform surfaces and broader colour selection. Flagstone requires more maintenance over time as natural movement in {soil} can shift individual pieces. Interlocking is generally more cost-effective for large areas. We can help you choose during your consultation.',
                ],
                [
                    'question' => 'How much does a natural stone patio cost in {city}?',
                    'short_answer' => 'Natural stone patio costs in {city} range from $30 to $55 per square foot depending on the stone type, installation method, and site preparation required. Flagstone patios are typically $30 to $45 per square foot, while armour stone features range from $150 to $400 per linear foot. The {soil} on {city} properties requires careful base preparation that factors into the total cost.',
                    'answer' => 'Natural stone patio costs in {city} range from $30 to $55 per square foot depending on the stone type, installation method, and site preparation required. Flagstone patios are typically $30 to $45 per square foot, while armour stone features range from $150 to $400 per linear foot. The {soil} on {city} properties requires careful base preparation that factors into the total cost.',
                ],
                [
                    'question' => 'Do you dry-lay or mortar-set flagstone in {city}?',
                    'short_answer' => 'We use both methods depending on the application. Dry-laid flagstone on compacted aggregate base works well for garden patios and walkways where slight natural movement is acceptable. Mortar-set installations on concrete slab are recommended for high-traffic areas, pool surrounds, and locations where a perfectly level surface is required. In {city}, where {soil} is prone to seasonal movement, the base specification is critical regardless of the setting method.',
                    'answer' => 'We use both methods depending on the application. Dry-laid flagstone on compacted aggregate base works well for garden patios and walkways where slight natural movement is acceptable. Mortar-set installations on concrete slab are recommended for high-traffic areas, pool surrounds, and locations where a perfectly level surface is required. In {city}, where {soil} is prone to seasonal movement, the base specification is critical regardless of the setting method.',
                ],
                [
                    'question' => 'How do you maintain natural stone in {city} winters?',
                    'short_answer' => 'Natural stone is inherently durable in {city} winters, but some care helps extend its life. Avoid calcium chloride de-icers on limestone surfaces, as they can cause pitting. Use sand or potassium-based products instead. Seal porous stones like sandstone before the first winter to prevent water absorption and spalling. We provide a maintenance guide specific to your stone type and {city} conditions after every installation.',
                    'answer' => 'Natural stone is inherently durable in {city} winters, but some care helps extend its life. Avoid calcium chloride de-icers on limestone surfaces, as they can cause pitting. Use sand or potassium-based products instead. Seal porous stones like sandstone before the first winter to prevent water absorption and spalling. We provide a maintenance guide specific to your stone type and {city} conditions after every installation.',
                ],
            ],

            'Porcelain Pavers' => [
                [
                    'question' => 'Are porcelain pavers frost-proof for {city} winters?',
                    'short_answer' => 'Yes. The 20mm porcelain pavers we install have zero water absorption (less than 0.5%), which makes them immune to freeze-thaw damage. Unlike natural stone or concrete pavers that absorb moisture and can spall after repeated freezing, porcelain pavers maintain their surface integrity through the {snowfall} of annual snowfall that {city} receives. They are certified to ASTM C1026 freeze-thaw standards.',
                    'answer' => 'Yes. The 20mm porcelain pavers we install have zero water absorption (less than 0.5%), which makes them immune to freeze-thaw damage. Unlike natural stone or concrete pavers that absorb moisture and can spall after repeated freezing, porcelain pavers maintain their surface integrity through the {snowfall} of annual snowfall that {city} receives. They are certified to ASTM C1026 freeze-thaw standards.',
                ],
                [
                    'question' => 'How are porcelain pavers installed in {city}?',
                    'short_answer' => 'We use two methods depending on the application. Ground-level patios use a compacted aggregate base with levelling screed and open-graded joint material, similar to traditional interlocking but with tighter tolerances. Rooftop terraces and elevated surfaces use adjustable pedestal systems that accommodate drainage underneath. Both methods work on {city} properties with {soil}.',
                    'answer' => 'We use two methods depending on the application. Ground-level patios use a compacted aggregate base with levelling screed and open-graded joint material, similar to traditional interlocking but with tighter tolerances. Rooftop terraces and elevated surfaces use adjustable pedestal systems that accommodate drainage underneath. Both methods work on {city} properties with {soil}.',
                ],
                [
                    'question' => 'Are porcelain pavers slippery when wet?',
                    'short_answer' => 'No. The 20mm porcelain pavers we install carry an R11 slip rating, which exceeds the requirements for residential outdoor use. The textured surface provides reliable traction even when wet from rain or snowmelt. This makes them an excellent choice for pool surrounds, walkways, and covered patios in {city} where wet conditions are common during spring and fall.',
                    'answer' => 'No. The 20mm porcelain pavers we install carry an R11 slip rating, which exceeds the requirements for residential outdoor use. The textured surface provides reliable traction even when wet from rain or snowmelt. This makes them an excellent choice for pool surrounds, walkways, and covered patios in {city} where wet conditions are common during spring and fall.',
                ],
                [
                    'question' => 'How much do porcelain pavers cost in {city}?',
                    'short_answer' => 'Porcelain paver installations in {city} typically range from $30 to $50 per square foot, depending on the paver style, installation method, and site conditions. Pedestal systems for elevated applications cost more than ground-level installations. While the material cost is higher than standard interlocking pavers, the zero-maintenance and superior longevity often make porcelain the more cost-effective choice over time.',
                    'answer' => 'Porcelain paver installations in {city} typically range from $30 to $50 per square foot, depending on the paver style, installation method, and site conditions. Pedestal systems for elevated applications cost more than ground-level installations. While the material cost is higher than standard interlocking pavers, the zero-maintenance and superior longevity often make porcelain the more cost-effective choice over time.',
                ],
                [
                    'question' => 'Can porcelain pavers be used around pools in {city}?',
                    'short_answer' => 'Absolutely. Porcelain pavers are one of the best materials for pool surrounds in {city}. Their zero water absorption means they will not stain from pool chemicals, their R11 slip rating provides safe footing on wet surfaces, and they stay cooler underfoot than dark-coloured natural stone or concrete. We install pool coping profiles and drainage channels as part of every pool surround project.',
                    'answer' => 'Absolutely. Porcelain pavers are one of the best materials for pool surrounds in {city}. Their zero water absorption means they will not stain from pool chemicals, their R11 slip rating provides safe footing on wet surfaces, and they stay cooler underfoot than dark-coloured natural stone or concrete. We install pool coping profiles and drainage channels as part of every pool surround project.',
                ],
            ],

            'Concrete Driveways' => [
                [
                    'question' => 'How much does a concrete driveway cost in {city}?',
                    'short_answer' => 'Concrete driveway costs in {city} range from $12 to $25 per square foot depending on the finish. Broom-finish is the most affordable, exposed aggregate falls in the middle range, and stamped concrete with colour hardener is at the higher end. Most two-car driveways in {city} cost between $8,000 and $18,000 fully installed including excavation, base preparation, and forming.',
                    'answer' => 'Concrete driveway costs in {city} range from $12 to $25 per square foot depending on the finish. Broom-finish is the most affordable, exposed aggregate falls in the middle range, and stamped concrete with colour hardener is at the higher end. Most two-car driveways in {city} cost between $8,000 and $18,000 fully installed including excavation, base preparation, and forming.',
                ],
                [
                    'question' => 'What concrete mix do you use for {city} driveways?',
                    'short_answer' => 'We use 32 MPa air-entrained concrete with 5 to 7 percent air content, which is the Ontario specification for freeze-thaw durability. Every pour includes 10M rebar on 400mm centres and fibre-mesh reinforcement. The air entrainment creates microscopic voids that allow the concrete to expand during freezing without cracking, critical for {city} properties that endure {snowfall} of snowfall annually.',
                    'answer' => 'We use 32 MPa air-entrained concrete with 5 to 7 percent air content, which is the Ontario specification for freeze-thaw durability. Every pour includes 10M rebar on 400mm centres and fibre-mesh reinforcement. The air entrainment creates microscopic voids that allow the concrete to expand during freezing without cracking, critical for {city} properties that endure {snowfall} of snowfall annually.',
                ],
                [
                    'question' => 'How long before I can drive on a new concrete driveway in {city}?',
                    'short_answer' => 'We recommend keeping vehicles off a new concrete driveway for a minimum of 7 days after the pour. Full cure takes approximately 28 days, during which the concrete reaches its rated 32 MPa strength. Foot traffic is safe after 24 to 48 hours. In cooler {city} weather during spring and fall, curing times may extend slightly.',
                    'answer' => 'We recommend keeping vehicles off a new concrete driveway for a minimum of 7 days after the pour. Full cure takes approximately 28 days, during which the concrete reaches its rated 32 MPa strength. Foot traffic is safe after 24 to 48 hours. In cooler {city} weather during spring and fall, curing times may extend slightly.',
                ],
                [
                    'question' => 'Will a concrete driveway crack in {city}?',
                    'short_answer' => 'Controlled cracking is managed through properly spaced control joints cut into the slab at calculated intervals. These joints direct any shrinkage cracking to occur along predetermined lines where it remains invisible. On {city} properties with {soil}, proper sub-base preparation is the most critical factor in preventing uncontrolled cracking from soil movement and frost heave.',
                    'answer' => 'Controlled cracking is managed through properly spaced control joints cut into the slab at calculated intervals. These joints direct any shrinkage cracking to occur along predetermined lines where it remains invisible. On {city} properties with {soil}, proper sub-base preparation is the most critical factor in preventing uncontrolled cracking from soil movement and frost heave.',
                ],
                [
                    'question' => 'What decorative concrete finishes do you offer in {city}?',
                    'short_answer' => 'We offer broom-finish, exposed aggregate, stamped patterns (ashlar slate, cobblestone, European fan, wood-plank), and acid-stain decorative finishes for {city} driveways. Colour hardener and release agents create multi-toned, natural-looking surfaces. Exposed aggregate reveals the natural stone aggregate within the mix for a textured, slip-resistant finish. We bring sample boards to your consultation so you can see finishes in person.',
                    'answer' => 'We offer broom-finish, exposed aggregate, stamped patterns (ashlar slate, cobblestone, European fan, wood-plank), and acid-stain decorative finishes for {city} driveways. Colour hardener and release agents create multi-toned, natural-looking surfaces. Exposed aggregate reveals the natural stone aggregate within the mix for a textured, slip-resistant finish. We bring sample boards to your consultation so you can see finishes in person.',
                ],
            ],

            'Concrete Patios & Walkways' => [
                [
                    'question' => 'How much does a stamped concrete patio cost in {city}?',
                    'short_answer' => 'Stamped concrete patio costs in {city} range from $16 to $30 per square foot, depending on pattern complexity, colour selection, and site preparation. A typical 300 to 400 square foot backyard patio runs $5,000 to $12,000 fully installed. Plain broom-finish concrete is more affordable at $10 to $16 per square foot. The {soil} on {city} lots may require additional base work.',
                    'answer' => 'Stamped concrete patio costs in {city} range from $16 to $30 per square foot, depending on pattern complexity, colour selection, and site preparation. A typical 300 to 400 square foot backyard patio runs $5,000 to $12,000 fully installed. Plain broom-finish concrete is more affordable at $10 to $16 per square foot. The {soil} on {city} lots may require additional base work.',
                ],
                [
                    'question' => 'Is stamped concrete durable enough for {city} winters?',
                    'short_answer' => 'Yes, when installed with the correct specifications. We use 32 MPa air-entrained concrete that is designed for Ontario freeze-thaw conditions. The stamped surface receives colour hardener that densifies the top layer and a UV-resistant sealer that protects against salt damage and moisture penetration. Properly installed stamped concrete performs as well as any paver system through {city} winters.',
                    'answer' => 'Yes, when installed with the correct specifications. We use 32 MPa air-entrained concrete that is designed for Ontario freeze-thaw conditions. The stamped surface receives colour hardener that densifies the top layer and a UV-resistant sealer that protects against salt damage and moisture penetration. Properly installed stamped concrete performs as well as any paver system through {city} winters.',
                ],
                [
                    'question' => 'What stamped concrete patterns are popular in {city}?',
                    'short_answer' => 'The most popular stamped concrete patterns in {city} are ashlar slate, random stone, and European fan for traditional homes, and wood-plank and large-format tile patterns for contemporary properties. Cobblestone patterns work well on walkways and smaller accents. We apply colour hardener and release agents to create multi-toned, natural-looking finishes that complement your home architecture.',
                    'answer' => 'The most popular stamped concrete patterns in {city} are ashlar slate, random stone, and European fan for traditional homes, and wood-plank and large-format tile patterns for contemporary properties. Cobblestone patterns work well on walkways and smaller accents. We apply colour hardener and release agents to create multi-toned, natural-looking finishes that complement your home architecture.',
                ],
                [
                    'question' => 'How long does a concrete patio last in {city}?',
                    'short_answer' => 'A properly installed concrete patio in {city} can last 25 to 30 years or more with routine maintenance. The key factors are correct mix design (32 MPa air-entrained), proper sub-base preparation for {soil}, adequate control joints, and periodic resealing every 2 to 3 years for stamped or exposed aggregate finishes. We provide a maintenance guide with every project.',
                    'answer' => 'A properly installed concrete patio in {city} can last 25 to 30 years or more with routine maintenance. The key factors are correct mix design (32 MPa air-entrained), proper sub-base preparation for {soil}, adequate control joints, and periodic resealing every 2 to 3 years for stamped or exposed aggregate finishes. We provide a maintenance guide with every project.',
                ],
                [
                    'question' => 'Can you pour a concrete patio next to my house in {city}?',
                    'short_answer' => 'Yes. We install expansion joints where the patio meets your house foundation to allow independent movement between the two structures. Positive drainage grade slopes away from the foundation at a minimum 2 percent slope to prevent water from pooling against your home. On {city} properties where {soil} is present, this drainage detail is especially important to protect your basement from moisture intrusion.',
                    'answer' => 'Yes. We install expansion joints where the patio meets your house foundation to allow independent movement between the two structures. Positive drainage grade slopes away from the foundation at a minimum 2 percent slope to prevent water from pooling against your home. On {city} properties where {soil} is present, this drainage detail is especially important to protect your basement from moisture intrusion.',
                ],
            ],

            'Interlock Restoration & Sealing' => [
                [
                    'question' => 'How much does interlock restoration cost in {city}?',
                    'short_answer' => 'Interlock restoration and sealing in {city} typically costs $4 to $8 per square foot, depending on the condition of the existing surface and the sealer type selected. A standard two-car driveway restoration runs $2,500 to $5,500 including pressure washing, polymeric sand replacement, and sealer application. This is significantly less expensive than full replacement.',
                    'answer' => 'Interlock restoration and sealing in {city} typically costs $4 to $8 per square foot, depending on the condition of the existing surface and the sealer type selected. A standard two-car driveway restoration runs $2,500 to $5,500 including pressure washing, polymeric sand replacement, and sealer application. This is significantly less expensive than full replacement.',
                ],
                [
                    'question' => 'What does your interlock restoration process involve?',
                    'short_answer' => 'Our {city} interlock restoration is a 3-day process. Day one: hot-water pressure washing at 3,000+ PSI to remove dirt, moss, algae, and efflorescence. Day two: drying time followed by polymeric sand application to all joints with proper activation. Day three: UV-resistant sealer application in your choice of matte, satin, or wet-look finish. The sealer locks in colour and prevents future staining.',
                    'answer' => 'Our {city} interlock restoration is a 3-day process. Day one: hot-water pressure washing at 3,000+ PSI to remove dirt, moss, algae, and efflorescence. Day two: drying time followed by polymeric sand application to all joints with proper activation. Day three: UV-resistant sealer application in your choice of matte, satin, or wet-look finish. The sealer locks in colour and prevents future staining.',
                ],
                [
                    'question' => 'How often should I seal my interlocking pavers in {city}?',
                    'short_answer' => 'We recommend resealing interlocking pavers in {city} every 3 to 5 years, depending on the sealer type and traffic level. Driveways that see daily vehicle traffic may benefit from resealing every 3 years, while backyard patios with lighter use can go 4 to 5 years between applications. The {snowfall} of annual snowfall and road salt exposure in {city} accelerate sealer degradation on front surfaces.',
                    'answer' => 'We recommend resealing interlocking pavers in {city} every 3 to 5 years, depending on the sealer type and traffic level. Driveways that see daily vehicle traffic may benefit from resealing every 3 years, while backyard patios with lighter use can go 4 to 5 years between applications. The {snowfall} of annual snowfall and road salt exposure in {city} accelerate sealer degradation on front surfaces.',
                ],
                [
                    'question' => 'Can restoration fix sunken or uneven pavers in {city}?',
                    'short_answer' => 'Restoration and sealing is designed for surfaces that are structurally sound but visually faded. If your pavers are sunken, heaving, or significantly uneven, you need a lift-and-relay repair to correct the base issue before any sealing. We assess the structural condition of your {city} pavers during the initial consultation and recommend the appropriate service.',
                    'answer' => 'Restoration and sealing is designed for surfaces that are structurally sound but visually faded. If your pavers are sunken, heaving, or significantly uneven, you need a lift-and-relay repair to correct the base issue before any sealing. We assess the structural condition of your {city} pavers during the initial consultation and recommend the appropriate service.',
                ],
                [
                    'question' => 'What sealer finishes do you offer in {city}?',
                    'short_answer' => 'We offer three sealer finishes for {city} homeowners: matte (no sheen change, natural appearance), satin (subtle enhancement of paver colour), and wet-look (high-gloss enhancement that deepens colours). All options are UV-resistant and protect against staining, efflorescence, and weed growth. The best choice depends on your aesthetic preference and we can show samples at your property.',
                    'answer' => 'We offer three sealer finishes for {city} homeowners: matte (no sheen change, natural appearance), satin (subtle enhancement of paver colour), and wet-look (high-gloss enhancement that deepens colours). All options are UV-resistant and protect against staining, efflorescence, and weed growth. The best choice depends on your aesthetic preference and we can show samples at your property.',
                ],
            ],

            'Interlock Repair (Lift & Relay)' => [
                [
                    'question' => 'How much does interlock repair cost in {city}?',
                    'short_answer' => 'Lift-and-relay repair in {city} typically costs $8 to $18 per square foot for the affected area, depending on the extent of damage and root cause. Most residential repairs cover 50 to 200 square feet and run $1,500 to $4,000. This is significantly less than full driveway replacement and preserves your existing pavers and their naturally weathered appearance.',
                    'answer' => 'Lift-and-relay repair in {city} typically costs $8 to $18 per square foot for the affected area, depending on the extent of damage and root cause. Most residential repairs cover 50 to 200 square feet and run $1,500 to $4,000. This is significantly less than full driveway replacement and preserves your existing pavers and their naturally weathered appearance.',
                ],
                [
                    'question' => 'Why are my interlocking pavers sinking in {city}?',
                    'short_answer' => 'The most common cause of sinking pavers in {city} is inadequate base preparation, particularly insufficient excavation depth or poor compaction on lots with {soil}. Other causes include tree root displacement, downspout water erosion under the base, and subsurface drainage failures. Our repair process identifies the specific cause on your property and corrects it permanently rather than just relevelling the surface.',
                    'answer' => 'The most common cause of sinking pavers in {city} is inadequate base preparation, particularly insufficient excavation depth or poor compaction on lots with {soil}. Other causes include tree root displacement, downspout water erosion under the base, and subsurface drainage failures. Our repair process identifies the specific cause on your property and corrects it permanently rather than just relevelling the surface.',
                ],
                [
                    'question' => 'Will the repaired area match the rest of my pavers?',
                    'short_answer' => 'Yes. Lift-and-relay uses your original pavers, so the colour and texture match is inherent. After years of weathering, your pavers have developed a natural patina that new replacement pavers cannot replicate. By carefully removing and re-laying the existing material, the repair blends seamlessly with the surrounding undisturbed surface on your {city} property.',
                    'answer' => 'Yes. Lift-and-relay uses your original pavers, so the colour and texture match is inherent. After years of weathering, your pavers have developed a natural patina that new replacement pavers cannot replicate. By carefully removing and re-laying the existing material, the repair blends seamlessly with the surrounding undisturbed surface on your {city} property.',
                ],
                [
                    'question' => 'How long does a lift-and-relay repair take in {city}?',
                    'short_answer' => 'Most lift-and-relay repairs in {city} are completed in 1 to 2 working days depending on the area involved. Small repairs under 100 square feet can often be completed in a single day. The process includes paver removal, base excavation, HPB aggregate installation and compaction, paver re-laying, new polymeric sand application, and final compaction.',
                    'answer' => 'Most lift-and-relay repairs in {city} are completed in 1 to 2 working days depending on the area involved. Small repairs under 100 square feet can often be completed in a single day. The process includes paver removal, base excavation, HPB aggregate installation and compaction, paver re-laying, new polymeric sand application, and final compaction.',
                ],
                [
                    'question' => 'Do you warranty lift-and-relay repairs in {city}?',
                    'short_answer' => 'Yes. We warranty all lift-and-relay repairs in {city} for the repaired area. The warranty covers base settlement and joint stability under normal residential use. Because we diagnose and correct the root cause of the original failure, whether that is base inadequacy, drainage issues, or root intrusion common on {city} properties, the repair is designed to be permanent.',
                    'answer' => 'Yes. We warranty all lift-and-relay repairs in {city} for the repaired area. The warranty covers base settlement and joint stability under normal residential use. Because we diagnose and correct the root cause of the original failure, whether that is base inadequacy, drainage issues, or root intrusion common on {city} properties, the repair is designed to be permanent.',
                ],
            ],

            'Retaining Walls' => [
                [
                    'question' => 'Do I need a permit for a retaining wall in {city}?',
                    'short_answer' => 'In {city}, retaining walls over 1.0 metre in retained height require a building permit from {permit_auth}. Walls near watercourses or within regulated areas also require approval from {conservation}. Engineering drawings are required for walls over 1.0 metre. We coordinate all permitting requirements as part of our project scope so you do not have to manage the approval process yourself.',
                    'answer' => 'In {city}, retaining walls over 1.0 metre in retained height require a building permit from {permit_auth}. Walls near watercourses or within regulated areas also require approval from {conservation}. Engineering drawings are required for walls over 1.0 metre. We coordinate all permitting requirements as part of our project scope so you do not have to manage the approval process yourself.',
                ],
                [
                    'question' => 'What retaining wall materials do you use in {city}?',
                    'short_answer' => 'We build retaining walls in {city} using three primary materials: armour stone (natural Ontario-quarried boulders from 1,000 to 4,000 pounds), precast concrete blocks (Allan Block, Cornerstone, Unilock), and poured-in-place reinforced concrete. The best material depends on the wall height, retained load, aesthetic preference, and budget. Armour stone offers a natural look while concrete block provides engineered precision.',
                    'answer' => 'We build retaining walls in {city} using three primary materials: armour stone (natural Ontario-quarried boulders from 1,000 to 4,000 pounds), precast concrete blocks (Allan Block, Cornerstone, Unilock), and poured-in-place reinforced concrete. The best material depends on the wall height, retained load, aesthetic preference, and budget. Armour stone offers a natural look while concrete block provides engineered precision.',
                ],
                [
                    'question' => 'How much does a retaining wall cost in {city}?',
                    'short_answer' => 'Retaining wall costs in {city} vary significantly by material and height. Armour stone walls range from $200 to $450 per linear foot. Precast concrete block walls range from $150 to $350 per linear foot. Walls over 1.0 metre that require engineering and geogrid reinforcement cost more due to the design and permitting requirements. We provide detailed scope plans after assessing your {city} property.',
                    'answer' => 'Retaining wall costs in {city} vary significantly by material and height. Armour stone walls range from $200 to $450 per linear foot. Precast concrete block walls range from $150 to $350 per linear foot. Walls over 1.0 metre that require engineering and geogrid reinforcement cost more due to the design and permitting requirements. We provide detailed scope plans after assessing your {city} property.',
                ],
                [
                    'question' => 'What drainage do retaining walls need in {city}?',
                    'short_answer' => 'Every retaining wall in {city} requires clear stone backfill, filter fabric to prevent soil migration, and weeping tile connected to a storm outlet or daylight point. On {city} properties with {soil}, drainage is especially critical because the clay retains moisture and creates hydrostatic pressure behind the wall. Without proper drainage, even a well-built wall can fail from water pressure.',
                    'answer' => 'Every retaining wall in {city} requires clear stone backfill, filter fabric to prevent soil migration, and weeping tile connected to a storm outlet or daylight point. On {city} properties with {soil}, drainage is especially critical because the clay retains moisture and creates hydrostatic pressure behind the wall. Without proper drainage, even a well-built wall can fail from water pressure.',
                ],
                [
                    'question' => 'How long does retaining wall construction take in {city}?',
                    'short_answer' => 'A typical residential retaining wall in {city} takes 3 to 7 working days depending on length, height, and material. Armour stone walls generally install faster than concrete block walls that require geogrid and backfill in layers. Walls requiring engineering approval from {permit_auth} may add 2 to 4 weeks of lead time for permit processing before construction begins.',
                    'answer' => 'A typical residential retaining wall in {city} takes 3 to 7 working days depending on length, height, and material. Armour stone walls generally install faster than concrete block walls that require geogrid and backfill in layers. Walls requiring engineering approval from {permit_auth} may add 2 to 4 weeks of lead time for permit processing before construction begins.',
                ],
            ],

            'Sod Installation & Grading' => [
                [
                    'question' => 'How much does sod installation cost in {city}?',
                    'short_answer' => 'Sod installation in {city} typically costs $3 to $6 per square foot including topsoil, grading, and Kentucky Bluegrass sod. Full lawn renovation that includes subgrade correction, drainage grading, 4 to 6 inches of Triple-Mix, and premium sod ranges from $5 to $8 per square foot. Larger lots in {neighborhoods} often benefit from volume pricing on material delivery.',
                    'answer' => 'Sod installation in {city} typically costs $3 to $6 per square foot including topsoil, grading, and Kentucky Bluegrass sod. Full lawn renovation that includes subgrade correction, drainage grading, 4 to 6 inches of Triple-Mix, and premium sod ranges from $5 to $8 per square foot. Larger lots in {neighborhoods} often benefit from volume pricing on material delivery.',
                ],
                [
                    'question' => 'When is the best time to install sod in {city}?',
                    'short_answer' => 'The ideal sod installation window in {city} is mid-May through mid-October, with spring (May to June) and early fall (September to early October) being the best periods. Summer installations are possible but require more intensive watering during the root establishment period. We avoid installing sod during the dormant season as root establishment requires active growth conditions in Zone {zone}.',
                    'answer' => 'The ideal sod installation window in {city} is mid-May through mid-October, with spring (May to June) and early fall (September to early October) being the best periods. Summer installations are possible but require more intensive watering during the root establishment period. We avoid installing sod during the dormant season as root establishment requires active growth conditions in Zone {zone}.',
                ],
                [
                    'question' => 'Why is grading important before sod in {city}?',
                    'short_answer' => 'Proper grading in {city} prevents water from pooling against your foundation and ensures even moisture distribution across your lawn. On {city} properties with {soil}, poor grading causes standing water that drowns grass roots and creates muddy patches. We grade to a minimum 2 percent slope away from your foundation for the first 6 feet, then transition to a gentler slope across the yard.',
                    'answer' => 'Proper grading in {city} prevents water from pooling against your foundation and ensures even moisture distribution across your lawn. On {city} properties with {soil}, poor grading causes standing water that drowns grass roots and creates muddy patches. We grade to a minimum 2 percent slope away from your foundation for the first 6 feet, then transition to a gentler slope across the yard.',
                ],
                [
                    'question' => 'What type of sod do you install in {city}?',
                    'short_answer' => 'We install premium Kentucky Bluegrass sod in {city}, which is the standard lawn species for Zone {zone} climates. Kentucky Bluegrass is self-repairing through underground rhizomes, tolerates moderate shade, and maintains a dense, dark-green appearance through the growing season. All sod is laid the same day it is cut from the farm to ensure maximum root viability.',
                    'answer' => 'We install premium Kentucky Bluegrass sod in {city}, which is the standard lawn species for Zone {zone} climates. Kentucky Bluegrass is self-repairing through underground rhizomes, tolerates moderate shade, and maintains a dense, dark-green appearance through the growing season. All sod is laid the same day it is cut from the farm to ensure maximum root viability.',
                ],
                [
                    'question' => 'How long does sod take to root in {city}?',
                    'short_answer' => 'Sod typically establishes initial root connection in 10 to 14 days in {city} when our watering schedule is followed. Full root establishment takes 4 to 6 weeks during the active growing season. We recommend staying off the sod for the first 2 weeks and beginning mowing after 3 weeks at a high setting. We provide detailed care instructions specific to {city} conditions with every installation.',
                    'answer' => 'Sod typically establishes initial root connection in 10 to 14 days in {city} when our watering schedule is followed. Full root establishment takes 4 to 6 weeks during the active growing season. We recommend staying off the sod for the first 2 weeks and beginning mowing after 3 weeks at a high setting. We provide detailed care instructions specific to {city} conditions with every installation.',
                ],
            ],

            'Artificial Turf' => [
                [
                    'question' => 'How much does artificial turf cost in {city}?',
                    'short_answer' => 'Artificial turf installation in {city} typically costs $12 to $22 per square foot fully installed, including excavation, aggregate base, geotextile fabric, turf, and infill. Pet-friendly installations with antimicrobial infill cost slightly more. Putting greens range from $18 to $30 per square foot due to specialized turf profiles and contouring work. We provide exact pricing after an on-site assessment.',
                    'answer' => 'Artificial turf installation in {city} typically costs $12 to $22 per square foot fully installed, including excavation, aggregate base, geotextile fabric, turf, and infill. Pet-friendly installations with antimicrobial infill cost slightly more. Putting greens range from $18 to $30 per square foot due to specialized turf profiles and contouring work. We provide exact pricing after an on-site assessment.',
                ],
                [
                    'question' => 'How long does artificial turf last in {city}?',
                    'short_answer' => 'Premium artificial turf installed in {city} is rated to last 15 to 20 years with proper maintenance. UV stabilization prevents colour fading even through intense summer sun, and the materials are engineered to withstand the {snowfall} of annual snowfall and freeze-thaw cycling that {city} experiences. After 15+ years, the turf can be replaced without disturbing the base.',
                    'answer' => 'Premium artificial turf installed in {city} is rated to last 15 to 20 years with proper maintenance. UV stabilization prevents colour fading even through intense summer sun, and the materials are engineered to withstand the {snowfall} of annual snowfall and freeze-thaw cycling that {city} experiences. After 15+ years, the turf can be replaced without disturbing the base.',
                ],
                [
                    'question' => 'Is artificial turf safe for pets in {city}?',
                    'short_answer' => 'Yes. Our pet-friendly artificial turf installations in {city} use antimicrobial infill that prevents bacterial growth and eliminates odour. The turf achieves drainage rates exceeding 30 inches per hour, which is especially important on {city} properties with {soil} that would otherwise trap moisture. Solid waste is easily removed from the surface and the turf can be rinsed with a garden hose.',
                    'answer' => 'Yes. Our pet-friendly artificial turf installations in {city} use antimicrobial infill that prevents bacterial growth and eliminates odour. The turf achieves drainage rates exceeding 30 inches per hour, which is especially important on {city} properties with {soil} that would otherwise trap moisture. Solid waste is easily removed from the surface and the turf can be rinsed with a garden hose.',
                ],
                [
                    'question' => 'Does artificial turf drain properly in {city}?',
                    'short_answer' => 'When properly installed, yes. Our {city} installations use a compacted aggregate base with positive drainage grade and geotextile separation fabric that prevents the underlying {soil} from migrating into the base layer. The turf backing is perforated to allow water to pass through at 30+ inches per hour. This engineered drainage prevents the standing water that natural clay soils would otherwise create.',
                    'answer' => 'When properly installed, yes. Our {city} installations use a compacted aggregate base with positive drainage grade and geotextile separation fabric that prevents the underlying {soil} from migrating into the base layer. The turf backing is perforated to allow water to pass through at 30+ inches per hour. This engineered drainage prevents the standing water that natural clay soils would otherwise create.',
                ],
                [
                    'question' => 'Can I install artificial turf in my front yard in {city}?',
                    'short_answer' => 'Yes, artificial turf can be installed in front yards in {city}. Modern synthetic turf products look natural enough that most neighbours will not notice the difference. However, some {city} neighbourhoods may have homeowner agreements or property standards by-laws regarding front-yard materials. We recommend checking with {permit_auth} about any local restrictions before proceeding with a front-yard installation.',
                    'answer' => 'Yes, artificial turf can be installed in front yards in {city}. Modern synthetic turf products look natural enough that most neighbours will not notice the difference. However, some {city} neighbourhoods may have homeowner agreements or property standards by-laws regarding front-yard materials. We recommend checking with {permit_auth} about any local restrictions before proceeding with a front-yard installation.',
                ],
            ],

            'Garden Design & Planting' => [
                [
                    'question' => 'What plants grow well in {city}?',
                    'short_answer' => 'As a Zone {zone} municipality, {city} supports a wide range of hardy perennials, ornamental grasses, flowering shrubs, and shade trees. Popular choices include Echinacea, Black-Eyed Susan, Hostas, Hydrangeas, Karl Foerster grass, and native shrubs like Ninebark and Serviceberry. We select cultivars proven in Zone {zone} conditions and source from Ontario-accredited nurseries.',
                    'answer' => 'As a Zone {zone} municipality, {city} supports a wide range of hardy perennials, ornamental grasses, flowering shrubs, and shade trees. Popular choices include Echinacea, Black-Eyed Susan, Hostas, Hydrangeas, Karl Foerster grass, and native shrubs like Ninebark and Serviceberry. We select cultivars proven in Zone {zone} conditions and source from Ontario-accredited nurseries.',
                ],
                [
                    'question' => 'How much does garden design and planting cost in {city}?',
                    'short_answer' => 'Garden design and planting projects in {city} range from $3,000 for a foundation planting upgrade to $15,000+ for comprehensive front and backyard landscape design. Costs include design consultation, soil amendment, plant material, edging, mulch, and installation. Plant material typically represents 40 to 50 percent of the total budget, with preparation and installation making up the balance.',
                    'answer' => 'Garden design and planting projects in {city} range from $3,000 for a foundation planting upgrade to $15,000+ for comprehensive front and backyard landscape design. Costs include design consultation, soil amendment, plant material, edging, mulch, and installation. Plant material typically represents 40 to 50 percent of the total budget, with preparation and installation making up the balance.',
                ],
                [
                    'question' => 'Do you create pollinator-friendly gardens in {city}?',
                    'short_answer' => 'Yes. Pollinator gardens are one of our most requested services in {city}. We design gardens using Ontario-native flowering species that support bees, butterflies, and hummingbirds throughout the growing season. Native plants are well-adapted to {city} soil and climate conditions, require less maintenance than exotic species, and contribute to local biodiversity in {neighborhoods} and surrounding areas.',
                    'answer' => 'Yes. Pollinator gardens are one of our most requested services in {city}. We design gardens using Ontario-native flowering species that support bees, butterflies, and hummingbirds throughout the growing season. Native plants are well-adapted to {city} soil and climate conditions, require less maintenance than exotic species, and contribute to local biodiversity in {neighborhoods} and surrounding areas.',
                ],
                [
                    'question' => 'Do you guarantee the plants you install in {city}?',
                    'short_answer' => 'Yes. All plant material installed by our crews in {city} is backed by a one-year health guarantee when our watering and care instructions are followed. This covers plant failure due to nursery stock quality, improper planting technique, or root establishment issues. It does not cover damage from drought (unwatered plants), physical damage, or pest infestations beyond our control.',
                    'answer' => 'Yes. All plant material installed by our crews in {city} is backed by a one-year health guarantee when our watering and care instructions are followed. This covers plant failure due to nursery stock quality, improper planting technique, or root establishment issues. It does not cover damage from drought (unwatered plants), physical damage, or pest infestations beyond our control.',
                ],
                [
                    'question' => 'When is the best time for garden planting in {city}?',
                    'short_answer' => 'The best planting windows in {city} are spring (mid-May through June) and early fall (September through mid-October). Spring planting gives roots a full growing season to establish before winter. Fall planting takes advantage of cooler temperatures and natural rainfall that reduce transplant stress. We avoid planting during the heat of July and August unless irrigation is available on the property.',
                    'answer' => 'The best planting windows in {city} are spring (mid-May through June) and early fall (September through mid-October). Spring planting gives roots a full growing season to establish before winter. Fall planting takes advantage of cooler temperatures and natural rainfall that reduce transplant stress. We avoid planting during the heat of July and August unless irrigation is available on the property.',
                ],
            ],

            'Landscape Lighting' => [
                [
                    'question' => 'How much does landscape lighting cost in {city}?',
                    'short_answer' => 'Landscape lighting projects in {city} typically range from $3,500 for a basic path and entry package (8 to 12 fixtures) to $12,000+ for comprehensive whole-property illumination. Each brass or marine-grade aluminum fixture installed runs $250 to $600 including the transformer share, wiring, and installation. We design systems that can be expanded later without requiring transformer upgrades.',
                    'answer' => 'Landscape lighting projects in {city} typically range from $3,500 for a basic path and entry package (8 to 12 fixtures) to $12,000+ for comprehensive whole-property illumination. Each brass or marine-grade aluminum fixture installed runs $250 to $600 including the transformer share, wiring, and installation. We design systems that can be expanded later without requiring transformer upgrades.',
                ],
                [
                    'question' => 'What type of lighting fixtures do you use in {city}?',
                    'short_answer' => 'We exclusively use solid brass or marine-grade aluminum fixtures that withstand {city} freeze-thaw cycling, salt exposure, and moisture without corroding or discolouring. All fixtures use LED technology with low-voltage transformers. We avoid plastic or painted fixtures that degrade within a few years in Ontario weather. Fixture styles range from contemporary to traditional to match your home architecture.',
                    'answer' => 'We exclusively use solid brass or marine-grade aluminum fixtures that withstand {city} freeze-thaw cycling, salt exposure, and moisture without corroding or discolouring. All fixtures use LED technology with low-voltage transformers. We avoid plastic or painted fixtures that degrade within a few years in Ontario weather. Fixture styles range from contemporary to traditional to match your home architecture.',
                ],
                [
                    'question' => 'Can I control landscape lighting from my phone?',
                    'short_answer' => 'Yes. Our {city} landscape lighting installations include commercial-grade transformers with WiFi smart controls compatible with phone apps and home automation platforms. You can set schedules, adjust brightness, create zones, and control your lights remotely. Timer functions account for seasonal daylight changes in {city}, automatically adjusting on/off times throughout the year.',
                    'answer' => 'Yes. Our {city} landscape lighting installations include commercial-grade transformers with WiFi smart controls compatible with phone apps and home automation platforms. You can set schedules, adjust brightness, create zones, and control your lights remotely. Timer functions account for seasonal daylight changes in {city}, automatically adjusting on/off times throughout the year.',
                ],
                [
                    'question' => 'How many landscape lights do I need for my {city} property?',
                    'short_answer' => 'The number of fixtures depends on your property size, the features you want to highlight, and the lighting effects desired. A typical {city} residential property in {neighborhoods} uses 12 to 20 fixtures for a balanced design including path lights, uplights for trees or architectural features, and accent lights for garden beds. Our design consultation includes a lighting plan showing exact fixture placement.',
                    'answer' => 'The number of fixtures depends on your property size, the features you want to highlight, and the lighting effects desired. A typical {city} residential property in {neighborhoods} uses 12 to 20 fixtures for a balanced design including path lights, uplights for trees or architectural features, and accent lights for garden beds. Our design consultation includes a lighting plan showing exact fixture placement.',
                ],
                [
                    'question' => 'Do landscape lights work in {city} winter conditions?',
                    'short_answer' => 'Yes. Our low-voltage LED landscape lighting systems are designed to operate year-round in {city} conditions including through the {snowfall} of annual snowfall. Brass and marine-grade aluminum fixtures are impervious to salt and moisture. Direct-burial cable is trenched below grade to prevent damage from frost heave. LED bulbs generate minimal heat and are unaffected by cold temperatures, often performing better in winter.',
                    'answer' => 'Yes. Our low-voltage LED landscape lighting systems are designed to operate year-round in {city} conditions including through the {snowfall} of annual snowfall. Brass and marine-grade aluminum fixtures are impervious to salt and moisture. Direct-burial cable is trenched below grade to prevent damage from frost heave. LED bulbs generate minimal heat and are unaffected by cold temperatures, often performing better in winter.',
                ],
            ],

        ];
    }
}
