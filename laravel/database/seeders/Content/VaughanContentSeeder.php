<?php

namespace Database\Seeders\Content;

use App\Models\City;
use App\Models\Service;
use App\Models\ServiceCityPage;
use Illuminate\Database\Seeder;

class VaughanContentSeeder extends Seeder
{
    public function run(): void
    {
        $city = City::where('name', 'Vaughan')->firstOrFail();
        $pages = $this->getPages();

        $neighborhoods = [
            'Woodbridge', 'Maple', 'Kleinburg', 'Thornhill', 'Concord', 'Vellore Village',
            'Patterson', 'Sonoma Heights', 'Islington Woods', 'Nashville',
            'Elder Mills', 'Carrville',
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
                        'text' => 'Book a Consultation in Vaughan',
                        'url' => '/request-quote?service='.urlencode($service->name).'&city=Vaughan',
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
                'page_title' => 'Interlocking Driveways in Vaughan',
                'h1' => 'Interlocking Driveways in Vaughan',
                'local_intro' => "Vaughan homeowners take pride in their properties, and nowhere is that more visible than the driveway. Across Woodbridge, where Italian-Canadian families have invested in hardscaping for generations, a beautifully patterned interlocking driveway is a statement of craftsmanship and curb appeal. The Newmarket Till clay soils that underlie most of Vaughan retain moisture aggressively, expand during freeze cycles, and shift under vehicle loads if base preparation falls short. Our Vaughan driveway installations begin with a full geotechnical assessment to determine excavation depth, base thickness, and drainage strategy specific to your lot.\n\nWe excavate to a minimum 18-inch depth, accounting for the 48-inch frost penetration depth in this Zone 5b climate. A compacted Granular A sub-base, 1-inch HPB levelling course, and premium pavers from Unilock, Techo-Bloc, and Belgard rated at 8,000+ PSI form the finished surface. Herringbone or 45-degree bond patterns maximize interlock strength under daily vehicle traffic, while polymeric sand and soldier-course edge restraint anchored below frost line ensure long-term joint stability across Vaughan's harsh winters with over 130 cm of average snowfall.\n\nWhether you are replacing a crumbling concrete pad in Maple or upgrading builder-grade asphalt on a new-build lot in Vellore Village, our ICPI-certified crews deliver the same exacting standard. From grand Mediterranean-inspired circular drives in Woodbridge to clean contemporary layouts in Patterson and Sonoma Heights, we back every Vaughan interlocking driveway with our 10-year workmanship warranty.",
                'meta_title' => 'Interlocking Driveways Vaughan | Lush Landscape',
                'meta_description' => 'Custom interlocking driveway installation in Vaughan. ICPI-certified crews, 8,000+ PSI pavers, engineered for Newmarket Till clay with consultation-led planning.',
                'og_title' => 'Interlocking Driveways in Vaughan | Lush Landscape',
                'og_description' => 'Premium interlocking driveway construction for Vaughan homeowners. Engineered for Newmarket Till clay soils and Zone 5b winters.',
                'sort_order' => 1,
            ],

            // ─── 2. Interlocking Patios & Backyard Living ───────────────────
            'Interlocking Patios & Backyard Living' => [
                'page_title' => 'Interlocking Patios & Outdoor Living in Vaughan',
                'h1' => 'Interlocking Patios & Backyard Living in Vaughan',
                'local_intro' => "Outdoor living is central to Vaughan's residential culture. In Woodbridge and Kleinburg, homeowners build expansive rear yards designed around Mediterranean-inspired courtyards with pizza ovens, built-in BBQ islands, fire pits, and pergola-covered dining areas that host multi-generational family gatherings from May through October. Our Vaughan patio installations transform these visions into engineered hardscape environments that perform through every season.\n\nThe Newmarket Till clay soils beneath most Vaughan properties create specific drainage challenges that must be solved at the base level. We engineer positive grade away from your foundation, integrate catch basins where clay impermeability creates ponding risk, and use open-graded base systems on lots where conventional drainage proves insufficient. Many rear-yard projects in Vaughan require coordination with stormwater management ponds and swales mandated by the TRCA for newer subdivisions. Every patio includes compacted Granular A sub-base, HPB levelling course, and premium pavers selected for both freeze-thaw durability and design appeal.\n\nFrom multi-level outdoor rooms with wood-fired ovens and fireplaces on estate lots in Kleinburg to intimate courtyard patios in Thornhill and Concord, our Vaughan design team works from concept through completion. We coordinate gas, electrical, and plumbing trades as needed, delivering a single-source project experience backed by our 10-year workmanship warranty.",
                'meta_title' => 'Interlocking Patios Vaughan | Lush Landscape',
                'meta_description' => 'Custom interlocking patios in Vaughan. Outdoor kitchens, fire pits, courtyards. Engineered for clay soils and TRCA compliance with consultation-led planning.',
                'og_title' => 'Interlocking Patios & Backyard Living in Vaughan',
                'og_description' => 'Transform your Vaughan backyard with custom interlocking patios, outdoor kitchens, and Mediterranean-inspired courtyards.',
                'sort_order' => 2,
            ],

            // ─── 3. Walkways & Steps ────────────────────────────────────────
            'Walkways & Steps' => [
                'page_title' => 'Interlocking Walkways & Steps in Vaughan',
                'h1' => 'Walkways & Steps in Vaughan',
                'local_intro' => "A well-built walkway does more than connect your driveway to your front door. In Vaughan, where winter ice and spring thaw create seasonal tripping hazards across an average of 130 cm of snowfall, a properly graded interlocking walkway with consistent riser heights is a safety investment that also elevates your home's curb appeal. Our walkway and step installations follow Ontario Building Code requirements for riser height, tread depth, and handrail placement.\n\nWe design walkways that complement your existing hardscaping, whether that means matching paver colours and patterns with an interlocking driveway or introducing natural stone accents for visual contrast. For Vaughan properties with grade changes, common on lots that back onto TRCA-regulated Humber River valley lands, we build landing pads at required intervals, install non-slip tread surfaces, and ensure positive drainage away from the path. Steps use reinforced concrete cores with paver or stone cladding for structural permanence, anchored to footings extending below the 48-inch frost depth.\n\nFrom Kleinburg's heritage village homes where walkway design must respect the community's architectural character to modern subdivisions in Vellore Village and Patterson, our Vaughan walkway projects range from simple front-entry paths to elaborate garden walkways with integrated lighting and planting beds. Every installation includes a compacted aggregate base, edge restraint, and polymeric sand to prevent weed growth and paver migration.",
                'meta_title' => 'Walkways & Steps Vaughan | Lush Landscape',
                'meta_description' => 'Interlocking walkways and steps in Vaughan. Non-slip surfaces, OBC-compliant risers, and premium pavers. Safety meets curb appeal.',
                'og_title' => 'Walkways & Steps in Vaughan | Lush Landscape',
                'og_description' => 'Professional walkway and step installation for Vaughan homes. Eliminate tripping hazards and boost curb appeal.',
                'sort_order' => 3,
            ],

            // ─── 4. Natural Stone & Flagstone ───────────────────────────────
            'Natural Stone & Flagstone' => [
                'page_title' => 'Natural Stone & Flagstone in Vaughan',
                'h1' => 'Natural Stone & Flagstone Installation in Vaughan',
                'local_intro' => "Natural stone brings an organic, timeless character to Vaughan properties that manufactured pavers cannot replicate. In Woodbridge and Kleinburg, where homeowners favour Mediterranean and Tuscan design themes, natural stone patios, walkways, and accent walls connect outdoor living spaces to the architectural warmth of the home itself. Our natural stone division works with Ontario-quarried materials including Eramosa limestone, Algonquin flagstone, Muskoka granite, and Owen Sound ledgerock to create features that look as though they have always been part of the landscape.\n\nFlagstone installations on Vaughan's Newmarket Till clay soils require meticulous base preparation to prevent seasonal movement. We install a minimum 12-inch compacted aggregate base under all flagstone work, use mortar-set or dry-laid techniques depending on application and traffic load, and fill joints with polymeric sand or stone dust to prevent weed intrusion. Armour stone installations use Ontario-quarried boulders weighing 1,000 to 4,000 pounds, placed with equipment precision and anchored against the aggressive soil movement that Vaughan's clay subgrade produces during freeze-thaw cycling.\n\nFrom hand-cut flagstone courtyards in Woodbridge to armour stone garden features in Kleinburg and natural stone front-entry landings in Maple and Thornhill, our crews bring the craftsmanship that stone work demands. Every piece is selected for colour consistency, thickness uniformity, and structural integrity before it reaches your Vaughan property.",
                'meta_title' => 'Natural Stone & Flagstone Vaughan | Lush Landscape',
                'meta_description' => 'Natural stone and flagstone installation in Vaughan. Ontario-quarried materials, hand-cut patios, and armour stone features with consultation-led planning.',
                'og_title' => 'Natural Stone & Flagstone in Vaughan | Lush Landscape',
                'og_description' => 'Premium natural stone patios, flagstone walkways, and armour stone features for Vaughan properties.',
                'sort_order' => 4,
            ],

            // ─── 5. Porcelain Pavers ────────────────────────────────────────
            'Porcelain Pavers' => [
                'page_title' => 'Porcelain Paver Installation in Vaughan',
                'h1' => 'Porcelain Pavers in Vaughan',
                'local_intro' => "Porcelain pavers are rapidly gaining popularity among Vaughan homeowners who want a modern, clean aesthetic with virtually zero maintenance. These 20mm-thick engineered tiles deliver zero water absorption, R11 slip rating, and UV stability, making them resistant to the freeze-thaw cycling, road salt tracking, and heavy snowfall that Vaughan properties endure through Zone 5b winters. For homeowners in Concord and Thornhill seeking a contemporary alternative to traditional interlocking, porcelain pavers are the ideal solution.\n\nOur Vaughan porcelain paver installations use pedestal or compacted-aggregate base systems depending on the project type. Rooftop terraces and pool surrounds typically use adjustable pedestal systems that accommodate drainage underneath and provide easy access to membrane surfaces. Ground-level patios use compacted aggregate base with levelling screed and open-graded joint material, engineered specifically for the Newmarket Till clay that sits beneath most Vaughan lots. We source 20mm porcelain pavers from leading Italian and Spanish manufacturers through Canadian distributors who certify freeze-thaw performance to ASTM C1026 standards.\n\nFrom sleek grey concrete-look terraces in Sonoma Heights to warm travertine-look pool surrounds in Woodbridge, porcelain pavers give Vaughan homeowners design flexibility that few other materials can match. Properties in newer Vellore Village subdivisions are especially well-suited to porcelain's clean lines and low-profile aesthetic. Every installation is backed by our workmanship warranty and manufacturer material guarantees.",
                'meta_title' => 'Porcelain Pavers Vaughan | Lush Landscape',
                'meta_description' => 'Modern 20mm porcelain paver installation in Vaughan. Zero absorption, R11 slip rating, frost-proof. Patios, pool decks, terraces.',
                'og_title' => 'Porcelain Pavers in Vaughan | Lush Landscape',
                'og_description' => 'Contemporary porcelain paver installation for Vaughan patios, terraces, and pool surrounds. Maintenance-free elegance.',
                'sort_order' => 5,
            ],

            // ─── 6. Concrete Driveways ──────────────────────────────────────
            'Concrete Driveways' => [
                'page_title' => 'Concrete Driveways in Vaughan',
                'h1' => 'Concrete Driveways in Vaughan',
                'local_intro' => "A concrete driveway remains the most cost-effective way to achieve a durable, clean-looking surface for Vaughan properties. Our concrete driveway installations use 32 MPa air-entrained mix with 5 to 7 percent air content, the Ontario specification for freeze-thaw durability in Zone 5b climates where frost penetrates to 48 inches. Every pour includes 10M rebar on 400mm centres, fibre-mesh reinforcement, and control joints calculated for the slab dimensions and expected load.\n\nVaughan's Newmarket Till clay soils require careful sub-base preparation to prevent the cracking and heaving that plagues poorly built concrete driveways across the city. We excavate to a minimum 14-inch depth, install compacted Granular A sub-base, and use a polyethylene vapour barrier to prevent moisture from migrating upward into the slab. Curing compound is applied immediately after finishing to ensure proper hydration and surface hardness, critical in Vaughan where temperature swings during spring and fall can compromise the curing process.\n\nWe offer broom-finish, exposed aggregate, stamped patterns including ashlar slate, cobblestone, and wood-plank textures, and acid-stain decorative finishes. From classic exposed aggregate in Maple to stamped cobblestone that complements the Tuscan-inspired facades popular in Woodbridge, our Vaughan concrete crews handle complex curves, multi-level transitions, and integrated drainage channels on every project.",
                'meta_title' => 'Concrete Driveways Vaughan | Lush Landscape',
                'meta_description' => 'Concrete driveway installation in Vaughan. 32 MPa air-entrained mix, rebar reinforcement, and decorative finishes with consultation-led planning.',
                'og_title' => 'Concrete Driveways in Vaughan | Lush Landscape',
                'og_description' => 'High-strength concrete driveways for Vaughan homes. Stamped, exposed aggregate, and broom-finish options.',
                'sort_order' => 6,
            ],

            // ─── 7. Concrete Patios & Walkways ─────────────────────────────
            'Concrete Patios & Walkways' => [
                'page_title' => 'Concrete Patios & Walkways in Vaughan',
                'h1' => 'Concrete Patios & Walkways in Vaughan',
                'local_intro' => "Architectural concrete transforms ordinary outdoor surfaces into design statements that complement the bold residential architecture found across Vaughan. In a city where homeowners invest heavily in their outdoor living spaces, stamped, stained, and exposed aggregate finishes deliver the visual impact of premium materials at a practical price point. We pour patios, walkways, pool decks, and stepping-stone paths using the same 32 MPa air-entrained specifications as our driveways, engineered for Zone 5b freeze-thaw performance.\n\nStamped concrete patterns include ashlar slate, European fan, random stone, cobblestone, and wood-plank textures applied with colour hardener and release agents for a natural, multi-toned finish. Exposed aggregate finishes reveal the natural stone within the concrete mix, creating a textured, slip-resistant surface ideal for pool surrounds and garden paths. Acid-stain finishes produce translucent colour variations that mimic natural travertine and sandstone, a popular choice in Vaughan's Woodbridge and Kleinburg communities where Mediterranean design influences are strong.\n\nOur Vaughan concrete patio projects include proper sub-base preparation for the Newmarket Till clay soil profile, positive drainage grading away from your foundation, and expansion joints where the patio meets the house. Properties in newer subdivisions with TRCA-mandated rear-yard grading receive careful coordination to maintain approved stormwater management slopes. We back all concrete patio and walkway work with our workmanship warranty.",
                'meta_title' => 'Concrete Patios & Walkways Vaughan | Lush',
                'meta_description' => 'Stamped and exposed aggregate concrete patios and walkways in Vaughan. Decorative finishes, proper drainage, lasting durability.',
                'og_title' => 'Concrete Patios & Walkways in Vaughan | Lush Landscape',
                'og_description' => 'Architectural concrete patios and walkways for Vaughan homes. Stamped, stained, and exposed aggregate options.',
                'sort_order' => 7,
            ],

            // ─── 8. Interlock Restoration & Sealing ─────────────────────────
            'Interlock Restoration & Sealing' => [
                'page_title' => 'Interlock Restoration & Sealing in Vaughan',
                'h1' => 'Interlock Restoration & Sealing in Vaughan',
                'local_intro' => "Years of Vaughan weather, road salt tracking from municipal winter maintenance, and organic growth take a visible toll on interlocking surfaces. Faded colours, displaced polymeric sand, weed invasion, and efflorescence buildup make even quality installations look tired. Across Woodbridge and Maple, where homeowners installed premium interlocking over a decade ago, restoration and sealing is the most cost-effective way to renew hardscaping without the expense and disruption of full replacement. Our 3-day interlock restoration process brings your existing Vaughan pavers back to their original appearance.\n\nDay one involves thorough hot-water pressure washing at 3,000+ PSI with surface-cleaning attachments that remove embedded dirt, moss, algae, and the efflorescence deposits that are especially common on Vaughan properties where moisture wicks through Newmarket Till clay and deposits mineral salts on paver surfaces. Day two allows the surface to dry completely before we re-apply polymeric sand to all joints, ensuring proper activation and curing. Day three applies UV-resistant sealer in the homeowner's choice of matte, satin, or wet-look finish, locking in colour, preventing future staining, and making ongoing maintenance as simple as a garden hose rinse.\n\nWe restore interlocking driveways, patios, walkways, and pool decks across Vaughan from Concord to Kleinburg. If your pavers are structurally sound but visually faded, our restoration process is the smartest investment you can make in your existing hardscaping.",
                'meta_title' => 'Interlock Restoration & Sealing Vaughan | Lush',
                'meta_description' => 'Professional interlock restoration and sealing in Vaughan. 3-day process: steam wash, polymeric sand, UV sealer. Renew your pavers.',
                'og_title' => 'Interlock Restoration & Sealing in Vaughan',
                'og_description' => 'Restore faded interlocking surfaces in Vaughan. Hot-water wash, polymeric sand, and protective sealer application.',
                'sort_order' => 8,
            ],

            // ─── 9. Interlock Repair (Lift & Relay) ─────────────────────────
            'Interlock Repair (Lift & Relay)' => [
                'page_title' => 'Interlock Repair (Lift & Relay) in Vaughan',
                'h1' => 'Interlock Repair in Vaughan: Lift & Relay',
                'local_intro' => "Sunken, heaving, or uneven interlocking pavers are a widespread issue across Vaughan, where the Newmarket Till clay subgrade expands and contracts aggressively through freeze-thaw cycles that push frost to 48 inches deep. These displaced surfaces create tripping hazards, allow water to pool against foundations, and worsen progressively if the underlying cause is not corrected. A proper lift-and-relay repair targets the root failure rather than masking the symptom.\n\nOur Vaughan repair process begins with carefully removing the affected pavers and setting them aside for re-use. We excavate the failed base material, diagnose the cause of failure, whether insufficient original base depth, poor compaction by the builder, tree root intrusion, or subsurface drainage failure caused by Vaughan's impermeable clay layer, and correct it permanently. Fresh HPB aggregate is installed, compacted in lifts to 95 percent Standard Proctor density, and screeded to the correct elevation. Original pavers are then re-laid in their original pattern, new polymeric sand fills all joints, and the repaired section is compacted to match the surrounding surface.\n\nLift-and-relay preserves your existing pavers and their naturally weathered patina, so the repair blends seamlessly with undisturbed areas. We perform this service on driveways, patios, walkways, and pool decks across Vaughan, from established Woodbridge and Thornhill properties to newer installations in Vellore Village and Patterson that have experienced premature settlement due to inadequate builder-grade base preparation.",
                'meta_title' => 'Interlock Repair Vaughan - Lift & Relay | Lush',
                'meta_description' => 'Permanent interlock repair in Vaughan. Lift and relay sunken pavers with HPB base correction. Fix the cause, not just the symptom.',
                'og_title' => 'Interlock Repair (Lift & Relay) in Vaughan',
                'og_description' => 'Fix sunken and heaving pavers in Vaughan permanently. HPB base correction engineered for Newmarket Till clay subgrade.',
                'sort_order' => 9,
            ],

            // ─── 10. Retaining Walls ────────────────────────────────────────
            'Retaining Walls' => [
                'page_title' => 'Retaining Walls in Vaughan',
                'h1' => 'Retaining Wall Construction in Vaughan',
                'local_intro' => "Vaughan's terrain ranges from the gently rolling lots in Maple and Vellore Village to the steeper ravine-edge properties along the Humber River valley in Woodbridge and Kleinburg. Many residential lots in Vaughan feature rear-yard grade changes that require engineered retaining solutions, particularly on properties adjacent to TRCA-regulated lands where stormwater management ponds and swales create elevation differentials. Whether you need a terraced backyard for usable outdoor living space, a front-yard retaining wall to manage a sloped driveway approach, or erosion control along a property boundary, our retaining wall division delivers structural results.\n\nWe build with armour stone sourced from Ontario quarries in weights from 1,000 to 4,000 pounds, precast concrete blocks from Allan Block, Cornerstone, and Unilock, and poured-in-place reinforced concrete where structural loads dictate. Every retaining wall over 1.0 metre in retained height is designed to Ontario Building Code Part 4 standards with geogrid reinforcement at calculated intervals, clear stone backfill, filter fabric, and weeping tile drainage connected to storm or daylight outlets. Vaughan's Newmarket Till clay backfill conditions make proper drainage behind the wall as critical as the wall structure itself.\n\nOur Vaughan retaining wall projects include full engineering coordination where required, TRCA permitting for properties within the Humber River watershed regulated area, and design consideration for the lot-grading plans that govern newer subdivisions across the city. All structural components carry our 10-year workmanship warranty.",
                'meta_title' => 'Retaining Walls Vaughan | Lush Landscape',
                'meta_description' => 'Retaining wall construction in Vaughan. Armour stone, concrete block, geogrid reinforced. OBC-compliant, TRCA-permitted with consultation-led planning.',
                'og_title' => 'Retaining Walls in Vaughan | Lush Landscape',
                'og_description' => 'Engineered retaining walls for Vaughan properties. Armour stone and precast block with proper drainage and TRCA coordination.',
                'sort_order' => 10,
            ],

            // ─── 11. Sod Installation & Grading ─────────────────────────────
            'Sod Installation & Grading' => [
                'page_title' => 'Sod Installation & Grading in Vaughan',
                'h1' => 'Sod Installation & Grading in Vaughan',
                'local_intro' => "Establishing a healthy lawn in Vaughan requires understanding the soil beneath it. The Newmarket Till clay that covers most of the city resists root penetration, drains poorly, and bakes hard during summer drought. Builder-grade lots in newer Vaughan subdivisions across Vellore Village and Patterson often arrive with subsoil compacted by heavy equipment and only a thin skim of topsoil, while established properties in Woodbridge and Maple may have decades of compaction to address. Our sod installation process corrects these conditions from the ground up.\n\nWe establish positive drainage grade away from your foundation at a minimum 2 percent slope for the first 6 feet, then transition to a gentler grade across the remainder of the yard. On Vaughan properties where TRCA-mandated stormwater management swales and rear-yard grading plans must be maintained, we work within the approved lot-grading elevations to ensure compliance while still delivering optimal lawn establishment. Four to 6 inches of Triple-Mix, a blend of screened topsoil, peat, and compost, is spread and raked to final grade. Premium Kentucky Bluegrass sod is laid the same day it is cut from the farm, rolled for full root contact, and given starter fertilizer.\n\nFrom post-construction grading on new builds in Sonoma Heights to full lawn renovation on mature lots in Thornhill and Kleinburg, our Vaughan sod crews handle projects of every scale. We guarantee root establishment when our watering schedule is followed.",
                'meta_title' => 'Sod Installation & Grading Vaughan | Lush',
                'meta_description' => 'Professional sod installation and grading in Vaughan. Kentucky Bluegrass, Triple-Mix soil, precision drainage. Same-day installation.',
                'og_title' => 'Sod Installation & Grading in Vaughan | Lush',
                'og_description' => 'Premium sod installation with proper grading and soil amendment for Vaughan lawns. Instant results, lasting health.',
                'sort_order' => 11,
            ],

            // ─── 12. Artificial Turf ────────────────────────────────────────
            'Artificial Turf' => [
                'page_title' => 'Artificial Turf Installation in Vaughan',
                'h1' => 'Artificial Turf in Vaughan',
                'local_intro' => "For Vaughan homeowners who want a consistently green, maintenance-free lawn year-round, artificial turf eliminates the mowing, watering, fertilizing, and seasonal brown patches that natural grass demands. Modern synthetic turf has advanced well beyond the plastic appearance of earlier generations. Today's premium products feature multi-toned blade profiles, integrated thatch layers for natural appearance, and UV stabilization that maintains colour integrity for 15 or more years, even through Vaughan's intense summer sun and heavy winter snowfall that averages over 130 cm annually.\n\nOur Vaughan artificial turf installations address the specific drainage limitations of the city's Newmarket Till clay subgrade. We excavate existing soil, install compacted aggregate base with positive drainage grade, lay geotextile separation fabric, and secure the turf with landscape spikes and infill material. Pet-friendly installations use antimicrobial infill and achieve drainage rates exceeding 30 inches per hour, critical for Vaughan properties where the underlying clay would otherwise trap moisture at the surface and create standing water.\n\nWhether you need a backyard play surface for children in Maple, a pet run in Concord that stays clean through mud season, a putting green for golf practice in Kleinburg, or a front-yard accent in Woodbridge that looks immaculate without weekend maintenance, our Vaughan artificial turf team delivers turnkey installations tailored to your property and lifestyle.",
                'meta_title' => 'Artificial Turf Vaughan | Lush Landscape',
                'meta_description' => 'Artificial turf installation in Vaughan. Pet-friendly, UV-stable, 30+ in/hr drainage on clay soils. No mowing, no watering, always green.',
                'og_title' => 'Artificial Turf in Vaughan | Lush Landscape',
                'og_description' => 'Maintenance-free artificial turf for Vaughan lawns, pet areas, and play zones. Engineered drainage for Newmarket Till soils.',
                'sort_order' => 12,
            ],

            // ─── 13. Garden Design & Planting ───────────────────────────────
            'Garden Design & Planting' => [
                'page_title' => 'Garden Design & Planting in Vaughan',
                'h1' => 'Garden Design & Planting in Vaughan',
                'local_intro' => "Vaughan's Zone 5b climate and Newmarket Till clay soils present a specific set of conditions that garden design must account for. The clay retains moisture in spring and compacts to near-impermeable hardness in summer drought, limiting the root development of plants that are not selected and installed with these conditions in mind. Our garden design service translates Vaughan's botanical potential into curated planting plans that provide four-season visual interest, support local pollinators, and thrive in the specific soil and light conditions found across the city's diverse neighbourhoods.\n\nWe start every Vaughan garden project with an on-site assessment of sun exposure, soil type, drainage patterns, and existing vegetation. Properties in Kleinburg's heritage village often feature mature tree canopies that create deep shade conditions requiring specialized understory plantings, while newer lots in Vellore Village and Patterson may have full southern exposure and minimal existing vegetation. Planting plans specify cultivars proven in Zone 5b conditions, sourced from Ontario-accredited nurseries. Bed construction includes excavation of compacted subsoil, amendment with premium planting mix, and installation of steel or aluminum edging for clean, permanent bed lines.\n\nFrom Mediterranean-inspired courtyard gardens with olive-toned evergreens and ornamental grasses in Woodbridge to native pollinator meadows along the Humber River corridor in Kleinburg, our Vaughan garden design team creates outdoor spaces that look better with every passing season. All plant material carries a one-year health guarantee when our care instructions are followed.",
                'meta_title' => 'Garden Design & Planting Vaughan | Lush',
                'meta_description' => 'Professional garden design and planting in Vaughan. Zone 5b-adapted species, pollinator gardens, four-season interest. One-year guarantee.',
                'og_title' => 'Garden Design & Planting in Vaughan | Lush',
                'og_description' => 'Custom garden design with climate-adapted plants for Vaughan properties. Beautiful, sustainable, and pollinator-friendly.',
                'sort_order' => 13,
            ],

            // ─── 14. Landscape Lighting ─────────────────────────────────────
            'Landscape Lighting' => [
                'page_title' => 'Landscape Lighting in Vaughan',
                'h1' => 'Landscape Lighting in Vaughan',
                'local_intro' => "Professional landscape lighting transforms your Vaughan property after dark, extending usable outdoor hours through the long summer evenings and enhancing security during the early winter darkness that settles over the city by 4:30 PM in December. Across Woodbridge and Kleinburg, where homeowners invest significantly in their outdoor living environments, a well-designed lighting system ensures that courtyards, patios, and garden features remain visible and inviting well beyond sunset.\n\nWe use low-voltage LED systems powered by commercial-grade transformers with built-in timers and WiFi smart controls compatible with phone apps and home automation platforms. All fixtures are solid brass or marine-grade aluminum, selected for their ability to withstand Vaughan's Zone 5b freeze-thaw cycles, over 130 cm of annual snowfall, and road salt exposure without corroding or discolouring. Direct-burial cable rated for outdoor use is trenched below grade to protect against damage from seasonal ground movement in the Newmarket Till clay that underlies most Vaughan properties.\n\nFrom driveway pillar and facade uplighting in Thornhill to backyard patio illumination in Maple and garden bed accent lighting along the heritage streetscapes of Kleinburg, our Vaughan lighting projects are designed by certified professionals who understand colour temperature, beam angles, and lumen requirements for each application. Every system is sized with spare transformer capacity for future expansion, so adding fixtures later never requires equipment upgrades.",
                'meta_title' => 'Landscape Lighting Vaughan | Lush Landscape',
                'meta_description' => 'Professional LED landscape lighting in Vaughan. Brass fixtures, WiFi smart controls, and custom design. Extend your outdoor living hours.',
                'og_title' => 'Landscape Lighting in Vaughan | Lush Landscape',
                'og_description' => 'Custom landscape lighting design and installation for Vaughan homes. Low-voltage LED, brass fixtures, smart controls.',
                'sort_order' => 14,
            ],

        ];
    }
}
