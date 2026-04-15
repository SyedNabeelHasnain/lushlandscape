<?php

namespace Database\Seeders\Content;

use App\Models\City;
use App\Models\Service;
use App\Models\ServiceCityPage;
use Illuminate\Database\Seeder;

class MississaugaContentSeeder extends Seeder
{
    public function run(): void
    {
        $city = City::where('name', 'Mississauga')->firstOrFail();
        $pages = $this->getPages();

        $neighborhoods = [
            'Port Credit', 'Streetsville', 'Erin Mills', 'Meadowvale', 'Mississauga Valleys',
            'Clarkson', 'Lorne Park', 'Mineola', 'Cooksville', 'Malton',
            'Churchill Meadows', 'Lisgar',
        ];

        foreach ($pages as $serviceName => $data) {
            $service = Service::where('name', $serviceName)->firstOrFail();

            $page = ServiceCityPage::updateOrCreate(
                ['service_id' => $service->id, 'city_id' => $city->id],
                array_merge($data, [
                    'is_active' => true,
                    'is_indexable' => true,
                    'navigation_label' => $service->name,
                    'keywords_json' => ContentBlockHelper::defaultKeywords($service->name, $city->name),
                    'cta_json' => [
                        'text' => 'Book a Consultation in Mississauga',
                        'url' => '/request-quote?service='.urlencode($service->name).'&city=Mississauga',
                    ],
                ])
            );

            ContentBlockHelper::createBlocks($page, $service, $city, [
                'neighborhoods' => $neighborhoods,
            ]);
        }
    }

