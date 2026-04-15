<?php

namespace Database\Seeders\Content;

use App\Models\City;
use App\Models\Service;
use App\Models\ServiceCityPage;
use Illuminate\Database\Seeder;

class HamiltonContentSeeder extends Seeder
{
    public function run(): void
    {
        $city = City::where('name', 'Hamilton')->firstOrFail();
        $pages = $this->getPages();

        $neighborhoods = [
            'Ancaster', 'Dundas', 'Westdale', 'Stoney Creek', 'Waterdown', 'Flamborough',
            'Binbrook', 'Upper Paradise', 'Locke Street', 'Crown Point',
            'Hess Village', 'Kirkendall',
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
                        'text' => 'Book a Consultation in Hamilton',
                        'url' => '/request-quote?service='.urlencode($service->name).'&city=Hamilton',
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
                'page_title' => 'Interlocking Driveways in Hamilton',
                'h1' => 'Interlocking Driveways in Hamilton',
                'local_intro' => "Hamilton's dramatic topography, split between the lower city at lake level and the upper city atop the Niagara Escarpment, creates unique engineering demands for every driveway project. The clay-heavy glacial till that blankets much of Hamilton sits over Queenston shale bedrock, producing a subgrade that retains moisture, expands during freeze cycles, and shifts under load if not properly addressed. Our Hamilton interlocking driveway installations begin with geotechnical assessment of your lot to determine excavation depth, base thickness, and drainage strategy specific to your elevation and soil profile.\n\nWe excavate to a minimum 18-inch depth on Hamilton properties, accounting for the 48-inch frost penetration depth in this Zone 6a climate. A compacted Granular A sub-base, 1-inch HPB levelling course, and premium pavers from Unilock, Techo-Bloc, or Belgard rated at 8,000+ PSI form the finished surface. Herringbone or 45-degree bond patterns maximize interlock strength under vehicle loads, while polymeric sand and soldier-course edge restraint anchored below frost line ensure long-term joint stability.\n\nWhether your property sits on a steep Escarpment-access street in Dundas or a flat suburban lot in Stoney Creek, our ICPI-certified crews adapt every installation to Hamilton's local conditions. Heritage district properties in Durand and Kirkendall receive design consideration that respects the neighbourhood's architectural character while delivering modern performance.",
                'meta_title' => 'Interlocking Driveways Hamilton | Lush Landscape',
                'meta_description' => 'Custom interlocking driveway installation in Hamilton. Engineered for Escarpment clay soils, 48-inch frost depth. ICPI-certified with consultation-led planning.',
                'og_title' => 'Interlocking Driveways in Hamilton | Lush Landscape',
                'og_description' => 'Premium interlocking driveway construction for Hamilton homeowners. Engineered for glacial till soils and Escarpment terrain.',
                'sort_order' => 1,
            ],

            // ─── 2. Interlocking Patios & Backyard Living ───────────────────
            'Interlocking Patios & Backyard Living' => [
                'page_title' => 'Interlocking Patios & Outdoor Living in Hamilton',
                'h1' => 'Interlocking Patios & Backyard Living in Hamilton',
                'local_intro' => "Hamilton homeowners enjoy some of the most varied backyard settings in southern Ontario. Properties in Ancaster look out over forested ravines, Westdale lots border the Royal Botanical Gardens corridor, and Stoney Creek backyards open to views of Lake Ontario. Regardless of your setting, a properly engineered interlocking patio transforms unused yard space into an outdoor living environment complete with BBQ islands, fire pits, seating walls, and integrated lighting.\n\nThe clay-rich glacial till that underlies most Hamilton properties creates specific drainage challenges that must be solved at the base level, not the surface. We engineer positive grade away from your foundation, integrate catch basins where impermeable clay creates ponding risk, and install open-graded base systems on lots where conventional drainage proves insufficient. Every Hamilton patio includes compacted Granular A sub-base, HPB levelling course, and premium pavers selected for both freeze-thaw durability and design appeal.\n\nFrom multi-level outdoor rooms with kitchens and fireplaces on Ancaster estate lots to intimate courtyard patios in Hamilton's historic Durand neighbourhood, our design team works from concept through completion. We coordinate gas, electrical, and plumbing trades as needed, and all patio work carries our 10-year workmanship warranty.",
                'meta_title' => 'Interlocking Patios Hamilton | Lush Landscape',
                'meta_description' => 'Custom interlocking patios in Hamilton. Outdoor kitchens, fire pits, seating walls. Engineered for clay soils and Escarpment terrain with consultation-led planning.',
                'og_title' => 'Interlocking Patios & Backyard Living in Hamilton',
                'og_description' => 'Transform your Hamilton backyard with custom interlocking patios, outdoor kitchens, and fire features built for Escarpment terrain.',
                'sort_order' => 2,
            ],

            // ─── 3. Walkways & Steps ────────────────────────────────────────
            'Walkways & Steps' => [
                'page_title' => 'Walkways & Steps in Hamilton',
                'h1' => 'Walkways & Steps in Hamilton',
                'local_intro' => "Hamilton's Escarpment geography means that many residential properties deal with significant elevation changes between the street, front entry, and backyard. Combined with lake-effect snow that blankets Stoney Creek and Winona each winter, these grade transitions demand walkways and steps that are structurally sound, properly drained, and slip-resistant in all seasons. Our walkway and step installations follow Ontario Building Code requirements for riser height, tread depth, and handrail placement.\n\nWe design walkways that complement your existing hardscaping and respond to your property's specific grade conditions. For Hamilton homes with steep front approaches, we build landing pads at code-required intervals, install non-slip tread surfaces, and ensure positive drainage diverts meltwater away from walking surfaces. Steps use reinforced concrete cores with paver or natural stone cladding for structural permanence, anchored to footings that extend below the 48-inch frost depth.\n\nFrom heritage homes in Kirkendall where step design must respect the neighbourhood's historic character to modern subdivisions in Waterdown where builder-grade concrete is ready for an upgrade, our Hamilton walkway projects deliver safety and curb appeal in equal measure. Every installation includes compacted aggregate base, edge restraint, and polymeric sand to prevent weed growth and paver migration.",
                'meta_title' => 'Walkways & Steps Hamilton | Lush Landscape',
                'meta_description' => 'Interlocking walkways and steps in Hamilton. Non-slip surfaces, OBC-compliant risers, Escarpment-grade engineering. Safety meets curb appeal.',
                'og_title' => 'Walkways & Steps in Hamilton | Lush Landscape',
                'og_description' => 'Professional walkway and step installation for Hamilton homes. Engineered for Escarpment elevation changes and winter safety.',
                'sort_order' => 3,
            ],

            // ─── 4. Natural Stone & Flagstone ───────────────────────────────
            'Natural Stone & Flagstone' => [
                'page_title' => 'Natural Stone & Flagstone in Hamilton',
                'h1' => 'Natural Stone & Flagstone Installation in Hamilton',
                'local_intro' => "Hamilton sits at the heart of Ontario's geological showcase, where the Niagara Escarpment exposes 450-million-year-old dolostone, limestone, and shale formations. That geological heritage makes natural stone a fitting material choice for Hamilton properties. Our natural stone division works with locally quarried Eramosa limestone, Algonquin flagstone, Muskoka granite, and Wiarton bluestone to create patios, walkways, accent walls, and water features that connect your property to the region's natural landscape.\n\nFlagstone installations on Hamilton's clay-heavy glacial till require meticulous base preparation. We install a minimum 12-inch compacted aggregate base under all flagstone work, use mortar-set or dry-laid techniques depending on application and load requirements, and fill joints with polymeric sand or natural stone dust to prevent weed intrusion. Armour stone installations use Ontario-quarried boulders weighing 1,000 to 4,000 pounds, placed with equipment precision and seated against seasonal soil movement.\n\nFrom hand-cut flagstone patios overlooking the Dundas Valley to armour stone terracing on steep Escarpment-face properties in Ancaster, our Hamilton crews bring the craftsmanship that natural stone demands. Properties in Hamilton Conservation Authority regulated areas receive full permitting coordination as part of our project scope.",
                'meta_title' => 'Natural Stone & Flagstone Hamilton | Lush Landscape',
                'meta_description' => 'Natural stone and flagstone installation in Hamilton. Ontario-quarried materials, Escarpment-inspired design, HCA permit coordination with consultation-led planning.',
                'og_title' => 'Natural Stone & Flagstone in Hamilton | Lush Landscape',
                'og_description' => 'Premium natural stone patios, flagstone walkways, and armour stone features for Hamilton properties near the Niagara Escarpment.',
                'sort_order' => 4,
            ],

            // ─── 5. Porcelain Pavers ────────────────────────────────────────
            'Porcelain Pavers' => [
                'page_title' => 'Porcelain Paver Installation in Hamilton',
                'h1' => 'Porcelain Pavers in Hamilton',
                'local_intro' => "Porcelain pavers offer Hamilton homeowners a modern hardscaping material that thrives in the demanding conditions of a Zone 6a climate. These 20mm-thick engineered tiles deliver zero water absorption, R11 slip rating, and UV stability, making them resistant to the freeze-thaw cycling, lake-effect moisture, and road salt exposure that Hamilton properties endure from November through April. For homeowners who want a clean contemporary aesthetic without constant maintenance, porcelain pavers are the ideal solution.\n\nOur Hamilton porcelain paver installations use pedestal or compacted-aggregate base systems depending on the project type. Rooftop terraces and pool surrounds typically use adjustable pedestal systems that accommodate drainage underneath and provide easy access to membrane surfaces. Ground-level patios use compacted aggregate base with levelling screed and open-graded joint material, engineered for Hamilton's clay subgrade. We source 20mm porcelain pavers from leading Italian and Spanish manufacturers through Canadian distributors who certify freeze-thaw performance to ASTM C1026 standards.\n\nFrom sleek concrete-look terraces on Westdale properties near McMaster University to warm wood-tone pool surrounds in Stoney Creek, porcelain pavers give Hamilton homeowners design flexibility that few other materials can match. Every installation is backed by our workmanship warranty and manufacturer material guarantees.",
                'meta_title' => 'Porcelain Pavers Hamilton | Lush Landscape',
                'meta_description' => 'Modern 20mm porcelain paver installation in Hamilton. Zero absorption, R11 slip rating, frost-proof. Patios, pool decks, and terraces.',
                'og_title' => 'Porcelain Pavers in Hamilton | Lush Landscape',
                'og_description' => 'Contemporary porcelain paver installation for Hamilton patios, terraces, and pool surrounds. Built for Escarpment climate.',
                'sort_order' => 5,
            ],

            // ─── 6. Concrete Driveways ──────────────────────────────────────
            'Concrete Driveways' => [
                'page_title' => 'Concrete Driveways in Hamilton',
                'h1' => 'Concrete Driveways in Hamilton',
                'local_intro' => "A concrete driveway remains the most cost-effective way to achieve a durable, clean-looking surface for Hamilton properties, from flat suburban lots in Binbrook to steep Escarpment-access driveways in Dundas. Our concrete driveway installations use 32 MPa air-entrained mix with 5 to 7 percent air content, the Ontario specification for freeze-thaw durability in Zone 6a climates. Every pour includes 10M rebar on 400mm centres, fibre-mesh reinforcement, and control joints calculated for slab dimensions and expected load.\n\nHamilton's glacial till over Queenston shale creates a subgrade that holds water and heaves if base preparation is inadequate. We excavate to a minimum 14-inch depth, install compacted Granular A sub-base, and use a polyethylene vapour barrier to prevent moisture migration into the slab. On Escarpment-slope driveways where grade exceeds 8 percent, we incorporate broom-finish texturing for traction and install cross-slope drainage swales to redirect surface water before it reaches the garage.\n\nWe offer broom-finish, exposed aggregate, stamped patterns including ashlar slate, cobblestone, and wood-plank textures, and acid-stain decorative finishes. Heritage properties in Hamilton's Durand and Kirkendall neighbourhoods benefit from stamped patterns that complement period architecture while delivering modern structural performance.",
                'meta_title' => 'Concrete Driveways Hamilton | Lush Landscape',
                'meta_description' => 'Concrete driveway installation in Hamilton. 32 MPa air-entrained mix, rebar reinforcement, Escarpment-grade engineering with consultation-led planning.',
                'og_title' => 'Concrete Driveways in Hamilton | Lush Landscape',
                'og_description' => 'High-strength concrete driveways for Hamilton homes. Stamped, exposed aggregate, and broom-finish options for every terrain.',
                'sort_order' => 6,
            ],

            // ─── 7. Concrete Patios & Walkways ─────────────────────────────
            'Concrete Patios & Walkways' => [
                'page_title' => 'Concrete Patios & Walkways in Hamilton',
                'h1' => 'Concrete Patios & Walkways in Hamilton',
                'local_intro' => "Architectural concrete gives Hamilton homeowners the opportunity to create outdoor surfaces with genuine design impact, far beyond a plain grey slab. Our stamped, stained, and exposed aggregate finishes transform patios, walkways, pool decks, and stepping-stone paths into features that complement both modern builds in Waterdown and century-old homes in Westdale. We pour all flatwork using 32 MPa air-entrained concrete specified for Hamilton's freeze-thaw climate.\n\nStamped concrete patterns include ashlar slate, European fan, random stone, cobblestone, and wood-plank textures applied with colour hardener and release agents for a natural, multi-toned appearance. Exposed aggregate finishes reveal the natural stone within the mix, creating a textured, slip-resistant surface ideal for pool surrounds and garden paths. For Hamilton properties on the Escarpment brow where outdoor entertaining comes with panoramic views, acid-stain finishes produce translucent colour variations that echo the natural stone formations below.\n\nEvery Hamilton concrete patio project includes proper sub-base preparation for the local clay-till soil profile, positive drainage grading away from your foundation, and isolation joints where the patio meets the house structure. We coordinate with Hamilton building permit requirements for projects that involve structural components or significant grade changes.",
                'meta_title' => 'Concrete Patios & Walkways Hamilton | Lush',
                'meta_description' => 'Stamped and exposed aggregate concrete patios in Hamilton. Decorative finishes, proper drainage, and Escarpment-ready engineering.',
                'og_title' => 'Concrete Patios & Walkways in Hamilton | Lush Landscape',
                'og_description' => 'Architectural concrete patios and walkways for Hamilton homes. Stamped, stained, and exposed aggregate options.',
                'sort_order' => 7,
            ],

            // ─── 8. Interlock Restoration & Sealing ─────────────────────────
            'Interlock Restoration & Sealing' => [
                'page_title' => 'Interlock Restoration & Sealing in Hamilton',
                'h1' => 'Interlock Restoration & Sealing in Hamilton',
                'local_intro' => "Hamilton's industrial heritage means that many properties contend with airborne particulates, road salt residue, and mineral staining that accelerate the aging of interlocking surfaces. Combined with organic growth fuelled by the Escarpment's humid microclimate, even quality paver installations in Hamilton can look faded, stained, and weed-invaded within a few years. Our 3-day interlock restoration process reverses that deterioration and provides lasting protection.\n\nDay one involves thorough hot-water pressure washing at 3,000+ PSI with surface-cleaning attachments that remove embedded dirt, moss, algae, efflorescence, and the iron-oxide staining common on Hamilton properties near the Escarpment's mineral-rich groundwater seeps. Day two allows complete surface drying before we re-apply polymeric sand to all joints, ensuring proper activation and curing. Day three applies UV-resistant sealer in the homeowner's choice of matte, satin, or wet-look finish, locking in colour, preventing future staining, and simplifying ongoing maintenance.\n\nWe restore interlocking driveways, patios, walkways, and pool decks throughout Hamilton, from Ancaster to Stoney Creek. If your pavers are structurally sound but visually tired, restoration and sealing delivers the most cost-effective renewal available without the expense and disruption of full replacement.",
                'meta_title' => 'Interlock Restoration & Sealing Hamilton | Lush',
                'meta_description' => 'Professional interlock restoration and sealing in Hamilton. 3-day process: pressure wash, polymeric sand, UV sealer. Renew your pavers.',
                'og_title' => 'Interlock Restoration & Sealing in Hamilton',
                'og_description' => 'Restore faded interlocking surfaces in Hamilton. Hot-water wash, polymeric sand, and protective sealer application.',
                'sort_order' => 8,
            ],

            // ─── 9. Interlock Repair (Lift & Relay) ─────────────────────────
            'Interlock Repair (Lift & Relay)' => [
                'page_title' => 'Interlock Repair (Lift & Relay) in Hamilton',
                'h1' => 'Interlock Repair in Hamilton: Lift & Relay',
                'local_intro' => "Sunken, heaving, or uneven interlocking pavers are a common problem across Hamilton, where the clay-heavy glacial till expands and contracts aggressively through freeze-thaw cycles. These displaced surfaces create tripping hazards, allow water to pool against foundations, and worsen progressively if the underlying cause is not corrected. A proper lift-and-relay repair targets the root failure rather than masking the symptom.\n\nOur Hamilton repair process begins with carefully removing the affected pavers and setting them aside for re-use. We excavate the failed base material, diagnose the cause of failure, whether insufficient base depth, poor original compaction, tree root intrusion, or subsurface drainage failure from Hamilton's impermeable clay layer, and correct it permanently. Fresh HPB aggregate is installed, compacted in lifts to 95 percent Standard Proctor density, and screeded to the correct elevation. Original pavers are then re-laid in their original pattern, new polymeric sand fills all joints, and the repaired section is compacted to match the surrounding surface.\n\nLift-and-relay preserves your existing pavers and their naturally weathered patina, so the repair blends seamlessly with undisturbed areas. We perform this service on driveways, patios, walkways, and pool decks across Hamilton, from the lower city to upper Mountain neighbourhoods.",
                'meta_title' => 'Interlock Repair Hamilton - Lift & Relay | Lush',
                'meta_description' => 'Permanent interlock repair in Hamilton. Lift and relay sunken pavers with HPB base correction. Fix the root cause on clay-till soils.',
                'og_title' => 'Interlock Repair (Lift & Relay) in Hamilton',
                'og_description' => 'Fix sunken and heaving pavers in Hamilton permanently. HPB base correction engineered for glacial till subgrade.',
                'sort_order' => 9,
            ],

            // ─── 10. Retaining Walls ────────────────────────────────────────
            'Retaining Walls' => [
                'page_title' => 'Retaining Walls in Hamilton',
                'h1' => 'Retaining Wall Construction in Hamilton',
                'local_intro' => "Hamilton's Niagara Escarpment creates some of the most dramatic residential grade changes in southern Ontario. Properties on the Escarpment brow, along the Mountain access roads, and throughout the Dundas Valley face elevation differences that demand engineered retaining solutions. Whether you need a terraced backyard carved into a hillside in Ancaster, a front-yard wall to stabilize a sloped driveway in upper Hamilton, or erosion control along a ravine boundary in Dundas, our retaining wall division delivers structurally certified results.\n\nWe build with armour stone sourced from Ontario quarries in weights from 1,000 to 4,000 pounds, precast concrete blocks from Allan Block, Cornerstone, and Unilock, and poured-in-place reinforced concrete where structural loads require it. Every retaining wall over 1.0 metre in retained height is designed to Ontario Building Code Part 4 standards with geogrid reinforcement at calculated intervals, clear stone backfill, filter fabric, and weeping tile connected to storm or daylight outlets. Hamilton's clay-till backfill conditions make proper drainage behind the wall as critical as the wall structure itself.\n\nOur Hamilton retaining wall projects include full engineering coordination where required, Hamilton Conservation Authority permitting for properties within HCA-regulated areas, and Niagara Escarpment Commission approvals for properties within the Escarpment Development Control Area. All structural components carry our 10-year workmanship warranty.",
                'meta_title' => 'Retaining Walls Hamilton | Lush Landscape',
                'meta_description' => 'Retaining wall construction in Hamilton. Armour stone, concrete block, geogrid reinforced. OBC-compliant, HCA and NEC permitted with consultation-led planning.',
                'og_title' => 'Retaining Walls in Hamilton | Lush Landscape',
                'og_description' => 'Engineered retaining walls for Hamilton Escarpment properties. Armour stone and precast block with proper drainage and permitting.',
                'sort_order' => 10,
            ],

            // ─── 11. Sod Installation & Grading ─────────────────────────────
            'Sod Installation & Grading' => [
                'page_title' => 'Sod Installation & Grading in Hamilton',
                'h1' => 'Sod Installation & Grading in Hamilton',
                'local_intro' => "Establishing a healthy lawn in Hamilton requires understanding the soil that sits beneath it. Much of the city's residential land is covered in compacted glacial till with high clay content, a subgrade that resists root penetration, drains poorly, and bakes hard in summer drought. Builder-grade lots in new subdivisions across upper Stoney Creek and Waterdown often arrive with subsoil compacted by heavy equipment and only a thin skim of topsoil. Our sod installation process corrects these conditions from the ground up.\n\nWe establish positive drainage grade away from your foundation at a minimum 2 percent slope for the first 6 feet, then transition to a gentler grade across the remainder of the yard. On Hamilton's Escarpment-slope properties where natural grades are steep, we incorporate terracing or swale systems to prevent erosion and control surface water. Four to 6 inches of Triple-Mix, a blend of screened topsoil, peat, and compost, is spread and raked to final grade. Premium Kentucky Bluegrass sod is laid the same day it is cut, rolled for full root contact, and given starter fertilizer.\n\nFrom post-construction grading on new builds in Binbrook to full lawn renovation on mature Westdale lots shaded by century-old trees, our Hamilton sod crews handle projects of every scale and complexity. We guarantee root establishment when our watering schedule is followed.",
                'meta_title' => 'Sod Installation & Grading Hamilton | Lush',
                'meta_description' => 'Professional sod installation and grading in Hamilton. Kentucky Bluegrass, Triple-Mix soil, Escarpment drainage solutions. Same-day install.',
                'og_title' => 'Sod Installation & Grading in Hamilton | Lush',
                'og_description' => 'Premium sod installation with proper grading and soil amendment for Hamilton lawns. Engineered for clay-till soils.',
                'sort_order' => 11,
            ],

            // ─── 12. Artificial Turf ────────────────────────────────────────
            'Artificial Turf' => [
                'page_title' => 'Artificial Turf Installation in Hamilton',
                'h1' => 'Artificial Turf in Hamilton',
                'local_intro' => "For Hamilton homeowners who want a consistently green, maintenance-free lawn from spring through winter, artificial turf eliminates the mowing, watering, fertilizing, and seasonal brown patches that natural grass demands. Modern synthetic turf has advanced well beyond the plastic appearance of earlier generations. Today's premium products feature multi-toned blade profiles, integrated thatch layers for natural appearance, and UV stabilization that maintains colour integrity for 15 or more years, even through Hamilton's intense summer sun and heavy lake-effect snowfall.\n\nOur Hamilton artificial turf installations address the specific drainage limitations of the city's clay-heavy glacial till. We excavate existing soil, install compacted aggregate base with positive drainage grade, lay geotextile separation fabric, and secure the turf with landscape spikes and infill material. Pet-friendly installations use antimicrobial infill and achieve drainage rates exceeding 30 inches per hour, critical for Hamilton properties where the underlying clay would otherwise trap moisture at the surface.\n\nWhether you need a backyard play surface for children in Waterdown, a pet run in Ancaster that stays clean through mud season, a putting green for golf practice in Stoney Creek, or a front-yard accent in Dundas that looks immaculate without weekend maintenance, our Hamilton artificial turf team delivers turnkey installations tailored to your property.",
                'meta_title' => 'Artificial Turf Hamilton | Lush Landscape',
                'meta_description' => 'Artificial turf installation in Hamilton. Pet-friendly, UV-stable, 30+ in/hr drainage on clay soils. No mowing, no watering, always green.',
                'og_title' => 'Artificial Turf in Hamilton | Lush Landscape',
                'og_description' => 'Maintenance-free artificial turf for Hamilton lawns, pet areas, and play zones. Engineered drainage for clay-till soils.',
                'sort_order' => 12,
            ],

            // ─── 13. Garden Design & Planting ───────────────────────────────
            'Garden Design & Planting' => [
                'page_title' => 'Garden Design & Planting in Hamilton',
                'h1' => 'Garden Design & Planting in Hamilton',
                'local_intro' => "Hamilton's position at the base of the Niagara Escarpment creates a rich botanical environment that few Ontario cities can match. The Escarpment's sheltered microclimates, combined with the moderating influence of Lake Ontario, allow Hamilton gardeners to grow plant species that struggle in colder inland locations. As a Zone 6a city with pockets pushing toward 6b near the lakeshore, Hamilton offers an expansive palette of hardy perennials, ornamental grasses, flowering shrubs, and specimen trees that our garden design team leverages in every planting plan.\n\nWe start every Hamilton garden project with an on-site assessment of sun exposure, soil type, drainage conditions, and existing vegetation. Properties near the Escarpment brow often have thin, rocky soil that requires raised bed construction, while lower-city lots typically sit on deep clay that needs amendment for proper root establishment. Planting plans specify cultivars proven in Zone 6a conditions, sourced from Ontario-accredited nurseries. Bed construction includes excavation of compacted subsoil, amendment with premium planting mix, and installation of steel or aluminum edging for clean, permanent bed lines.\n\nFrom pollinator gardens in Dundas that support the Escarpment's biodiversity to privacy screening hedges along Ancaster's estate boundaries, our Hamilton garden design team creates outdoor spaces that improve with every passing season. All plant material carries a one-year health guarantee when our care instructions are followed.",
                'meta_title' => 'Garden Design & Planting Hamilton | Lush',
                'meta_description' => 'Professional garden design and planting in Hamilton. Escarpment-adapted species, pollinator gardens, four-season interest. One-year guarantee.',
                'og_title' => 'Garden Design & Planting in Hamilton | Lush',
                'og_description' => 'Custom garden design with Escarpment-adapted plants for Hamilton properties. Beautiful, sustainable, and pollinator-friendly.',
                'sort_order' => 13,
            ],

            // ─── 14. Landscape Lighting ─────────────────────────────────────
            'Landscape Lighting' => [
                'page_title' => 'Landscape Lighting in Hamilton',
                'h1' => 'Landscape Lighting in Hamilton',
                'local_intro' => "Professional landscape lighting extends usable outdoor hours on Hamilton properties, enhances security along Escarpment-slope driveways and walkways, and highlights the architectural and natural features that define your home's character. With Hamilton's shorter winter days and early-evening darkness from November through March, a well-designed lighting system transforms how you experience your outdoor spaces during the months when you need light the most.\n\nWe use low-voltage LED systems powered by commercial-grade transformers with built-in timers and WiFi smart controls compatible with phone apps and home automation platforms. All fixtures are solid brass or marine-grade aluminum, selected for their resistance to Hamilton's freeze-thaw cycling, lake-effect moisture, and salt exposure. Direct-burial cable rated for outdoor use is trenched below grade to protect against damage from seasonal ground movement in the clay-till soil that covers most Hamilton properties.\n\nFrom Escarpment brow properties in upper Hamilton where path lighting guides visitors safely along steep approaches to backyard patio illumination in Ancaster and garden bed accent lighting in Westdale, our Hamilton lighting projects are designed by certified professionals who understand colour temperature, beam angles, and lumen output for each application. Every system is sized with spare transformer capacity for future expansion, so adding fixtures later never requires equipment upgrades.",
                'meta_title' => 'Landscape Lighting Hamilton | Lush Landscape',
                'meta_description' => 'Professional LED landscape lighting in Hamilton. Brass fixtures, WiFi smart controls, and custom design for Escarpment properties.',
                'og_title' => 'Landscape Lighting in Hamilton | Lush Landscape',
                'og_description' => 'Custom landscape lighting design and installation for Hamilton homes. Low-voltage LED, brass fixtures, smart controls.',
                'sort_order' => 14,
            ],

        ];
    }
}
