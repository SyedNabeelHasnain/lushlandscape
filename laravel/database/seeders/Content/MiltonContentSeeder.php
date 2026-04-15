<?php

namespace Database\Seeders\Content;

use App\Models\City;
use App\Models\Service;
use App\Models\ServiceCityPage;
use Illuminate\Database\Seeder;

class MiltonContentSeeder extends Seeder
{
    public function run(): void
    {
        $city = City::where('name', 'Milton')->firstOrFail();
        $pages = $this->getPages();

        $neighborhoods = [
            'Old Milton', 'Dempsey', 'Willmott', 'Timberlea', 'Harrison', 'Bronte Meadows',
            'Scott', 'Clarke', 'Cobblestone', 'Beaty',
            'Sherwood Survey', 'Dorset Park',
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
                        'text' => 'Book a Consultation in Milton',
                        'url' => '/request-quote?service='.urlencode($service->name).'&city=Milton',
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
                'page_title' => 'Interlocking Driveways in Milton',
                'h1' => 'Interlocking Driveways in Milton',
                'local_intro' => "Milton sits at the western edge of the Niagara Escarpment, where rapid residential growth has transformed former agricultural land into some of Halton Region's newest subdivisions. The clay-heavy soils that once supported Milton's farming heritage now present a serious challenge for driveway construction. Compacted builder-grade subsoil left behind by heavy construction equipment on new builds in Timberlea, Harrison, and Bristol holds moisture, resists drainage, and heaves aggressively through freeze-thaw cycles. Our Milton interlocking driveway installations address these conditions from the subgrade up.\n\nWe excavate to a minimum 18-inch depth on Milton properties, accounting for the 48-inch frost penetration depth in this Zone 5b-6a transitional climate. A compacted Granular A sub-base, 1-inch HPB levelling course, and premium pavers from Unilock, Techo-Bloc, or Belgard rated at 8,000+ PSI form the finished surface. Herringbone or 45-degree bond patterns maximize interlock strength under vehicle loads, while polymeric sand and soldier-course edge restraint anchored below frost line ensure long-term joint stability across Milton's heavy snowfall seasons that average 130 cm annually.\n\nWhether your property is in an established pocket of Old Milton near Main Street or a recently built home in Scott or Willmott, our ICPI-certified crews adapt every installation to your lot's specific soil profile and drainage conditions. Milton homeowners receive the same engineered approach we apply across Halton Region, backed by our workmanship warranty.",
                'meta_title' => 'Interlocking Driveways Milton | Lush Landscape',
                'meta_description' => 'Custom interlocking driveway installation in Milton. Engineered for Escarpment clay soils, 48-inch frost depth. ICPI-certified with consultation-led planning.',
                'og_title' => 'Interlocking Driveways in Milton | Lush Landscape',
                'og_description' => 'Premium interlocking driveway construction for Milton homeowners. Engineered for Escarpment-edge clay soils and Halton Region winters.',
                'sort_order' => 1,
            ],

            // ─── 2. Interlocking Patios & Backyard Living ───────────────────
            'Interlocking Patios & Backyard Living' => [
                'page_title' => 'Interlocking Patios & Outdoor Living in Milton',
                'h1' => 'Interlocking Patios & Backyard Living in Milton',
                'local_intro' => "Milton homeowners are investing in outdoor living spaces that match the quality of the new homes being built across this fast-growing Halton Hills town. Properties in Mountainview back onto protected Escarpment greenspace, Harrison lots offer mature trees and generous rear yards, and new builds in Scott and Willmott arrive with blank-canvas backyards ready for transformation. A properly engineered interlocking patio turns any of these settings into a complete outdoor living environment with BBQ islands, fire pits, seating walls, and integrated lighting.\n\nThe former agricultural clay that underlies most Milton subdivisions creates specific drainage challenges that must be solved at the base level. We engineer positive grade away from your foundation, integrate catch basins where impermeable clay creates ponding risk, and install open-graded base systems on lots where conventional drainage proves insufficient. Every Milton patio includes compacted Granular A sub-base, HPB levelling course, and premium pavers selected for both freeze-thaw durability and design appeal. Conservation Halton regulations apply to many Milton properties near the Escarpment, and we coordinate HCA approvals as part of our project scope.\n\nFrom multi-level outdoor rooms with kitchens and fireplaces on Mountainview estate lots to intimate courtyard patios in Old Milton, our design team works from concept through completion. We coordinate gas, electrical, and plumbing trades as needed, and all patio work in Milton carries our 10-year workmanship warranty.",
                'meta_title' => 'Interlocking Patios Milton | Lush Landscape',
                'meta_description' => 'Custom interlocking patios in Milton. Outdoor kitchens, fire pits, seating walls. Engineered for clay soils and Escarpment terrain with consultation-led planning.',
                'og_title' => 'Interlocking Patios & Backyard Living in Milton',
                'og_description' => 'Transform your Milton backyard with custom interlocking patios, outdoor kitchens, and fire features built for Escarpment-edge terrain.',
                'sort_order' => 2,
            ],

            // ─── 3. Walkways & Steps ────────────────────────────────────────
            'Walkways & Steps' => [
                'page_title' => 'Walkways & Steps in Milton',
                'h1' => 'Walkways & Steps in Milton',
                'local_intro' => "Milton's position along the Niagara Escarpment means that many residential properties deal with grade transitions between the street, front entry, and backyard. Combined with annual snowfall averaging 130 cm and frost penetration reaching 48 inches, these elevation changes demand walkways and steps that are structurally sound, properly drained, and slip-resistant in all seasons. Our walkway and step installations in Milton follow Ontario Building Code requirements for riser height, tread depth, and handrail placement.\n\nWe design walkways that complement your existing hardscaping and respond to your property's specific grade conditions. For Milton homes with sloped front approaches in Mountainview and Escarpment-edge lots, we build landing pads at code-required intervals, install non-slip tread surfaces, and ensure positive drainage diverts meltwater away from walking surfaces. Steps use reinforced concrete cores with paver or natural stone cladding for structural permanence, anchored to footings that extend below the 48-inch frost depth that Milton's transitional Zone 5b-6a climate demands.\n\nFrom character homes in Old Milton where step design must respect the neighbourhood's established streetscape to new subdivisions in Bristol and Willmott where builder-grade concrete is ready for an upgrade, our Milton walkway projects deliver safety and curb appeal in equal measure. Every installation includes compacted aggregate base, edge restraint, and polymeric sand to prevent weed growth and paver migration.",
                'meta_title' => 'Walkways & Steps Milton | Lush Landscape',
                'meta_description' => 'Interlocking walkways and steps in Milton. Non-slip surfaces, OBC-compliant risers, Escarpment-grade engineering with consultation-led planning.',
                'og_title' => 'Walkways & Steps in Milton | Lush Landscape',
                'og_description' => 'Professional walkway and step installation for Milton homes. Engineered for Escarpment elevation changes and winter safety.',
                'sort_order' => 3,
            ],

            // ─── 4. Natural Stone & Flagstone ───────────────────────────────
            'Natural Stone & Flagstone' => [
                'page_title' => 'Natural Stone & Flagstone in Milton',
                'h1' => 'Natural Stone & Flagstone Installation in Milton',
                'local_intro' => "Milton's location at the edge of the Niagara Escarpment places it directly alongside one of Ontario's most significant geological formations. The exposed dolostone, limestone, and shale that define the Escarpment make natural stone a material choice that feels inherently connected to Milton's landscape. Our natural stone division works with locally quarried Eramosa limestone, Algonquin flagstone, Muskoka granite, and Wiarton bluestone to create patios, walkways, accent walls, and water features for Milton properties.\n\nFlagstone installations on Milton's clay-heavy agricultural soils require meticulous base preparation. We install a minimum 12-inch compacted aggregate base under all flagstone work, use mortar-set or dry-laid techniques depending on application and load requirements, and fill joints with polymeric sand or natural stone dust to prevent weed intrusion. Armour stone installations use Ontario-quarried boulders weighing 1,000 to 4,000 pounds, placed with equipment precision and seated against seasonal soil movement. Milton's Escarpment Commission regulations and Conservation Halton requirements apply to many properties, and we handle all permitting as part of our project scope.\n\nFrom hand-cut flagstone patios overlooking Escarpment greenspace in Mountainview to armour stone terracing on graded lots in Timberlea and Harrison, our Milton crews bring the craftsmanship that natural stone demands. Every project reflects the geological character that makes Milton's Escarpment setting unique in Halton Region.",
                'meta_title' => 'Natural Stone & Flagstone Milton | Lush Landscape',
                'meta_description' => 'Natural stone and flagstone installation in Milton. Ontario-quarried materials, Escarpment-inspired design, HCA permit coordination with consultation-led planning.',
                'og_title' => 'Natural Stone & Flagstone in Milton | Lush Landscape',
                'og_description' => 'Premium natural stone patios, flagstone walkways, and armour stone features for Milton properties near the Niagara Escarpment.',
                'sort_order' => 4,
            ],

            // ─── 5. Porcelain Pavers ────────────────────────────────────────
            'Porcelain Pavers' => [
                'page_title' => 'Porcelain Paver Installation in Milton',
                'h1' => 'Porcelain Pavers in Milton',
                'local_intro' => "Porcelain pavers offer Milton homeowners a modern hardscaping material that thrives in the demanding conditions of a Zone 5b-6a transitional climate. These 20mm-thick engineered tiles deliver zero water absorption, R11 slip rating, and UV stability, making them resistant to the freeze-thaw cycling, heavy snowfall averaging 130 cm, and road salt exposure that Milton properties endure from November through April. For homeowners in Milton's newer subdivisions who want a clean contemporary aesthetic without constant maintenance, porcelain pavers are the ideal solution.\n\nOur Milton porcelain paver installations use pedestal or compacted-aggregate base systems depending on the project type. Rooftop terraces and pool surrounds typically use adjustable pedestal systems that accommodate drainage underneath and provide easy access to membrane surfaces. Ground-level patios use compacted aggregate base with levelling screed and open-graded joint material, engineered for Milton's former agricultural clay subgrade that retains moisture and resists natural percolation. We source 20mm porcelain pavers from leading Italian and Spanish manufacturers through Canadian distributors who certify freeze-thaw performance to ASTM C1026 standards.\n\nFrom sleek concrete-look terraces on new builds in Harrison and Scott to warm wood-tone pool surrounds in established Timberlea, porcelain pavers give Milton homeowners design flexibility that few other materials can match. Every installation is backed by our workmanship warranty and manufacturer material guarantees.",
                'meta_title' => 'Porcelain Pavers Milton | Lush Landscape',
                'meta_description' => 'Modern 20mm porcelain paver installation in Milton. Zero absorption, R11 slip rating, frost-proof. Patios, pool decks, and terraces.',
                'og_title' => 'Porcelain Pavers in Milton | Lush Landscape',
                'og_description' => 'Contemporary porcelain paver installation for Milton patios, terraces, and pool surrounds. Built for Escarpment-edge climate.',
                'sort_order' => 5,
            ],

            // ─── 6. Concrete Driveways ──────────────────────────────────────
            'Concrete Driveways' => [
                'page_title' => 'Concrete Driveways in Milton',
                'h1' => 'Concrete Driveways in Milton',
                'local_intro' => "A concrete driveway remains the most cost-effective way to achieve a durable, clean-looking surface for Milton properties, from established lots in Old Milton to new builds across the town's expanding western subdivisions. Our concrete driveway installations use 32 MPa air-entrained mix with 5 to 7 percent air content, the Ontario specification for freeze-thaw durability in Zone 5b-6a climates. Every pour includes 10M rebar on 400mm centres, fibre-mesh reinforcement, and control joints calculated for slab dimensions and expected load.\n\nMilton's former agricultural clay creates a subgrade that holds water and heaves if base preparation is inadequate. New subdivisions in Bristol, Scott, and Willmott are particularly affected, where heavy construction equipment has compacted builder-grade subsoil into a nearly impermeable layer. We excavate to a minimum 14-inch depth, install compacted Granular A sub-base, and use a polyethylene vapour barrier to prevent moisture migration into the slab. On sloped driveways near the Escarpment edge, we incorporate broom-finish texturing for traction and install cross-slope drainage swales to redirect surface water before it reaches the garage.\n\nWe offer broom-finish, exposed aggregate, stamped patterns including ashlar slate, cobblestone, and wood-plank textures, and acid-stain decorative finishes. Milton homeowners in established neighbourhoods benefit from stamped patterns that complement existing streetscape character while delivering modern structural performance built for Halton Region's winters.",
                'meta_title' => 'Concrete Driveways Milton | Lush Landscape',
                'meta_description' => 'Concrete driveway installation in Milton. 32 MPa air-entrained mix, rebar reinforcement, clay-soil engineering with consultation-led planning.',
                'og_title' => 'Concrete Driveways in Milton | Lush Landscape',
                'og_description' => 'High-strength concrete driveways for Milton homes. Stamped, exposed aggregate, and broom-finish options for every lot.',
                'sort_order' => 6,
            ],

            // ─── 7. Concrete Patios & Walkways ─────────────────────────────
            'Concrete Patios & Walkways' => [
                'page_title' => 'Concrete Patios & Walkways in Milton',
                'h1' => 'Concrete Patios & Walkways in Milton',
                'local_intro' => "Architectural concrete gives Milton homeowners the opportunity to create outdoor surfaces with genuine design impact, far beyond a plain grey slab. Our stamped, stained, and exposed aggregate finishes transform patios, walkways, pool decks, and stepping-stone paths into features that complement both modern builds in Harrison and character homes in Old Milton. We pour all flatwork using 32 MPa air-entrained concrete specified for Milton's freeze-thaw climate, where frost penetration reaches 48 inches and annual snowfall averages 130 cm.\n\nStamped concrete patterns include ashlar slate, European fan, random stone, cobblestone, and wood-plank textures applied with colour hardener and release agents for a natural, multi-toned appearance. Exposed aggregate finishes reveal the natural stone within the mix, creating a textured, slip-resistant surface ideal for pool surrounds and garden paths. For Milton properties that back onto Escarpment greenspace where outdoor entertaining comes with views of the protected landscape, acid-stain finishes produce translucent colour variations that echo the natural stone formations along the escarpment corridor.\n\nEvery Milton concrete patio project includes proper sub-base preparation for the local agricultural clay soil profile, positive drainage grading away from your foundation, and isolation joints where the patio meets the house structure. We coordinate with Conservation Halton and Niagara Escarpment Commission requirements for projects on regulated properties in Milton.",
                'meta_title' => 'Concrete Patios & Walkways Milton | Lush',
                'meta_description' => 'Stamped and exposed aggregate concrete patios in Milton. Decorative finishes, proper drainage, and Escarpment-ready engineering.',
                'og_title' => 'Concrete Patios & Walkways in Milton | Lush Landscape',
                'og_description' => 'Architectural concrete patios and walkways for Milton homes. Stamped, stained, and exposed aggregate options.',
                'sort_order' => 7,
            ],

            // ─── 8. Interlock Restoration & Sealing ─────────────────────────
            'Interlock Restoration & Sealing' => [
                'page_title' => 'Interlock Restoration & Sealing in Milton',
                'h1' => 'Interlock Restoration & Sealing in Milton',
                'local_intro' => "Milton's rapid growth over the past two decades means that thousands of homes built in the early 2000s in Timberlea, Harrison, and Bristol now have interlocking driveways and patios that show 15 to 20 years of wear. Road salt tracked from Milton's well-maintained winter roads, organic growth fuelled by the Escarpment's humid microclimate, and UV fading from unprotected exposure all contribute to surfaces that look tired long before the pavers themselves are structurally compromised. Our 3-day interlock restoration process reverses that deterioration and provides lasting protection.\n\nDay one involves thorough hot-water pressure washing at 3,000+ PSI with surface-cleaning attachments that remove embedded dirt, moss, algae, efflorescence, and the mineral staining common near the Escarpment's groundwater seeps. Day two allows complete surface drying before we re-apply polymeric sand to all joints, ensuring proper activation and curing. Day three applies UV-resistant sealer in the homeowner's choice of matte, satin, or wet-look finish, locking in colour, preventing future staining, and simplifying ongoing maintenance.\n\nWe restore interlocking driveways, patios, walkways, and pool decks throughout Milton, from Old Milton's established streets to the maturing subdivisions of Mountainview and Scott. If your pavers are structurally sound but visually tired, restoration and sealing delivers the most cost-effective renewal available without the expense and disruption of full replacement.",
                'meta_title' => 'Interlock Restoration & Sealing Milton | Lush',
                'meta_description' => 'Professional interlock restoration and sealing in Milton. 3-day process: pressure wash, polymeric sand, UV sealer. Renew your pavers.',
                'og_title' => 'Interlock Restoration & Sealing in Milton',
                'og_description' => 'Restore faded interlocking surfaces in Milton. Hot-water wash, polymeric sand, and protective sealer application.',
                'sort_order' => 8,
            ],

            // ─── 9. Interlock Repair (Lift & Relay) ─────────────────────────
            'Interlock Repair (Lift & Relay)' => [
                'page_title' => 'Interlock Repair (Lift & Relay) in Milton',
                'h1' => 'Interlock Repair in Milton: Lift & Relay',
                'local_intro' => "Sunken, heaving, or uneven interlocking pavers are a common problem across Milton, where former agricultural clay expands and contracts aggressively through freeze-thaw cycles. New subdivisions built on compacted builder-grade subsoil are especially vulnerable, as the heavy equipment used during construction creates dense, poorly draining layers beneath thin topsoil and aggregate bases. These displaced surfaces create tripping hazards, allow water to pool against foundations, and worsen progressively if the underlying cause is not corrected.\n\nOur Milton repair process begins with carefully removing the affected pavers and setting them aside for re-use. We excavate the failed base material, diagnose the cause of failure, whether insufficient base depth, poor original compaction, tree root intrusion, or subsurface drainage failure from Milton's impermeable clay layer, and correct it permanently. Fresh HPB aggregate is installed, compacted in lifts to 95 percent Standard Proctor density, and screeded to the correct elevation. Original pavers are then re-laid in their original pattern, new polymeric sand fills all joints, and the repaired section is compacted to match the surrounding surface.\n\nLift-and-relay preserves your existing pavers and their naturally weathered patina, so the repair blends seamlessly with undisturbed areas. We perform this service on driveways, patios, walkways, and pool decks across Milton, from Timberlea and Harrison to Bristol and Mountainview.",
                'meta_title' => 'Interlock Repair Milton - Lift & Relay | Lush',
                'meta_description' => 'Permanent interlock repair in Milton. Lift and relay sunken pavers with HPB base correction. Fix the root cause on clay soils.',
                'og_title' => 'Interlock Repair (Lift & Relay) in Milton',
                'og_description' => 'Fix sunken and heaving pavers in Milton permanently. HPB base correction engineered for agricultural clay subgrade.',
                'sort_order' => 9,
            ],

            // ─── 10. Retaining Walls ────────────────────────────────────────
            'Retaining Walls' => [
                'page_title' => 'Retaining Walls in Milton',
                'h1' => 'Retaining Wall Construction in Milton',
                'local_intro' => "Milton's position along the Niagara Escarpment creates residential properties with grade changes that demand engineered retaining solutions. Lots that back onto Escarpment greenspace in Mountainview, sloped front yards in the town's western expansion areas, and erosion-prone boundaries along ravines and watercourses all require walls designed for structural permanence. Whether you need a terraced backyard carved into a hillside, a front-yard wall to stabilize a sloped driveway, or erosion control along a conservation boundary, our retaining wall division delivers structurally certified results in Milton.\n\nWe build with armour stone sourced from Ontario quarries in weights from 1,000 to 4,000 pounds, precast concrete blocks from Allan Block, Cornerstone, and Unilock, and poured-in-place reinforced concrete where structural loads require it. Every retaining wall over 1.0 metre in retained height is designed to Ontario Building Code Part 4 standards with geogrid reinforcement at calculated intervals, clear stone backfill, filter fabric, and weeping tile connected to storm or daylight outlets. Milton's clay backfill conditions make proper drainage behind the wall as critical as the wall structure itself, especially on lots where compacted builder-grade subsoil traps water against the wall face.\n\nOur Milton retaining wall projects include full engineering coordination where required, Conservation Halton permitting for properties within HCA-regulated areas, and Niagara Escarpment Commission approvals for properties within the Escarpment Development Control Area. Milton homeowners receive clear guidance on which permits apply to their lot before any work begins.",
                'meta_title' => 'Retaining Walls Milton | Lush Landscape',
                'meta_description' => 'Retaining wall construction in Milton. Armour stone, concrete block, geogrid reinforced. OBC-compliant, HCA and NEC permitted with consultation-led planning.',
                'og_title' => 'Retaining Walls in Milton | Lush Landscape',
                'og_description' => 'Engineered retaining walls for Milton Escarpment properties. Armour stone and precast block with proper drainage and permitting.',
                'sort_order' => 10,
            ],

            // ─── 11. Sod Installation & Grading ─────────────────────────────
            'Sod Installation & Grading' => [
                'page_title' => 'Sod Installation & Grading in Milton',
                'h1' => 'Sod Installation & Grading in Milton',
                'local_intro' => "Establishing a healthy lawn in Milton requires understanding the soil that sits beneath it. As one of the fastest-growing towns in Halton Region, Milton has seen vast tracts of former agricultural land converted into residential subdivisions over the past two decades. The clay-heavy soils that once sustained Milton's farming economy now sit compacted by heavy construction equipment, covered with a thin skim of topsoil that does little to support healthy turf establishment. Builder-grade lots in Bristol, Scott, Willmott, and the town's western expansion areas arrive with subsoil that resists root penetration and drains poorly.\n\nWe establish positive drainage grade away from your foundation at a minimum 2 percent slope for the first 6 feet, then transition to a gentler grade across the remainder of the yard. On Milton's Escarpment-adjacent properties where natural grades are steep, we incorporate terracing or swale systems to prevent erosion and control surface water. Four to 6 inches of Triple-Mix, a blend of screened topsoil, peat, and compost, is spread and raked to final grade. Premium Kentucky Bluegrass sod is laid the same day it is cut, rolled for full root contact, and given starter fertilizer.\n\nFrom post-construction grading on new builds across Milton's expanding subdivisions to full lawn renovation on mature Old Milton lots shaded by established trees, our Milton sod crews handle projects of every scale. We guarantee root establishment when our watering schedule is followed.",
                'meta_title' => 'Sod Installation & Grading Milton | Lush',
                'meta_description' => 'Professional sod installation and grading in Milton. Kentucky Bluegrass, Triple-Mix soil, Escarpment drainage solutions. Same-day install.',
                'og_title' => 'Sod Installation & Grading in Milton | Lush',
                'og_description' => 'Premium sod installation with proper grading and soil amendment for Milton lawns. Engineered for clay soils on former farmland.',
                'sort_order' => 11,
            ],

            // ─── 12. Artificial Turf ────────────────────────────────────────
            'Artificial Turf' => [
                'page_title' => 'Artificial Turf Installation in Milton',
                'h1' => 'Artificial Turf in Milton',
                'local_intro' => "For Milton homeowners who want a consistently green, maintenance-free lawn from spring through winter, artificial turf eliminates the mowing, watering, fertilizing, and seasonal brown patches that natural grass demands. Modern synthetic turf has advanced well beyond the plastic appearance of earlier generations. Today's premium products feature multi-toned blade profiles, integrated thatch layers for natural appearance, and UV stabilization that maintains colour integrity for 15 or more years, even through Milton's intense summer sun and heavy snowfall that averages 130 cm annually.\n\nOur Milton artificial turf installations address the specific drainage limitations of the town's former agricultural clay. We excavate existing soil, install compacted aggregate base with positive drainage grade, lay geotextile separation fabric, and secure the turf with landscape spikes and infill material. Pet-friendly installations use antimicrobial infill and achieve drainage rates exceeding 30 inches per hour, critical for Milton properties where the underlying clay would otherwise trap moisture at the surface and create standing water issues.\n\nWhether you need a backyard play surface for children in Harrison, a pet run in Timberlea that stays clean through mud season, a putting green for golf practice in Mountainview, or a front-yard accent in Old Milton that looks immaculate without weekend maintenance, our Milton artificial turf team delivers turnkey installations tailored to your property and soil conditions.",
                'meta_title' => 'Artificial Turf Milton | Lush Landscape',
                'meta_description' => 'Artificial turf installation in Milton. Pet-friendly, UV-stable, 30+ in/hr drainage on clay soils. No mowing, no watering, always green.',
                'og_title' => 'Artificial Turf in Milton | Lush Landscape',
                'og_description' => 'Maintenance-free artificial turf for Milton lawns, pet areas, and play zones. Engineered drainage for agricultural clay soils.',
                'sort_order' => 12,
            ],

            // ─── 13. Garden Design & Planting ───────────────────────────────
            'Garden Design & Planting' => [
                'page_title' => 'Garden Design & Planting in Milton',
                'h1' => 'Garden Design & Planting in Milton',
                'local_intro' => "Milton's position at the edge of the Niagara Escarpment provides a rich botanical context that few Ontario towns can match. The Escarpment's sheltered microclimates and the moderating effect of the region's geography allow Milton gardeners to grow plant species that thrive in the Zone 5b-6a transitional climate. Protected Escarpment woodlands adjacent to Mountainview and the conservation corridors managed by Conservation Halton provide a natural reference point for native planting designs. Our garden design team leverages Milton's climate and landscape character in every planting plan.\n\nWe start every Milton garden project with an on-site assessment of sun exposure, soil type, drainage conditions, and existing vegetation. Properties near the Escarpment edge often have thin, rocky soil mixed with clay that requires raised bed construction, while lots in newer subdivisions like Bristol and Willmott sit on deep compacted builder-grade subsoil that needs thorough amendment for proper root establishment. Planting plans specify cultivars proven in Zone 5b-6a conditions, sourced from Ontario-accredited nurseries. Bed construction includes excavation of compacted subsoil, amendment with premium planting mix, and installation of steel or aluminum edging for clean, permanent bed lines.\n\nFrom pollinator gardens that support the Escarpment's biodiversity to privacy screening hedges along Milton's newer lot lines where neighbours sit close, our Milton garden design team creates outdoor spaces that improve with every passing season. All plant material carries a one-year health guarantee when our care instructions are followed.",
                'meta_title' => 'Garden Design & Planting Milton | Lush',
                'meta_description' => 'Professional garden design and planting in Milton. Escarpment-adapted species, pollinator gardens, four-season interest. One-year guarantee.',
                'og_title' => 'Garden Design & Planting in Milton | Lush',
                'og_description' => 'Custom garden design with Escarpment-adapted plants for Milton properties. Beautiful, sustainable, and pollinator-friendly.',
                'sort_order' => 13,
            ],

            // ─── 14. Landscape Lighting ─────────────────────────────────────
            'Landscape Lighting' => [
                'page_title' => 'Landscape Lighting in Milton',
                'h1' => 'Landscape Lighting in Milton',
                'local_intro' => "Professional landscape lighting extends usable outdoor hours on Milton properties, enhances security along driveways and walkways, and highlights the architectural and natural features that define your home's character. With Milton's shorter winter days and early-evening darkness from November through March, a well-designed lighting system transforms how you experience your outdoor spaces during the months when you need light the most. Milton's newer subdivisions in particular benefit from lighting that adds curb appeal and distinguishes your property from adjacent homes sharing similar builder-grade exteriors.\n\nWe use low-voltage LED systems powered by commercial-grade transformers with built-in timers and WiFi smart controls compatible with phone apps and home automation platforms. All fixtures are solid brass or marine-grade aluminum, selected for their resistance to Milton's freeze-thaw cycling, heavy snowfall averaging 130 cm annually, and road salt exposure throughout winter. Direct-burial cable rated for outdoor use is trenched below grade to protect against damage from seasonal ground movement in the clay soils that underlie most Milton properties.\n\nFrom Escarpment-edge properties in Mountainview where path lighting guides visitors safely along graded approaches to backyard patio illumination in Timberlea and garden bed accent lighting in Harrison, our Milton lighting projects are designed by certified professionals who understand colour temperature, beam angles, and lumen output for each application. Every system is sized with spare transformer capacity for future expansion, so adding fixtures to your Milton property later never requires equipment upgrades.",
                'meta_title' => 'Landscape Lighting Milton | Lush Landscape',
                'meta_description' => 'Professional LED landscape lighting in Milton. Brass fixtures, WiFi smart controls, and custom design for Escarpment-edge properties.',
                'og_title' => 'Landscape Lighting in Milton | Lush Landscape',
                'og_description' => 'Custom landscape lighting design and installation for Milton homes. Low-voltage LED, brass fixtures, smart controls.',
                'sort_order' => 14,
            ],

        ];
    }
}
