<?php

namespace Database\Seeders\Content;

use App\Models\City;
use App\Models\Service;
use App\Models\ServiceCityPage;
use Illuminate\Database\Seeder;

class RichmondHillContentSeeder extends Seeder
{
    public function run(): void
    {
        $city = City::where('name', 'Richmond Hill')->firstOrFail();
        $pages = $this->getPages();

        $neighborhoods = [
            'Oak Ridges', 'Jefferson', 'Bayview Hill', 'Westbrook', 'Mill Pond', 'Langstaff',
            'Elgin Mills', 'Doncrest', 'Harding Park', 'Rouge Woods',
            'Observatory', 'Richvale',
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
                        'text' => 'Book a Consultation in Richmond Hill',
                        'url' => '/request-quote?service='.urlencode($service->name).'&city=Richmond+Hill',
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
                'page_title' => 'Interlocking Driveways in Richmond Hill',
                'h1' => 'Interlocking Driveways in Richmond Hill',
                'local_intro' => "Richmond Hill driveways must perform under conditions shaped by the Oak Ridges Moraine to the north and heavy Halton Till clay that dominates the southern half of the city. Properties in South Richvale and Bayview Hill sit on dense clay that retains moisture and is prone to frost heave, while lots in Oak Ridges encounter sandy moraine soils that drain quickly but shift without adequate compaction. Our Richmond Hill interlocking driveway installations begin with a site-specific soil assessment to determine excavation depth, base thickness, and drainage strategy matched to your lot's position within this geological transition zone.\n\nWe excavate to a minimum 16-inch depth, install compacted Granular A sub-base, a 1-inch HPB levelling course, and premium pavers from Unilock, Techo-Bloc, and Belgard rated at 8,000+ PSI compressive strength. Every Richmond Hill driveway uses herringbone or 45-degree bond patterns to maximize interlock strength under vehicle loads, with polymeric sand jointing and soldier-course edge restraint anchored below the 48-inch frost line that defines Climate Zone 5b. Properties within the Oak Ridges Moraine Conservation Plan area receive impervious coverage calculations to comply with zero-increase stormwater requirements.\n\nWhether you are upgrading a grand executive driveway in Bayview Hill or replacing a builder-grade surface in Jefferson, our ICPI-certified crews deliver the same exacting standard. We back every interlocking driveway in Richmond Hill with our 10-year workmanship warranty and coordinate with the Town on any required permits, including TRCA approvals for properties near Don River or Rouge River headwater corridors.",
                'meta_title' => 'Interlocking Driveways Richmond Hill | Lush',
                'meta_description' => 'Custom interlocking driveway installation in Richmond Hill. ICPI-certified crews, 8,000+ PSI pavers, moraine-compliant designs with consultation-led planning.',
                'og_title' => 'Interlocking Driveways in Richmond Hill | Lush Landscape',
                'og_description' => 'Premium interlocking driveway construction for Richmond Hill homeowners. Engineered for moraine and clay soils.',
                'sort_order' => 1,
            ],

            // ─── 2. Interlocking Patios & Backyard Living ───────────────────
            'Interlocking Patios & Backyard Living' => [
                'page_title' => 'Interlocking Patios & Outdoor Living in Richmond Hill',
                'h1' => 'Interlocking Patios & Backyard Living in Richmond Hill',
                'local_intro' => "Richmond Hill homeowners invest heavily in outdoor living spaces, and the generous lot sizes found in Bayview Hill, South Richvale, and Observatory provide the canvas for exceptional backyard transformations. We design and build interlocking patios that serve as the foundation for complete outdoor environments including BBQ islands, fire pits, seating walls, and integrated lighting systems tailored to each property's layout and lifestyle. The executive homes that define Richmond Hill's premium neighbourhoods demand outdoor spaces built to an equally high standard.\n\nOur Richmond Hill patio installations address the specific drainage challenges that the city's split soil profile presents. Southern neighbourhoods like Crosby and Westbrook sit on heavy Halton Till clay that holds moisture and demands open-graded base systems with catch basins to prevent ponding. Northern properties toward Oak Ridges encounter sandy moraine soils that drain rapidly but require careful base stabilization. Every patio project includes a compacted Granular A sub-base, HPB levelling course, and premium pavers installed in patterns selected for both aesthetics and structural performance against 135 cm of average annual snowfall.\n\nWhether you envision a simple entertaining terrace or a multi-level outdoor room with kitchen, fireplace, and pergola, our design team works with you from concept through completion. We coordinate with gas, electrical, and plumbing trades as needed, delivering a single-source project experience in Richmond Hill backed by our 10-year workmanship warranty.",
                'meta_title' => 'Interlocking Patios Richmond Hill | Lush',
                'meta_description' => 'Custom interlocking patio installation in Richmond Hill. Outdoor kitchens, fire pits, seating walls. Engineered for local soils with consultation-led planning.',
                'og_title' => 'Interlocking Patios & Backyard Living in Richmond Hill',
                'og_description' => 'Transform your Richmond Hill backyard with custom interlocking patios, outdoor kitchens, and fire features.',
                'sort_order' => 2,
            ],

            // ─── 3. Walkways & Steps ────────────────────────────────────────
            'Walkways & Steps' => [
                'page_title' => 'Interlocking Walkways & Steps in Richmond Hill',
                'h1' => 'Walkways & Steps in Richmond Hill',
                'local_intro' => "A well-built walkway is both a safety feature and a design statement, and in Richmond Hill's established neighbourhoods and executive estates, both aspects carry equal weight. Winter ice, spring thaw, and the seasonal ground movement caused by the heavy clay soils south of the moraine create conditions that demand precision engineering in every walkway and step installation. Our projects follow Ontario Building Code requirements for riser height, tread depth, and handrail placement to ensure code compliance and pedestrian safety across Richmond Hill's 48-inch frost depth.\n\nWe design walkways that complement existing hardscaping and architectural character. In the heritage Mill Pond area, this often means selecting natural stone or heritage-compatible paver profiles that respect the neighbourhood's historic character. For contemporary homes in Jefferson and Westbrook, modern linear pavers and clean geometric layouts create a fitting aesthetic. Steps use reinforced concrete cores with paver or stone cladding for structural permanence, and landing pads are installed at required intervals on sloped entries throughout Richmond Hill.\n\nFrom grand front-entry paths on Bayview Hill estates to garden walkways winding through mature landscapes near the David Dunlap Observatory lands, our walkway projects include a compacted aggregate base, edge restraint, polymeric sand, and integrated lighting options. Every Richmond Hill installation is built to withstand Climate Zone 5b conditions and the seasonal ground movement that defines this region.",
                'meta_title' => 'Walkways & Steps Richmond Hill | Lush Landscape',
                'meta_description' => 'Interlocking walkways and steps in Richmond Hill. OBC-compliant risers, heritage-compatible materials, premium pavers with consultation-led planning.',
                'og_title' => 'Walkways & Steps in Richmond Hill | Lush Landscape',
                'og_description' => 'Professional walkway and step installation for Richmond Hill homes. Safety, durability, and lasting curb appeal.',
                'sort_order' => 3,
            ],

            // ─── 4. Natural Stone & Flagstone ───────────────────────────────
            'Natural Stone & Flagstone' => [
                'page_title' => 'Natural Stone & Flagstone in Richmond Hill',
                'h1' => 'Natural Stone & Flagstone Installation in Richmond Hill',
                'local_intro' => "Natural stone carries a timeless character that resonates with Richmond Hill's established neighbourhoods and heritage properties around the Mill Pond area. Our natural stone division works with Ontario-quarried materials including Eramosa limestone, Algonquin flagstone, Muskoka granite, and Owen Sound ledgerock to create patios, walkways, accent walls, and water features that complement the refined aesthetic Richmond Hill homeowners expect. For executive properties in South Richvale and Bayview Hill, natural stone delivers the prestige and permanence that these premium streetscapes demand.\n\nFlagstone installations in Richmond Hill require careful base preparation adapted to the city's dual soil profile. Southern properties on heavy Halton Till clay require enhanced drainage provisions beneath the stone surface, including a minimum 12-inch compacted aggregate base and perimeter drainage to prevent frost heave. Northern lots in Oak Ridges sitting on sandy moraine soils drain well but need stabilized bases to prevent settling. We use mortar-set or dry-laid techniques depending on the application and fill joints with polymeric sand or stone dust to prevent weed intrusion and maintain a clean appearance year after year.\n\nFrom hand-cut flagstone patios on Observatory estate lots to armour stone retaining features along the Don River and Rouge River headwater corridors regulated by the TRCA, our Richmond Hill crews bring the craftsmanship that natural stone demands. Every piece is selected for colour consistency, thickness uniformity, and structural integrity before placement on your property.",
                'meta_title' => 'Natural Stone & Flagstone Richmond Hill | Lush',
                'meta_description' => 'Natural stone and flagstone installation in Richmond Hill. Ontario-quarried materials, expert craftsmanship, moraine-compliant with consultation-led planning.',
                'og_title' => 'Natural Stone & Flagstone in Richmond Hill | Lush Landscape',
                'og_description' => 'Premium natural stone patios, flagstone walkways, and armour stone features for Richmond Hill properties.',
                'sort_order' => 4,
            ],

            // ─── 5. Porcelain Pavers ────────────────────────────────────────
            'Porcelain Pavers' => [
                'page_title' => 'Porcelain Paver Installation in Richmond Hill',
                'h1' => 'Porcelain Pavers in Richmond Hill',
                'local_intro' => "Porcelain pavers have become a leading choice among Richmond Hill homeowners who want a modern, low-maintenance surface without sacrificing visual sophistication. These 20mm-thick engineered tiles deliver the look of natural stone, wood, or polished concrete with zero water absorption, R11 slip rating, UV stability, and virtually no maintenance. For Richmond Hill's discerning homeowners in neighbourhoods like South Richvale and Bayview Hill who expect premium aesthetics alongside practical performance, porcelain pavers offer an ideal balance.\n\nOur Richmond Hill porcelain paver installations use pedestal or compacted-aggregate base systems depending on the application and site conditions. Ground-level patios use the same compacted aggregate base as traditional interlocking, with levelling screed and open-graded joint material engineered to accommodate Climate Zone 5b freeze-thaw cycles and 135 cm of average annual snowfall. Properties in the Oak Ridges Moraine zone benefit from the zero water absorption of porcelain, which helps meet impervious coverage requirements under the Oak Ridges Moraine Conservation Plan when combined with permeable base systems. We source 20mm porcelain pavers from leading Italian and Spanish manufacturers through Canadian distributors who guarantee freeze-thaw performance.\n\nFrom sleek contemporary terraces on executive properties in Observatory to warm wood-look pool surrounds in Crosby, porcelain pavers give Richmond Hill homeowners design flexibility that no other material matches. The result is a surface that maintains its appearance season after season with nothing more than an occasional rinse.",
                'meta_title' => 'Porcelain Pavers Richmond Hill | Lush Landscape',
                'meta_description' => 'Modern 20mm porcelain paver installation in Richmond Hill. Zero absorption, R11 slip rating, frost-proof. Patios, pool decks, terraces.',
                'og_title' => 'Porcelain Pavers in Richmond Hill | Lush Landscape',
                'og_description' => 'Contemporary porcelain paver installation for Richmond Hill patios, terraces, and pool surrounds.',
                'sort_order' => 5,
            ],

            // ─── 6. Concrete Driveways ──────────────────────────────────────
            'Concrete Driveways' => [
                'page_title' => 'Concrete Driveways in Richmond Hill',
                'h1' => 'Concrete Driveways in Richmond Hill',
                'local_intro' => "A concrete driveway delivers a clean, durable surface that suits Richmond Hill properties ranging from modern builds in Jefferson to established executive homes in Bayview Hill. Our concrete driveway installations use 32 MPa air-entrained mix with 5 to 7 percent air content, the Ontario specification for freeze-thaw durability across the 48-inch frost depth that defines Richmond Hill's Climate Zone 5b conditions. Every pour includes 10M rebar on 400mm centres, fibre-mesh reinforcement, and control joints calculated for slab dimensions and expected load.\n\nRichmond Hill's variable soil conditions require site-specific sub-base preparation. Southern properties in Crosby, Westbrook, and South Richvale sit on heavy Halton Till clay that holds moisture and is prone to frost heave. Northern lots in Oak Ridges encounter sandy moraine soils that drain quickly but compact differently under load. We excavate to a minimum 12-inch depth in all cases, install compacted Granular A sub-base, and use a polyethylene vapour barrier to prevent moisture from migrating upward into the slab. Curing compound is applied immediately after finishing to ensure proper hydration and surface hardness.\n\nWe offer broom-finish, exposed aggregate, stamped patterns including ashlar slate, cobblestone, and wood-plank textures, and acid-stain decorative finishes. Our Richmond Hill concrete crews handle complex curves, multi-level transitions, and integrated drainage channels with the precision this community expects.",
                'meta_title' => 'Concrete Driveways Richmond Hill | Lush',
                'meta_description' => 'Concrete driveway installation in Richmond Hill. 32 MPa air-entrained mix, rebar reinforcement, decorative finishes with consultation-led planning.',
                'og_title' => 'Concrete Driveways in Richmond Hill | Lush Landscape',
                'og_description' => 'High-strength concrete driveways for Richmond Hill homes. Stamped, exposed aggregate, and broom-finish options.',
                'sort_order' => 6,
            ],

            // ─── 7. Concrete Patios & Walkways ─────────────────────────────
            'Concrete Patios & Walkways' => [
                'page_title' => 'Concrete Patios & Walkways in Richmond Hill',
                'h1' => 'Concrete Patios & Walkways in Richmond Hill',
                'local_intro' => "Architectural concrete transforms ordinary outdoor surfaces into design statements that match the calibre of Richmond Hill's finest properties. Where homeowners want the durability of concrete with visual interest that elevates beyond a plain grey slab, our stamped, stained, and exposed aggregate finishes deliver exactly that. We pour patios, walkways, pool decks, and stepping-stone paths using the same 32 MPa air-entrained specifications as our driveways, ensuring long-term performance through Richmond Hill's demanding freeze-thaw cycles and 135 cm of average annual snowfall.\n\nStamped concrete patterns include ashlar slate, European fan, random stone, cobblestone, and wood-plank textures applied with colour hardener and release agents for a natural, multi-toned finish. Exposed aggregate finishes reveal the natural stone within the concrete mix, creating a textured, slip-resistant surface ideal for pool surrounds and walkways. Acid-stain finishes produce translucent colour variations that mimic natural stone at a fraction of the cost, a popular choice among Richmond Hill homeowners looking for high-end results with practical maintenance.\n\nOur Richmond Hill concrete patio projects include proper sub-base preparation tailored to the local soil profile, positive drainage grading away from your foundation, and expansion joints where the patio meets the house. From Bayview Hill backyards to Observatory pool decks, we back all concrete patio and walkway work with our workmanship warranty.",
                'meta_title' => 'Concrete Patios & Walkways Richmond Hill | Lush',
                'meta_description' => 'Stamped and exposed aggregate concrete patios and walkways in Richmond Hill. Decorative finishes, proper drainage, lasting durability.',
                'og_title' => 'Concrete Patios & Walkways in Richmond Hill | Lush Landscape',
                'og_description' => 'Architectural concrete patios and walkways for Richmond Hill homes. Stamped, stained, and exposed aggregate options.',
                'sort_order' => 7,
            ],

            // ─── 8. Interlock Restoration & Sealing ─────────────────────────
            'Interlock Restoration & Sealing' => [
                'page_title' => 'Interlock Restoration & Sealing in Richmond Hill',
                'h1' => 'Interlock Restoration & Sealing in Richmond Hill',
                'local_intro' => "Years of Richmond Hill weather, road salt tracking, and organic growth from the city's mature tree canopy take a visible toll on interlocking surfaces. Faded colours, displaced polymeric sand, weed invasion, and efflorescence buildup make even high-quality installations look tired and neglected. With 135 cm of annual snowfall and heavy salt use through the winter months, Richmond Hill interlocking surfaces face accelerated wear that makes periodic restoration essential for maintaining both appearance and structural integrity.\n\nOur 3-day interlock restoration process brings your existing pavers back to their original appearance and protects them for years to come. Day one involves thorough hot-water pressure washing at 3,000+ PSI with surface-cleaning attachments that remove embedded dirt, moss, algae, and efflorescence without damaging paver surfaces. Day two allows the surface to dry completely before we re-apply polymeric sand to all joints, ensuring proper activation and curing. Day three applies UV-resistant sealer in the homeowner's choice of matte, satin, or wet-look finish. The sealer locks in colour, prevents future staining, and makes ongoing maintenance as simple as a garden hose rinse.\n\nWe restore interlocking driveways, patios, walkways, and pool decks across Richmond Hill from the heritage Mill Pond area to newer subdivisions in Jefferson and Westbrook. If your pavers are structurally sound but visually faded, restoration and sealing is the most cost-effective way to renew your Richmond Hill hardscaping without the expense of full replacement.",
                'meta_title' => 'Interlock Restoration & Sealing Richmond Hill',
                'meta_description' => 'Professional interlock restoration and sealing in Richmond Hill. 3-day process: steam wash, polymeric sand, UV sealer. Renew your pavers.',
                'og_title' => 'Interlock Restoration & Sealing in Richmond Hill',
                'og_description' => 'Restore faded interlocking surfaces in Richmond Hill. Hot-water wash, polymeric sand, and protective sealer.',
                'sort_order' => 8,
            ],

            // ─── 9. Interlock Repair (Lift & Relay) ─────────────────────────
            'Interlock Repair (Lift & Relay)' => [
                'page_title' => 'Interlock Repair (Lift & Relay) in Richmond Hill',
                'h1' => 'Interlock Repair in Richmond Hill: Lift & Relay',
                'local_intro' => "Sunken, heaving, or uneven interlocking pavers are more than an eyesore on Richmond Hill's well-maintained streetscapes. They create tripping hazards and allow water to pool against your foundation, risking long-term structural damage. In Richmond Hill, where heavy clay soil movement in southern neighbourhoods like Crosby and Westbrook and root intrusion from mature street trees are the leading causes of paver displacement, a proper lift-and-relay repair addresses the root cause rather than masking the symptom.\n\nOur repair process starts with carefully removing the affected pavers and setting them aside. We then excavate the failed base material, identify the cause of the failure, whether that is insufficient base depth, poor compaction, tree root intrusion, or subsurface drainage issues from the dense Halton Till clay that underlies much of southern Richmond Hill, and correct it. Fresh HPB aggregate is installed, compacted in lifts to 95 percent Standard Proctor density, and screeded to the correct elevation. The original pavers are then re-laid in their original pattern, new polymeric sand is applied to all joints, and the repaired area is compacted to match the surrounding surface.\n\nUnlike full replacement, lift-and-relay preserves your existing pavers and their naturally weathered colour, so the repair blends seamlessly with the undisturbed areas. We perform lift-and-relay repairs on driveways, patios, walkways, and pool decks across Richmond Hill, from South Richvale to Oak Ridges and every neighbourhood in between.",
                'meta_title' => 'Interlock Repair Richmond Hill - Lift & Relay',
                'meta_description' => 'Permanent interlock repair in Richmond Hill. Lift and relay sunken pavers with HPB base correction. Fix the cause, not just the symptom.',
                'og_title' => 'Interlock Repair (Lift & Relay) in Richmond Hill',
                'og_description' => 'Fix sunken and heaving pavers in Richmond Hill permanently. HPB base correction and precision re-levelling.',
                'sort_order' => 9,
            ],

            // ─── 10. Retaining Walls ────────────────────────────────────────
            'Retaining Walls' => [
                'page_title' => 'Retaining Walls in Richmond Hill',
                'h1' => 'Retaining Wall Construction in Richmond Hill',
                'local_intro' => "Richmond Hill's terrain features notable grade changes, particularly along the Don River and Rouge River headwater valleys that cut through the city and the rolling topography of the Oak Ridges Moraine in the north. Residential properties throughout Richmond Hill frequently require engineered retaining solutions, whether to terrace a sloped backyard for usable outdoor living space, manage a front-yard grade transition, or control erosion along watercourse setbacks regulated by the Toronto and Region Conservation Authority.\n\nWe build with armour stone (natural Ontario boulders weighing 1,000 to 4,000 pounds each), precast concrete blocks from Allan Block, Cornerstone, and Unilock, and poured-in-place concrete where structural requirements dictate. Every retaining wall over 1.0 metre in retained height is designed to Ontario Building Code Part 4 standards, with geogrid reinforcement at calculated intervals, clear stone backfill, filter fabric, and weeping tile drainage connected to storm or daylight outlets. Richmond Hill's 48-inch frost depth is factored into every footing design to prevent seasonal heaving, and properties within the Oak Ridges Moraine Conservation Plan area receive stormwater management calculations to ensure zero increase in impervious coverage.\n\nOur Richmond Hill retaining wall projects include full engineering coordination where required, TRCA permitting for properties within regulated areas along the headwater corridors, and coordination with the Town of Richmond Hill on site plan requirements. From executive lots in Bayview Hill to sloped properties in Mill Pond, every structural component is backed by our 10-year workmanship warranty.",
                'meta_title' => 'Retaining Walls Richmond Hill | Lush Landscape',
                'meta_description' => 'Retaining wall construction in Richmond Hill. Armour stone, concrete block, geogrid reinforced. OBC-compliant, TRCA permitted.',
                'og_title' => 'Retaining Walls in Richmond Hill | Lush Landscape',
                'og_description' => 'Engineered retaining walls for Richmond Hill properties. Armour stone and precast block with proper drainage.',
                'sort_order' => 10,
            ],

            // ─── 11. Sod Installation & Grading ─────────────────────────────
            'Sod Installation & Grading' => [
                'page_title' => 'Sod Installation & Grading in Richmond Hill',
                'h1' => 'Sod Installation & Grading in Richmond Hill',
                'local_intro' => "A healthy lawn starts with what is underneath it, and in Richmond Hill the soil profile varies dramatically from south to north. Properties in South Richvale, Crosby, and Westbrook sit on heavy Halton Till clay that compacts easily, resists root penetration, and drains poorly without proper amendment. Northern lots in Oak Ridges encounter sandy moraine soils that drain quickly but lack the organic nutrients grass needs to thrive. Our sod installation process addresses the soil profile first and the grass surface second, adapting our approach to each Richmond Hill property's specific conditions.\n\nWe begin by establishing positive drainage grade away from your foundation at a minimum 2 percent slope for the first 6 feet, then transitioning to a gentler grade across the remainder of the yard. Topsoil is stripped from areas requiring grade adjustment, subgrade is shaped and compacted, and 4 to 6 inches of Triple-Mix (screened topsoil, peat, and compost blend) is spread and raked to final grade. Premium Kentucky Bluegrass sod is laid the same day it is cut from the farm, rolled for root contact, and given starter fertilizer and initial watering instructions. Properties adjacent to TRCA-regulated Don River or Rouge River headwater corridors receive erosion control measures to protect nearby watercourses during installation.\n\nFrom post-construction grading on new builds in Jefferson to full lawn renovation on mature estate lots in Bayview Hill, our Richmond Hill sod crews handle projects of every scale. We guarantee root establishment when our watering schedule is followed.",
                'meta_title' => 'Sod Installation & Grading Richmond Hill | Lush',
                'meta_description' => 'Professional sod installation and grading in Richmond Hill. Kentucky Bluegrass, Triple-Mix soil, precision drainage. Same-day installation.',
                'og_title' => 'Sod Installation & Grading in Richmond Hill | Lush',
                'og_description' => 'Premium sod installation with proper grading and soil amendment for Richmond Hill lawns. Instant results, lasting health.',
                'sort_order' => 11,
            ],

            // ─── 12. Artificial Turf ────────────────────────────────────────
            'Artificial Turf' => [
                'page_title' => 'Artificial Turf Installation in Richmond Hill',
                'h1' => 'Artificial Turf in Richmond Hill',
                'local_intro' => "For Richmond Hill homeowners who want a perfectly green, maintenance-free lawn year-round, artificial turf delivers. No mowing, no watering, no fertilizing, and no brown patches through July drought or March snowmelt. With 135 cm of average annual snowfall and the heavy clay soils that dominate southern Richmond Hill, natural lawns face compaction, drainage struggles, and seasonal dieback that synthetic turf eliminates entirely. Modern premium turf features multi-toned blade profiles, thatch layers for natural appearance, and UV stabilization that maintains colour integrity for 15 or more years.\n\nOur Richmond Hill artificial turf installations use a properly engineered base that addresses the local soil conditions. Southern properties on heavy Halton Till clay demand enhanced drainage provisions including deeper aggregate beds and perforated subdrain lines to handle the poor percolation rates these soils present. Northern lots in Oak Ridges on sandy moraine soils drain naturally and require standard aggregate base preparation. We excavate existing soil, install compacted aggregate base with positive drainage grade, lay geotextile separation fabric, and secure the turf with landscape spikes and infill material. Pet-friendly installations use antimicrobial infill and achieve drainage rates exceeding 30 inches per hour.\n\nWhether you need a backyard play area in Westbrook, a pet run in Crosby, a putting green in Observatory, or a front-yard accent in South Richvale that looks immaculate without weekend maintenance, our Richmond Hill artificial turf division delivers turnkey installations tailored to each property's requirements.",
                'meta_title' => 'Artificial Turf Richmond Hill | Lush Landscape',
                'meta_description' => 'Artificial turf installation in Richmond Hill. Pet-friendly, UV-stable, 30+ in/hr drainage. No mowing, no watering, always green.',
                'og_title' => 'Artificial Turf in Richmond Hill | Lush Landscape',
                'og_description' => 'Maintenance-free artificial turf for Richmond Hill lawns, pet areas, and play zones. Looks natural year-round.',
                'sort_order' => 12,
            ],

            // ─── 13. Garden Design & Planting ───────────────────────────────
            'Garden Design & Planting' => [
                'page_title' => 'Garden Design & Planting in Richmond Hill',
                'h1' => 'Garden Design & Planting in Richmond Hill',
                'local_intro' => "Richmond Hill sits in USDA Climate Zone 5b with conditions shaped by the Oak Ridges Moraine to the north, giving homeowners access to a hardy palette of perennials, ornamental grasses, flowering shrubs, and shade trees proven to thrive in this region's shorter growing season and colder winters compared to lakeshore communities. The city's tree protection bylaws and mature canopy in established neighbourhoods like Mill Pond and South Richvale add an important layer of consideration to every planting plan. Our garden design service translates Richmond Hill's botanical potential into curated designs that provide four-season visual interest and support local pollinators.\n\nWe start every garden design project with an on-site assessment of sun exposure, soil type, drainage patterns, and existing vegetation, paying particular attention to protected trees and their root zones. The heavy clay soils in southern Richmond Hill require amendment with organic matter and proper drainage provisions in garden beds, while sandy moraine soils in Oak Ridges need enrichment to retain moisture and nutrients. Planting plans specify cultivars proven in Zone 5b conditions, sourced from Ontario-accredited nurseries. Garden bed construction includes excavation of compacted subsoil, amendment with premium planting mix, and installation of steel or aluminum edging for clean, permanent bed lines.\n\nFrom pollinator gardens in Jefferson to privacy screening hedges in Observatory and estate foundation plantings along Bayview Avenue, our Richmond Hill garden design team creates outdoor spaces that grow more beautiful with every passing season. All plant material is backed by a one-year health guarantee when our care instructions are followed.",
                'meta_title' => 'Garden Design & Planting Richmond Hill | Lush',
                'meta_description' => 'Professional garden design and planting in Richmond Hill. Zone 5b native perennials, four-season interest, moraine-adapted plans with consultation-led planning.',
                'og_title' => 'Garden Design & Planting in Richmond Hill | Lush',
                'og_description' => 'Custom garden design with Ontario-native plants for Richmond Hill properties. Beautiful, sustainable, and climate-adapted.',
                'sort_order' => 13,
            ],

            // ─── 14. Landscape Lighting ─────────────────────────────────────
            'Landscape Lighting' => [
                'page_title' => 'Landscape Lighting in Richmond Hill',
                'h1' => 'Landscape Lighting in Richmond Hill',
                'local_intro' => "Professional landscape lighting transforms your Richmond Hill property after dark, extending usable outdoor hours, enhancing security, and highlighting the architectural and landscape features that define your home's character. In a community where executive homes in Bayview Hill and South Richvale, mature tree canopies near the David Dunlap Observatory lands, and premium estate landscaping set the visual standard, a thoughtfully designed lighting scheme adds a layer of evening elegance that few other investments can match. Our lighting designs go beyond simple path lights to create layered illumination schemes that include uplighting, downlighting, wash lighting, and accent techniques.\n\nWe use low-voltage LED systems powered by commercial-grade transformers with built-in timers and WiFi smart controls compatible with phone apps and home automation platforms. All fixtures are solid brass or marine-grade aluminum, selected for their ability to withstand Richmond Hill's Climate Zone 5b freeze-thaw cycles and salt exposure without corroding or discolouring. Direct-burial cable rated for outdoor use is trenched to below-grade depth to protect against damage. Properties within the Oak Ridges Moraine Conservation Plan area receive fixture layouts designed to minimize light pollution in environmentally sensitive zones.\n\nFrom driveway pillar lighting on Bayview Hill estates to backyard patio and garden illumination in Observatory, Mill Pond, and Westbrook, our Richmond Hill lighting projects are designed by certified professionals who understand colour temperature, beam angles, and lumen requirements for each application. Every system is sized for future expansion so adding fixtures later does not require transformer upgrades.",
                'meta_title' => 'Landscape Lighting Richmond Hill | Lush',
                'meta_description' => 'Professional LED landscape lighting in Richmond Hill. Brass fixtures, WiFi smart controls, and custom design. Extend your outdoor hours.',
                'og_title' => 'Landscape Lighting in Richmond Hill | Lush Landscape',
                'og_description' => 'Custom landscape lighting design and installation for Richmond Hill homes. Low-voltage LED, brass fixtures, smart controls.',
                'sort_order' => 14,
            ],

        ];
    }
}
