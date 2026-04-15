<?php

namespace Database\Seeders\Content;

use App\Models\City;
use App\Models\Service;
use App\Models\ServiceCityPage;
use Illuminate\Database\Seeder;

class BramptonContentSeeder extends Seeder
{
    public function run(): void
    {
        $city = City::where('name', 'Brampton')->firstOrFail();
        $pages = $this->getPages();

        $neighborhoods = [
            'Heart Lake', 'Bramalea', 'Springdale', 'Mount Pleasant', 'Castlemore', 'Sandalwood',
            'Gore Meadows', 'Bram West', "Fletcher's Meadow", 'Credit Valley',
            'Churchville', 'Snelgrove',
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
                        'text' => 'Book a Consultation in Brampton',
                        'url' => '/request-quote?service='.urlencode($service->name).'&city=Brampton',
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
                'page_title' => 'Interlocking Driveways in Brampton',
                'h1' => 'Interlocking Driveways Built for Brampton',
                'local_intro' => "Brampton is one of Canada's fastest-growing cities, and thousands of homes across its expanding subdivisions still sit behind builder-grade concrete driveways that were never designed to last. From Castlemore's established estate lots to the newer developments pushing north through Gore Meadows and Brampton North, homeowners are replacing aging concrete with interlocking driveways engineered to handle the unique soil and climate pressures of this region. The Halton Till clay that underlies most of Brampton carries the highest shrink-swell potential in the GTA, meaning base preparation is not optional but rather the single most important factor in driveway longevity.\n\nOur Brampton interlocking driveway installations begin with a comprehensive site assessment covering soil composition, lot drainage, and vehicle load requirements. We excavate to a minimum 16-inch depth to account for the 48-inch frost penetration depth in this Zone 5b climate, install compacted Granular A sub-base in measured lifts, add a 1-inch HPB levelling course, and finish with premium pavers from Unilock, Techo-Bloc, or Belgard rated at 8,000+ PSI compressive strength. Herringbone or 45-degree bond patterns maximize interlock strength under daily vehicle traffic, and polymeric sand jointing with soldier-course edge restraint anchored below the frost line keeps every unit locked tight through 125 cm of average annual snowfall.\n\nWhether you are upgrading a 20-year-old builder pour in Bramalea or designing a grand entrance for a custom home in Credit Valley, our ICPI-certified crews bring the same precision to every Brampton project. Each interlocking driveway complies with Brampton Zoning By-Law 270-2004 coverage limits and is backed by our 10-year workmanship warranty.",
                'meta_title' => 'Interlocking Driveways Brampton | Lush Landscape',
                'meta_description' => 'Custom interlocking driveway installation in Brampton. ICPI-certified, 8,000+ PSI pavers, engineered for Halton Till clay with consultation-led planning.',
                'og_title' => 'Interlocking Driveways in Brampton | Lush Landscape',
                'og_description' => 'Premium interlocking driveway construction for Brampton homeowners. Engineered for Halton Till clay soils and Zone 5b winters.',
                'sort_order' => 1,
            ],

            // ─── 2. Interlocking Patios & Backyard Living ───────────────────
            'Interlocking Patios & Backyard Living' => [
                'page_title' => 'Interlocking Patios & Outdoor Living in Brampton',
                'h1' => 'Interlocking Patios and Backyard Living Spaces in Brampton',
                'local_intro' => "Brampton families are discovering that a well-designed backyard can deliver the same lifestyle value as an indoor renovation at a fraction of the cost per square foot. Across neighborhoods like Springdale, Sandalwood, and Heart Lake, homeowners are investing in interlocking patios that serve as the foundation for complete outdoor living environments with BBQ islands, fire pits, seating walls, and integrated lighting. With Brampton's growing season running from early May to early October, a properly built patio extends your usable living space by five full months each year.\n\nThe heavy Halton Till clay soil across Brampton presents specific drainage challenges that demand engineering solutions, not shortcuts. We design every patio project with positive grade away from the foundation at a minimum 2 percent slope, integrate catch basins in low-lying areas, and use open-graded base systems on lots where clay impermeability creates ponding risk. Every installation includes compacted Granular A sub-base, HPB levelling course, and premium pavers selected for both structural performance and the design aesthetic your family envisions.\n\nFrom a simple entertaining pad behind a Fletchers Creek townhouse to a multi-level outdoor room with kitchen, fireplace, and pergola on a Castlemore estate lot, our Brampton design team manages every project from concept through completion. We coordinate with gas, electrical, and plumbing trades, delivering a single-source experience backed by our 10-year workmanship warranty.",
                'meta_title' => 'Interlocking Patios Brampton | Lush Landscape',
                'meta_description' => 'Custom interlocking patio installation in Brampton. Outdoor kitchens, fire pits, seating walls. Engineered for clay soils with consultation-led planning.',
                'og_title' => 'Interlocking Patios & Backyard Living in Brampton',
                'og_description' => 'Transform your Brampton backyard with custom interlocking patios, outdoor kitchens, and fire features designed for Halton Till clay.',
                'sort_order' => 2,
            ],

            // ─── 3. Walkways & Steps ────────────────────────────────────────
            'Walkways & Steps' => [
                'page_title' => 'Interlocking Walkways & Steps in Brampton',
                'h1' => 'Walkways and Steps for Brampton Homes',
                'local_intro' => "In a city that receives 125 cm of snowfall annually and endures repeated freeze-thaw cycles from November through April, a poorly built front walkway becomes a liability. Brampton homeowners across Mount Pleasant, Central Brampton, and Bram West are replacing settling concrete paths and uneven stepping stones with properly engineered interlocking walkways and steps that eliminate tripping hazards while transforming curb appeal. Every walkway and step installation we complete in Brampton follows Ontario Building Code requirements for riser height, tread depth, and handrail placement.\n\nWe design walkways that integrate with your existing hardscaping, whether that means matching paver colours and patterns with an interlocking driveway or introducing natural stone accents for visual contrast. For properties with grade changes, we build landing pads at code-required intervals, install non-slip tread surfaces rated for wet and icy conditions, and ensure positive drainage away from the path to prevent ice formation. Steps are constructed on reinforced concrete cores with paver or stone cladding for structural permanence that outlasts Brampton's demanding winter conditions.\n\nFrom replacing a cracked builder-grade walkway on a 25-year-old Bramalea home to creating an elaborate garden pathway with integrated lighting on a Gore Meadows property, our Brampton walkway projects include compacted aggregate base, edge restraint, and polymeric sand to prevent weed growth and paver migration. Brampton Zoning By-Law 270-2004 front-yard coverage limits are factored into every design.",
                'meta_title' => 'Walkways & Steps Brampton | Lush Landscape',
                'meta_description' => 'Interlocking walkways and steps in Brampton. Non-slip surfaces, OBC-compliant risers, premium pavers. Safety meets curb appeal with consultation-led planning.',
                'og_title' => 'Walkways & Steps in Brampton | Lush Landscape',
                'og_description' => 'Professional walkway and step installation for Brampton homes. Engineered for 125 cm annual snowfall and Halton Till clay.',
                'sort_order' => 3,
            ],

            // ─── 4. Natural Stone & Flagstone ───────────────────────────────
            'Natural Stone & Flagstone' => [
                'page_title' => 'Natural Stone & Flagstone in Brampton',
                'h1' => 'Natural Stone and Flagstone Installation in Brampton',
                'local_intro' => "Natural stone brings a timeless, organic character to Brampton properties that manufactured pavers simply cannot replicate. As one of Canada's fastest-growing cities, Brampton is home to thousands of newer builds that benefit enormously from the visual warmth and uniqueness that hand-selected stone provides. Our natural stone division works with Ontario-quarried materials including Eramosa limestone, Algonquin flagstone, Muskoka granite, and Owen Sound ledgerock to create patios, walkways, accent walls, and water features that feel like they have always belonged on your property.\n\nThe heavy Halton Till clay beneath Brampton's subdivisions makes base preparation for natural stone installations especially critical. We install a minimum 12-inch compacted aggregate base under all flagstone work, use mortar-set or dry-laid techniques depending on the application and client preference, and fill joints with polymeric sand or stone dust to prevent weed intrusion and insect nesting. Armour stone installations use boulders sourced from Ontario quarries, placed with equipment precision and anchored against the seasonal soil movement that Brampton's extreme shrink-swell clay produces. For properties near the Credit River tributaries or Etobicoke Creek, we coordinate with Credit Valley Conservation or TRCA where regulated-area permits are required.\n\nFrom hand-cut flagstone patios in Credit Valley to armour stone terracing on sloped lots in Sandalwood, our Brampton crews bring the craftsmanship that natural stone demands. Every piece is selected for colour consistency, thickness uniformity, and structural integrity before it reaches your property.",
                'meta_title' => 'Natural Stone & Flagstone Brampton | Lush Landscape',
                'meta_description' => 'Natural stone and flagstone installation in Brampton. Ontario-quarried materials, hand-cut patios, armour stone features with consultation-led planning.',
                'og_title' => 'Natural Stone & Flagstone in Brampton | Lush Landscape',
                'og_description' => 'Premium natural stone patios, flagstone walkways, and armour stone features for Brampton properties. Ontario-quarried materials.',
                'sort_order' => 4,
            ],

            // ─── 5. Porcelain Pavers ────────────────────────────────────────
            'Porcelain Pavers' => [
                'page_title' => 'Porcelain Paver Installation in Brampton',
                'h1' => 'Porcelain Pavers for Brampton Properties',
                'local_intro' => "Porcelain pavers are rapidly gaining popularity among Brampton homeowners who want the appearance of natural stone or hardwood without the maintenance demands those materials carry. These 20mm-thick engineered tiles deliver zero water absorption, R11 slip rating, UV stability, and stain resistance that makes them virtually maintenance-free. For Brampton's newer subdivisions in Springdale, Gore Meadows, and Brampton North, where homeowners want a clean, contemporary aesthetic, porcelain pavers provide a design vocabulary that traditional concrete products cannot match.\n\nOur Brampton porcelain paver installations use pedestal or compacted-aggregate base systems depending on the specific application. Elevated installations on second-storey decks or rooftop terraces use adjustable pedestal systems that allow for drainage underneath and easy access to waterproof membranes. Ground-level patios and walkways use the same compacted aggregate base as traditional interlocking, with levelling screed and open-graded joint material to accommodate the thermal expansion that Brampton's Zone 5b temperature swings produce. We source 20mm porcelain pavers from leading Italian and Spanish manufacturers through Canadian distributors who guarantee freeze-thaw performance for our climate.\n\nFrom sleek charcoal stone-look terraces on Mount Pleasant properties to warm wood-grain pool surrounds in Castlemore, porcelain pavers give Brampton homeowners design flexibility that suits both modern and transitional architectural styles. Peel Region's encouragement of on-site water retention makes porcelain's pedestal system, which allows natural drainage beneath the surface, an increasingly smart choice.",
                'meta_title' => 'Porcelain Pavers Brampton | Lush Landscape',
                'meta_description' => 'Modern 20mm porcelain paver installation in Brampton. Zero absorption, R11 slip rating, frost-proof. Patios, pool decks, terraces.',
                'og_title' => 'Porcelain Pavers in Brampton | Lush Landscape',
                'og_description' => 'Contemporary porcelain paver installation for Brampton patios, terraces, and pool surrounds. Maintenance-free elegance.',
                'sort_order' => 5,
            ],

            // ─── 6. Concrete Driveways ──────────────────────────────────────
            'Concrete Driveways' => [
                'page_title' => 'Concrete Driveways in Brampton',
                'h1' => 'Concrete Driveway Installation in Brampton',
                'local_intro' => "For Brampton homeowners who prefer the clean simplicity of a poured surface, a properly engineered concrete driveway delivers lasting performance at a competitive price point. With thousands of homes across Bramalea, Heart Lake, and Central Brampton sitting on builder-grade concrete that is now 20 to 30 years old and showing cracks, spalling, and settlement, demand for professional concrete replacement has never been higher. Our concrete driveway installations use 32 MPa air-entrained mix with 5 to 7 percent air content, the Ontario specification for freeze-thaw durability in Zone 5b conditions.\n\nBrampton's dominant Halton Till clay soil, with its extreme shrink-swell behaviour, is the primary reason builder-grade driveways fail prematurely. We address this by excavating to a minimum 12-inch depth, installing compacted Granular A sub-base, and placing a polyethylene vapour barrier to prevent moisture migration into the slab. Every pour includes 10M rebar on 400mm centres, fibre-mesh reinforcement, and control joints calculated for the slab dimensions and expected load. Curing compound is applied immediately after finishing to ensure proper hydration and surface hardness. All driveways comply with Brampton Zoning By-Law 270-2004, which limits single driveways to 6.0 m and double driveways to 7.3 m in width.\n\nWe offer broom-finish, exposed aggregate, stamped patterns including ashlar slate, cobblestone, and wood-plank textures, and acid-stain decorative finishes. Our Brampton concrete crews handle complex configurations including flared aprons, multi-level transitions, and integrated drainage channels that direct water away from foundations and toward the municipal storm system.",
                'meta_title' => 'Concrete Driveways Brampton | Lush Landscape',
                'meta_description' => 'Concrete driveway installation in Brampton. 32 MPa air-entrained mix, rebar reinforced, built for Halton Till clay with consultation-led planning.',
                'og_title' => 'Concrete Driveways in Brampton | Lush Landscape',
                'og_description' => 'High-strength concrete driveways for Brampton homes. Stamped, exposed aggregate, and broom-finish options available.',
                'sort_order' => 6,
            ],

            // ─── 7. Concrete Patios & Walkways ─────────────────────────────
            'Concrete Patios & Walkways' => [
                'page_title' => 'Concrete Patios & Walkways in Brampton',
                'h1' => 'Concrete Patios and Walkways in Brampton',
                'local_intro' => "Architectural concrete gives Brampton homeowners a way to achieve the visual richness of natural stone or designer pavers with the structural continuity of a single poured slab. Across Brampton's rapidly expanding neighborhoods, from Sandalwood's family properties to Bram West's newer builds, stamped, stained, and exposed aggregate concrete surfaces are delivering outdoor living areas that perform through the city's demanding four-season climate. We pour patios, walkways, pool decks, and landing pads using the same 32 MPa air-entrained specifications as our driveways, ensuring every surface meets Ontario's freeze-thaw durability standard.\n\nStamped concrete patterns available for Brampton projects include ashlar slate, European fan, random stone, cobblestone, and wood-plank textures applied with integral colour hardener and release agents that produce a natural, multi-toned finish. Exposed aggregate finishes reveal the natural stone within the concrete mix, creating a textured, slip-resistant surface ideal for pool surrounds and garden walkways. Acid-stain finishes produce translucent colour variations that mimic quarried stone at a fraction of the material cost, a popular choice among Brampton homeowners upgrading builder-grade plain grey slabs.\n\nEvery Brampton concrete patio project includes sub-base preparation engineered for Halton Till clay, positive drainage grading away from the foundation, and expansion joints where the slab meets the house. Peel Region's encouragement of on-site stormwater retention is integrated into our grading plans. All concrete patio and walkway work is backed by our workmanship warranty.",
                'meta_title' => 'Concrete Patios & Walkways Brampton | Lush',
                'meta_description' => 'Stamped and exposed aggregate concrete patios and walkways in Brampton. Decorative finishes, engineered drainage, lasting durability.',
                'og_title' => 'Concrete Patios & Walkways in Brampton | Lush Landscape',
                'og_description' => 'Architectural concrete patios and walkways for Brampton homes. Stamped, stained, and exposed aggregate options.',
                'sort_order' => 7,
            ],

            // ─── 8. Interlock Restoration & Sealing ─────────────────────────
            'Interlock Restoration & Sealing' => [
                'page_title' => 'Interlock Restoration & Sealing in Brampton',
                'h1' => 'Interlock Restoration and Sealing Services in Brampton',
                'local_intro' => "Brampton's housing stock is dominated by homes built within the last 20 to 30 years, and many of those properties received interlocking driveways, patios, or walkways during the original build or as early upgrades. After a decade or more of Ontario winters, road salt tracking, and organic growth, even quality installations show faded colours, displaced polymeric sand, weed invasion, and efflorescence buildup. Our 3-day interlock restoration process brings your existing Brampton pavers back to their original vibrancy and locks in that appearance for years to come.\n\nDay one involves thorough hot-water pressure washing at 3,000+ PSI with rotary surface-cleaning attachments that remove embedded dirt, moss, algae, and efflorescence without damaging paver surfaces. This step alone reveals how much colour your pavers have been hiding under years of grime. Day two allows the surface to dry completely before we re-apply premium polymeric sand to all joints, ensuring proper activation and curing. Day three applies UV-resistant sealer in your choice of matte, satin, or wet-look finish. The sealer locks in colour, prevents future staining from oil, salt, and organics, and makes ongoing maintenance as simple as rinsing with a garden hose.\n\nWe restore interlocking surfaces across every Brampton neighborhood, from established Bramalea subdivisions to newer developments in Springdale and Gore Meadows. If your pavers are structurally sound but visually tired, restoration and sealing is the most cost-effective way to renew your hardscaping without a full replacement.",
                'meta_title' => 'Interlock Restoration & Sealing Brampton | Lush',
                'meta_description' => 'Professional interlock restoration and sealing in Brampton. 3-day process: power wash, polymeric sand, UV sealer. Renew your pavers.',
                'og_title' => 'Interlock Restoration & Sealing in Brampton',
                'og_description' => 'Restore faded interlocking surfaces across Brampton. Hot-water wash, polymeric sand, and protective UV sealer application.',
                'sort_order' => 8,
            ],

            // ─── 9. Interlock Repair (Lift & Relay) ─────────────────────────
            'Interlock Repair (Lift & Relay)' => [
                'page_title' => 'Interlock Repair (Lift & Relay) in Brampton',
                'h1' => 'Interlock Lift and Relay Repair in Brampton',
                'local_intro' => "Sunken, heaving, or uneven interlocking pavers are among the most common hardscaping complaints in Brampton, and the cause is almost always the same. The Halton Till clay that sits beneath most of this city has the highest shrink-swell potential in the GTA, expanding when saturated and contracting during dry periods. This relentless cycle displaces base material, creates voids, and pushes pavers out of alignment. A proper lift-and-relay repair addresses the root cause beneath the surface rather than just resetting pavers on the same failing base.\n\nOur Brampton repair process begins with carefully removing the affected pavers and setting them aside for reinstallation. We then excavate the failed base material and identify the specific cause of failure, whether that is insufficient original base depth, poor compaction, root intrusion from nearby trees, or subsurface drainage deficiencies. Fresh HPB aggregate is installed, compacted in measured lifts to 95 percent Standard Proctor density, and screeded to the correct elevation. The original pavers are then re-laid in their original pattern, new polymeric sand is applied to all joints, and the repaired area is plate-compacted to match the surrounding surface height and density.\n\nLift-and-relay preserves your existing pavers and their naturally weathered colour, so the repair blends seamlessly with undisturbed areas. We perform these repairs on driveways, patios, walkways, and pool decks throughout Brampton, from Heart Lake and Mount Pleasant to Fletchers Creek and Credit Valley. Many of these repairs are on installations that are 15 to 25 years old and were originally built without adequate base depth for the extreme clay conditions in this area.",
                'meta_title' => 'Interlock Repair Brampton - Lift & Relay | Lush',
                'meta_description' => 'Permanent interlock repair in Brampton. Lift and relay sunken pavers with HPB base correction for Halton Till clay with consultation-led planning.',
                'og_title' => 'Interlock Repair (Lift & Relay) in Brampton',
                'og_description' => 'Fix sunken and heaving pavers in Brampton permanently. HPB base correction engineered for Halton Till clay soils.',
                'sort_order' => 9,
            ],

            // ─── 10. Retaining Walls ────────────────────────────────────────
            'Retaining Walls' => [
                'page_title' => 'Retaining Walls in Brampton',
                'h1' => 'Retaining Wall Construction in Brampton',
                'local_intro' => "Brampton's landscape includes significant grade changes along the Etobicoke Creek corridor, the Credit River tributaries, and the Humber River headwaters that run through several residential neighborhoods. Properties in Credit Valley, Bram West, and Castlemore frequently require engineered retaining solutions to manage slopes, create usable tiered outdoor living areas, or stabilize eroding embankments. The heavy Halton Till clay that dominates Brampton's subsurface adds hydrostatic pressure behind retaining structures, making proper drainage design as important as the wall itself.\n\nWe build retaining walls using armour stone (natural Ontario boulders weighing 1,000 to 4,000 pounds each), precast concrete blocks from Allan Block, Cornerstone, and Unilock, and poured-in-place concrete where structural loads demand it. Every retaining wall over 1.0 metre in retained height is designed to Ontario Building Code Part 4 standards, with geogrid reinforcement at calculated intervals, clear stone backfill, filter fabric, and weeping tile drainage connected to storm or daylight outlets. For walls on Brampton properties within Credit Valley Conservation or TRCA regulated areas, we handle the full permitting process so homeowners do not have to navigate conservation authority requirements on their own.\n\nFrom terracing a steep backyard in Sandalwood to building a front-yard retaining feature that brings a sloped Springdale lot up to street level, our Brampton retaining wall projects are engineered for the long term. Peel Region's on-site retention guidelines are factored into every drainage design, and all structural components are backed by our 10-year workmanship warranty.",
                'meta_title' => 'Retaining Walls Brampton | Lush Landscape',
                'meta_description' => 'Retaining wall construction in Brampton. Armour stone, concrete block, geogrid reinforced. OBC-compliant, CVC-permitted with consultation-led planning.',
                'og_title' => 'Retaining Walls in Brampton | Lush Landscape',
                'og_description' => 'Engineered retaining walls for Brampton properties. Armour stone and precast block with proper drainage for Halton Till clay.',
                'sort_order' => 10,
            ],

            // ─── 11. Sod Installation & Grading ─────────────────────────────
            'Sod Installation & Grading' => [
                'page_title' => 'Sod Installation & Grading in Brampton',
                'h1' => 'Sod Installation and Grading for Brampton Properties',
                'local_intro' => "A healthy lawn in Brampton starts with understanding what lies beneath it. The compacted Halton Till clay subsoil found across most of this city retains water during wet periods and cracks during dry spells, creating conditions that starve grass roots of oxygen and then desiccate them in rapid succession. Builder-grade grading on many of Brampton's 20 to 30-year-old homes often left minimal topsoil depth and questionable drainage slopes, which is why so many established lawns in Bramalea, Heart Lake, and Central Brampton struggle year after year despite regular watering and fertilizing.\n\nOur sod installation process addresses the soil profile before the grass surface. We establish positive drainage grade away from the foundation at a minimum 2 percent slope for the first 6 feet, then transition to a gentler grade across the remainder of the yard. Existing topsoil is stripped from areas requiring grade adjustment, the subgrade is shaped and compacted for stability, and 4 to 6 inches of Triple-Mix (screened topsoil, peat, and compost blend) is spread and raked to final grade. Premium Kentucky Bluegrass sod is laid the same day it is cut from the farm, rolled for root-to-soil contact, and given starter fertilizer along with a detailed watering schedule tailored to Brampton's growing season from early May to early October.\n\nFrom post-construction grading on new builds in Gore Meadows and Brampton North to full lawn renovation on mature lots in Mount Pleasant and Fletchers Creek, our Brampton sod crews handle projects of every scale. We guarantee root establishment when our watering instructions are followed.",
                'meta_title' => 'Sod Installation & Grading Brampton | Lush',
                'meta_description' => 'Professional sod installation and grading in Brampton. Kentucky Bluegrass, Triple-Mix soil, precision drainage grading with consultation-led planning.',
                'og_title' => 'Sod Installation & Grading in Brampton | Lush',
                'og_description' => 'Premium sod installation with proper grading and soil amendment for Brampton lawns. Engineered for Halton Till clay soils.',
                'sort_order' => 11,
            ],

            // ─── 12. Artificial Turf ────────────────────────────────────────
            'Artificial Turf' => [
                'page_title' => 'Artificial Turf Installation in Brampton',
                'h1' => 'Artificial Turf for Brampton Homes',
                'local_intro' => "Brampton homeowners who want a consistently green, maintenance-free lawn without weekly mowing, seasonal fertilizing, or daily watering through summer dry spells are turning to artificial turf in growing numbers. Modern synthetic turf products have evolved far beyond the plastic-looking surfaces of a decade ago. Today's premium turf features multi-toned blade profiles, thatch layers for natural appearance, and UV stabilization that maintains colour integrity for 15 or more years through Brampton's Zone 5b sun exposure and 125 cm of annual snowfall.\n\nThe Halton Till clay beneath Brampton's subdivisions creates unique drainage challenges for any surface installation, and artificial turf is no exception. Our installations begin with excavating existing soil to the required depth, installing compacted aggregate base with positive drainage grade away from the home's foundation, laying geotextile separation fabric over the aggregate, and securing the turf with landscape spikes and infill material. Pet-friendly installations use antimicrobial infill and achieve drainage rates exceeding 30 inches per hour, ensuring rapid moisture clearance even during Brampton's intense summer downpours when clay-saturated ground cannot absorb additional water.\n\nWhether you need a backyard play area for children in Springdale, a pet run that stays clean through mud season in Sandalwood, a putting green in your Castlemore backyard, or a front-yard accent in Heart Lake that looks immaculate without weekend maintenance, our Brampton artificial turf division delivers turnkey installations with a clean, natural finish.",
                'meta_title' => 'Artificial Turf Brampton | Lush Landscape',
                'meta_description' => 'Artificial turf installation in Brampton. Pet-friendly, UV-stable, 30+ in/hr drainage on clay soils. No mowing, always green.',
                'og_title' => 'Artificial Turf in Brampton | Lush Landscape',
                'og_description' => 'Maintenance-free artificial turf for Brampton lawns, pet areas, and play zones. Engineered drainage for Halton Till clay.',
                'sort_order' => 12,
            ],

            // ─── 13. Garden Design & Planting ───────────────────────────────
            'Garden Design & Planting' => [
                'page_title' => 'Garden Design & Planting in Brampton',
                'h1' => 'Garden Design and Planting Services in Brampton',
                'local_intro' => "Brampton sits squarely in USDA Hardiness Zone 5b, where winter temperatures can dip to minus 26 degrees Celsius and the growing season extends from early May to early October. This climate profile, combined with the heavy Halton Till clay soil that dominates the city, dictates the plant palette and soil amendment strategy required for gardens that actually thrive rather than merely survive. Our garden design service translates Brampton's specific growing conditions into curated planting plans that deliver four-season visual interest, support local pollinators, and build stronger root systems year over year.\n\nEvery Brampton garden design project starts with an on-site assessment covering sun exposure patterns, soil composition, drainage behaviour, and existing vegetation worth preserving. Planting plans specify cultivars proven in Zone 5b conditions, sourced from Ontario-accredited nurseries that guarantee plant health at delivery. Garden bed construction includes excavation of compacted clay subsoil, amendment with premium planting mix formulated for clay-heavy conditions, and installation of steel or aluminum edging for clean, permanent bed lines. Mulch is applied at 3-inch depth for moisture retention and weed suppression, particularly important in Brampton where summer heat can bake exposed clay into an impenetrable surface.\n\nFrom pollinator gardens near the Etobicoke Creek corridor to privacy screening hedges along busy streets in Bramalea, and from ornamental front-yard plantings in Credit Valley to shade gardens beneath mature trees in Mount Pleasant, our Brampton garden design team creates outdoor spaces that look better with every passing season. All plant material is backed by a one-year health guarantee when our care instructions are followed.",
                'meta_title' => 'Garden Design & Planting Brampton | Lush',
                'meta_description' => 'Professional garden design and planting in Brampton. Zone 5b hardy plants, Ontario nursery stock, four-season interest. Free consultations.',
                'og_title' => 'Garden Design & Planting in Brampton | Lush',
                'og_description' => 'Custom garden design with Zone 5b hardy plants for Brampton properties. Beautiful, sustainable, and pollinator-friendly.',
                'sort_order' => 13,
            ],

            // ─── 14. Landscape Lighting ─────────────────────────────────────
            'Landscape Lighting' => [
                'page_title' => 'Landscape Lighting in Brampton',
                'h1' => 'Landscape Lighting Design and Installation in Brampton',
                'local_intro' => "Professional landscape lighting transforms your Brampton property after dark, extending usable outdoor hours from the short days of November through the long summer evenings of July. In a city growing as rapidly as Brampton, where new subdivisions in Gore Meadows, Brampton North, and Bram West are adding thousands of homes each year, landscape lighting also distinguishes your property from identical builds on the same street. Our lighting designs go beyond simple path markers to create layered illumination schemes using uplighting, downlighting, wash lighting, moonlighting, and accent techniques that highlight architectural features, mature trees, and hardscaping details.\n\nWe use low-voltage LED systems powered by commercial-grade transformers with built-in timers and WiFi smart controls compatible with phone apps and popular home automation platforms. All fixtures are solid brass or marine-grade aluminum, selected specifically for their ability to withstand Ontario's freeze-thaw cycles, road salt exposure, and the moisture retention that Brampton's Halton Till clay produces around buried components. Direct-burial cable rated for outdoor use is trenched below grade to protect against lawn care equipment damage and frost heave displacement.\n\nFrom driveway pillar lighting and address illumination in Castlemore to backyard patio, fire pit, and garden bed lighting in Springdale and Sandalwood, our Brampton lighting projects are designed by certified professionals who understand colour temperature selection, beam angle calculation, and lumen requirements for each specific application. Every system is sized with spare transformer capacity so adding fixtures later does not require equipment upgrades.",
                'meta_title' => 'Landscape Lighting Brampton | Lush Landscape',
                'meta_description' => 'Professional LED landscape lighting in Brampton. Brass fixtures, WiFi smart controls, custom design for any property with consultation-led planning.',
                'og_title' => 'Landscape Lighting in Brampton | Lush Landscape',
                'og_description' => 'Custom landscape lighting design and installation for Brampton homes. Low-voltage LED, brass fixtures, smart controls.',
                'sort_order' => 14,
            ],

        ];
    }
}
