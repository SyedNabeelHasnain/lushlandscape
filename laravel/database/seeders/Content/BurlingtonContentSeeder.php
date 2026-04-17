<?php

namespace Database\Seeders\Content;

use App\Models\City;
use App\Models\Service;
use App\Models\ServiceCityPage;
use Illuminate\Database\Seeder;

class BurlingtonContentSeeder extends Seeder
{
    public function run(): void
    {
        $city = City::where('name', 'Burlington')->firstOrFail();
        $pages = $this->getPages();

        $neighborhoods = [
            'Aldershot', 'Tyandaga', 'Roseland', 'Shoreacres', 'Headon Forest', 'Millcroft',
            'Orchard', 'Palmer', 'Brant Hills', 'Elizabeth Gardens',
            'Burlington Downtown', 'Maple',
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
                        'text' => 'Book a Consultation in Burlington',
                        'url' => '/consultation?service='.urlencode($service->name).'&city=Burlington',
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
                'page_title' => 'Interlocking Driveways in Burlington',
                'h1' => 'Interlocking Driveways in Burlington',
                'local_intro' => "Burlington sits between the Niagara Escarpment and Lake Ontario, creating a landscape where lakeshore sandy loam transitions to dense silty clay on the escarpment bench within a few kilometres. This soil variability means your driveway base design in Aldershot may differ significantly from one in Headon Forest. Our Burlington interlocking driveway installations begin with a geotechnical assessment of your lot's specific soil composition, ensuring excavation depth, base thickness, and drainage strategy are calibrated to the conditions under your property rather than a generic specification.\n\nWe excavate to a minimum 16-inch depth on standard Burlington residential lots, install compacted Granular A sub-base in controlled lifts, add a 1-inch HPB levelling course, and lay premium pavers from Unilock, Techo-Bloc, and Belgard rated at 8,000+ PSI compressive strength. Herringbone or 45-degree patterns maximize interlock strength, polymeric sand fills every joint, and soldier-course edge restraint is anchored below the 48-inch frost line that Burlington's Zone 6a climate demands. Properties near Shoreacres and the lakeshore receive additional consideration for salt mist exposure, with sealer recommendations that protect paver colour against chloride staining.\n\nWhether you are replacing a cracked concrete pad on a mature Roseland lot or building a new driveway for a Millcroft custom home, our ICPI-certified crews deliver precision installation backed by a 10-year workmanship warranty. Burlington homeowners trust us because we build for the specific ground beneath their homes, not a one-size-fits-all approach.",
                'meta_title' => 'Interlocking Driveways Burlington | Lush Landscape',
                'meta_description' => 'Custom interlocking driveway installation in Burlington. ICPI-certified crews, 8,000+ PSI pavers, frost-line anchoring with consultation-led planning.',
                'og_title' => 'Interlocking Driveways in Burlington | Lush Landscape',
                'og_description' => 'Premium interlocking driveway construction for Burlington homeowners. Engineered for lakeshore and escarpment soils.',
                'sort_order' => 1,
            ],

            // ─── 2. Interlocking Patios & Backyard Living ───────────────────
            'Interlocking Patios & Backyard Living' => [
                'page_title' => 'Interlocking Patios & Outdoor Living in Burlington',
                'h1' => 'Interlocking Patios & Backyard Living in Burlington',
                'local_intro' => "Burlington backyards range from the generous, tree-lined lots of Tyandaga and Roseland to the compact but well-designed outdoor spaces found in newer Millcroft developments. Regardless of lot size, our patio designs transform your backyard into a functional outdoor living environment that accommodates cooking stations, fire features, seating walls, and integrated lighting. We treat every Burlington patio project as a custom design challenge, accounting for mature tree root zones, existing grade, and the specific sun and shade patterns your property receives throughout the day.\n\nDrainage engineering is central to every Burlington patio installation. The silty clay soils common on the escarpment bench drain slowly and can create ponding behind retaining features or against your foundation if grade is not carefully managed. We engineer positive slope away from your home at a minimum 2 percent for the first 6 feet, integrate catch basins where grade constraints require them, and use open-graded base systems on lots where clay impermeability creates standing water risk. Halton Region stormwater policies also inform our designs, ensuring your patio installation complies with local runoff management requirements.\n\nFrom a simple entertaining pad beside your garden in Brant Hills to a multi-level outdoor room with kitchen island, natural gas fireplace, and cedar pergola in Aldershot, our Burlington design team manages every element from concept through completion. We coordinate gas, electrical, and plumbing trades as needed and back every installation with our 10-year workmanship warranty.",
                'meta_title' => 'Interlocking Patios Burlington | Lush Landscape',
                'meta_description' => 'Custom interlocking patio installation in Burlington. Outdoor kitchens, fire features, seating walls. Halton-compliant drainage with consultation-led planning.',
                'og_title' => 'Interlocking Patios & Backyard Living in Burlington',
                'og_description' => 'Transform your Burlington backyard with custom interlocking patios, outdoor kitchens, and fire features.',
                'sort_order' => 2,
            ],

            // ─── 3. Walkways & Steps ────────────────────────────────────────
            'Walkways & Steps' => [
                'page_title' => 'Interlocking Walkways & Steps in Burlington',
                'h1' => 'Walkways & Steps in Burlington',
                'local_intro' => "Burlington properties frequently present grade changes that require thoughtful walkway and step design. Homes along the escarpment bench in Tyandaga and Headon Forest often sit above or below street level, creating approach paths that demand carefully calculated riser heights, landing intervals, and non-slip tread surfaces. Our walkway and step installations follow Ontario Building Code requirements for maximum riser height, minimum tread depth, and handrail placement, ensuring your entry path is safe through Burlington's icy winters.\n\nWe design walkways that integrate with your existing hardscaping palette, whether that means matching paver colours and patterns from your interlocking driveway or introducing natural stone accents that complement the mature tree canopy and garden beds that define so many Burlington streetscapes. For properties with significant elevation change, we build reinforced concrete step cores clad with pavers or natural stone, install landing pads at code-required intervals, and ensure positive drainage across and away from every walking surface.\n\nFrom a graceful curved front walkway on a Roseland heritage lot to a functional side-yard path connecting a Shoreacres driveway to a lakefront backyard, our Burlington walkway projects deliver safety, durability, and visual refinement. Every installation includes a compacted aggregate base, edge restraint, and polymeric sand to prevent weed growth and paver migration.",
                'meta_title' => 'Walkways & Steps Burlington | Lush Landscape',
                'meta_description' => 'Interlocking walkways and steps in Burlington. OBC-compliant risers, non-slip surfaces, and premium pavers with consultation-led planning.',
                'og_title' => 'Walkways & Steps in Burlington | Lush Landscape',
                'og_description' => 'Professional walkway and step installation for Burlington homes. Safe, durable, and beautifully designed.',
                'sort_order' => 3,
            ],

            // ─── 4. Natural Stone & Flagstone ───────────────────────────────
            'Natural Stone & Flagstone' => [
                'page_title' => 'Natural Stone & Flagstone in Burlington',
                'h1' => 'Natural Stone & Flagstone Installation in Burlington',
                'local_intro' => "Burlington's proximity to the Niagara Escarpment gives natural stone installations a deep sense of place that manufactured products cannot replicate. The same Silurian dolostone and limestone that forms the escarpment ridge running through the city is available as quarried material for patios, walkways, step treads, and accent walls. We work with Ontario-sourced Eramosa limestone, Algonquin flagstone, Muskoka granite, and Owen Sound ledgerock, selecting each piece for colour consistency, thickness uniformity, and structural integrity before it reaches your Burlington property.\n\nFlagstone patios and walkways in Burlington require base preparation calibrated to your lot's soil profile. Sandy loam near the lakeshore drains well but can shift under heavy stone, requiring a deeper compacted aggregate base. Silty clay on the escarpment bench demands careful drainage engineering to prevent water from pooling beneath the stone and causing frost heave. We install a minimum 12-inch compacted aggregate base under all flagstone work and use mortar-set or dry-laid techniques depending on application, climate exposure, and the homeowner's aesthetic preference. Joints receive polymeric sand or stone dust to prevent weed intrusion and insect habitation.\n\nFrom hand-cut flagstone terraces overlooking the lake in Shoreacres to armour stone retaining features framing a garden path in Brant Hills, our Burlington stone crews bring the precision and craftsmanship that natural materials demand. Natural stone ages gracefully, and installations we completed years ago continue to look better with time.",
                'meta_title' => 'Natural Stone & Flagstone Burlington | Lush Landscape',
                'meta_description' => 'Natural stone and flagstone installation in Burlington. Ontario-quarried materials, escarpment stone, and expert craftsmanship with consultation-led planning.',
                'og_title' => 'Natural Stone & Flagstone in Burlington | Lush Landscape',
                'og_description' => 'Premium flagstone patios, natural stone walkways, and armour stone features for Burlington properties.',
                'sort_order' => 4,
            ],

            // ─── 5. Porcelain Pavers ────────────────────────────────────────
            'Porcelain Pavers' => [
                'page_title' => 'Porcelain Paver Installation in Burlington',
                'h1' => 'Porcelain Pavers in Burlington',
                'local_intro' => "Porcelain pavers have become the material of choice for Burlington homeowners seeking a modern, low-maintenance surface that performs flawlessly through Ontario winters. These 20mm-thick engineered tiles offer zero water absorption, R11 slip rating, UV stability, and stain resistance that natural stone and concrete simply cannot match. For lakefront properties in Aldershot and Shoreacres, where salt mist accelerates surface deterioration on traditional materials, porcelain pavers maintain their colour and texture without sealing or special treatment.\n\nOur Burlington porcelain paver installations use pedestal or compacted-aggregate base systems depending on the application and site conditions. Rooftop terraces and pool surrounds typically receive adjustable pedestal systems that allow drainage beneath the surface and provide easy access to waterproof membranes. Ground-level patios use the same compacted Granular A base as traditional interlocking, with levelling screed and open-graded joint material. We source 20mm porcelain pavers from leading Italian and Spanish manufacturers through Canadian distributors who certify freeze-thaw performance for Zone 6a climates.\n\nFrom sleek concrete-look terraces on contemporary Millcroft builds to warm wood-tone pool surrounds at Roseland homes, porcelain pavers give Burlington homeowners design options that span industrial minimalism to rustic naturalism. Our installations include detailed cut work around curves, columns, and transitions that showcase the material's precision potential.",
                'meta_title' => 'Porcelain Pavers Burlington | Lush Landscape',
                'meta_description' => 'Modern 20mm porcelain paver installation in Burlington. Zero absorption, R11 slip rating, frost-proof. Patios, pool decks, terraces.',
                'og_title' => 'Porcelain Pavers in Burlington | Lush Landscape',
                'og_description' => 'Contemporary porcelain paver installation for Burlington patios, terraces, and pool surrounds. Maintenance-free elegance.',
                'sort_order' => 5,
            ],

            // ─── 6. Concrete Driveways ──────────────────────────────────────
            'Concrete Driveways' => [
                'page_title' => 'Concrete Driveways in Burlington',
                'h1' => 'Concrete Driveways in Burlington',
                'local_intro' => "A concrete driveway remains the most cost-effective way to achieve a clean, durable surface for Burlington properties. Our concrete driveway installations use 32 MPa air-entrained mix with 5 to 7 percent air content, the Ontario specification engineered for the freeze-thaw cycles that Burlington's Zone 6a climate delivers year after year. Every pour includes 10M rebar on 400mm centres, fibre-mesh reinforcement, and control joints calculated for slab dimensions and expected load.\n\nBurlington's variable soil conditions require sub-base preparation tailored to your specific lot. Properties near the lakeshore in Aldershot and Shoreacres sit on sandy loam that drains quickly but can settle under heavy loads without adequate compaction. Homes on the escarpment bench in Headon Forest and Tyandaga encounter silty clay that holds moisture and drives frost heave if base depth is insufficient. We excavate to a minimum 12-inch depth on all Burlington projects, install compacted Granular A sub-base, and use polyethylene vapour barrier to prevent moisture migration into the slab. Curing compound is applied immediately after finishing to ensure proper hydration and maximum surface hardness.\n\nWe offer broom-finish, exposed aggregate, stamped patterns including ashlar slate, cobblestone, and wood-plank textures, and acid-stain decorative finishes. Our Burlington concrete crews handle complex site access, steep driveway approaches common to escarpment properties, and integrated drainage channels that manage runoff in accordance with Halton Region stormwater requirements.",
                'meta_title' => 'Concrete Driveways Burlington | Lush Landscape',
                'meta_description' => 'Concrete driveway installation in Burlington. 32 MPa air-entrained mix, rebar reinforcement, decorative finishes with consultation-led planning.',
                'og_title' => 'Concrete Driveways in Burlington | Lush Landscape',
                'og_description' => 'High-strength concrete driveways for Burlington homes. Stamped, exposed aggregate, and broom-finish options.',
                'sort_order' => 6,
            ],

            // ─── 7. Concrete Patios & Walkways ─────────────────────────────
            'Concrete Patios & Walkways' => [
                'page_title' => 'Concrete Patios & Walkways in Burlington',
                'h1' => 'Concrete Patios & Walkways in Burlington',
                'local_intro' => "Architectural concrete allows Burlington homeowners to achieve the visual richness of natural stone or premium pavers at a more accessible price point. Our stamped, stained, and exposed aggregate concrete finishes transform ordinary slabs into design features that complement Burlington's range of architectural styles, from Aldershot's mid-century homes to Millcroft's contemporary builds. We pour patios, walkways, pool decks, and stepping-stone paths using the same 32 MPa air-entrained specifications as our driveways, ensuring structural performance matches visual appeal.\n\nStamped concrete patterns available for Burlington projects include ashlar slate, European fan, random stone, cobblestone, and wood-plank textures applied with colour hardener and release agents for a natural, multi-toned finish. Exposed aggregate finishes reveal the natural stone aggregate within the concrete mix, creating a textured, slip-resistant surface ideal for pool surrounds and garden paths. Acid-stain finishes produce translucent colour variations that complement the greens and earth tones of Burlington's mature tree canopy and garden landscapes.\n\nOur Burlington concrete patio projects include sub-base preparation calibrated to your lot's soil type, positive drainage grading away from your foundation, and expansion joints where the patio meets the house. Properties near the escarpment benefit from our experience managing steep transitions and multi-level concrete terracing that maximizes usable outdoor space on sloped lots.",
                'meta_title' => 'Concrete Patios & Walkways Burlington | Lush',
                'meta_description' => 'Stamped and exposed aggregate concrete patios in Burlington. Decorative finishes, proper drainage, and lasting durability with consultation-led planning.',
                'og_title' => 'Concrete Patios & Walkways in Burlington | Lush Landscape',
                'og_description' => 'Architectural concrete patios and walkways for Burlington homes. Stamped, stained, and exposed aggregate options.',
                'sort_order' => 7,
            ],

            // ─── 8. Interlock Restoration & Sealing ─────────────────────────
            'Interlock Restoration & Sealing' => [
                'page_title' => 'Interlock Restoration & Sealing in Burlington',
                'h1' => 'Interlock Restoration & Sealing in Burlington',
                'local_intro' => "Burlington's lakeside climate accelerates the weathering that takes a toll on interlocking surfaces. Salt mist from Lake Ontario, road salt tracked from winter-maintained streets, organic debris from the city's extensive mature tree canopy, and moss growth in shaded areas combine to fade colours, displace polymeric sand, and encourage weed invasion. Our 3-day interlock restoration process addresses every one of these factors, returning your existing pavers to their original vibrancy and protecting them for years to come.\n\nDay one involves thorough hot-water pressure washing at 3,000+ PSI with surface-cleaning attachments that remove embedded dirt, moss, algae, and efflorescence without damaging paver surfaces. This is particularly important on Burlington properties near the lakeshore, where mineral deposits from salt mist create a haze that standard cold-water washing cannot remove. Day two allows the surface to dry completely before we re-apply polymeric sand to all joints, ensuring proper activation and curing. Day three applies UV-resistant sealer in your choice of matte, satin, or wet-look finish, locking in colour, preventing future staining, and making ongoing maintenance as simple as a garden hose rinse.\n\nWe restore interlocking driveways, patios, walkways, and pool decks throughout Burlington, from lakefront properties in Shoreacres to established neighbourhoods in Brant Hills and Tyandaga. If your pavers are structurally sound but visually tired, restoration and sealing is the most cost-effective way to renew your hardscaping without full replacement.",
                'meta_title' => 'Interlock Restoration & Sealing Burlington | Lush',
                'meta_description' => 'Professional interlock restoration and sealing in Burlington. 3-day process: hot wash, polymeric sand, UV sealer. Renew your pavers.',
                'og_title' => 'Interlock Restoration & Sealing in Burlington',
                'og_description' => 'Restore faded interlocking surfaces in Burlington. Hot-water wash, polymeric sand, and protective sealer application.',
                'sort_order' => 8,
            ],

            // ─── 9. Interlock Repair (Lift & Relay) ─────────────────────────
            'Interlock Repair (Lift & Relay)' => [
                'page_title' => 'Interlock Repair (Lift & Relay) in Burlington',
                'h1' => 'Interlock Repair in Burlington: Lift & Relay',
                'local_intro' => "Sunken, heaving, or uneven interlocking pavers create tripping hazards and direct water toward your foundation. In Burlington, where soil conditions shift from sandy loam near the lakeshore to expansive silty clay on the escarpment bench, the cause of paver displacement varies by neighbourhood. Lakeshore properties in Aldershot and Shoreacres often experience settling from sand migration, while escarpment-area homes in Headon Forest and Tyandaga see heaving driven by clay expansion during freeze-thaw cycles. A proper lift-and-relay repair identifies and corrects the specific failure mechanism rather than treating only the visible symptom.\n\nOur repair process begins with carefully removing the affected pavers and setting them aside for re-use. We excavate the failed base material, diagnose the root cause, whether that is insufficient base depth, poor compaction, tree root intrusion from Burlington's mature canopy, or subsurface drainage failure, and correct it permanently. Fresh HPB aggregate is installed, compacted in lifts to 95 percent Standard Proctor density, and screeded to the correct elevation. Original pavers are re-laid in their original pattern, new polymeric sand is applied to all joints, and the repaired area is plate-compacted to integrate seamlessly with the surrounding undisturbed surface.\n\nLift-and-relay preserves your existing pavers and their naturally weathered patina, so repairs blend with the original installation rather than standing out as a visible patch. We perform lift-and-relay repairs on driveways, patios, walkways, and pool decks across Burlington, and every repair is backed by our workmanship warranty.",
                'meta_title' => 'Interlock Repair Burlington - Lift & Relay | Lush',
                'meta_description' => 'Permanent interlock repair in Burlington. Lift and relay sunken pavers with HPB base correction. Fix the cause, not just the symptom.',
                'og_title' => 'Interlock Repair (Lift & Relay) in Burlington',
                'og_description' => 'Fix sunken and heaving pavers in Burlington permanently. Root cause diagnosis and precision re-levelling.',
                'sort_order' => 9,
            ],

            // ─── 10. Retaining Walls ────────────────────────────────────────
            'Retaining Walls' => [
                'page_title' => 'Retaining Walls in Burlington',
                'h1' => 'Retaining Wall Construction in Burlington',
                'local_intro' => "Burlington's topography makes retaining walls one of the most common structural landscaping requirements in the city. The Niagara Escarpment runs directly through Burlington, and properties along the escarpment bench, in Tyandaga, and throughout Headon Forest frequently present grade changes of 3 to 8 feet that require engineered retention to create usable outdoor living space. Even lower-elevation neighbourhoods like Aldershot encounter grade transitions where lots slope toward Grindstone Creek or the lake, necessitating erosion control and soil stabilization.\n\nWe build retaining walls with armour stone (natural Ontario boulders weighing 1,000 to 4,000 pounds each), precast concrete blocks from Allan Block, Cornerstone, and Unilock, and poured-in-place concrete where structural demands require it. Every retaining wall over 1.0 metre in retained height is designed to Ontario Building Code Part 4 standards, with geogrid reinforcement at calculated intervals, clear stone backfill, filter fabric, and weeping tile drainage connected to storm or daylight outlets. Burlington projects within the Conservation Halton or Hamilton Conservation Authority regulated areas receive full permitting coordination as part of our scope, ensuring compliance with environmental protection requirements before construction begins.\n\nOur Burlington retaining wall projects range from single-course armour stone borders defining garden beds in Roseland to multi-tier engineered walls creating terraced backyards on escarpment lots. Every structural wall includes a 10-year workmanship warranty and documentation suitable for building permit close-out where applicable.",
                'meta_title' => 'Retaining Walls Burlington | Lush Landscape',
                'meta_description' => 'Retaining wall construction in Burlington. Armour stone, concrete block, geogrid reinforced. OBC-compliant, conservation-permitted.',
                'og_title' => 'Retaining Walls in Burlington | Lush Landscape',
                'og_description' => 'Engineered retaining walls for Burlington properties. Armour stone and precast block with proper drainage and permits.',
                'sort_order' => 10,
            ],

            // ─── 11. Sod Installation & Grading ─────────────────────────────
            'Sod Installation & Grading' => [
                'page_title' => 'Sod Installation & Grading in Burlington',
                'h1' => 'Sod Installation & Grading in Burlington',
                'local_intro' => "A healthy lawn in Burlington begins with understanding what lies beneath the surface. Near the lakeshore in Aldershot and Shoreacres, sandy loam drains quickly but lacks the nutrient density and moisture retention that turf grass needs to thrive through July heat. On the escarpment bench in Headon Forest and Tyandaga, silty clay holds water too long, suffocating roots and promoting fungal disease. Our sod installation process addresses these soil profile challenges with site-specific amendments that give your new lawn the foundation it needs to establish and endure.\n\nWe begin every Burlington project by establishing positive drainage grade away from your foundation at a minimum 2 percent slope for the first 6 feet, then transitioning to a gentler grade across the remainder of the yard. Halton Region stormwater policies require that lot grading directs surface water to approved outlets, and our grading plans comply with these requirements. Topsoil is stripped from areas requiring grade adjustment, subgrade is shaped and compacted, and 4 to 6 inches of Triple-Mix (screened topsoil, peat, and compost blend) is spread and raked to final grade. Premium Kentucky Bluegrass sod is laid the same day it is harvested, rolled for root-to-soil contact, and treated with starter fertilizer.\n\nFrom post-construction grading on new builds in Millcroft to complete lawn renovation on mature Roseland lots where decades of tree canopy have thinned the turf, our Burlington sod crews handle projects of every scope. We guarantee root establishment when our watering schedule is followed and provide seasonal care guidance tailored to Burlington's Zone 6a growing conditions.",
                'meta_title' => 'Sod Installation & Grading Burlington | Lush',
                'meta_description' => 'Professional sod installation and grading in Burlington. Kentucky Bluegrass, Triple-Mix soil, Halton-compliant drainage with consultation-led planning.',
                'og_title' => 'Sod Installation & Grading in Burlington | Lush',
                'og_description' => 'Premium sod installation with proper grading and soil amendment for Burlington lawns. Instant results, lasting health.',
                'sort_order' => 11,
            ],

            // ─── 12. Artificial Turf ────────────────────────────────────────
            'Artificial Turf' => [
                'page_title' => 'Artificial Turf Installation in Burlington',
                'h1' => 'Artificial Turf in Burlington',
                'local_intro' => "For Burlington homeowners who want a consistently green, maintenance-free lawn regardless of season, artificial turf delivers year-round performance that natural grass cannot match. No mowing, no watering, no fertilizing, and no brown patches through summer drought or spring snowmelt. Modern synthetic turf features multi-toned blade profiles, realistic thatch layers, and UV stabilization that maintains colour integrity for 15 or more years, even on south-facing Burlington lots that receive full sun exposure.\n\nOur Burlington artificial turf installations use a base system engineered for the specific soil conditions on your lot. Sandy loam near the lakeshore drains quickly, but the base still requires proper compaction and grading to prevent surface undulation. Silty clay on the escarpment bench demands an aggregate base deep enough to intercept moisture and allow it to move laterally to drainage outlets rather than pooling beneath the turf. We excavate existing soil, install compacted aggregate with positive drainage grade, lay geotextile separation fabric, and secure the turf with landscape spikes and infill material. Pet-friendly installations use antimicrobial infill and achieve drainage rates exceeding 30 inches per hour.\n\nWhether you need a backyard play surface for children, a pet area that stays clean beside your Brant Hills garden, a putting green tucked into a Millcroft side yard, or a front-lawn accent that looks immaculate without weekend work, our Burlington artificial turf division delivers complete installations designed to perform for over a decade.",
                'meta_title' => 'Artificial Turf Burlington | Lush Landscape',
                'meta_description' => 'Artificial turf installation in Burlington. Pet-friendly, UV-stable, 30+ in/hr drainage. No mowing, no watering, always green.',
                'og_title' => 'Artificial Turf in Burlington | Lush Landscape',
                'og_description' => 'Maintenance-free artificial turf for Burlington lawns, pet areas, and play zones. Looks natural year-round.',
                'sort_order' => 12,
            ],

            // ─── 13. Garden Design & Planting ───────────────────────────────
            'Garden Design & Planting' => [
                'page_title' => 'Garden Design & Planting in Burlington',
                'h1' => 'Garden Design & Planting in Burlington',
                'local_intro' => "Burlington offers one of the most rewarding planting environments in the Greater Toronto Area. The city's Zone 6a climate is moderated by Lake Ontario's thermal mass, extending the growing season and allowing a wider palette of hardy perennials, ornamental grasses, flowering shrubs, and specimen trees than inland communities enjoy. The proximity of the Royal Botanical Gardens, one of Canada's premier horticultural institutions, reflects the deep gardening tradition that Burlington homeowners carry into their own properties. Our garden design service channels that tradition into curated planting plans that deliver four-season visual interest, support native pollinators, and thrive in Burlington's specific soil and light conditions.\n\nEvery Burlington garden design project begins with an on-site assessment of sun exposure across the day, soil type and pH, drainage patterns, and existing vegetation including mature tree canopy that creates shade management challenges on many established lots. Planting plans specify cultivars proven in Zone 6a conditions, sourced from Ontario-accredited nurseries. Garden bed construction includes excavation of compacted subsoil, amendment with premium planting mix formulated for your soil type, and installation of steel or aluminum edging for clean, permanent bed lines. Mulch is applied at 3-inch depth for moisture retention and weed suppression.\n\nFrom pollinator-friendly native gardens inspired by the escarpment's natural flora in Tyandaga to formal foundation plantings framing a Roseland colonial, our Burlington garden design team creates landscapes that mature gracefully and look better with every passing season. All plant material is backed by a one-year health guarantee when our care instructions are followed.",
                'meta_title' => 'Garden Design & Planting Burlington | Lush',
                'meta_description' => 'Professional garden design and planting in Burlington. Native perennials, pollinator gardens, four-season interest. One-year guarantee.',
                'og_title' => 'Garden Design & Planting in Burlington | Lush',
                'og_description' => 'Custom garden design with native and adapted plants for Burlington properties. Beautiful, sustainable, pollinator-friendly.',
                'sort_order' => 13,
            ],

            // ─── 14. Landscape Lighting ─────────────────────────────────────
            'Landscape Lighting' => [
                'page_title' => 'Landscape Lighting in Burlington',
                'h1' => 'Landscape Lighting in Burlington',
                'local_intro' => "Professional landscape lighting extends the usable hours of your Burlington outdoor spaces, enhances property security, and highlights the architectural and landscape features that give your home its character after dark. Burlington's mature tree canopy, escarpment stone features, and lakeshore sightlines create lighting opportunities that generic fixture placement cannot capture. Our lighting designs use layered illumination techniques including uplighting for specimen trees and architectural columns, downlighting from canopy-mounted fixtures for natural moonlight effects, wash lighting for retaining walls and garden beds, and accent spotlighting for sculptural and water features.\n\nWe use low-voltage LED systems powered by commercial-grade transformers with built-in timers and WiFi smart controls compatible with phone apps and home automation platforms. All fixtures are solid brass or marine-grade aluminum, critical for Burlington properties near the lakeshore where salt mist corrodes lesser metals within a few seasons. Direct-burial cable rated for outdoor use is trenched below grade to protect against damage from gardening, foot traffic, and freeze-thaw soil movement at the 48-inch frost depth Burlington's climate demands.\n\nFrom driveway pillar and path lighting on Roseland's tree-lined streets to backyard patio and garden illumination in Headon Forest, our Burlington lighting projects are designed by certified lighting professionals who understand colour temperature selection, beam spread calculation, and lumen requirements for each application. Every system is sized with expansion capacity so adding fixtures in future seasons does not require transformer upgrades or rewiring.",
                'meta_title' => 'Landscape Lighting Burlington | Lush Landscape',
                'meta_description' => 'Professional LED landscape lighting in Burlington. Brass fixtures, WiFi smart controls, salt-resistant materials with consultation-led planning.',
                'og_title' => 'Landscape Lighting in Burlington | Lush Landscape',
                'og_description' => 'Custom landscape lighting design and installation for Burlington homes. Low-voltage LED, brass fixtures, smart controls.',
                'sort_order' => 14,
            ],

        ];
    }
}
