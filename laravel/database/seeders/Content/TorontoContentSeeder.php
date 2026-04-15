<?php

namespace Database\Seeders\Content;

use App\Models\City;
use App\Models\Service;
use App\Models\ServiceCityPage;
use Illuminate\Database\Seeder;

class TorontoContentSeeder extends Seeder
{
    public function run(): void
    {
        $city = City::where('name', 'Toronto')->firstOrFail();
        $pages = $this->getPages();

        $neighborhoods = [
            'Etobicoke', 'North York', 'Scarborough', 'Rosedale', 'The Beaches', 'High Park',
            'Forest Hill', 'Lawrence Park', 'Leaside', 'Don Mills',
            'Bloor West Village', 'Yorkville',
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
                        'text' => 'Book a Consultation in Toronto',
                        'url' => '/request-quote?service='.urlencode($service->name).'&city=Toronto',
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
                'page_title' => 'Interlocking Driveways in Toronto',
                'h1' => 'Interlocking Driveways in Toronto',
                'local_intro' => "Toronto driveways face a unique combination of urban density constraints and aggressive climate cycling that demands precision engineering from the ground up. Properties across the city range from narrow Victorian-era lots in the Annex and Cabbagetown, where every inch of driveway width matters, to estate-scale frontages in Forest Hill and Bridle Path, where circular drives and multi-car pads require heavy-load engineering. Our Toronto interlocking driveway installations begin with a full site assessment that accounts for your lot's soil composition, municipal setback requirements under Zoning Bylaw 569-2013, and any Toronto ravine or tree protection bylaw obligations that apply to your property.\n\nWe excavate to a minimum 16-inch depth to address the 48-inch frost penetration depth in Toronto's Zone 6a climate. The base structure includes compacted Granular A sub-base, a 1-inch HPB levelling course, and premium pavers from Unilock, Techo-Bloc, or Belgard rated at 8,000+ PSI compressive strength. Herringbone or 45-degree bond patterns maximize interlock strength under daily vehicle traffic, while polymeric sand jointing and soldier-course edge restraint anchored below frost line ensure long-term stability through Toronto's freeze-thaw cycles.\n\nWhether you are replacing a crumbling concrete pad on a compact Leaside lot or building a new driveway on an expansive Lawrence Park property, our ICPI-certified crews deliver the same exacting standard. Toronto properties in combined sewer areas receive special drainage consideration to manage stormwater runoff in compliance with municipal requirements.",
                'meta_title' => 'Interlocking Driveways Toronto | Lush Landscape',
                'meta_description' => 'Custom interlocking driveway installation in Toronto. ICPI-certified crews, 8,000+ PSI pavers, engineered for Zone 6a frost depth with consultation-led planning.',
                'og_title' => 'Interlocking Driveways in Toronto | Lush Landscape',
                'og_description' => 'Premium interlocking driveway construction for Toronto homeowners. Engineered for urban lots and Ontario winters.',
                'sort_order' => 1,
            ],

            // ─── 2. Interlocking Patios & Backyard Living ───────────────────
            'Interlocking Patios & Backyard Living' => [
                'page_title' => 'Interlocking Patios & Outdoor Living in Toronto',
                'h1' => 'Interlocking Patios & Backyard Living in Toronto',
                'local_intro' => "Toronto homeowners are reclaiming their backyards as full-function outdoor living spaces, and a properly engineered interlocking patio is the foundation of every successful project. From the generous estate lots of Forest Hill and The Kingsway to the compact urban yards of Leslieville and the Junction, we design and build interlocking patios that accommodate BBQ islands, fire pits, seating walls, and integrated lighting within whatever footprint your Toronto property provides. Lot coverage limits under Zoning Bylaw 569-2013 are factored into every design to ensure your project is compliant before construction begins.\n\nToronto's variable soil conditions, from the sandy glacial deposits along the Scarborough Bluffs to the heavy clay found across much of North York, require base engineering tailored to each site. We establish positive grade away from your foundation, integrate catch basins where needed, and use open-graded base systems on properties where clay impermeability creates ponding risk. In combined sewer areas common across older Toronto neighbourhoods, stormwater management is built into the patio design. Every installation includes compacted Granular A sub-base, HPB levelling course, and premium pavers selected for both aesthetics and freeze-thaw durability.\n\nWhether you envision a simple entertaining pad or a multi-level outdoor room with kitchen, fireplace, and pergola, our Toronto design team works with you from concept through completion. We coordinate with gas, electrical, and plumbing trades as needed, delivering a single-source project experience backed by our 10-year workmanship warranty.",
                'meta_title' => 'Interlocking Patios Toronto | Lush Landscape',
                'meta_description' => 'Custom interlocking patio installation in Toronto. Outdoor kitchens, fire pits, seating walls. Engineered for local soils with consultation-led planning.',
                'og_title' => 'Interlocking Patios & Backyard Living in Toronto',
                'og_description' => 'Transform your Toronto backyard with custom interlocking patios, outdoor kitchens, and fire features.',
                'sort_order' => 2,
            ],

            // ─── 3. Walkways & Steps ────────────────────────────────────────
            'Walkways & Steps' => [
                'page_title' => 'Interlocking Walkways & Steps in Toronto',
                'h1' => 'Walkways & Steps in Toronto',
                'local_intro' => "A well-built walkway does more than connect your driveway to your front door. In Toronto, where winter ice and spring thaw create seasonal tripping hazards on thousands of residential properties, a properly graded interlocking walkway with consistent riser heights is a safety investment that also elevates curb appeal. Our Toronto walkway and step installations follow Ontario Building Code requirements for riser height, tread depth, and handrail placement, with additional attention to the municipal sidewalk-to-entry grade transitions common on the city's older streetscapes.\n\nWe design walkways that complement your existing hardscaping, whether that means matching paver colours and patterns with an interlocking driveway or introducing natural stone accents for visual contrast. For Toronto properties with grade changes, we build landing pads at required intervals, install non-slip tread surfaces, and ensure positive drainage away from the path. Steps use reinforced concrete cores with paver or stone cladding for structural permanence, with footings extending below the 48-inch frost depth that Toronto's Zone 6a climate demands.\n\nFrom heritage homes in Rosedale where step design must respect the neighbourhood's architectural character to modern builds in Etobicoke where builder-grade concrete is ready for an upgrade, our Toronto walkway projects deliver safety and curb appeal in equal measure. Properties subject to Toronto's tree protection bylaw receive root-sensitive excavation planning to avoid damaging any tree with a trunk diameter of 30 centimetres or greater.",
                'meta_title' => 'Walkways & Steps Toronto | Lush Landscape',
                'meta_description' => 'Interlocking walkways and steps in Toronto. Non-slip surfaces, OBC-compliant risers, and premium pavers. Safety meets curb appeal.',
                'og_title' => 'Walkways & Steps in Toronto | Lush Landscape',
                'og_description' => 'Professional walkway and step installation for Toronto homes. Engineered for urban grade transitions and winter safety.',
                'sort_order' => 3,
            ],

            // ─── 4. Natural Stone & Flagstone ───────────────────────────────
            'Natural Stone & Flagstone' => [
                'page_title' => 'Natural Stone & Flagstone in Toronto',
                'h1' => 'Natural Stone & Flagstone Installation in Toronto',
                'local_intro' => "Natural stone brings a timeless, organic character to Toronto properties that manufactured pavers cannot replicate. Our natural stone division works with Ontario-quarried materials including Eramosa limestone, Algonquin flagstone, Muskoka granite, and Owen Sound ledgerock to create patios, walkways, accent walls, and water features that complement the architectural diversity found across Toronto's established neighbourhoods. From the Georgian Revival estates of Forest Hill to the Victorian streetscapes of Cabbagetown, natural stone connects your hardscaping to a centuries-old building tradition.\n\nFlagstone patios in Toronto require careful attention to the variable soil conditions found across the city. Properties in Scarborough often sit on sandy glacial deposits that drain freely but shift without proper compaction, while North York lots typically feature heavy clay that retains moisture and heaves through freeze-thaw cycles. We install a minimum 12-inch compacted aggregate base under all flagstone work, use mortar-set or dry-laid techniques depending on the application, and fill joints with polymeric sand or stone dust to prevent weed intrusion. Armour stone installations use boulders sourced from Ontario quarries, placed with equipment precision and anchored against seasonal soil movement.\n\nFrom hand-cut flagstone patios in Lawrence Park to armour stone retaining features along ravine-edge properties in The Kingsway, our Toronto crews bring the craftsmanship that natural stone demands. Properties within Toronto and Region Conservation Authority regulated areas receive full permitting coordination as part of our project scope.",
                'meta_title' => 'Natural Stone & Flagstone Toronto | Lush Landscape',
                'meta_description' => 'Natural stone and flagstone installation in Toronto. Ontario-quarried materials, hand-cut patios, and armour stone features with consultation-led planning.',
                'og_title' => 'Natural Stone & Flagstone in Toronto | Lush Landscape',
                'og_description' => 'Premium natural stone patios, flagstone walkways, and armour stone features for Toronto properties.',
                'sort_order' => 4,
            ],

            // ─── 5. Porcelain Pavers ────────────────────────────────────────
            'Porcelain Pavers' => [
                'page_title' => 'Porcelain Paver Installation in Toronto',
                'h1' => 'Porcelain Pavers in Toronto',
                'local_intro' => "Porcelain pavers are the fastest-growing segment in Toronto's residential hardscaping market, and the reasons are clear. These 20mm-thick engineered tiles deliver the look of natural stone, wood, or concrete with zero water absorption, R11 slip rating, UV stability, and virtually no maintenance. For Toronto homeowners who want a modern, clean aesthetic without the upkeep of natural materials, porcelain pavers are the ideal solution. Their slim profile also makes them a practical choice on the compact urban lots common across midtown and downtown Toronto, where minimizing base excavation depth near property lines and mature tree roots is an advantage.\n\nOur Toronto porcelain paver installations use pedestal or compacted-aggregate base systems depending on the application. Rooftop terraces, which are increasingly popular on Toronto's urban infill properties, typically use adjustable pedestal systems that allow drainage underneath and easy access to membrane surfaces. Ground-level patios use compacted aggregate base with levelling screed and open-graded joint material, engineered to match the soil profile found on your specific Toronto lot. We source 20mm porcelain pavers from leading Italian and Spanish manufacturers through Canadian distributors who guarantee freeze-thaw performance to ASTM C1026 standards.\n\nFrom sleek grey concrete-look terraces on Leaside properties to warm wood-tone pool surrounds in Etobicoke, porcelain pavers give Toronto homeowners design flexibility that few other materials can match. Every installation is backed by our workmanship warranty and manufacturer material guarantees.",
                'meta_title' => 'Porcelain Pavers Toronto | Lush Landscape',
                'meta_description' => 'Modern 20mm porcelain paver installation in Toronto. Zero absorption, R11 slip rating, frost-proof. Patios, pool decks, terraces.',
                'og_title' => 'Porcelain Pavers in Toronto | Lush Landscape',
                'og_description' => 'Contemporary porcelain paver installation for Toronto patios, terraces, and pool surrounds. Maintenance-free elegance.',
                'sort_order' => 5,
            ],

            // ─── 6. Concrete Driveways ──────────────────────────────────────
            'Concrete Driveways' => [
                'page_title' => 'Concrete Driveways in Toronto',
                'h1' => 'Concrete Driveways in Toronto',
                'local_intro' => "A concrete driveway remains the most cost-effective way to achieve a durable, clean-looking surface for Toronto properties, from narrow single-car pads in the Annex to wide double-car approaches in North York. Our concrete driveway installations use 32 MPa air-entrained mix with 5 to 7 percent air content, the Ontario specification for freeze-thaw durability in Zone 6a climates. Every pour includes 10M rebar on 400mm centres, fibre-mesh reinforcement, and control joints calculated for slab dimensions and expected load.\n\nToronto's variable subgrade conditions require site-specific base preparation to prevent the cracking and heaving that plagues poorly built concrete driveways. Sandy soils along the Scarborough Bluffs demand different compaction strategies than the heavy clay found in North York and Etobicoke. We excavate to a minimum 12-inch depth, install compacted Granular A sub-base, and use a polyethylene vapour barrier to prevent moisture migration into the slab. On Toronto properties in combined sewer catchment areas, we incorporate drainage design that manages stormwater runoff in compliance with the city's wet weather flow management guidelines.\n\nWe offer broom-finish, exposed aggregate, stamped patterns including ashlar slate, cobblestone, and wood-plank textures, and acid-stain decorative finishes. Our Toronto concrete crews handle complex curves, multi-level transitions, and the tight equipment access that narrow urban lots demand. Heritage properties in Rosedale and Lawrence Park benefit from stamped finishes that complement period architecture.",
                'meta_title' => 'Concrete Driveways Toronto | Lush Landscape',
                'meta_description' => 'Concrete driveway installation in Toronto. 32 MPa air-entrained mix, rebar reinforcement, and decorative finishes with consultation-led planning.',
                'og_title' => 'Concrete Driveways in Toronto | Lush Landscape',
                'og_description' => 'High-strength concrete driveways for Toronto homes. Stamped, exposed aggregate, and broom-finish options.',
                'sort_order' => 6,
            ],

            // ─── 7. Concrete Patios & Walkways ─────────────────────────────
            'Concrete Patios & Walkways' => [
                'page_title' => 'Concrete Patios & Walkways in Toronto',
                'h1' => 'Concrete Patios & Walkways in Toronto',
                'local_intro' => "Architectural concrete transforms ordinary outdoor surfaces into design statements. In Toronto, where homeowners want the durability of concrete with visual interest that goes beyond a plain grey slab, our stamped, stained, and exposed aggregate finishes deliver exactly that. We pour patios, walkways, pool decks, and stepping-stone paths using the same 32 MPa air-entrained specifications as our driveways, engineered for the 48-inch frost depth and aggressive salt exposure that Toronto surfaces endure.\n\nStamped concrete patterns include ashlar slate, European fan, random stone, cobblestone, and wood-plank textures applied with colour hardener and release agents for a natural, multi-toned finish. Exposed aggregate finishes reveal the natural stone within the concrete mix, creating a textured, slip-resistant surface ideal for pool surrounds and garden walkways. Acid-stain finishes produce translucent colour variations that mimic natural stone at a fraction of the cost. For Toronto's compact urban backyards, decorative concrete maximizes usable outdoor space without the joint lines and edge restraints that interlocking requires.\n\nOur Toronto concrete patio projects include proper sub-base preparation tailored to your lot's specific soil profile, positive drainage grading away from your foundation, and expansion joints where the patio meets the house. Properties within TRCA regulated areas or subject to ravine protection bylaws receive full permitting coordination. We back all concrete patio and walkway work with our workmanship warranty.",
                'meta_title' => 'Concrete Patios & Walkways Toronto | Lush',
                'meta_description' => 'Stamped and exposed aggregate concrete patios and walkways in Toronto. Decorative finishes, proper drainage, and lasting durability.',
                'og_title' => 'Concrete Patios & Walkways in Toronto | Lush Landscape',
                'og_description' => 'Architectural concrete patios and walkways for Toronto homes. Stamped, stained, and exposed aggregate options.',
                'sort_order' => 7,
            ],

            // ─── 8. Interlock Restoration & Sealing ─────────────────────────
            'Interlock Restoration & Sealing' => [
                'page_title' => 'Interlock Restoration & Sealing in Toronto',
                'h1' => 'Interlock Restoration & Sealing in Toronto',
                'local_intro' => "Years of Toronto weather, road salt tracking, and organic growth take a visible toll on interlocking surfaces. Faded colours, displaced polymeric sand, weed invasion, and efflorescence buildup make even quality installations look tired. Across Toronto, from high-traffic driveways in North York to shaded patios in The Kingsway where moss and algae thrive under mature tree canopies, these conditions accelerate with each passing season. Our 3-day interlock restoration process brings your existing pavers back to their original appearance and protects them for years to come.\n\nDay one involves thorough hot-water pressure washing at 3,000+ PSI with surface-cleaning attachments that remove embedded dirt, moss, algae, and efflorescence without damaging paver surfaces. Day two allows the surface to dry completely before we re-apply polymeric sand to all joints, ensuring proper activation and curing. Day three applies UV-resistant sealer in the homeowner's choice of matte, satin, or wet-look finish. The sealer locks in colour, prevents future staining, and makes ongoing maintenance as simple as a garden hose rinse.\n\nWe restore interlocking driveways, patios, walkways, and pool decks across Toronto, from Etobicoke to Scarborough. If your pavers are structurally sound but visually faded, restoration and sealing is the most cost-effective way to renew your Toronto hardscaping without the expense and disruption of full replacement.",
                'meta_title' => 'Interlock Restoration & Sealing Toronto | Lush',
                'meta_description' => 'Professional interlock restoration and sealing in Toronto. 3-day process: steam wash, polymeric sand, UV sealer. Renew your pavers.',
                'og_title' => 'Interlock Restoration & Sealing in Toronto',
                'og_description' => 'Restore faded interlocking surfaces in Toronto. Hot-water wash, polymeric sand, and protective sealer application.',
                'sort_order' => 8,
            ],

            // ─── 9. Interlock Repair (Lift & Relay) ─────────────────────────
            'Interlock Repair (Lift & Relay)' => [
                'page_title' => 'Interlock Repair (Lift & Relay) in Toronto',
                'h1' => 'Interlock Repair in Toronto: Lift & Relay',
                'local_intro' => "Sunken, heaving, or uneven interlocking pavers are more than an eyesore. They create tripping hazards and allow water to pool against your foundation. In Toronto, where soil conditions vary dramatically from one neighbourhood to the next, the causes of paver displacement are equally varied. Clay expansion in North York, root intrusion from protected trees in Lawrence Park, sandy substrate settling along the Scarborough Bluffs, and frost heave on properties across the city all produce different failure modes that require targeted correction rather than a one-size-fits-all approach.\n\nOur Toronto repair process starts with carefully removing the affected pavers and setting them aside. We then excavate the failed base material, identify the specific cause of failure, whether that is insufficient base depth, poor original compaction, root intrusion from a tree protected under Toronto's tree preservation bylaw, or subsurface drainage issues in a combined sewer area, and correct it. Fresh HPB aggregate is installed, compacted in lifts to 95 percent Standard Proctor density, and screeded to the correct elevation. The original pavers are re-laid in their original pattern, new polymeric sand is applied to all joints, and the repaired area is compacted to match the surrounding surface.\n\nUnlike full replacement, lift-and-relay preserves your existing pavers and their naturally weathered colour, so the repair blends seamlessly with undisturbed areas. We perform lift-and-relay repairs on driveways, patios, walkways, and pool decks across Toronto, from Leaside to Etobicoke.",
                'meta_title' => 'Interlock Repair Toronto - Lift & Relay | Lush',
                'meta_description' => 'Permanent interlock repair in Toronto. Lift and relay sunken pavers with HPB base correction. Fix the cause, not just the symptom.',
                'og_title' => 'Interlock Repair (Lift & Relay) in Toronto',
                'og_description' => 'Fix sunken and heaving pavers in Toronto permanently. HPB base correction and precision re-levelling.',
                'sort_order' => 9,
            ],

            // ─── 10. Retaining Walls ────────────────────────────────────────
            'Retaining Walls' => [
                'page_title' => 'Retaining Walls in Toronto',
                'h1' => 'Retaining Wall Construction in Toronto',
                'local_intro' => "Toronto's ravine system, one of the largest urban ravine networks in the world, creates residential properties with significant grade changes that require engineered retaining solutions. Whether you need a terraced backyard along a ravine edge in The Kingsway, a front-yard retaining wall to manage a sloped entry in Leaside, or erosion control on a property boundary in Scarborough, our retaining wall division delivers structural solutions that perform under Toronto's demanding conditions. Properties within the city's ravine protection bylaw boundary or TRCA regulated areas require permits before construction, and we coordinate that process from application through approval.\n\nWe build with armour stone sourced from Ontario quarries in weights from 1,000 to 4,000 pounds, precast concrete blocks from Allan Block, Cornerstone, and Unilock, and poured-in-place reinforced concrete where structural loads require it. Every retaining wall over 1.0 metre in retained height is designed to Ontario Building Code Part 4 standards with geogrid reinforcement at calculated intervals, clear stone backfill, filter fabric, and weeping tile drainage connected to storm or daylight outlets. Toronto's variable soils, from sandy deposits in Scarborough to heavy clay in North York, make proper drainage behind the wall as critical as the wall structure itself.\n\nOur Toronto retaining wall projects include full engineering coordination where required, TRCA permitting for properties within regulated areas, and compliance with lot coverage limits under Zoning Bylaw 569-2013. All structural components carry our 10-year workmanship warranty.",
                'meta_title' => 'Retaining Walls Toronto | Lush Landscape',
                'meta_description' => 'Retaining wall construction in Toronto. Armour stone, concrete block, geogrid reinforced. OBC-compliant, TRCA-permitted with consultation-led planning.',
                'og_title' => 'Retaining Walls in Toronto | Lush Landscape',
                'og_description' => 'Engineered retaining walls for Toronto properties. Armour stone and precast block with proper drainage and permit coordination.',
                'sort_order' => 10,
            ],

            // ─── 11. Sod Installation & Grading ─────────────────────────────
            'Sod Installation & Grading' => [
                'page_title' => 'Sod Installation & Grading in Toronto',
                'h1' => 'Sod Installation & Grading in Toronto',
                'local_intro' => "A healthy lawn starts with what is underneath it. In Toronto, where soil conditions range from the sandy glacial deposits along the Scarborough Bluffs to compacted clay across much of North York and Etobicoke, proper grading and soil amendment are the difference between a lawn that thrives and one that struggles. Builder-grade lots on Toronto infill properties often arrive with subsoil compacted by heavy equipment and minimal topsoil, while mature lots in established neighbourhoods may have decades of compaction and root competition. Our sod installation process addresses the soil profile first and the grass surface second.\n\nWe begin by establishing positive drainage grade away from your foundation at a minimum 2 percent slope for the first 6 feet, then transitioning to a gentler grade across the remainder of the yard. On Toronto properties subject to lot grading requirements or within combined sewer catchment areas, we design drainage to prevent stormwater from flowing toward neighbouring lots or the municipal system. Four to 6 inches of Triple-Mix, a blend of screened topsoil, peat, and compost, is spread and raked to final grade. Premium Kentucky Bluegrass sod is laid the same day it is cut from the farm, rolled for root contact, and given starter fertilizer and initial watering instructions.\n\nFrom post-construction grading on new infill builds in Leaside to full lawn renovation on mature Forest Hill properties shaded by trees protected under Toronto's tree preservation bylaw, our Toronto sod crews handle projects of every scale. We guarantee root establishment when our watering schedule is followed.",
                'meta_title' => 'Sod Installation & Grading Toronto | Lush',
                'meta_description' => 'Professional sod installation and grading in Toronto. Kentucky Bluegrass, Triple-Mix soil, precision drainage. Same-day installation.',
                'og_title' => 'Sod Installation & Grading in Toronto | Lush',
                'og_description' => 'Premium sod installation with proper grading and soil amendment for Toronto lawns. Instant results, lasting health.',
                'sort_order' => 11,
            ],

            // ─── 12. Artificial Turf ────────────────────────────────────────
            'Artificial Turf' => [
                'page_title' => 'Artificial Turf Installation in Toronto',
                'h1' => 'Artificial Turf in Toronto',
                'local_intro' => "For Toronto homeowners who want a green, maintenance-free lawn year-round, artificial turf delivers. No mowing, no watering, no fertilizing, and no brown patches through July drought or March snowmelt. On the compact urban lots common across midtown and downtown Toronto, where shade from neighbouring structures and mature trees makes natural grass difficult to establish, synthetic turf provides a consistently green surface regardless of sun exposure. Modern synthetic turf products feature multi-toned blade profiles, thatch layers for natural appearance, and UV stabilization that maintains colour integrity for 15 or more years.\n\nOur Toronto artificial turf installations use a properly engineered base that addresses the variable drainage conditions found across the city's diverse soil profiles. Sandy subgrades in Scarborough require different base strategies than the heavy clay in North York, and we engineer each installation accordingly. We excavate existing soil, install compacted aggregate base with positive drainage grade, lay geotextile separation fabric, and secure the turf with landscape spikes and infill material. Pet-friendly installations use antimicrobial infill and achieve drainage rates exceeding 30 inches per hour, ensuring rapid moisture clearance even in Toronto's combined sewer areas where surface drainage management is critical.\n\nWhether you need a backyard play area for children in Leaside, a pet run in Etobicoke that stays clean and dry, a putting green for golf practice in North York, or a front-yard accent in Lawrence Park that looks immaculate without weekend maintenance, our Toronto artificial turf division delivers turnkey installations.",
                'meta_title' => 'Artificial Turf Toronto | Lush Landscape',
                'meta_description' => 'Artificial turf installation in Toronto. Pet-friendly, UV-stable, 30+ in/hr drainage. No mowing, no watering, always green.',
                'og_title' => 'Artificial Turf in Toronto | Lush Landscape',
                'og_description' => 'Maintenance-free artificial turf for Toronto lawns, pet areas, and play zones. Looks natural year-round.',
                'sort_order' => 12,
            ],

            // ─── 13. Garden Design & Planting ───────────────────────────────
            'Garden Design & Planting' => [
                'page_title' => 'Garden Design & Planting in Toronto',
                'h1' => 'Garden Design & Planting in Toronto',
                'local_intro' => "Toronto's Zone 6a climate, moderated by Lake Ontario's proximity, gives homeowners access to a wide palette of hardy perennials, ornamental grasses, flowering shrubs, and specimen trees. The city's tree preservation bylaw, which protects any tree with a trunk diameter of 30 centimetres or greater, means that garden design in Toronto must work around existing mature canopy rather than clearing it. Our garden design service translates that constraint into an advantage, creating layered planting plans that thrive in the dappled light beneath established trees while providing four-season visual interest and supporting local pollinators.\n\nWe start every Toronto garden design project with an on-site assessment of sun exposure, soil type, drainage patterns, and existing vegetation. The soil analysis is essential in Toronto, where sandy loam in Scarborough supports different plant communities than the heavy clay in North York, and ravine-edge properties in Rosedale and The Kingsway present unique microclimates shaped by elevation, wind exposure, and moisture levels. Planting plans specify cultivars proven in Zone 6a conditions, sourced from Ontario-accredited nurseries. Garden bed construction includes excavation of compacted subsoil, amendment with premium planting mix, and installation of steel or aluminum edging for clean, permanent bed lines.\n\nFrom pollinator gardens in Leaside to privacy screening hedges along Forest Hill property boundaries, our Toronto garden design team creates outdoor spaces that look better with every passing season. All plant material is backed by a one-year health guarantee when our care instructions are followed.",
                'meta_title' => 'Garden Design & Planting Toronto | Lush',
                'meta_description' => 'Professional garden design and planting in Toronto. Ontario-native perennials, pollinator gardens, and four-season interest. One-year guarantee.',
                'og_title' => 'Garden Design & Planting in Toronto | Lush',
                'og_description' => 'Custom garden design with Ontario-native plants for Toronto properties. Beautiful, sustainable, and pollinator-friendly.',
                'sort_order' => 13,
            ],

            // ─── 14. Landscape Lighting ─────────────────────────────────────
            'Landscape Lighting' => [
                'page_title' => 'Landscape Lighting in Toronto',
                'h1' => 'Landscape Lighting in Toronto',
                'local_intro' => "Professional landscape lighting transforms your Toronto property after dark, extending usable outdoor hours, enhancing security, and highlighting the architectural and landscape features that define your home's character. In a city where mature tree canopies in neighbourhoods like Forest Hill, Lawrence Park, and Rosedale create dramatic uplighting opportunities, and where narrow Victorian-lot sightlines in the Annex and Cabbagetown reward precise accent placement, thoughtful lighting design delivers impact that is visible from the street and enjoyed from the patio.\n\nWe use low-voltage LED systems powered by commercial-grade transformers with built-in timers and WiFi smart controls compatible with phone apps and home automation platforms. All fixtures are solid brass or marine-grade aluminum, selected for their ability to withstand Toronto's freeze-thaw cycles and road salt exposure without corroding or discolouring. Direct-burial cable rated for outdoor use is trenched to below-grade depth, with routing planned to avoid conflict with roots of trees protected under Toronto's tree preservation bylaw. On ravine-edge properties within TRCA regulated areas, fixture placement respects light-spill restrictions that protect natural habitats.\n\nFrom driveway pillar lighting in Bridle Path to backyard patio and garden bed illumination in Leaside, our Toronto lighting projects are designed by certified professionals who understand colour temperature, beam angles, and lumen requirements for each application. Every system is sized for future expansion so adding fixtures later does not require transformer upgrades.",
                'meta_title' => 'Landscape Lighting Toronto | Lush Landscape',
                'meta_description' => 'Professional LED landscape lighting in Toronto. Brass fixtures, WiFi smart controls, and custom design. Extend your outdoor living hours.',
                'og_title' => 'Landscape Lighting in Toronto | Lush Landscape',
                'og_description' => 'Custom landscape lighting design and installation for Toronto homes. Low-voltage LED, brass fixtures, smart controls.',
                'sort_order' => 14,
            ],

        ];
    }
}
