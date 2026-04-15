<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Interlock & Specialty Paving',
                'short_description' => 'Premium interlocking stone and specialty paver installations for driveways, patios, walkways, and more.',
                'long_description' => 'Our interlock and specialty paving division delivers Ontario homeowners the full spectrum of hardscape surfacing, from classic clay-brick driveways to contemporary porcelain-paver patios. Every project starts with a compacted granular-A sub-base, 3/4-inch HPB levelling course, and ICPI-certified installation practices that exceed Ontario Building Code requirements. We work with leading manufacturers including Unilock, Techo-Bloc, Belgard, and Rinox to source pavers rated for 8,000+ PSI compressive strength and proven in Canadian freeze-thaw conditions. Whether you need a herringbone-pattern driveway engineered for daily vehicle loads, a free-form flagstone patio with armour-stone seating walls, or a slip-rated porcelain terrace for poolside entertaining, our crews handle excavation, grading, drainage integration, polymeric sand jointing, and final sealing in a single turnkey scope.',
                'meta_title' => 'Interlock & Specialty Paving Services in Ontario',
                'meta_description' => 'Premium interlocking stone driveways, patios, walkways, flagstone, and porcelain paver installations across Ontario. ICPI-certified crews. 10-year warranty.',
                'og_title' => 'Interlock & Specialty Paving | Lush Landscape Service',
                'og_description' => 'Custom interlocking and specialty paver installations for Ontario homeowners. From driveways to poolside patios, built to last.',
                'icon' => 'brick-wall',
                'status' => 'published',
                'sort_order' => 1,
                'keywords_json' => [
                    'primary' => ['interlocking services ontario', 'paver installation ontario'],
                    'secondary' => ['interlock driveway contractor', 'patio paver installer', 'flagstone installation', 'porcelain pavers ontario'],
                    'long_tail' => ['best interlocking company in ontario', 'how much do interlocking pavers cost in ontario'],
                ],
                'services' => [
                    [
                        'name' => 'Interlocking Driveways',
                        'service_summary' => 'Custom interlocking driveway installation with HPB bases and premium pavers engineered for Ontario winters.',
                        'icon' => 'car',
                        'default_meta_title' => 'Interlocking Driveway Installation | Lush Landscape',
                        'default_meta_description' => 'Custom interlocking driveway installation with ICPI-certified crews, 8,000+ PSI pavers, and 10-year workmanship warranty across Ontario.',
                        'default_og_title' => 'Interlocking Driveways | Lush Landscape Service',
                        'default_og_description' => 'Premium interlocking driveway construction with HPB base, herringbone or basket-weave patterns, and polymeric sand finish.',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Interlocking Patios & Backyard Living',
                        'service_summary' => 'Custom interlocking patios with integrated outdoor kitchens, BBQ islands, and fire features.',
                        'icon' => 'armchair',
                        'default_meta_title' => 'Interlocking Patios & Outdoor Living | Lush Landscape',
                        'default_meta_description' => 'Custom interlocking patios with outdoor kitchens, fire pits, and seating walls. Designed for Ontario backyards with consultation-led planning.',
                        'default_og_title' => 'Interlocking Patios & Backyard Living | Lush Landscape',
                        'default_og_description' => 'Transform your backyard with custom interlocking patios, BBQ islands, fire features, and outdoor living spaces.',
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'Walkways & Steps',
                        'service_summary' => 'Interlocking walkways and natural stone steps that eliminate tripping hazards and boost curb appeal.',
                        'icon' => 'footprints',
                        'default_meta_title' => 'Interlocking Walkways & Steps | Lush Landscape',
                        'default_meta_description' => 'Professional interlocking walkway and step installation. ADA-compliant risers, non-slip surfaces, and drainage integration.',
                        'default_og_title' => 'Walkways & Steps | Lush Landscape Service',
                        'default_og_description' => 'Safe, beautiful interlocking walkways and natural stone steps for Ontario homes. Eliminate tripping hazards.',
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'Natural Stone & Flagstone',
                        'service_summary' => 'Hand-cut flagstone patios, armour stone walls, and premium natural stone installations.',
                        'icon' => 'mountain',
                        'default_meta_title' => 'Natural Stone & Flagstone Installation | Lush Landscape',
                        'default_meta_description' => 'Hand-cut flagstone patios, armour stone features, and natural stone installations. Locally sourced Ontario stone with consultation-led planning.',
                        'default_og_title' => 'Natural Stone & Flagstone | Lush Landscape Service',
                        'default_og_description' => 'Premium flagstone patios, armour stone retaining walls, and natural stone accents for Ontario properties.',
                        'sort_order' => 4,
                    ],
                    [
                        'name' => 'Porcelain Pavers',
                        'service_summary' => 'Modern 20mm porcelain paver installation with zero water absorption and R11 slip rating.',
                        'icon' => 'square',
                        'default_meta_title' => 'Porcelain Paver Installation | Lush Landscape',
                        'default_meta_description' => 'Modern 20mm porcelain paver installation with zero water absorption, R11 slip rating, and frost-proof durability for Ontario.',
                        'default_og_title' => 'Porcelain Pavers | Lush Landscape Service',
                        'default_og_description' => 'Contemporary porcelain paver patios and terraces. Stain-proof, frost-proof, and maintenance-free.',
                        'sort_order' => 5,
                    ],
                ],
            ],
            [
                'name' => 'Concrete Services',
                'short_description' => 'Professional concrete driveway, patio, and walkway construction with 32 MPa air-entrained specifications.',
                'long_description' => 'Our concrete division pours driveways, patios, walkways, and garage pads using 32 MPa air-entrained concrete with 5-7 percent air content, the Ontario standard for freeze-thaw durability. Every pour includes 10M rebar on 400mm centres, fibre-mesh reinforcement, and control joints spaced at intervals calculated for the slab dimensions and expected load. We offer broom-finish, exposed aggregate, stamped patterns (ashlar slate, cobblestone, wood-plank), and acid-stain decorative finishes. All concrete work includes proper sub-base preparation with granular compaction to 95 percent Standard Proctor, polyethylene vapour barrier where required, and cure-and-seal application for surface protection. Our forming crews handle complex curves, multi-level transitions, and integrated drainage channels with the same precision on every project.',
                'meta_title' => 'Concrete Driveway & Patio Services in Ontario',
                'meta_description' => 'Professional concrete driveways, patios, and walkways with 32 MPa air-entrained mix, rebar reinforcement, and decorative finishes across Ontario.',
                'og_title' => 'Concrete Services | Lush Landscape Service',
                'og_description' => 'High-strength concrete construction for Ontario driveways, patios, and walkways. Stamped, exposed aggregate, and decorative options.',
                'icon' => 'construction',
                'status' => 'published',
                'sort_order' => 2,
                'keywords_json' => [
                    'primary' => ['concrete services ontario', 'concrete driveway ontario'],
                    'secondary' => ['stamped concrete contractor', 'exposed aggregate patio', 'concrete walkway installation'],
                    'long_tail' => ['how much does a concrete driveway cost in ontario', 'best concrete contractor near me ontario'],
                ],
                'services' => [
                    [
                        'name' => 'Concrete Driveways',
                        'service_summary' => 'High-strength concrete driveways with 32 MPa air-entrained mix, rebar reinforcement, and decorative finishes.',
                        'icon' => 'rectangle-horizontal',
                        'default_meta_title' => 'Concrete Driveway Installation | Lush Landscape',
                        'default_meta_description' => 'High-strength concrete driveways with 32 MPa air-entrained mix, rebar grid, and decorative finishes. Ontario frost-rated specs.',
                        'default_og_title' => 'Concrete Driveways | Lush Landscape Service',
                        'default_og_description' => 'Durable concrete driveway construction with stamped, exposed aggregate, and broom-finish options.',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Concrete Patios & Walkways',
                        'service_summary' => 'Architectural concrete patios and walkways with stamped patterns and exposed aggregate finishes.',
                        'icon' => 'layout-grid',
                        'default_meta_title' => 'Concrete Patios & Walkways | Lush Landscape',
                        'default_meta_description' => 'Architectural concrete patios and walkways with stamped patterns, exposed aggregate, and acid-stain finishes for Ontario homes.',
                        'default_og_title' => 'Concrete Patios & Walkways | Lush Landscape Service',
                        'default_og_description' => 'Custom concrete patios and walkways with decorative finishes. Built for Ontario weather conditions.',
                        'sort_order' => 2,
                    ],
                ],
            ],
            [
                'name' => 'Structural Hardscape & Repair',
                'short_description' => 'Expert interlock restoration, sealing, repair, and retaining wall construction for lasting structural integrity.',
                'long_description' => 'Ontario freeze-thaw cycles, de-icing salt, and clay-soil movement put constant stress on hardscape surfaces. Our structural repair and restoration division reverses settling, heaving, and erosion damage to bring driveways, patios, and walkways back to like-new condition. Services range from targeted lift-and-relay paver repairs to full-scope restoration projects that include hot-water pressure washing, re-levelling with fresh HPB aggregate, joint stabilization with polymeric sand, and UV-resistant sealer application. For grade changes and erosion control, we design and build engineered retaining walls using armour stone, precast concrete blocks (Allan Block, Cornerstone, Unilock), and geogrid-reinforced earth retention systems. Every retaining wall over 1.0 metre in retained height is engineered to Ontario Building Code Part 4 standards with proper drainage provisions including weeping tile, clear stone backfill, and filter fabric.',
                'meta_title' => 'Hardscape Repair & Restoration Services in Ontario',
                'meta_description' => 'Expert interlock repair, restoration, sealing, and retaining wall construction across Ontario. Fix sunken pavers and erosion damage permanently.',
                'og_title' => 'Structural Hardscape & Repair | Lush Landscape Service',
                'og_description' => 'Professional hardscape repair, interlock restoration and sealing, lift-and-relay, and retaining wall construction for Ontario properties.',
                'icon' => 'hammer',
                'status' => 'published',
                'sort_order' => 3,
                'keywords_json' => [
                    'primary' => ['interlock repair ontario', 'retaining wall construction ontario'],
                    'secondary' => ['interlock restoration sealing', 'paver repair contractor', 'lift and relay interlock'],
                    'long_tail' => ['how to fix sunken interlocking pavers in ontario', 'interlock sealing cost ontario'],
                ],
                'services' => [
                    [
                        'name' => 'Interlock Restoration & Sealing',
                        'service_summary' => 'Professional 3-day steam wash, polymeric sand, and UV-resistant sealer application.',
                        'icon' => 'sparkles',
                        'default_meta_title' => 'Interlock Restoration & Sealing | Lush Landscape',
                        'default_meta_description' => 'Professional 3-day interlock restoration: steam wash, polymeric sand, and UV-resistant sealer. Restore your pavers to like-new condition.',
                        'default_og_title' => 'Interlock Restoration & Sealing | Lush Landscape',
                        'default_og_description' => 'Complete interlock restoration with hot-water pressure wash, polymeric sand re-application, and protective sealer.',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Interlock Repair (Lift & Relay)',
                        'service_summary' => 'Permanent fix for sunken pavers using HPB base correction and precision re-leveling.',
                        'icon' => 'wrench',
                        'default_meta_title' => 'Interlock Repair - Lift & Relay | Lush Landscape',
                        'default_meta_description' => 'Permanent fix for sunken or heaving pavers. HPB base correction, precision re-levelling, and polymeric sand finish.',
                        'default_og_title' => 'Interlock Repair (Lift & Relay) | Lush Landscape',
                        'default_og_description' => 'Fix sunken, heaving, or uneven interlocking pavers permanently with our lift-and-relay repair service.',
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'Retaining Walls',
                        'service_summary' => 'Armour stone and concrete block retaining walls with geogrid reinforcement and weeping tile drainage.',
                        'icon' => 'layers',
                        'default_meta_title' => 'Retaining Wall Construction | Lush Landscape',
                        'default_meta_description' => 'Engineered retaining walls with armour stone, concrete block, geogrid reinforcement, and weeping tile drainage for Ontario properties.',
                        'default_og_title' => 'Retaining Walls | Lush Landscape Service',
                        'default_og_description' => 'Custom retaining wall construction using armour stone and precast blocks. Engineered for Ontario soil conditions.',
                        'sort_order' => 3,
                    ],
                ],
            ],
            [
                'name' => 'Softscaping & Lifestyle Enhancements',
                'short_description' => 'Complete softscaping services including sod, turf, garden design, and landscape lighting to elevate your outdoor living.',
                'long_description' => 'Our softscaping and lifestyle enhancement division completes the outdoor transformation that hardscaping begins. We install premium Kentucky Bluegrass sod on precision-graded, Triple-Mix-amended soil beds, lay pet-friendly artificial turf with antimicrobial infill and engineered drainage, design pollinator-friendly garden beds using Ontario-native perennials and ornamental grasses, and illuminate everything with low-voltage LED landscape lighting on WiFi-enabled smart controls. Every softscaping project integrates with existing or new hardscape elements for seamless transitions between paved and planted areas. We source plants from Ontario-accredited nurseries, specify cultivars proven in USDA Zone 5b/6a conditions, and back all installations with a one-year plant health guarantee. Our landscape lighting systems use solid-brass fixtures, direct-burial cable, and commercial-grade transformers sized for future expansion.',
                'meta_title' => 'Softscaping & Landscape Enhancement Services in Ontario',
                'meta_description' => 'Professional sod installation, artificial turf, garden design, and landscape lighting across Ontario. Native plants, smart controls, and warranties.',
                'og_title' => 'Softscaping & Lifestyle Enhancements | Lush Landscape',
                'og_description' => 'Complete softscaping services: sod, turf, garden design, and LED landscape lighting. Transform your Ontario outdoor space.',
                'icon' => 'flower-2',
                'status' => 'published',
                'sort_order' => 4,
                'keywords_json' => [
                    'primary' => ['landscaping services ontario', 'sod installation ontario'],
                    'secondary' => ['artificial turf installer', 'garden design ontario', 'landscape lighting contractor'],
                    'long_tail' => ['how much does sod installation cost in ontario', 'best landscape lighting company near me ontario'],
                ],
                'services' => [
                    [
                        'name' => 'Sod Installation & Grading',
                        'service_summary' => 'Same-day Kentucky Bluegrass installation with Triple-Mix soil amendment and precision grading.',
                        'icon' => 'sprout',
                        'default_meta_title' => 'Sod Installation & Grading | Lush Landscape',
                        'default_meta_description' => 'Same-day Kentucky Bluegrass sod installation with Triple-Mix soil amendment, precision grading, and starter fertilizer.',
                        'default_og_title' => 'Sod Installation & Grading | Lush Landscape Service',
                        'default_og_description' => 'Professional sod installation with proper grading, soil preparation, and same-day laying for instant results.',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Artificial Turf',
                        'service_summary' => 'Pet-friendly synthetic turf with anti-microbial infill and 30+ inches per hour drainage rate.',
                        'icon' => 'leaf',
                        'default_meta_title' => 'Artificial Turf Installation | Lush Landscape',
                        'default_meta_description' => 'Pet-friendly artificial turf installation with antimicrobial infill, 30+ in/hr drainage, and 15-year UV warranty for Ontario.',
                        'default_og_title' => 'Artificial Turf | Lush Landscape Service',
                        'default_og_description' => 'Low-maintenance synthetic turf for lawns, pet areas, and play zones. Looks green year-round.',
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'Garden Design & Planting',
                        'service_summary' => 'Native Ontario plant selection, garden bed construction, and pollinator-friendly landscape design.',
                        'icon' => 'flower',
                        'default_meta_title' => 'Garden Design & Planting | Lush Landscape',
                        'default_meta_description' => 'Professional garden design with Ontario-native plants, pollinator-friendly beds, and seasonal interest. One-year plant health guarantee.',
                        'default_og_title' => 'Garden Design & Planting | Lush Landscape Service',
                        'default_og_description' => 'Custom garden design using native Ontario perennials, ornamental grasses, and four-season interest planting.',
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'Landscape Lighting',
                        'service_summary' => 'Low-voltage LED landscape lighting with brass fixtures and WiFi smart controls.',
                        'icon' => 'lightbulb',
                        'default_meta_title' => 'Landscape Lighting Installation | Lush Landscape',
                        'default_meta_description' => 'Low-voltage LED landscape lighting with solid-brass fixtures, WiFi smart controls, and professional design for Ontario homes.',
                        'default_og_title' => 'Landscape Lighting | Lush Landscape Service',
                        'default_og_description' => 'Professional landscape lighting design and installation. Brass fixtures, smart controls, and energy-efficient LEDs.',
                        'sort_order' => 4,
                    ],
                ],
            ],
        ];

        foreach ($categories as $catData) {
            $services = $catData['services'];
            unset($catData['services']);

            $category = ServiceCategory::updateOrCreate(
                ['name' => $catData['name']],
                $catData
            );

            foreach ($services as $svcData) {
                $svcData['category_id'] = $category->id;
                $svcData['status'] = 'published';

                Service::updateOrCreate(
                    ['name' => $svcData['name'], 'category_id' => $category->id],
                    $svcData
                );
            }
        }
    }
}