    private function getPages(): array
    {
        return [

            // ─── 1. Interlocking Driveways ──────────────────────────────────
            'Interlocking Driveways' => [
                'page_title' => 'Interlocking Driveways in Mississauga',
                'h1' => 'Interlocking Driveways in Mississauga',
                'local_intro' => "Your driveway is the first thing visitors see and the surface your family uses every single day. In Mississauga, where Halton Till clay soils expand and contract through harsh freeze-thaw cycles, a properly engineered interlocking driveway is not just an aesthetic upgrade but a structural investment. Our Mississauga driveway installations start with a full site assessment to determine your lot's specific soil composition, drainage patterns, and load requirements.\n\nWe excavate to a minimum 16-inch depth, install compacted Granular A sub-base, a 1-inch HPB levelling course, and premium pavers from manufacturers like Unilock, Techo-Bloc, and Belgard rated at 8,000+ PSI compressive strength. Every driveway includes herringbone or 45-degree pattern installation for maximum interlock strength, polymeric sand jointing, and soldier-course edge restraint anchored below the frost line. The result is a driveway that handles daily vehicle traffic, resists seasonal movement, and looks exceptional for decades.\n\nWhether you are in Port Credit replacing a crumbling concrete pad or in Erin Mills upgrading builder-grade asphalt, our ICPI-certified crews deliver the same exacting standard. We back every interlocking driveway with our 10-year workmanship warranty.",
                'meta_title' => 'Interlocking Driveways Mississauga | Lush Landscape',
                'meta_description' => 'Custom interlocking driveway installation in Mississauga. ICPI-certified crews, 8,000+ PSI pavers, HPB base, and 10-year warranty with consultation-led planning.',
                'og_title' => 'Interlocking Driveways in Mississauga | Lush Landscape',
                'og_description' => 'Premium interlocking driveway construction for Mississauga homeowners. Engineered for Peel Region clay soils and Ontario winters.',
                'sort_order' => 1,
            ],

            // ─── 2. Interlocking Patios & Backyard Living ───────────────────
            'Interlocking Patios & Backyard Living' => [
                'page_title' => 'Interlocking Patios & Outdoor Living in Mississauga',
                'h1' => 'Interlocking Patios & Backyard Living in Mississauga',
                'local_intro' => "Mississauga homeowners are increasingly viewing their backyards as extensions of their indoor living space. From Lorne Park's spacious estate lots to Churchill Meadows' modern family properties, we design and build interlocking patios that serve as the foundation for complete outdoor living environments including BBQ islands, fire pits, seating walls, and integrated lighting.\n\nOur Mississauga patio installations address the specific drainage challenges that Peel Region clay soils present. We engineer positive grade away from your foundation, integrate catch basins where needed, and use open-graded base systems on lots where clay impermeability creates ponding risk. Every patio project includes a compacted Granular A sub-base, HPB levelling course, and premium pavers installed in patterns selected for both aesthetics and structural performance.\n\nWhether you envision a simple entertaining pad or a multi-level outdoor room with kitchen, fireplace, and pergola, our design team works with you from concept through completion. We coordinate with gas, electrical, and plumbing trades as needed, delivering a single-source project experience backed by our 10-year workmanship warranty.",
                'meta_title' => 'Interlocking Patios Mississauga | Lush Landscape',
                'meta_description' => 'Custom interlocking patio installation in Mississauga. Outdoor kitchens, fire pits, seating walls. Engineered for clay soils with consultation-led planning.',
                'og_title' => 'Interlocking Patios & Backyard Living in Mississauga',
                'og_description' => 'Transform your Mississauga backyard with custom interlocking patios, outdoor kitchens, and fire features.',
                'sort_order' => 2,
            ],

            // ─── 3. Walkways & Steps ────────────────────────────────────────
            'Walkways & Steps' => [
                'page_title' => 'Interlocking Walkways & Steps in Mississauga',
                'h1' => 'Walkways & Steps in Mississauga',
                'local_intro' => "A well-built walkway does more than connect your driveway to your front door. In Mississauga, where winter ice and spring thaw create seasonal tripping hazards, a properly graded interlocking walkway with consistent riser heights is a safety investment that also transforms your home's curb appeal. Our walkway and step installations follow Ontario Building Code requirements for riser height, tread depth, and handrail placement.\n\nWe design walkways that complement your existing hardscaping, whether that means matching paver colours and patterns with an interlocking driveway or introducing natural stone accents for visual contrast. For properties with grade changes, we build landing pads at required intervals, install non-slip tread surfaces, and ensure positive drainage away from the path. Steps use reinforced concrete cores with paver or stone cladding for structural permanence.\n\nFrom Streetsville's heritage homes to Meadowvale's modern subdivisions, our Mississauga walkway projects range from simple front-entry paths to elaborate garden walkways with integrated lighting and planting beds. Every installation includes a compacted aggregate base, edge restraint, and polymeric sand to prevent weed growth and paver migration.",
                'meta_title' => 'Walkways & Steps Mississauga | Lush Landscape',
                'meta_description' => 'Interlocking walkways and steps in Mississauga. Non-slip surfaces, OBC-compliant risers, and premium pavers. Safety meets curb appeal.',
                'og_title' => 'Walkways & Steps in Mississauga | Lush Landscape',
                'og_description' => 'Professional walkway and step installation for Mississauga homes. Eliminate tripping hazards and boost curb appeal.',
                'sort_order' => 3,
            ],

            // ─── 4. Natural Stone & Flagstone ───────────────────────────────
            'Natural Stone & Flagstone' => [
                'page_title' => 'Natural Stone & Flagstone in Mississauga',
                'h1' => 'Natural Stone & Flagstone Installation in Mississauga',
                'local_intro' => "Natural stone brings a timeless, organic character to Mississauga properties that manufactured pavers cannot replicate. Our natural stone division works with Ontario-quarried materials including Eramosa limestone, Algonquin flagstone, Muskoka granite, and Owen Sound ledgerock to create patios, walkways, accent walls, and water features that look like they have always been part of the landscape.\n\nFlagstone patios in Mississauga require careful attention to the base preparation that clay-heavy soils demand. We install a minimum 12-inch compacted aggregate base under all flagstone work, use mortar-set or dry-laid techniques depending on the application, and fill joints with polymeric sand or stone dust to prevent weed intrusion. Armour stone installations use boulders sourced from Ontario quarries, placed with equipment precision and anchored against seasonal soil movement.\n\nFrom hand-cut flagstone patios in Lorne Park to armour stone retaining features in Erindale, our crews bring the craftsmanship that natural stone demands. Every piece is selected for colour consistency, thickness uniformity, and structural integrity before it reaches your property.",
                'meta_title' => 'Natural Stone & Flagstone Mississauga | Lush Landscape',
                'meta_description' => 'Natural stone and flagstone installation in Mississauga. Ontario-quarried materials, hand-cut patios, and armour stone features with consultation-led planning.',
                'og_title' => 'Natural Stone & Flagstone in Mississauga | Lush Landscape',
                'og_description' => 'Premium natural stone patios, flagstone walkways, and armour stone features for Mississauga properties.',
                'sort_order' => 4,
            ],

            // ─── 5. Porcelain Pavers ────────────────────────────────────────
            'Porcelain Pavers' => [
                'page_title' => 'Porcelain Paver Installation in Mississauga',
                'h1' => 'Porcelain Pavers in Mississauga',
                'local_intro' => "Porcelain pavers are the fastest-growing segment in Mississauga's residential hardscaping market, and for good reason. These 20mm-thick engineered tiles deliver the look of natural stone, wood, or concrete with zero water absorption, R11 slip rating, UV stability, and virtually no maintenance. For Mississauga homeowners who want a modern, clean aesthetic without the upkeep of natural materials, porcelain pavers are the ideal solution.\n\nOur porcelain paver installations use pedestal or compacted-aggregate base systems depending on the application. Rooftop terraces and pool surrounds typically use adjustable pedestal systems that allow for drainage underneath and easy access to membrane surfaces. Ground-level patios use the same compacted aggregate base as traditional interlocking, with levelling screed and open-graded joint material. We source 20mm porcelain pavers from leading Italian and Spanish manufacturers through Canadian distributors who guarantee freeze-thaw performance.\n\nFrom sleek grey concrete-look terraces in Port Credit waterfront homes to warm wood-look pool surrounds in Erin Mills, porcelain pavers give Mississauga homeowners design flexibility that no other material matches.",
                'meta_title' => 'Porcelain Pavers Mississauga | Lush Landscape',
                'meta_description' => 'Modern 20mm porcelain paver installation in Mississauga. Zero absorption, R11 slip rating, frost-proof. Patios, pool decks, terraces.',
                'og_title' => 'Porcelain Pavers in Mississauga | Lush Landscape',
                'og_description' => 'Contemporary porcelain paver installation for Mississauga patios, terraces, and pool surrounds. Maintenance-free elegance.',
                'sort_order' => 5,
            ],

            // ─── 6. Concrete Driveways ──────────────────────────────────────
            'Concrete Driveways' => [
                'page_title' => 'Concrete Driveways in Mississauga',
                'h1' => 'Concrete Driveways in Mississauga',
                'local_intro' => "A concrete driveway remains the most cost-effective way to achieve a durable, clean-looking surface for Mississauga properties. Our concrete driveway installations use 32 MPa air-entrained mix with 5 to 7 percent air content, the Ontario specification for freeze-thaw durability. Every pour includes 10M rebar on 400mm centres, fibre-mesh reinforcement, and control joints calculated for the slab dimensions and expected load.\n\nMississauga's clay-heavy soils require careful sub-base preparation to prevent the cracking and heaving that plagues poorly built concrete driveways. We excavate to a minimum 12-inch depth, install compacted Granular A sub-base, and use a polyethylene vapour barrier to prevent moisture from migrating upward into the slab. Curing compound is applied immediately after finishing to ensure proper hydration and surface hardness.\n\nWe offer broom-finish, exposed aggregate, stamped patterns including ashlar slate, cobblestone, and wood-plank textures, and acid-stain decorative finishes. Our Mississauga concrete crews handle complex curves, multi-level transitions, and integrated drainage channels on every project.",
                'meta_title' => 'Concrete Driveways Mississauga | Lush Landscape',
                'meta_description' => 'Concrete driveway installation in Mississauga. 32 MPa air-entrained mix, rebar reinforcement, and decorative finishes with consultation-led planning.',
                'og_title' => 'Concrete Driveways in Mississauga | Lush Landscape',
                'og_description' => 'High-strength concrete driveways for Mississauga homes. Stamped, exposed aggregate, and broom-finish options.',
                'sort_order' => 6,
            ],

            // ─── 7. Concrete Patios & Walkways ─────────────────────────────
            'Concrete Patios & Walkways' => [
                'page_title' => 'Concrete Patios & Walkways in Mississauga',
                'h1' => 'Concrete Patios & Walkways in Mississauga',
                'local_intro' => "Architectural concrete transforms ordinary outdoor surfaces into design statements. In Mississauga, where homeowners want the durability of concrete with visual interest that goes beyond a plain grey slab, our stamped, stained, and exposed aggregate finishes deliver exactly that. We pour patios, walkways, pool decks, and stepping-stone paths using the same 32 MPa air-entrained specifications as our driveways.\n\nStamped concrete patterns include ashlar slate, European fan, random stone, cobblestone, and wood-plank textures applied with colour hardener and release agents for a natural, multi-toned finish. Exposed aggregate finishes reveal the natural stone within the concrete mix, creating a textured, slip-resistant surface ideal for pool surrounds and walkways. Acid-stain finishes produce translucent colour variations that mimic natural stone at a fraction of the cost.\n\nOur Mississauga concrete patio projects include proper sub-base preparation for clay soils, positive drainage grading away from your foundation, and expansion joints where the patio meets the house. We back all concrete patio and walkway work with our workmanship warranty.",
                'meta_title' => 'Concrete Patios & Walkways Mississauga | Lush Landscape',
                'meta_description' => 'Stamped and exposed aggregate concrete patios and walkways in Mississauga. Decorative finishes, proper drainage, and lasting durability.',
                'og_title' => 'Concrete Patios & Walkways in Mississauga | Lush Landscape',
                'og_description' => 'Architectural concrete patios and walkways for Mississauga homes. Stamped, stained, and exposed aggregate options.',
                'sort_order' => 7,
            ],

            // ─── 8. Interlock Restoration & Sealing ─────────────────────────
            'Interlock Restoration & Sealing' => [
                'page_title' => 'Interlock Restoration & Sealing in Mississauga',
                'h1' => 'Interlock Restoration & Sealing in Mississauga',
                'local_intro' => "Years of Mississauga weather, road salt tracking, and organic growth take a visible toll on interlocking surfaces. Faded colours, displaced polymeric sand, weed invasion, and efflorescence buildup make even quality installations look tired. Our 3-day interlock restoration process brings your existing pavers back to their original appearance and protects them for years to come.\n\nDay one involves thorough hot-water pressure washing at 3,000+ PSI with surface-cleaning attachments that remove embedded dirt, moss, algae, and efflorescence without damaging paver surfaces. Day two allows the surface to dry completely before we re-apply polymeric sand to all joints, ensuring proper activation and curing. Day three applies UV-resistant sealer in the homeowner's choice of matte, satin, or wet-look finish. The sealer locks in colour, prevents future staining, and makes ongoing maintenance as simple as a garden hose rinse.\n\nWe restore interlocking driveways, patios, walkways, and pool decks across Mississauga from Port Credit to Meadowvale. If your pavers are structurally sound but visually faded, restoration and sealing is the most cost-effective way to renew your hardscaping.",
                'meta_title' => 'Interlock Restoration & Sealing Mississauga | Lush',
                'meta_description' => 'Professional interlock restoration and sealing in Mississauga. 3-day process: steam wash, polymeric sand, UV sealer. Renew your pavers.',
                'og_title' => 'Interlock Restoration & Sealing in Mississauga',
                'og_description' => 'Restore faded interlocking surfaces in Mississauga. Hot-water wash, polymeric sand, and protective sealer application.',
                'sort_order' => 8,
            ],

            // ─── 9. Interlock Repair (Lift & Relay) ─────────────────────────
            'Interlock Repair (Lift & Relay)' => [
                'page_title' => 'Interlock Repair (Lift & Relay) in Mississauga',
                'h1' => 'Interlock Repair in Mississauga: Lift & Relay',
                'local_intro' => "Sunken, heaving, or uneven interlocking pavers are more than an eyesore. They create tripping hazards and allow water to pool against your foundation. In Mississauga, where clay soil movement is the leading cause of paver displacement, a proper lift-and-relay repair addresses the root cause rather than just the symptom.\n\nOur repair process starts with carefully removing the affected pavers and setting them aside. We then excavate the failed base material, identify the cause of the failure, whether that is insufficient base depth, poor compaction, root intrusion, or subsurface drainage issues, and correct it. Fresh HPB aggregate is installed, compacted in lifts to 95 percent Standard Proctor density, and screeded to the correct elevation. The original pavers are then re-laid in their original pattern, new polymeric sand is applied to all joints, and the repaired area is compacted to match the surrounding surface.\n\nUnlike full replacement, lift-and-relay preserves your existing pavers and their naturally weathered colour, so the repair blends seamlessly with the undisturbed areas. We perform lift-and-relay repairs on driveways, patios, walkways, and pool decks across Mississauga.",
                'meta_title' => 'Interlock Repair Mississauga - Lift & Relay | Lush',
                'meta_description' => 'Permanent interlock repair in Mississauga. Lift and relay sunken pavers with HPB base correction. Fix the cause, not just the symptom.',
                'og_title' => 'Interlock Repair (Lift & Relay) in Mississauga',
                'og_description' => 'Fix sunken and heaving pavers in Mississauga permanently. HPB base correction and precision re-levelling.',
                'sort_order' => 9,
            ],

            // ─── 10. Retaining Walls ────────────────────────────────────────
            'Retaining Walls' => [
                'page_title' => 'Retaining Walls in Mississauga',
                'h1' => 'Retaining Wall Construction in Mississauga',
                'local_intro' => "Mississauga's rolling terrain and Credit River valley create residential properties with significant grade changes that require engineered retaining solutions. Whether you need a terraced backyard for usable outdoor living space, a front-yard retaining wall to manage a sloped driveway approach, or erosion control along a property boundary, our retaining wall division delivers structural solutions that perform and look exceptional.\n\nWe build with armour stone (natural Ontario boulders weighing 1,000 to 4,000 pounds each), precast concrete blocks from Allan Block, Cornerstone, and Unilock, and poured-in-place concrete where structural requirements dictate. Every retaining wall over 1.0 metre in retained height is designed to Ontario Building Code Part 4 standards, with geogrid reinforcement at calculated intervals, clear stone backfill, filter fabric, and weeping tile drainage connected to storm or daylight outlets.\n\nOur Mississauga retaining wall projects include full engineering coordination where required, conservation authority permitting for properties within the Credit Valley Conservation regulated area, and a 10-year workmanship warranty on all structural components.",
                'meta_title' => 'Retaining Walls Mississauga | Lush Landscape',
                'meta_description' => 'Retaining wall construction in Mississauga. Armour stone, concrete block, geogrid reinforced. OBC-compliant, CVC-permitted with consultation-led planning.',
                'og_title' => 'Retaining Walls in Mississauga | Lush Landscape',
                'og_description' => 'Engineered retaining walls for Mississauga properties. Armour stone and precast block with proper drainage.',
                'sort_order' => 10,
            ],

            // ─── 11. Sod Installation & Grading ─────────────────────────────
            'Sod Installation & Grading' => [
                'page_title' => 'Sod Installation & Grading in Mississauga',
                'h1' => 'Sod Installation & Grading in Mississauga',
                'local_intro' => "A healthy lawn starts with what is underneath it. In Mississauga, where compacted builder-grade subsoil and clay-heavy native soil make it difficult for grass roots to establish, proper grading and soil amendment are the difference between a lawn that thrives and one that struggles. Our sod installation process addresses the soil profile first and the grass surface second.\n\nWe begin by establishing positive drainage grade away from your foundation at a minimum 2 percent slope for the first 6 feet, then transitioning to a gentler grade across the remainder of the yard. Topsoil is stripped from areas requiring grade adjustment, subgrade is shaped and compacted, and 4 to 6 inches of Triple-Mix (screened topsoil, peat, and compost blend) is spread and raked to final grade. Premium Kentucky Bluegrass sod is laid the same day it is cut from the farm, rolled for root contact, and given starter fertilizer and initial watering instructions.\n\nFrom post-construction grading on new builds in Churchill Meadows to full lawn renovation on mature lots in Lorne Park, our Mississauga sod crews handle projects of every scale. We guarantee root establishment when our watering schedule is followed.",
                'meta_title' => 'Sod Installation & Grading Mississauga | Lush',
                'meta_description' => 'Professional sod installation and grading in Mississauga. Kentucky Bluegrass, Triple-Mix soil, precision drainage. Same-day installation.',
                'og_title' => 'Sod Installation & Grading in Mississauga | Lush',
                'og_description' => 'Premium sod installation with proper grading and soil amendment for Mississauga lawns. Instant results, lasting health.',
                'sort_order' => 11,
            ],

            // ─── 12. Artificial Turf ────────────────────────────────────────
            'Artificial Turf' => [
                'page_title' => 'Artificial Turf Installation in Mississauga',
                'h1' => 'Artificial Turf in Mississauga',
                'local_intro' => "For Mississauga homeowners who want a green, maintenance-free lawn year-round, artificial turf delivers. No mowing, no watering, no fertilizing, and no brown patches through July drought or March snowmelt. Modern synthetic turf products have evolved far beyond the plastic-looking surfaces of a decade ago. Today's premium turf features multi-toned blade profiles, thatch layers for natural appearance, and UV stabilization that maintains colour integrity for 15 or more years.\n\nOur Mississauga artificial turf installations use a properly engineered base that addresses the clay-soil drainage challenges unique to Peel Region lots. We excavate existing soil, install compacted aggregate base with positive drainage grade, lay geotextile separation fabric, and secure the turf with landscape spikes and infill material. Pet-friendly installations use antimicrobial infill and achieve drainage rates exceeding 30 inches per hour, ensuring rapid moisture clearance.\n\nWhether you need a backyard play area for children, a pet run that stays clean and dry, a putting green for golf practice, or a front-yard accent that looks immaculate without weekend maintenance, our Mississauga artificial turf division delivers turnkey installations.",
                'meta_title' => 'Artificial Turf Mississauga | Lush Landscape',
                'meta_description' => 'Artificial turf installation in Mississauga. Pet-friendly, UV-stable, 30+ in/hr drainage. No mowing, no watering, always green.',
                'og_title' => 'Artificial Turf in Mississauga | Lush Landscape',
                'og_description' => 'Maintenance-free artificial turf for Mississauga lawns, pet areas, and play zones. Looks natural year-round.',
                'sort_order' => 12,
            ],

            // ─── 13. Garden Design & Planting ───────────────────────────────
            'Garden Design & Planting' => [
                'page_title' => 'Garden Design & Planting in Mississauga',
                'h1' => 'Garden Design & Planting in Mississauga',
                'local_intro' => "Mississauga's climate sits in the USDA Zone 6a range, moderated by Lake Ontario's proximity, which gives homeowners access to a wide palette of hardy perennials, ornamental grasses, flowering shrubs, and shade trees. Our garden design service translates that botanical potential into curated planting plans that provide four-season visual interest, support local pollinators, and thrive in Mississauga's specific soil and light conditions.\n\nWe start every garden design project with an on-site assessment of sun exposure, soil type, drainage patterns, and existing vegetation. Planting plans specify cultivars proven in Zone 6a conditions, sourced from Ontario-accredited nurseries. Garden bed construction includes excavation of compacted subsoil, amendment with premium planting mix, and installation of steel or aluminum edging for clean, permanent bed lines. Mulch is applied at 3-inch depth for moisture retention and weed suppression.\n\nFrom pollinator gardens in Erindale to privacy screening hedges in Meadowvale, our Mississauga garden design team creates outdoor spaces that look better with every passing season. All plant material is backed by a one-year health guarantee when our care instructions are followed.",
                'meta_title' => 'Garden Design & Planting Mississauga | Lush',
                'meta_description' => 'Professional garden design and planting in Mississauga. Ontario-native perennials, pollinator gardens, and four-season interest. One-year guarantee.',
                'og_title' => 'Garden Design & Planting in Mississauga | Lush',
                'og_description' => 'Custom garden design with Ontario-native plants for Mississauga properties. Beautiful, sustainable, and pollinator-friendly.',
                'sort_order' => 13,
            ],

            // ─── 14. Landscape Lighting ─────────────────────────────────────
            'Landscape Lighting' => [
                'page_title' => 'Landscape Lighting in Mississauga',
                'h1' => 'Landscape Lighting in Mississauga',
                'local_intro' => "Professional landscape lighting transforms your Mississauga property after dark, extending usable outdoor hours, enhancing security, and highlighting the architectural and landscape features that define your home's character. Our lighting designs go beyond simple path lights to create layered illumination schemes that include uplighting, downlighting, wash lighting, and accent techniques.\n\nWe use low-voltage LED systems powered by commercial-grade transformers with built-in timers and WiFi smart controls compatible with phone apps and home automation platforms. All fixtures are solid brass or marine-grade aluminum, selected for their ability to withstand Ontario's freeze-thaw cycles and salt exposure without corroding or discolouring. Direct-burial cable rated for outdoor use is trenched to below-grade depth to protect against damage.\n\nFrom driveway pillar lighting in Lorne Park to backyard patio and garden bed illumination in Erin Mills, our Mississauga lighting projects are designed by certified lighting professionals who understand colour temperature, beam angles, and lumen requirements for each application. Every system is sized for future expansion so adding fixtures later does not require transformer upgrades.",
                'meta_title' => 'Landscape Lighting Mississauga | Lush Landscape',
                'meta_description' => 'Professional LED landscape lighting in Mississauga. Brass fixtures, WiFi smart controls, and custom design. Extend your outdoor living hours.',
                'og_title' => 'Landscape Lighting in Mississauga | Lush Landscape',
                'og_description' => 'Custom landscape lighting design and installation for Mississauga homes. Low-voltage LED, brass fixtures, smart controls.',
                'sort_order' => 14,
            ],

        ];
    }
}
