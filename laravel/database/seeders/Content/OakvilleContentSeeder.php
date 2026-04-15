<?php

namespace Database\Seeders\Content;

use App\Models\City;
use App\Models\Service;
use App\Models\ServiceCityPage;
use Illuminate\Database\Seeder;

class OakvilleContentSeeder extends Seeder
{
    public function run(): void
    {
        $city = City::where('name', 'Oakville')->firstOrFail();
        $pages = $this->getPages();

        $neighborhoods = [
            'Old Oakville', 'Bronte', 'Glen Abbey', 'River Oaks', 'Iroquois Ridge',
            'Joshua Creek', 'Clearview', 'College Park', 'Eastlake', 'Palermo',
            'West Oak Trails', 'Uptown Core',
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
                        'text' => 'Book a Consultation in Oakville',
                        'url' => '/request-quote?service='.urlencode($service->name).'&city=Oakville',
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
                'page_title' => 'Interlocking Driveways in Oakville',
                'h1' => 'Interlocking Driveways in Oakville',
                'local_intro' => "Oakville homeowners understand that a driveway sets the tone for an entire property. In a community known for its heritage streetscapes and premium estates, an interlocking driveway must deliver both structural permanence and visual distinction. Our Oakville driveway installations begin with a comprehensive site evaluation that accounts for the sandy clay loam found near the lakeshore and the heavier clay soils inland toward Trafalgar Road, ensuring the base design matches the ground conditions beneath your specific lot.\n\nWe excavate to a minimum 16-inch depth, install compacted Granular A sub-base, a 1-inch HPB levelling course, and premium pavers from Unilock, Techo-Bloc, and Belgard rated at 8,000+ PSI compressive strength. Every Oakville driveway includes herringbone or 45-degree pattern installation for maximum interlock strength, polymeric sand jointing, and soldier-course edge restraint anchored below the 48-inch frost line. Properties within the Old Oakville Heritage Conservation District receive material selections that satisfy heritage guidelines while delivering modern engineering performance.\n\nWhether you are upgrading a grand circular driveway along Lakeshore Road or replacing a builder-grade surface in Joshua Creek, our ICPI-certified crews deliver the same exacting standard. We back every interlocking driveway with our 10-year workmanship warranty and coordinate with the Town of Oakville on any required permits.",
                'meta_title' => 'Interlocking Driveways Oakville | Lush Landscape',
                'meta_description' => 'Custom interlocking driveway installation in Oakville. ICPI-certified crews, 8,000+ PSI pavers, heritage-compliant options with consultation-led planning.',
                'og_title' => 'Interlocking Driveways in Oakville | Lush Landscape',
                'og_description' => 'Premium interlocking driveway construction for Oakville homeowners. Engineered for Halton Region soils and Ontario winters.',
                'sort_order' => 1,
            ],

            // ─── 2. Interlocking Patios & Backyard Living ───────────────────
            'Interlocking Patios & Backyard Living' => [
                'page_title' => 'Interlocking Patios & Outdoor Living in Oakville',
                'h1' => 'Interlocking Patios & Backyard Living in Oakville',
                'local_intro' => "Oakville residents consistently invest in their outdoor living spaces, and the community's generous lot sizes in neighbourhoods like Glen Abbey, River Oaks, and Iroquois Ridge provide the canvas for exceptional backyard transformations. We design and build interlocking patios that serve as the foundation for complete outdoor environments including BBQ islands, fire pits, seating walls, and integrated lighting systems tailored to each property's layout and lifestyle.\n\nOur Oakville patio installations address the specific drainage challenges that Halton Region soils present. Near the lakeshore, sandy clay loam drains moderately but requires careful grading to direct water away from foundations. Inland properties toward Trafalgar Road sit on heavier clay that demands open-graded base systems and catch basins to prevent ponding. Every patio project includes a compacted Granular A sub-base, HPB levelling course, and premium pavers installed in patterns selected for both aesthetics and structural performance.\n\nWhether you envision a simple entertaining terrace or a multi-level outdoor room with kitchen, fireplace, and pergola, our design team works with you from concept through completion. We coordinate with gas, electrical, and plumbing trades as needed, delivering a single-source project experience in Oakville backed by our 10-year workmanship warranty.",
                'meta_title' => 'Interlocking Patios Oakville | Lush Landscape',
                'meta_description' => 'Custom interlocking patio installation in Oakville. Outdoor kitchens, fire pits, seating walls. Engineered for Halton soils with consultation-led planning.',
                'og_title' => 'Interlocking Patios & Backyard Living in Oakville',
                'og_description' => 'Transform your Oakville backyard with custom interlocking patios, outdoor kitchens, and fire features.',
                'sort_order' => 2,
            ],

            // ─── 3. Walkways & Steps ────────────────────────────────────────
            'Walkways & Steps' => [
                'page_title' => 'Interlocking Walkways & Steps in Oakville',
                'h1' => 'Walkways & Steps in Oakville',
                'local_intro' => "A well-built walkway is both a safety feature and a design statement, and in Oakville's heritage neighbourhoods and premium estates, both aspects carry equal weight. Winter ice, spring thaw, and the seasonal ground movement caused by Halton Region's clay subsoils create conditions that demand precision engineering in every walkway and step installation. Our projects follow Ontario Building Code requirements for riser height, tread depth, and handrail placement to ensure code compliance and pedestrian safety.\n\nWe design walkways that complement existing hardscaping and architectural character. In Old Oakville's Heritage Conservation District, this often means selecting natural stone or heritage-compatible paver profiles that satisfy strict material guidelines. For contemporary homes in Bronte and Joshua Creek, modern linear pavers and clean geometric layouts create a fitting aesthetic. Steps use reinforced concrete cores with paver or stone cladding for structural permanence, and landing pads are installed at required intervals on sloped entries.\n\nFrom grand front-entry paths on Lakeshore Road estates to garden walkways winding through mature landscapes in Iroquois Ridge, our Oakville walkway projects include a compacted aggregate base, edge restraint, polymeric sand, and integrated lighting options. Every installation is built to withstand Oakville's 48-inch frost depth and seasonal ground movement.",
                'meta_title' => 'Walkways & Steps Oakville | Lush Landscape',
                'meta_description' => 'Interlocking walkways and steps in Oakville. Heritage-compliant materials, OBC-compliant risers, and premium pavers with consultation-led planning.',
                'og_title' => 'Walkways & Steps in Oakville | Lush Landscape',
                'og_description' => 'Professional walkway and step installation for Oakville homes. Safety, heritage compliance, and lasting curb appeal.',
                'sort_order' => 3,
            ],

            // ─── 4. Natural Stone & Flagstone ───────────────────────────────
            'Natural Stone & Flagstone' => [
                'page_title' => 'Natural Stone & Flagstone in Oakville',
                'h1' => 'Natural Stone & Flagstone Installation in Oakville',
                'local_intro' => "Natural stone carries a timeless character that resonates with Oakville's established neighbourhoods and heritage properties. Our natural stone division works with Ontario-quarried materials including Eramosa limestone, Algonquin flagstone, Muskoka granite, and Owen Sound ledgerock to create patios, walkways, accent walls, and water features that complement the refined aesthetic Oakville homeowners expect. For properties in the Old Oakville Heritage Conservation District, natural stone is often the preferred material to maintain architectural continuity.\n\nFlagstone installations in Oakville require careful base preparation adapted to the local soil profile. Near Bronte Creek and the lakeshore, sandy clay loam provides moderate drainage but still demands a minimum 12-inch compacted aggregate base to prevent seasonal shifting. Inland properties on heavier clay require enhanced drainage provisions beneath the stone surface. We use mortar-set or dry-laid techniques depending on the application and fill joints with polymeric sand or stone dust to prevent weed intrusion and maintain a clean appearance year after year.\n\nFrom hand-cut flagstone patios on Glen Abbey estates to armour stone retaining features along the creek corridors in River Oaks, our Oakville crews bring the craftsmanship that natural stone demands. Every piece is selected for colour consistency, thickness uniformity, and structural integrity before placement on your property.",
                'meta_title' => 'Natural Stone & Flagstone Oakville | Lush Landscape',
                'meta_description' => 'Natural stone and flagstone installation in Oakville. Ontario-quarried materials, heritage-compliant options, and expert craftsmanship with consultation-led planning.',
                'og_title' => 'Natural Stone & Flagstone in Oakville | Lush Landscape',
                'og_description' => 'Premium natural stone patios, flagstone walkways, and armour stone features for Oakville properties.',
                'sort_order' => 4,
            ],

            // ─── 5. Porcelain Pavers ────────────────────────────────────────
            'Porcelain Pavers' => [
                'page_title' => 'Porcelain Paver Installation in Oakville',
                'h1' => 'Porcelain Pavers in Oakville',
                'local_intro' => "Porcelain pavers have become a leading choice among Oakville homeowners who want a modern, low-maintenance surface without sacrificing visual sophistication. These 20mm-thick engineered tiles deliver the look of natural stone, wood, or polished concrete with zero water absorption, R11 slip rating, UV stability, and virtually no maintenance. For Oakville's discerning homeowners who expect premium aesthetics alongside practical performance, porcelain pavers offer an ideal balance.\n\nOur Oakville porcelain paver installations use pedestal or compacted-aggregate base systems depending on the application and site conditions. Rooftop terraces and pool surrounds typically use adjustable pedestal systems that allow for drainage underneath and easy access to membrane surfaces. Ground-level patios use the same compacted aggregate base as traditional interlocking, with levelling screed and open-graded joint material to accommodate Oakville's freeze-thaw cycles in Climate Zone 6a. We source 20mm porcelain pavers from leading Italian and Spanish manufacturers through Canadian distributors who guarantee freeze-thaw performance.\n\nFrom sleek contemporary terraces in Bronte waterfront properties to warm wood-look pool surrounds in Glen Abbey, porcelain pavers give Oakville homeowners design flexibility that no other material matches. The result is a surface that maintains its appearance season after season with nothing more than an occasional rinse.",
                'meta_title' => 'Porcelain Pavers Oakville | Lush Landscape',
                'meta_description' => 'Modern 20mm porcelain paver installation in Oakville. Zero absorption, R11 slip rating, frost-proof. Patios, pool decks, terraces.',
                'og_title' => 'Porcelain Pavers in Oakville | Lush Landscape',
                'og_description' => 'Contemporary porcelain paver installation for Oakville patios, terraces, and pool surrounds. Maintenance-free elegance.',
                'sort_order' => 5,
            ],

            // ─── 6. Concrete Driveways ──────────────────────────────────────
            'Concrete Driveways' => [
                'page_title' => 'Concrete Driveways in Oakville',
                'h1' => 'Concrete Driveways in Oakville',
                'local_intro' => "A concrete driveway delivers a clean, durable surface that suits Oakville properties ranging from modern builds in Palermo to established homes in Bronte. Our concrete driveway installations use 32 MPa air-entrained mix with 5 to 7 percent air content, the Ontario specification for freeze-thaw durability across the 48-inch frost depth that defines Oakville's Climate Zone 6a conditions. Every pour includes 10M rebar on 400mm centres, fibre-mesh reinforcement, and control joints calculated for slab dimensions and expected load.\n\nOakville's variable soil conditions require site-specific sub-base preparation. Properties near the lakeshore sit on sandy clay loam that drains moderately well, while inland lots toward Trafalgar Road encounter heavier clay that holds moisture and is prone to frost heave. We excavate to a minimum 12-inch depth in all cases, install compacted Granular A sub-base, and use a polyethylene vapour barrier to prevent moisture from migrating upward into the slab. Curing compound is applied immediately after finishing to ensure proper hydration and surface hardness.\n\nWe offer broom-finish, exposed aggregate, stamped patterns including ashlar slate, cobblestone, and wood-plank textures, and acid-stain decorative finishes. Our Oakville concrete crews handle complex curves, multi-level transitions, and integrated drainage channels with the precision this community expects.",
                'meta_title' => 'Concrete Driveways Oakville | Lush Landscape',
                'meta_description' => 'Concrete driveway installation in Oakville. 32 MPa air-entrained mix, rebar reinforcement, and decorative finishes with consultation-led planning.',
                'og_title' => 'Concrete Driveways in Oakville | Lush Landscape',
                'og_description' => 'High-strength concrete driveways for Oakville homes. Stamped, exposed aggregate, and broom-finish options.',
                'sort_order' => 6,
            ],

            // ─── 7. Concrete Patios & Walkways ─────────────────────────────
            'Concrete Patios & Walkways' => [
                'page_title' => 'Concrete Patios & Walkways in Oakville',
                'h1' => 'Concrete Patios & Walkways in Oakville',
                'local_intro' => "Architectural concrete transforms ordinary outdoor surfaces into design statements that match the calibre of Oakville's finest properties. Where homeowners want the durability of concrete with visual interest that elevates beyond a plain grey slab, our stamped, stained, and exposed aggregate finishes deliver exactly that. We pour patios, walkways, pool decks, and stepping-stone paths using the same 32 MPa air-entrained specifications as our driveways, ensuring long-term performance through Oakville's demanding freeze-thaw cycles.\n\nStamped concrete patterns include ashlar slate, European fan, random stone, cobblestone, and wood-plank textures applied with colour hardener and release agents for a natural, multi-toned finish. Exposed aggregate finishes reveal the natural stone within the concrete mix, creating a textured, slip-resistant surface ideal for pool surrounds and walkways. Acid-stain finishes produce translucent colour variations that mimic natural stone at a fraction of the cost, a popular choice among Oakville homeowners looking for high-end results with practical maintenance.\n\nOur Oakville concrete patio projects include proper sub-base preparation tailored to the local soil profile, positive drainage grading away from your foundation, and expansion joints where the patio meets the house. From Iroquois Ridge backyards to River Oaks pool decks, we back all concrete patio and walkway work with our workmanship warranty.",
                'meta_title' => 'Concrete Patios & Walkways Oakville | Lush Landscape',
                'meta_description' => 'Stamped and exposed aggregate concrete patios and walkways in Oakville. Decorative finishes, proper drainage, and lasting durability.',
                'og_title' => 'Concrete Patios & Walkways in Oakville | Lush Landscape',
                'og_description' => 'Architectural concrete patios and walkways for Oakville homes. Stamped, stained, and exposed aggregate options.',
                'sort_order' => 7,
            ],

            // ─── 8. Interlock Restoration & Sealing ─────────────────────────
            'Interlock Restoration & Sealing' => [
                'page_title' => 'Interlock Restoration & Sealing in Oakville',
                'h1' => 'Interlock Restoration & Sealing in Oakville',
                'local_intro' => "Years of Oakville weather, road salt tracking, and organic growth from the town's abundant mature tree canopy take a visible toll on interlocking surfaces. Faded colours, displaced polymeric sand, weed invasion, and efflorescence buildup make even high-quality installations look tired and neglected. Our 3-day interlock restoration process brings your existing pavers back to their original appearance and protects them for years to come.\n\nDay one involves thorough hot-water pressure washing at 3,000+ PSI with surface-cleaning attachments that remove embedded dirt, moss, algae, and efflorescence without damaging paver surfaces. Day two allows the surface to dry completely before we re-apply polymeric sand to all joints, ensuring proper activation and curing. Day three applies UV-resistant sealer in the homeowner's choice of matte, satin, or wet-look finish. The sealer locks in colour, prevents future staining, and makes ongoing maintenance as simple as a garden hose rinse.\n\nWe restore interlocking driveways, patios, walkways, and pool decks across Oakville from Old Oakville's heritage properties to Palermo's newer subdivisions. If your pavers are structurally sound but visually faded, restoration and sealing is the most cost-effective way to renew your Oakville hardscaping without the expense of full replacement.",
                'meta_title' => 'Interlock Restoration & Sealing Oakville | Lush',
                'meta_description' => 'Professional interlock restoration and sealing in Oakville. 3-day process: steam wash, polymeric sand, UV sealer. Renew your pavers.',
                'og_title' => 'Interlock Restoration & Sealing in Oakville',
                'og_description' => 'Restore faded interlocking surfaces in Oakville. Hot-water wash, polymeric sand, and protective sealer application.',
                'sort_order' => 8,
            ],

            // ─── 9. Interlock Repair (Lift & Relay) ─────────────────────────
            'Interlock Repair (Lift & Relay)' => [
                'page_title' => 'Interlock Repair (Lift & Relay) in Oakville',
                'h1' => 'Interlock Repair in Oakville: Lift & Relay',
                'local_intro' => "Sunken, heaving, or uneven interlocking pavers are more than an eyesore on Oakville's well-maintained streetscapes. They create tripping hazards and allow water to pool against your foundation, risking long-term structural damage. In Oakville, where clay soil movement inland and root intrusion from heritage-protected mature trees are the leading causes of paver displacement, a proper lift-and-relay repair addresses the root cause rather than masking the symptom.\n\nOur repair process starts with carefully removing the affected pavers and setting them aside. We then excavate the failed base material, identify the cause of the failure, whether that is insufficient base depth, poor compaction, tree root intrusion, or subsurface drainage issues from clay soils near Bronte Creek or Morrison Creek, and correct it. Fresh HPB aggregate is installed, compacted in lifts to 95 percent Standard Proctor density, and screeded to the correct elevation. The original pavers are then re-laid in their original pattern, new polymeric sand is applied to all joints, and the repaired area is compacted to match the surrounding surface.\n\nUnlike full replacement, lift-and-relay preserves your existing pavers and their naturally weathered colour, so the repair blends seamlessly with the undisturbed areas. We perform lift-and-relay repairs on driveways, patios, walkways, and pool decks across Oakville, from Glen Abbey to Old Oakville and every neighbourhood in between.",
                'meta_title' => 'Interlock Repair Oakville - Lift & Relay | Lush',
                'meta_description' => 'Permanent interlock repair in Oakville. Lift and relay sunken pavers with HPB base correction. Fix the cause, not just the symptom.',
                'og_title' => 'Interlock Repair (Lift & Relay) in Oakville',
                'og_description' => 'Fix sunken and heaving pavers in Oakville permanently. HPB base correction and precision re-levelling.',
                'sort_order' => 9,
            ],

            // ─── 10. Retaining Walls ────────────────────────────────────────
            'Retaining Walls' => [
                'page_title' => 'Retaining Walls in Oakville',
                'h1' => 'Retaining Wall Construction in Oakville',
                'local_intro' => "Oakville's terrain features significant grade changes along its creek valleys and the Niagara Escarpment's lower slopes, creating residential properties that require engineered retaining solutions. Whether you need a terraced backyard for usable outdoor living space, a front-yard retaining wall to manage a sloped entry, or erosion control along a property boundary near Bronte Creek, Fourteen Mile Creek, or Morrison Creek, our retaining wall division delivers structural solutions that perform and look exceptional.\n\nWe build with armour stone (natural Ontario boulders weighing 1,000 to 4,000 pounds each), precast concrete blocks from Allan Block, Cornerstone, and Unilock, and poured-in-place concrete where structural requirements dictate. Every retaining wall over 1.0 metre in retained height is designed to Ontario Building Code Part 4 standards, with geogrid reinforcement at calculated intervals, clear stone backfill, filter fabric, and weeping tile drainage connected to storm or daylight outlets. Oakville's 48-inch frost depth is factored into every footing design to prevent seasonal heaving.\n\nOur Oakville retaining wall projects include full engineering coordination where required, Conservation Halton permitting for properties within regulated areas along the town's three major creek corridors, and heritage review coordination for properties in designated conservation districts. Every structural component is backed by our 10-year workmanship warranty.",
                'meta_title' => 'Retaining Walls Oakville | Lush Landscape',
                'meta_description' => 'Retaining wall construction in Oakville. Armour stone, concrete block, geogrid reinforced. OBC-compliant, Conservation Halton permitted.',
                'og_title' => 'Retaining Walls in Oakville | Lush Landscape',
                'og_description' => 'Engineered retaining walls for Oakville properties. Armour stone and precast block with proper drainage.',
                'sort_order' => 10,
            ],

            // ─── 11. Sod Installation & Grading ─────────────────────────────
            'Sod Installation & Grading' => [
                'page_title' => 'Sod Installation & Grading in Oakville',
                'h1' => 'Sod Installation & Grading in Oakville',
                'local_intro' => "A healthy lawn starts with what is underneath it, and in Oakville the soil profile varies significantly from one neighbourhood to the next. Properties near the lakeshore in Old Oakville and Bronte sit on sandy clay loam that drains moderately well but lacks organic nutrients, while inland lots in Iroquois Ridge, Joshua Creek, and Palermo encounter heavier clay that compacts easily and resists root penetration. Our sod installation process addresses the soil profile first and the grass surface second, adapting our approach to each Oakville property's specific conditions.\n\nWe begin by establishing positive drainage grade away from your foundation at a minimum 2 percent slope for the first 6 feet, then transitioning to a gentler grade across the remainder of the yard. Topsoil is stripped from areas requiring grade adjustment, subgrade is shaped and compacted, and 4 to 6 inches of Triple-Mix (screened topsoil, peat, and compost blend) is spread and raked to final grade. Premium Kentucky Bluegrass sod is laid the same day it is cut from the farm, rolled for root contact, and given starter fertilizer and initial watering instructions. Properties adjacent to Conservation Halton regulated areas receive erosion control measures to protect nearby watercourses during installation.\n\nFrom post-construction grading on new builds in Palermo to full lawn renovation on mature estate lots along Lakeshore Road, our Oakville sod crews handle projects of every scale. We guarantee root establishment when our watering schedule is followed.",
                'meta_title' => 'Sod Installation & Grading Oakville | Lush',
                'meta_description' => 'Professional sod installation and grading in Oakville. Kentucky Bluegrass, Triple-Mix soil, precision drainage. Same-day installation.',
                'og_title' => 'Sod Installation & Grading in Oakville | Lush',
                'og_description' => 'Premium sod installation with proper grading and soil amendment for Oakville lawns. Instant results, lasting health.',
                'sort_order' => 11,
            ],

            // ─── 12. Artificial Turf ────────────────────────────────────────
            'Artificial Turf' => [
                'page_title' => 'Artificial Turf Installation in Oakville',
                'h1' => 'Artificial Turf in Oakville',
                'local_intro' => "For Oakville homeowners who want a perfectly green, maintenance-free lawn year-round, artificial turf delivers. No mowing, no watering, no fertilizing, and no brown patches through July drought or March snowmelt. Modern synthetic turf products have evolved far beyond the plastic-looking surfaces of a decade ago, and today's premium turf features multi-toned blade profiles, thatch layers for natural appearance, and UV stabilization that maintains colour integrity for 15 or more years, meeting the visual standards Oakville's premium neighbourhoods demand.\n\nOur Oakville artificial turf installations use a properly engineered base that addresses the local soil conditions. Near the lakeshore, sandy clay loam drains moderately well and requires standard aggregate base preparation. Inland properties on heavier clay demand enhanced drainage provisions including deeper aggregate beds and perforated subdrain lines. We excavate existing soil, install compacted aggregate base with positive drainage grade, lay geotextile separation fabric, and secure the turf with landscape spikes and infill material. Pet-friendly installations use antimicrobial infill and achieve drainage rates exceeding 30 inches per hour.\n\nWhether you need a backyard play area in River Oaks, a pet run in Joshua Creek, a putting green for golf enthusiasts near Glen Abbey, or a front-yard accent in Iroquois Ridge that looks immaculate without weekend maintenance, our Oakville artificial turf division delivers turnkey installations tailored to each property's requirements.",
                'meta_title' => 'Artificial Turf Oakville | Lush Landscape',
                'meta_description' => 'Artificial turf installation in Oakville. Pet-friendly, UV-stable, 30+ in/hr drainage. No mowing, no watering, always green.',
                'og_title' => 'Artificial Turf in Oakville | Lush Landscape',
                'og_description' => 'Maintenance-free artificial turf for Oakville lawns, pet areas, and play zones. Looks natural year-round.',
                'sort_order' => 12,
            ],

            // ─── 13. Garden Design & Planting ───────────────────────────────
            'Garden Design & Planting' => [
                'page_title' => 'Garden Design & Planting in Oakville',
                'h1' => 'Garden Design & Planting in Oakville',
                'local_intro' => "Oakville sits in USDA Climate Zone 6a with meaningful Lake Ontario moderation, giving homeowners access to a generous palette of hardy perennials, ornamental grasses, flowering shrubs, and shade trees. The town's heritage tree protection bylaws and mature canopy in neighbourhoods like Old Oakville and Bronte add an important layer of consideration to every planting plan. Our garden design service translates Oakville's botanical potential into curated designs that provide four-season visual interest, support local pollinators, and respect the existing tree canopy.\n\nWe start every garden design project with an on-site assessment of sun exposure, soil type, drainage patterns, and existing vegetation, paying particular attention to heritage-designated trees and their root protection zones. Planting plans specify cultivars proven in Zone 6a conditions, sourced from Ontario-accredited nurseries. Garden bed construction includes excavation of compacted subsoil, amendment with premium planting mix, and installation of steel or aluminum edging for clean, permanent bed lines. Mulch is applied at 3-inch depth for moisture retention and weed suppression.\n\nFrom pollinator gardens in Iroquois Ridge to privacy screening hedges in Palermo and estate foundation plantings along Lakeshore Road, our Oakville garden design team creates outdoor spaces that grow more beautiful with every passing season. All plant material is backed by a one-year health guarantee when our care instructions are followed.",
                'meta_title' => 'Garden Design & Planting Oakville | Lush',
                'meta_description' => 'Professional garden design and planting in Oakville. Ontario-native perennials, heritage-compliant plans, and four-season interest with consultation-led planning.',
                'og_title' => 'Garden Design & Planting in Oakville | Lush',
                'og_description' => 'Custom garden design with Ontario-native plants for Oakville properties. Beautiful, sustainable, and heritage-compliant.',
                'sort_order' => 13,
            ],

            // ─── 14. Landscape Lighting ─────────────────────────────────────
            'Landscape Lighting' => [
                'page_title' => 'Landscape Lighting in Oakville',
                'h1' => 'Landscape Lighting in Oakville',
                'local_intro' => "Professional landscape lighting transforms your Oakville property after dark, extending usable outdoor hours, enhancing security, and highlighting the architectural and landscape features that define your home's character. In a community where heritage homes, mature tree canopies, and premium estate landscaping set the visual standard, a thoughtfully designed lighting scheme adds a layer of evening elegance that few other investments can match. Our lighting designs go beyond simple path lights to create layered illumination schemes that include uplighting, downlighting, wash lighting, and accent techniques.\n\nWe use low-voltage LED systems powered by commercial-grade transformers with built-in timers and WiFi smart controls compatible with phone apps and home automation platforms. All fixtures are solid brass or marine-grade aluminum, selected for their ability to withstand Oakville's freeze-thaw cycles and salt exposure without corroding or discolouring. Direct-burial cable rated for outdoor use is trenched to below-grade depth to protect against damage. Heritage-district properties receive fixture selections that complement the neighbourhood's architectural character while delivering modern performance.\n\nFrom driveway pillar lighting along Lakeshore Road to backyard patio and garden illumination in Glen Abbey and River Oaks, our Oakville lighting projects are designed by certified professionals who understand colour temperature, beam angles, and lumen requirements for each application. Every system is sized for future expansion so adding fixtures later does not require transformer upgrades.",
                'meta_title' => 'Landscape Lighting Oakville | Lush Landscape',
                'meta_description' => 'Professional LED landscape lighting in Oakville. Brass fixtures, WiFi smart controls, and custom design. Extend your outdoor living hours.',
                'og_title' => 'Landscape Lighting in Oakville | Lush Landscape',
                'og_description' => 'Custom landscape lighting design and installation for Oakville homes. Low-voltage LED, brass fixtures, smart controls.',
                'sort_order' => 14,
            ],

        ];
    }
}
