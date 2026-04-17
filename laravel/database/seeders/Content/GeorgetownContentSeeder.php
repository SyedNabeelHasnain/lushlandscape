<?php

namespace Database\Seeders\Content;

use App\Models\City;
use App\Models\Service;
use App\Models\ServiceCityPage;
use Illuminate\Database\Seeder;

class GeorgetownContentSeeder extends Seeder
{
    public function run(): void
    {
        $city = City::where('name', 'Georgetown')->firstOrFail();
        $pages = $this->getPages();

        $neighborhoods = [
            'Georgetown South', 'Georgetown North', 'Glen Williams', 'Limehouse', 'Silver Creek',
            'Stewart Maiden', 'Delrex', 'Hungry Hollow', 'Georgetown GO Area',
            'Credit River Corridor',
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
                        'text' => 'Book a Consultation in Georgetown',
                        'url' => '/consultation?service='.urlencode($service->name).'&city=Georgetown',
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
                'page_title' => 'Interlocking Driveways in Georgetown',
                'h1' => 'Interlocking Driveways in Georgetown',
                'local_intro' => "Georgetown is the largest community within the Town of Halton Hills, where small-town charm meets steady suburban growth along the Credit River corridor. The silty clay loam soils here, layered with glacial cobble deposits near the river valley, create challenging conditions for driveway construction that demand experienced base engineering. Builder-grade lots in Georgetown South and Georgetown North often arrive with compacted subsoil that traps moisture and heaves through the severe freeze-thaw cycles this area endures every winter. Our interlocking driveway installations in Georgetown are built from the subgrade up to handle these exact conditions.\n\nWe excavate to a minimum 18-inch depth on Georgetown properties, engineered for the 48-inch frost penetration depth that defines this Zone 5b climate. A compacted Granular A sub-base, 1-inch HPB levelling course, and premium pavers from Unilock, Techo-Bloc, or Belgard rated at 8,000+ PSI form the finished surface. Herringbone or 45-degree bond patterns maximize interlock strength under vehicle loads, while polymeric sand and soldier-course edge restraint anchored below frost line ensure long-term joint stability through Georgetown's average annual snowfall of 140 cm. Halton Hills Zoning By-Law 2010-0050 limits driveway width to 6.0 metres, and we ensure every installation meets municipal requirements.\n\nWhether your home is near Georgetown's heritage downtown core, a newer build in Silver Creek or Cedarvale, or a rural property in Stewarttown or Limehouse within the Niagara Escarpment Development Control Area, our ICPI-certified crews tailor every Georgetown driveway to your lot's soil profile and drainage conditions. Properties regulated by Credit Valley Conservation receive full permitting coordination as part of our project scope.",
                'meta_title' => 'Interlocking Driveways Georgetown | Lush',
                'meta_description' => 'Custom interlocking driveway installation in Georgetown. Engineered for silty clay loam, 48-inch frost depth. ICPI-certified with consultation-led planning.',
                'og_title' => 'Interlocking Driveways in Georgetown | Lush Landscape',
                'og_description' => 'Premium interlocking driveway construction for Georgetown homeowners. Engineered for Credit River corridor soils and Halton Hills winters.',
                'sort_order' => 1,
            ],

            // ─── 2. Interlocking Patios & Backyard Living ───────────────────
            'Interlocking Patios & Backyard Living' => [
                'page_title' => 'Interlocking Patios & Outdoor Living in Georgetown',
                'h1' => 'Interlocking Patios & Backyard Living in Georgetown',
                'local_intro' => "Georgetown homeowners take pride in outdoor spaces that reflect the community's blend of small-town character and growing suburban amenity. Properties in Hungry Hollow back onto wooded ravine land along the Credit River, Silver Creek lots offer mature landscaping with generous rear yards, and newer builds in Georgetown South present blank-canvas backyards ready for transformation. A properly engineered interlocking patio turns any of these settings into a complete outdoor living environment with BBQ islands, fire pits, seating walls, and integrated lighting designed for Georgetown's climate.\n\nThe silty clay loam beneath most Georgetown properties creates specific drainage challenges that must be resolved at the base level. We engineer positive grade away from your foundation, integrate catch basins where impermeable clay creates ponding risk, and install open-graded base systems on lots where conventional drainage proves insufficient. Every Georgetown patio includes compacted Granular A sub-base, HPB levelling course, and premium pavers selected for both freeze-thaw durability and design appeal. Credit Valley Conservation regulations apply to many Georgetown properties near the Credit River, and Niagara Escarpment Commission approval may be required for lots in Glen Williams or Limehouse. We coordinate all agency approvals as part of our project scope.\n\nFrom multi-level outdoor rooms with kitchens and fireplaces on larger lots in Georgetown North to intimate courtyard patios in the heritage downtown area, our design team works from concept through completion. We coordinate gas, electrical, and plumbing trades as needed, and all patio work in Georgetown carries our workmanship warranty.",
                'meta_title' => 'Interlocking Patios Georgetown | Lush Landscape',
                'meta_description' => 'Custom interlocking patios in Georgetown. Outdoor kitchens, fire pits, seating walls. Engineered for Credit River clay soils with consultation-led planning.',
                'og_title' => 'Interlocking Patios & Backyard Living in Georgetown',
                'og_description' => 'Transform your Georgetown backyard with custom interlocking patios, outdoor kitchens, and fire features built for Halton Hills terrain.',
                'sort_order' => 2,
            ],

            // ─── 3. Walkways & Steps ────────────────────────────────────────
            'Walkways & Steps' => [
                'page_title' => 'Walkways & Steps in Georgetown',
                'h1' => 'Walkways & Steps in Georgetown',
                'local_intro' => "Georgetown's terrain along the Credit River valley and near the Niagara Escarpment means that many residential properties deal with significant grade transitions between the street, front entry, and backyard. Combined with annual snowfall averaging 140 cm and frost penetration reaching 48 inches, these elevation changes demand walkways and steps that are structurally sound, properly drained, and slip-resistant in all seasons. Our walkway and step installations in Georgetown follow Ontario Building Code requirements for riser height, tread depth, and handrail placement on every project.\n\nWe design walkways that complement your existing hardscaping and respond to your property's specific grade conditions. For Georgetown homes on the sloped terrain of Hungry Hollow and the ravine-edge lots along the Credit River, we build landing pads at code-required intervals, install non-slip tread surfaces, and ensure positive drainage diverts meltwater away from walking surfaces. Steps use reinforced concrete cores with paver or natural stone cladding for structural permanence, anchored to footings that extend below the 48-inch frost depth that Georgetown's Zone 5b climate demands. The glacial cobble deposits common near the Credit River corridor require careful footing preparation to avoid settling.\n\nFrom character homes near Georgetown's heritage downtown where step design must respect the neighbourhood's established streetscape to newer subdivisions in Georgetown South and Cedarvale where builder-grade concrete is ready for an upgrade, our Georgetown walkway projects deliver safety and curb appeal in equal measure. Every installation includes compacted aggregate base, edge restraint, and polymeric sand to prevent weed growth and paver migration.",
                'meta_title' => 'Walkways & Steps Georgetown | Lush Landscape',
                'meta_description' => 'Interlocking walkways and steps in Georgetown. Non-slip surfaces, OBC-compliant risers, Credit River valley engineering with consultation-led planning.',
                'og_title' => 'Walkways & Steps in Georgetown | Lush Landscape',
                'og_description' => 'Professional walkway and step installation for Georgetown homes. Engineered for Credit River valley elevation changes and winter safety.',
                'sort_order' => 3,
            ],

            // ─── 4. Natural Stone & Flagstone ───────────────────────────────
            'Natural Stone & Flagstone' => [
                'page_title' => 'Natural Stone & Flagstone in Georgetown',
                'h1' => 'Natural Stone & Flagstone Installation in Georgetown',
                'local_intro' => "Georgetown's setting within the Credit River valley and at the edge of the Niagara Escarpment provides a natural geological context that makes stone an ideal material choice for hardscaping. The exposed dolostone and limestone formations visible along the Escarpment in nearby Glen Williams and Limehouse connect Georgetown properties to the region's ancient bedrock. Our natural stone division works with locally quarried Eramosa limestone, Algonquin flagstone, Muskoka granite, and Wiarton bluestone to create patios, walkways, accent walls, and water features for Georgetown homeowners who appreciate authentic materials.\n\nFlagstone installations on Georgetown's silty clay loam soils require meticulous base preparation. We install a minimum 12-inch compacted aggregate base under all flagstone work, use mortar-set or dry-laid techniques depending on application and load requirements, and fill joints with polymeric sand or natural stone dust to prevent weed intrusion. Armour stone installations use Ontario-quarried boulders weighing 1,000 to 4,000 pounds, placed with equipment precision and seated against seasonal soil movement. Properties within the Niagara Escarpment Development Control Area, which covers portions of Glen Williams and Limehouse, require Niagara Escarpment Commission approval, and properties near the Credit River fall under Credit Valley Conservation jurisdiction. We handle all permitting as part of our project scope.\n\nFrom hand-cut flagstone patios overlooking the Credit River ravine in Hungry Hollow to armour stone terracing on graded lots in Georgetown North and Silver Creek, our Georgetown crews bring the craftsmanship that natural stone demands. Every project reflects the geological character that makes Georgetown's setting unique within Halton Hills.",
                'meta_title' => 'Natural Stone & Flagstone Georgetown | Lush',
                'meta_description' => 'Natural stone and flagstone installation in Georgetown. Ontario-quarried materials, Escarpment-inspired design, CVC permit coordination.',
                'og_title' => 'Natural Stone & Flagstone in Georgetown | Lush Landscape',
                'og_description' => 'Premium natural stone patios, flagstone walkways, and armour stone features for Georgetown properties along the Credit River valley.',
                'sort_order' => 4,
            ],

            // ─── 5. Porcelain Pavers ────────────────────────────────────────
            'Porcelain Pavers' => [
                'page_title' => 'Porcelain Paver Installation in Georgetown',
                'h1' => 'Porcelain Pavers in Georgetown',
                'local_intro' => "Porcelain pavers offer Georgetown homeowners a modern hardscaping material that thrives in the demanding conditions of a Zone 5b climate with 140 cm of average annual snowfall. These 20mm-thick engineered tiles deliver zero water absorption, R11 slip rating, and UV stability, making them resistant to the freeze-thaw cycling, heavy snow loads, and road salt exposure that Georgetown properties endure from November through April. For homeowners in Georgetown's growing neighbourhoods who want a clean contemporary aesthetic without constant maintenance, porcelain pavers provide an exceptional solution.\n\nOur Georgetown porcelain paver installations use pedestal or compacted-aggregate base systems depending on the project type. Rooftop terraces and pool surrounds typically use adjustable pedestal systems that accommodate drainage underneath and provide easy access to membrane surfaces. Ground-level patios use compacted aggregate base with levelling screed and open-graded joint material, engineered for Georgetown's silty clay loam subgrade that retains moisture and resists natural percolation. The glacial cobble deposits found near the Credit River corridor may require additional excavation depth to achieve stable bearing. We source 20mm porcelain pavers from leading Italian and Spanish manufacturers through Canadian distributors who certify freeze-thaw performance to ASTM C1026 standards.\n\nFrom sleek concrete-look terraces on newer builds in Georgetown South to warm wood-tone pool surrounds in established Silver Creek, porcelain pavers give Georgetown homeowners design flexibility that few other materials can match. Every installation is backed by our workmanship warranty and manufacturer material guarantees.",
                'meta_title' => 'Porcelain Pavers Georgetown | Lush Landscape',
                'meta_description' => 'Modern 20mm porcelain paver installation in Georgetown. Zero absorption, R11 slip rating, frost-proof. Patios, pool decks, terraces.',
                'og_title' => 'Porcelain Pavers in Georgetown | Lush Landscape',
                'og_description' => 'Contemporary porcelain paver installation for Georgetown patios, terraces, and pool surrounds. Built for Halton Hills climate.',
                'sort_order' => 5,
            ],

            // ─── 6. Concrete Driveways ──────────────────────────────────────
            'Concrete Driveways' => [
                'page_title' => 'Concrete Driveways in Georgetown',
                'h1' => 'Concrete Driveways in Georgetown',
                'local_intro' => "A concrete driveway remains the most cost-effective way to achieve a durable, clean-looking surface for Georgetown properties, from heritage homes near the downtown core to newer builds across the community's expanding southern neighbourhoods. Our concrete driveway installations use 32 MPa air-entrained mix with 5 to 7 percent air content, the Ontario specification for freeze-thaw durability in Zone 5b climates. Every pour includes 10M rebar on 400mm centres, fibre-mesh reinforcement, and control joints calculated for slab dimensions and expected load. Halton Hills Zoning By-Law 2010-0050 limits residential driveway widths to 6.0 metres, and we ensure every Georgetown installation meets municipal requirements.\n\nGeorgetown's silty clay loam, combined with glacial cobble deposits near the Credit River corridor, creates a subgrade that holds water and heaves if base preparation is inadequate. Newer areas in Georgetown South are particularly affected, where construction equipment has compacted builder-grade subsoil into a nearly impermeable layer. We excavate to a minimum 14-inch depth, install compacted Granular A sub-base, and use a polyethylene vapour barrier to prevent moisture migration into the slab. On sloped driveways near the Credit River valley, we incorporate broom-finish texturing for traction and install cross-slope drainage swales to redirect surface water before it reaches the garage.\n\nWe offer broom-finish, exposed aggregate, stamped patterns including ashlar slate, cobblestone, and wood-plank textures, and acid-stain decorative finishes. Georgetown homeowners in the heritage downtown area benefit from stamped patterns that complement the existing streetscape character while delivering modern structural performance built for Halton Hills winters.",
                'meta_title' => 'Concrete Driveways Georgetown | Lush Landscape',
                'meta_description' => 'Concrete driveway installation in Georgetown. 32 MPa air-entrained mix, rebar reinforcement, clay-soil engineering with consultation-led planning.',
                'og_title' => 'Concrete Driveways in Georgetown | Lush Landscape',
                'og_description' => 'High-strength concrete driveways for Georgetown homes. Stamped, exposed aggregate, and broom-finish options for every lot.',
                'sort_order' => 6,
            ],

            // ─── 7. Concrete Patios & Walkways ─────────────────────────────
            'Concrete Patios & Walkways' => [
                'page_title' => 'Concrete Patios & Walkways in Georgetown',
                'h1' => 'Concrete Patios & Walkways in Georgetown',
                'local_intro' => "Architectural concrete gives Georgetown homeowners the opportunity to create outdoor surfaces with genuine design impact, far beyond a plain grey slab. Our stamped, stained, and exposed aggregate finishes transform patios, walkways, pool decks, and stepping-stone paths into features that complement both newer builds in Georgetown South and established character homes near the heritage downtown. We pour all flatwork using 32 MPa air-entrained concrete specified for Georgetown's freeze-thaw climate, where frost penetration reaches 48 inches and annual snowfall averages 140 cm.\n\nStamped concrete patterns include ashlar slate, European fan, random stone, cobblestone, and wood-plank textures applied with colour hardener and release agents for a natural, multi-toned appearance. Exposed aggregate finishes reveal the natural stone within the mix, creating a textured, slip-resistant surface ideal for pool surrounds and garden paths. For Georgetown properties that back onto the Credit River ravine in Hungry Hollow, where outdoor entertaining comes with views of the wooded valley, acid-stain finishes produce translucent colour variations that echo the natural earth tones of the surrounding landscape.\n\nEvery Georgetown concrete patio project includes proper sub-base preparation for the local silty clay loam soil profile, positive drainage grading away from your foundation, and isolation joints where the patio meets the house structure. We coordinate with Credit Valley Conservation and, where applicable, the Niagara Escarpment Commission for projects on regulated properties in the Georgetown area.",
                'meta_title' => 'Concrete Patios & Walkways Georgetown | Lush',
                'meta_description' => 'Stamped and exposed aggregate concrete patios in Georgetown. Decorative finishes, proper drainage, clay-soil engineering with consultation-led planning.',
                'og_title' => 'Concrete Patios & Walkways in Georgetown | Lush Landscape',
                'og_description' => 'Architectural concrete patios and walkways for Georgetown homes. Stamped, stained, and exposed aggregate options.',
                'sort_order' => 7,
            ],

            // ─── 8. Interlock Restoration & Sealing ─────────────────────────
            'Interlock Restoration & Sealing' => [
                'page_title' => 'Interlock Restoration & Sealing in Georgetown',
                'h1' => 'Interlock Restoration & Sealing in Georgetown',
                'local_intro' => "Georgetown's established neighbourhoods have matured to the point where many interlocking driveways and patios installed 10 to 20 years ago now show significant wear. Road salt tracked from Halton Hills' well-maintained winter roads, organic growth fuelled by the Credit River valley's humid microclimate, and UV fading from years of unprotected sun exposure all contribute to surfaces that look tired long before the pavers themselves are structurally compromised. Properties in Georgetown Downtown, Silver Creek, and Cedarvale are prime candidates for our 3-day interlock restoration process that reverses deterioration and provides lasting protection.\n\nDay one involves thorough hot-water pressure washing at 3,000+ PSI with surface-cleaning attachments that remove embedded dirt, moss, algae, efflorescence, and the mineral staining common near Georgetown's groundwater seeps along the Credit River corridor. Day two allows complete surface drying before we re-apply polymeric sand to all joints, ensuring proper activation and curing. Day three applies UV-resistant sealer in the homeowner's choice of matte, satin, or wet-look finish, locking in colour, preventing future staining, and simplifying ongoing maintenance through Georgetown's demanding seasons.\n\nWe restore interlocking driveways, patios, walkways, and pool decks throughout Georgetown, from the heritage downtown streets to the maturing subdivisions of Georgetown North and Hungry Hollow. If your pavers are structurally sound but visually tired, restoration and sealing delivers the most cost-effective renewal available without the expense and disruption of full replacement.",
                'meta_title' => 'Interlock Restoration Georgetown | Lush',
                'meta_description' => 'Professional interlock restoration and sealing in Georgetown. 3-day process: pressure wash, polymeric sand, UV sealer. Renew your pavers.',
                'og_title' => 'Interlock Restoration & Sealing in Georgetown',
                'og_description' => 'Restore faded interlocking surfaces in Georgetown. Hot-water wash, polymeric sand, and protective sealer application.',
                'sort_order' => 8,
            ],

            // ─── 9. Interlock Repair (Lift & Relay) ─────────────────────────
            'Interlock Repair (Lift & Relay)' => [
                'page_title' => 'Interlock Repair (Lift & Relay) in Georgetown',
                'h1' => 'Interlock Repair in Georgetown: Lift & Relay',
                'local_intro' => "Sunken, heaving, or uneven interlocking pavers are a common problem across Georgetown, where silty clay loam soils expand and contract aggressively through freeze-thaw cycles. The glacial cobble deposits near the Credit River corridor add another layer of complexity, as these irregular subsurface stones create uneven bearing that leads to localized settling. Properties in Georgetown South built on compacted builder-grade subsoil are especially vulnerable, where heavy equipment used during construction created dense, poorly draining layers beneath thin topsoil and aggregate bases. These displaced surfaces create tripping hazards, allow water to pool against foundations, and worsen progressively if the underlying cause is not corrected.\n\nOur Georgetown repair process begins with carefully removing the affected pavers and setting them aside for re-use. We excavate the failed base material, diagnose the cause of failure, whether insufficient base depth, poor original compaction, tree root intrusion, or subsurface drainage failure from Georgetown's impermeable clay layer, and correct it permanently. Fresh HPB aggregate is installed, compacted in lifts to 95 percent Standard Proctor density, and screeded to the correct elevation. Original pavers are then re-laid in their original pattern, new polymeric sand fills all joints, and the repaired section is compacted to match the surrounding surface.\n\nLift-and-relay preserves your existing pavers and their naturally weathered patina, so the repair blends seamlessly with undisturbed areas. We perform this service on driveways, patios, walkways, and pool decks across Georgetown, from Silver Creek and Cedarvale to Georgetown North and the heritage downtown core.",
                'meta_title' => 'Interlock Repair Georgetown - Lift & Relay | Lush',
                'meta_description' => 'Permanent interlock repair in Georgetown. Lift and relay sunken pavers with HPB base correction. Fix the root cause on clay soils.',
                'og_title' => 'Interlock Repair (Lift & Relay) in Georgetown',
                'og_description' => 'Fix sunken and heaving pavers in Georgetown permanently. HPB base correction engineered for silty clay loam subgrade.',
                'sort_order' => 9,
            ],

            // ─── 10. Retaining Walls ────────────────────────────────────────
            'Retaining Walls' => [
                'page_title' => 'Retaining Walls in Georgetown',
                'h1' => 'Retaining Wall Construction in Georgetown',
                'local_intro' => "Georgetown's terrain along the Credit River valley and at the margin of the Niagara Escarpment creates residential properties with grade changes that demand engineered retaining solutions. Lots that back onto the Credit River ravine in Hungry Hollow, sloped front yards in Georgetown North, and erosion-prone boundaries along tributary creeks all require walls designed for structural permanence. Properties in Glen Williams and Limehouse that fall within the Niagara Escarpment Development Control Area face additional regulatory requirements. Whether you need a terraced backyard carved into a hillside, a front-yard wall to stabilize a sloped driveway, or erosion control along a conservation boundary, our retaining wall division delivers structurally certified results in Georgetown.\n\nWe build with armour stone sourced from Ontario quarries in weights from 1,000 to 4,000 pounds, precast concrete blocks from Allan Block, Cornerstone, and Unilock, and poured-in-place reinforced concrete where structural loads require it. Every retaining wall over 1.0 metre in retained height is designed to Ontario Building Code Part 4 standards with geogrid reinforcement at calculated intervals, clear stone backfill, filter fabric, and weeping tile connected to storm or daylight outlets. Georgetown's silty clay loam backfill conditions make proper drainage behind the wall as critical as the wall structure itself, especially on lots where glacial cobble deposits create unpredictable subsurface water flow paths toward the Credit River.\n\nOur Georgetown retaining wall projects include full engineering coordination where required, Credit Valley Conservation permitting for properties within CVC-regulated areas, and Niagara Escarpment Commission approvals for properties within the Escarpment Development Control Area. Halton Hills Building Services permits are obtained through Town channels, and Georgetown homeowners receive clear guidance on which permits apply to their lot before any work begins.",
                'meta_title' => 'Retaining Walls Georgetown | Lush Landscape',
                'meta_description' => 'Retaining wall construction in Georgetown. Armour stone, concrete block, geogrid reinforced. OBC-compliant, CVC and NEC permitted.',
                'og_title' => 'Retaining Walls in Georgetown | Lush Landscape',
                'og_description' => 'Engineered retaining walls for Georgetown properties along the Credit River valley. Armour stone and precast block with proper drainage.',
                'sort_order' => 10,
            ],

            // ─── 11. Sod Installation & Grading ─────────────────────────────
            'Sod Installation & Grading' => [
                'page_title' => 'Sod Installation & Grading in Georgetown',
                'h1' => 'Sod Installation & Grading in Georgetown',
                'local_intro' => "Establishing a healthy lawn in Georgetown requires understanding the silty clay loam soils that define this Credit River valley community. As the largest population centre within the Town of Halton Hills, Georgetown has seen significant residential growth that has converted agricultural and rural land into suburban neighbourhoods. The clay-heavy soils that once sustained farming now sit compacted by construction equipment, covered with a thin skim of topsoil that does little to support healthy turf establishment. Builder-grade lots in Georgetown South and newer pockets of Cedarvale arrive with subsoil that resists root penetration and drains poorly, while established areas closer to downtown Georgetown often have mature tree canopies that compete with turf for moisture and light.\n\nWe establish positive drainage grade away from your foundation at a minimum 2 percent slope for the first 6 feet, then transition to a gentler grade across the remainder of the yard. On Georgetown properties near the Credit River where natural grades are steep, particularly in Hungry Hollow and along tributary ravines, we incorporate terracing or swale systems to prevent erosion and control surface water. Four to 6 inches of Triple-Mix, a blend of screened topsoil, peat, and compost, is spread and raked to final grade. Premium Kentucky Bluegrass sod is laid the same day it is cut, rolled for full root contact, and given starter fertilizer. Georgetown's growing season from early May to early October provides a reliable window for establishment.\n\nFrom post-construction grading on new builds in Georgetown South to full lawn renovation on mature lots in Georgetown Downtown shaded by established maples and oaks, our Georgetown sod crews handle projects of every scale. We guarantee root establishment when our watering schedule is followed.",
                'meta_title' => 'Sod Installation Georgetown | Lush Landscape',
                'meta_description' => 'Professional sod installation and grading in Georgetown. Kentucky Bluegrass, Triple-Mix soil, Credit River drainage solutions. Same-day install.',
                'og_title' => 'Sod Installation & Grading in Georgetown | Lush',
                'og_description' => 'Premium sod installation with proper grading and soil amendment for Georgetown lawns. Engineered for silty clay loam soils.',
                'sort_order' => 11,
            ],

            // ─── 12. Artificial Turf ────────────────────────────────────────
            'Artificial Turf' => [
                'page_title' => 'Artificial Turf Installation in Georgetown',
                'h1' => 'Artificial Turf in Georgetown',
                'local_intro' => "For Georgetown homeowners who want a consistently green, maintenance-free lawn from spring through winter, artificial turf eliminates the mowing, watering, fertilizing, and seasonal brown patches that natural grass demands. Modern synthetic turf has advanced well beyond the plastic appearance of earlier generations. Today's premium products feature multi-toned blade profiles, integrated thatch layers for natural appearance, and UV stabilization that maintains colour integrity for 15 or more years, even through Georgetown's intense summer sun and the heavy snowfall that averages 140 cm annually in this part of Halton Hills.\n\nOur Georgetown artificial turf installations address the specific drainage limitations of the community's silty clay loam soils. We excavate existing soil, install compacted aggregate base with positive drainage grade, lay geotextile separation fabric, and secure the turf with landscape spikes and infill material. Pet-friendly installations use antimicrobial infill and achieve drainage rates exceeding 30 inches per hour, critical for Georgetown properties where the underlying clay would otherwise trap moisture at the surface and create standing water issues. The glacial cobble deposits near the Credit River corridor may require additional excavation to achieve a smooth, even bearing surface.\n\nWhether you need a backyard play surface for children in Georgetown South, a pet run in Silver Creek that stays clean through mud season, a putting green for golf practice in Georgetown North, or a front-yard accent near the heritage downtown that looks immaculate without weekend maintenance, our Georgetown artificial turf team delivers turnkey installations tailored to your property and soil conditions.",
                'meta_title' => 'Artificial Turf Georgetown | Lush Landscape',
                'meta_description' => 'Artificial turf installation in Georgetown. Pet-friendly, UV-stable, 30+ in/hr drainage on clay soils. No mowing, no watering.',
                'og_title' => 'Artificial Turf in Georgetown | Lush Landscape',
                'og_description' => 'Maintenance-free artificial turf for Georgetown lawns, pet areas, and play zones. Engineered drainage for silty clay loam soils.',
                'sort_order' => 12,
            ],

            // ─── 13. Garden Design & Planting ───────────────────────────────
            'Garden Design & Planting' => [
                'page_title' => 'Garden Design & Planting in Georgetown',
                'h1' => 'Garden Design & Planting in Georgetown',
                'local_intro' => "Georgetown's position along the Credit River and at the margin of the Niagara Escarpment provides a rich botanical context that few Ontario communities can match. The Credit River valley's sheltered microclimates and the moderating effect of the surrounding topography allow Georgetown gardeners to grow plant species that thrive in Zone 5b conditions, with a growing season that extends from early May to early October. The protected woodlands along the Credit River and the conservation corridors managed by Credit Valley Conservation provide a natural reference point for native planting designs. Our garden design team leverages Georgetown's unique landscape character in every planting plan we create.\n\nWe start every Georgetown garden project with an on-site assessment of sun exposure, soil type, drainage conditions, and existing vegetation. Properties near the Credit River ravine in Hungry Hollow often have variable soil depths mixed with glacial cobble that may require raised bed construction, while lots in newer areas like Georgetown South and Cedarvale sit on deep compacted builder-grade subsoil that needs thorough amendment for proper root establishment. Planting plans specify cultivars proven in Zone 5b conditions, sourced from Ontario-accredited nurseries. Bed construction includes excavation of compacted subsoil, amendment with premium planting mix, and installation of steel or aluminum edging for clean, permanent bed lines.\n\nFrom pollinator gardens that support the Credit River watershed's biodiversity to privacy screening hedges along Georgetown's newer lot lines where neighbours sit close, our Georgetown garden design team creates outdoor spaces that improve with every passing season. Properties in Glen Williams and Limehouse within the Niagara Escarpment Development Control Area benefit from native species selections that align with Escarpment Commission guidelines. All plant material carries a one-year health guarantee when our care instructions are followed.",
                'meta_title' => 'Garden Design Georgetown | Lush Landscape',
                'meta_description' => 'Professional garden design and planting in Georgetown. Credit River-adapted species, pollinator gardens, four-season interest with consultation-led planning.',
                'og_title' => 'Garden Design & Planting in Georgetown | Lush',
                'og_description' => 'Custom garden design with Credit River valley-adapted plants for Georgetown properties. Beautiful, sustainable, and pollinator-friendly.',
                'sort_order' => 13,
            ],

            // ─── 14. Landscape Lighting ─────────────────────────────────────
            'Landscape Lighting' => [
                'page_title' => 'Landscape Lighting in Georgetown',
                'h1' => 'Landscape Lighting in Georgetown',
                'local_intro' => "Professional landscape lighting extends usable outdoor hours on Georgetown properties, enhances security along driveways and walkways, and highlights the architectural and natural features that define your home's character. With Georgetown's shorter winter days and early-evening darkness from November through March, a well-designed lighting system transforms how you experience your outdoor spaces during the months when you need light the most. Georgetown's small-town streetscapes and heritage downtown character make tasteful exterior lighting an investment that enhances both your property and the broader neighbourhood aesthetic.\n\nWe use low-voltage LED systems powered by commercial-grade transformers with built-in timers and WiFi smart controls compatible with phone apps and home automation platforms. All fixtures are solid brass or marine-grade aluminum, selected for their resistance to Georgetown's freeze-thaw cycling, heavy snowfall averaging 140 cm annually, and road salt exposure throughout winter. Direct-burial cable rated for outdoor use is trenched below grade to protect against damage from seasonal ground movement in the silty clay loam soils that underlie most Georgetown properties. The glacial cobble deposits near the Credit River corridor require careful cable routing to avoid damage during installation.\n\nFrom Credit River ravine-edge properties in Hungry Hollow where path lighting guides visitors safely along graded approaches to backyard patio illumination in Silver Creek and garden bed accent lighting in Georgetown North, our Georgetown lighting projects are designed by certified professionals who understand colour temperature, beam angles, and lumen output for each application. Every system is sized with spare transformer capacity for future expansion, so adding fixtures to your Georgetown property later never requires equipment upgrades.",
                'meta_title' => 'Landscape Lighting Georgetown | Lush Landscape',
                'meta_description' => 'Professional LED landscape lighting in Georgetown. Brass fixtures, WiFi smart controls, custom design for Credit River valley properties.',
                'og_title' => 'Landscape Lighting in Georgetown | Lush Landscape',
                'og_description' => 'Custom landscape lighting design and installation for Georgetown homes. Low-voltage LED, brass fixtures, smart controls.',
                'sort_order' => 14,
            ],

        ];
    }
}
