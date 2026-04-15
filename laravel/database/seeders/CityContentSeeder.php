<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CityContentSeeder extends Seeder
{
    public function run(): void
    {
        $cityData = $this->getCityData();

        foreach ($cityData as $name => $data) {
            $city = City::where('name', $name)->first();
            if (! $city) {
                continue;
            }
            $city->update($data);
        }
    }

    private function getCityData(): array
    {
        return [

            // ─── Hamilton ───────────────────────────────────────────────────
            'Hamilton' => [
                'city_summary' => 'Professional landscaping construction services in Hamilton, Ontario. From Niagara Escarpment properties to Dundas Valley estates, our ICPI-certified crews deliver premium hardscaping and softscaping built for Hamilton soil and climate.',
                'city_body' => [
                    'local_intro_extended' => "Hamilton sits at the base of the Niagara Escarpment, creating a dramatic split between the lower city and the upper mountain. This geography produces unique landscaping challenges: steep grade transitions between upper and lower properties, clay-heavy glacial till soils that expand and contract with seasonal moisture, and microclimates that vary noticeably between the waterfront and the escarpment brow. Our Hamilton crews understand these conditions firsthand because we work here year-round.\n\nThe city's mix of century-old Westdale homes, Victorian-era Durand mansions, and modern Meadowlands subdivisions means no two projects look alike. We tailor excavation depths, base specifications, and drainage solutions to each neighbourhood's unique terrain and soil profile. Whether you are restoring a heritage driveway in Kirkendall or building a new patio on Ancaster's clay-heavy lots, we bring the local knowledge that prevents costly callbacks.",
                    'neighborhoods_served' => [
                        'Westdale', 'Dundas', 'Ancaster', 'Stoney Creek',
                        'Waterdown', 'Durand', 'Kirkendall', 'Locke Street',
                        'Binbrook', 'Mount Hope', 'Flamborough', 'Winona',
                    ],
                    'why_local_para' => "Hamilton's Niagara Escarpment geology means properties above the brow sit on shallow bedrock covered by thin clay soils, while lower-city lots rest on deep glacial deposits with high water tables. A crew unfamiliar with these conditions risks specifying inadequate base depths on the mountain or ignoring the drainage demands below the escarpment. Our Hamilton team has worked both sides of the city for years and adjusts every specification to the actual ground conditions on your property.",
                    'permit_summary' => 'Hamilton requires driveway widening permits through the Public Works Department for any change to the curb cut or boulevard. Projects within the Niagara Escarpment Development Control Area need approval from the Niagara Escarpment Commission. Retaining walls over 1.0 metre in retained height require a building permit. The Hamilton Conservation Authority regulates work near watercourses and within regulated areas of Cootes Paradise, Red Hill Creek, and Spencer Creek watersheds.',
                ],
                'local_conditions_json' => [
                    'soil_type' => 'Clay-heavy glacial till over Queenston shale (escarpment) and Lockport dolostone (lower city)',
                    'frost_depth' => '48 inches (1.2 m)',
                    'drainage' => 'Moderate to poor on clay-heavy upper mountain; variable lower city depending on water table depth',
                    'climate_zone' => '6a (moderated by Lake Ontario proximity)',
                    'growing_season' => 'Late April to mid-October',
                    'annual_snowfall' => '150 cm average, with lake-effect enhancement in Stoney Creek and Winona',
                ],
                'municipal_notes_json' => [
                    'hard_surface_bylaw' => 'Zoning By-Law 05-200: front yard hard-surface coverage limits vary by zone',
                    'driveway_width_max' => '6.0 m residential (single car) or as approved by Public Works',
                    'setback_requirements' => 'Side yard: 1.2 m minimum; varies by zone',
                    'permit_authority' => 'City of Hamilton Planning & Economic Development Department',
                    'conservation_authority' => 'Hamilton Conservation Authority (HCA)',
                    'stormwater_requirements' => 'Lot-level stormwater management encouraged; LID measures for projects exceeding 50 m2 impervious coverage',
                ],
                'default_meta_title' => 'Landscaping Services in Hamilton, Ontario',
                'default_meta_description' => 'Professional landscaping construction in Hamilton. Interlocking driveways, concrete patios, retaining walls, and softscaping. Consultation-led planning. 10-year warranty.',
                'default_og_title' => 'Hamilton Landscaping Services | Lush Landscape Service',
                'default_og_description' => 'Premium landscaping construction for Hamilton homeowners. From Westdale to Ancaster, built for escarpment conditions.',
            ],

            // ─── Burlington ─────────────────────────────────────────────────
            'Burlington' => [
                'city_summary' => 'Premium landscaping construction in Burlington, Ontario. Lakeside properties, established neighbourhoods, and new builds served by ICPI-certified crews who understand Halton Region soil and bylaw requirements.',
                'city_body' => [
                    'local_intro_extended' => "Burlington spans from the shores of Lake Ontario to the edge of the Niagara Escarpment, giving homeowners some of the most desirable residential lots in the Greater Toronto and Hamilton Area. The city's mature tree canopy, proximity to the Royal Botanical Gardens, and family-oriented community planning make outdoor living a priority for most residents.\n\nOur Burlington projects range from replacing aging concrete driveways in Aldershot's post-war bungalow belt to building expansive backyard patios in Millcroft's executive homes. Burlington's soil transitions from sandy loam near the lakeshore to heavy clay on the escarpment bench, and each project's base design reflects that reality. We also navigate Halton Region's progressive stormwater management requirements, which increasingly encourage permeable surfaces and on-site infiltration for new hardscape projects.",
                    'neighborhoods_served' => [
                        'Aldershot', 'Tyandaga', 'Roseland', 'Millcroft',
                        'Headon Forest', 'Orchard', 'LaSalle', 'Burlington Downtown',
                        'Shoreacres', 'Palmer', 'Brant Hills', 'Central Burlington',
                    ],
                    'why_local_para' => "Burlington's proximity to Lake Ontario creates localized freeze-thaw patterns that are more aggressive near the lakeshore than even a few kilometres inland. Properties in Aldershot and Shoreacres experience salt-laden winter mist that accelerates paver surface degradation if the wrong sealer is used. Our crews specify marine-grade sealers for lakefront projects and standard UV-resistant formulations for inland work, a distinction that generic contractors often miss.",
                    'permit_summary' => "Burlington requires a driveway entrance permit for any new or modified curb cut. Halton Region's Official Plan policies encourage on-site stormwater management for new impervious surfaces. Retaining walls over 1.0 metre require a building permit from the City of Burlington Building Department. The Hamilton Conservation Authority and Conservation Halton regulate work near Grindstone Creek, Bronte Creek, and other regulated watercourses.",
                ],
                'local_conditions_json' => [
                    'soil_type' => 'Sandy loam near lakeshore transitioning to silty clay on escarpment bench',
                    'frost_depth' => '48 inches (1.2 m)',
                    'drainage' => 'Good near lakeshore sandy soils; moderate to poor on clay-heavy escarpment lots',
                    'climate_zone' => '6a (Lake Ontario moderated)',
                    'growing_season' => 'Late April to mid-October',
                    'annual_snowfall' => '120 cm average',
                ],
                'municipal_notes_json' => [
                    'hard_surface_bylaw' => 'Zoning By-Law 2020: front yard hard-surface coverage limited by zone category',
                    'driveway_width_max' => '6.0 m residential standard',
                    'setback_requirements' => 'Side yard: 1.2 m minimum from property line',
                    'permit_authority' => 'City of Burlington Building and By-Law Department',
                    'conservation_authority' => 'Conservation Halton / Hamilton Conservation Authority (HCA)',
                    'stormwater_requirements' => 'On-site infiltration encouraged; permeable paver systems eligible for stormwater credit',
                ],
                'default_meta_title' => 'Landscaping Services in Burlington, Ontario',
                'default_meta_description' => 'Professional landscaping construction in Burlington. Interlocking, concrete, retaining walls, and softscaping. Halton Region experts with consultation-led planning.',
                'default_og_title' => 'Burlington Landscaping Services | Lush Landscape Service',
                'default_og_description' => 'Premium hardscaping and softscaping for Burlington homeowners. From lakeside Aldershot to Millcroft estates.',
            ],

            // ─── Oakville ───────────────────────────────────────────────────
            'Oakville' => [
                'city_summary' => 'Premium landscaping construction in Oakville, Ontario. From Morrison Road estates to Bronte Village heritage homes, our crews deliver hardscaping and softscaping that meets Oakville standards.',
                'city_body' => [
                    'local_intro_extended' => "Oakville consistently ranks among Canada's most affluent communities, and its homeowners expect a level of craftsmanship and material quality that matches. From the lakefront estates along Lakeshore Road to the executive homes in Joshua Creek and River Oaks, outdoor living spaces here are designed as extensions of the home's architecture.\n\nOur Oakville projects reflect this expectation: natural stone sourced from Ontario quarries, Techo-Bloc and Unilock pavers in premium colour blends, and landscape lighting schemes designed by certified lighting professionals. Oakville's mature tree canopy also means that many properties require careful root-zone management during excavation, and our crews follow arborist-guided protection protocols to preserve existing trees that often hold significant heritage value.",
                    'neighborhoods_served' => [
                        'Old Oakville', 'Bronte', 'Glen Abbey', 'River Oaks',
                        'Joshua Creek', 'Clearview', 'Falgarwood', 'College Park',
                        'Palermo', 'Iroquois Ridge', 'West Oak Trails', 'Eastlake',
                    ],
                    'why_local_para' => "Oakville's Heritage Conservation District regulations in Old Oakville require that hardscape materials, patterns, and colours complement the existing architectural character. A standard suburban interlock pattern that works in a new subdivision would be rejected here. Our crews consult Oakville's heritage guidelines before specifying materials and can recommend paver profiles, stone types, and layout patterns that satisfy both heritage review and modern performance requirements.",
                    'permit_summary' => "Oakville requires a road occupancy and entrance permit for driveway modifications through the Town's Engineering and Construction Department. Heritage Conservation Districts (Trafalgar Road, Old Oakville) impose additional design review for visible exterior changes including hardscaping. Retaining walls over 1.0 metre in retained height require a building permit. Conservation Halton regulates development near Bronte Creek, Fourteen Mile Creek, and Morrison Creek.",
                ],
                'local_conditions_json' => [
                    'soil_type' => 'Sandy clay loam near lakeshore; heavy clay inland toward Trafalgar Road',
                    'frost_depth' => '48 inches (1.2 m)',
                    'drainage' => 'Moderate near creeks; poor on inland clay lots requiring French drain solutions',
                    'climate_zone' => '6a (Lake Ontario moderated)',
                    'growing_season' => 'Late April to mid-October',
                    'annual_snowfall' => '110 cm average',
                ],
                'municipal_notes_json' => [
                    'hard_surface_bylaw' => 'Zoning By-Law 2014-014: front yard coverage limits; heritage districts have stricter material requirements',
                    'driveway_width_max' => '6.0 m residential; heritage districts may impose narrower limits',
                    'setback_requirements' => 'Side yard: 1.2 m minimum; varies by zone and heritage designation',
                    'permit_authority' => 'Town of Oakville Building Services',
                    'conservation_authority' => 'Conservation Halton',
                    'stormwater_requirements' => 'LID measures required for new developments; encouraged on retrofit projects',
                ],
                'default_meta_title' => 'Landscaping Services in Oakville, Ontario',
                'default_meta_description' => 'Premium landscaping construction in Oakville. Interlocking, natural stone, concrete, and softscaping for Halton Region estates with consultation-led planning.',
                'default_og_title' => 'Oakville Landscaping Services | Lush Landscape Service',
                'default_og_description' => 'High-end hardscaping and softscaping for Oakville homeowners. Heritage-compliant materials and estate-quality craftsmanship.',
            ],

            // ─── Mississauga ────────────────────────────────────────────────
            'Mississauga' => [
                'city_summary' => 'Professional landscaping construction services across Mississauga, Ontario. From Port Credit waterfront to Erin Mills estates, our certified crews build driveways, patios, and outdoor living spaces engineered for Peel Region conditions.',
                'city_body' => [
                    'local_intro_extended' => "Mississauga is Ontario's third-largest city and one of the most diverse municipalities in Canada. Its residential landscape ranges from the century-old cottages and townhomes along the Port Credit waterfront to the sprawling executive estates of Lorne Park and the modern suburban developments of Churchill Meadows and Lisgar. Each neighbourhood brings distinct soil conditions, lot configurations, and homeowner expectations.\n\nOur Mississauga division works across every corner of the city. We understand that a Port Credit homeowner replacing a narrow cottage driveway has different priorities than an Erin Mills family building a full backyard patio with outdoor kitchen. We also navigate the Credit Valley Conservation Authority requirements that apply to properties near the Credit River corridor, and Peel Region's evolving stormwater management policies that increasingly encourage permeable hardscape solutions on residential lots.",
                    'neighborhoods_served' => [
                        'Port Credit', 'Lorne Park', 'Mineola', 'Clarkson',
                        'Erin Mills', 'Meadowvale', 'Churchill Meadows', 'Streetsville',
                        'Lisgar', 'Cooksville', 'Mississauga Valleys', 'Erindale',
                    ],
                    'why_local_para' => 'Mississauga sits on the Credit River watershed where clay-heavy soils, seasonal flooding, and Credit Valley Conservation regulations create engineering requirements that go beyond standard residential hardscaping. Our crews know which neighbourhoods sit on sandy glacial outwash where bases drain freely, and which sit on impervious Halton Till where French drains and catch basins are non-negotiable. That local soil intelligence prevents the settling and heaving problems that plague installations done without proper site assessment.',
                    'permit_summary' => "Mississauga requires a driveway entrance permit through the Transportation and Works Department for curb cut modifications. The City's Zoning By-Law 0225-2007 sets maximum front-yard hard-surface coverage limits that vary by zone. Retaining walls over 1.0 metre require a building permit from Mississauga Building Division. The Credit Valley Conservation Authority regulates all development within the Credit River watershed, including grading, fill placement, and stormwater discharge near regulated features.",
                ],
                'local_conditions_json' => [
                    'soil_type' => 'Halton Till clay dominant; sandy glacial outwash pockets near Credit River corridor',
                    'frost_depth' => '48 inches (1.2 m)',
                    'drainage' => 'Poor on clay-heavy lots away from river; moderate near sandy outwash zones',
                    'climate_zone' => '6a (Lake Ontario moderated)',
                    'growing_season' => 'Late April to mid-October',
                    'annual_snowfall' => '108 cm average',
                ],
                'municipal_notes_json' => [
                    'hard_surface_bylaw' => 'Zoning By-Law 0225-2007: max 50% front yard hard-surface coverage in most residential zones',
                    'driveway_width_max' => '6.0 m single residential; 7.3 m double-car',
                    'setback_requirements' => 'Side yard: 1.2 m minimum; corner lots may have additional restrictions',
                    'permit_authority' => 'City of Mississauga Building Division',
                    'conservation_authority' => 'Credit Valley Conservation (CVC) / Toronto and Region Conservation Authority (TRCA)',
                    'stormwater_requirements' => 'Peel Region encourages LID; CVC requires stormwater management plans for development near regulated areas',
                ],
                'default_meta_title' => 'Landscaping Services in Mississauga, Ontario',
                'default_meta_description' => 'Professional landscaping construction in Mississauga. Interlocking, concrete, retaining walls, and softscaping. CVC-compliant with consultation-led planning.',
                'default_og_title' => 'Mississauga Landscaping Services | Lush Landscape Service',
                'default_og_description' => 'Premium hardscaping and softscaping for Mississauga homeowners. From Port Credit to Erin Mills, built for Peel Region conditions.',
            ],

            // ─── Milton ─────────────────────────────────────────────────────
            'Milton' => [
                'city_summary' => 'Professional landscaping construction in Milton, Ontario. Serving new developments and established neighbourhoods between the Niagara Escarpment and Halton Hills with ICPI-certified craftsmanship.',
                'city_body' => [
                    'local_intro_extended' => "Milton has been one of Canada's fastest-growing towns for over a decade, transforming from a quiet Halton Hills community into a vibrant suburban centre. The town's explosive growth means thousands of newly built homes with builder-grade landscapes that homeowners are eager to upgrade with custom hardscaping, proper grading, and thoughtful softscaping.\n\nMilton's unique position between the Niagara Escarpment and the agricultural lowlands creates a varied terrain profile. Properties on the escarpment edge in areas like Old Milton and Mountainview sit on shallow soils over bedrock, requiring careful excavation planning. The newer subdivisions in Bristol, Scott, and Willmott are built on former agricultural clay land that demands robust drainage systems. Our crews calibrate every project specification to these ground realities.",
                    'neighborhoods_served' => [
                        'Old Milton', 'Timberlea', 'Harrison', 'Clarke',
                        'Bristol', 'Scott', 'Willmott', 'Dempsey',
                        'Mountainview', 'Bronte Meadows', 'Ford', 'Coates',
                    ],
                    'why_local_para' => "Milton's rapid development means many properties have compacted builder-grade subsoil that was not properly prepared for hardscape installations. We frequently encounter situations where a driveway or patio is specified over fill material that has not had adequate time to consolidate. Our Milton crews probe and test subgrade conditions before committing to a base specification, preventing the settling problems that surface within one or two freeze-thaw seasons on improperly assessed lots.",
                    'permit_summary' => "Milton requires a driveway entrance permit through the Town's Engineering Department for new or modified curb cuts. The Niagara Escarpment Development Control Area covers portions of the town, requiring Niagara Escarpment Commission approval for development within regulated zones. Conservation Halton and the Hamilton Conservation Authority regulate development near watercourses including Bronte Creek and Sixteen Mile Creek. Retaining walls over 1.0 metre require a building permit.",
                ],
                'local_conditions_json' => [
                    'soil_type' => 'Clay loam on lowland areas; thin soil over Queenston shale near escarpment',
                    'frost_depth' => '48 inches (1.2 m)',
                    'drainage' => 'Poor on clay-heavy new subdivisions; variable near escarpment bedrock',
                    'climate_zone' => '5b to 6a (transitional)',
                    'growing_season' => 'Early May to early October',
                    'annual_snowfall' => '130 cm average',
                ],
                'municipal_notes_json' => [
                    'hard_surface_bylaw' => 'Zoning By-Law 144-2003: front yard coverage limits by zone; new developments may have registered subdivision agreements',
                    'driveway_width_max' => '6.0 m residential',
                    'setback_requirements' => 'Side yard: 1.2 m minimum; escarpment lots may have additional setbacks',
                    'permit_authority' => 'Town of Milton Building Division',
                    'conservation_authority' => 'Conservation Halton / Hamilton Conservation Authority (HCA)',
                    'stormwater_requirements' => 'New subdivisions typically have stormwater management ponds; individual lot LID encouraged by Halton Region',
                ],
                'default_meta_title' => 'Landscaping Services in Milton, Ontario',
                'default_meta_description' => 'Professional landscaping construction in Milton. Interlocking driveways, patios, retaining walls, and softscaping for new builds and established homes.',
                'default_og_title' => 'Milton Landscaping Services | Lush Landscape Service',
                'default_og_description' => 'Premium hardscaping and softscaping for Milton homeowners. New builds and upgrades between the escarpment and Halton Hills.',
            ],

            // ─── Toronto ────────────────────────────────────────────────────
            'Toronto' => [
                'city_summary' => 'Professional landscaping construction across Toronto, Ontario. From midtown estates to Etobicoke bungalows, our crews navigate Toronto bylaws, ravine protection zones, and urban lot constraints.',
                'city_body' => [
                    'local_intro_extended' => "Toronto's residential landscape is as diverse as the city itself. Narrow Victorian-lot frontages in the Annex and Cabbagetown, mid-century bungalow properties in Etobicoke and Scarborough, and estate-scale lots in Forest Hill and the Bridle Path each demand completely different hardscaping approaches. Our Toronto division has worked across every district of the city and understands the unique constraints that come with urban landscaping.\n\nToronto's ravine protection bylaws, tree preservation orders, and lot coverage regulations are among the strictest in Ontario. Many desirable neighbourhoods sit within the Toronto and Region Conservation Authority's regulated area, adding an extra layer of permitting requirements. Our crews manage these approvals as part of the project scope, ensuring that your investment is fully permitted and compliant before the first paver is laid.",
                    'neighborhoods_served' => [
                        'Etobicoke', 'North York', 'Scarborough', 'Forest Hill',
                        'Lawrence Park', 'The Kingsway', 'Leaside', 'Danforth',
                        'High Park', 'Rosedale', 'Midtown', 'Willowdale',
                    ],
                    'why_local_para' => "Toronto's lot coverage bylaws cap the total impervious surface area on residential properties, meaning that a driveway expansion in one area may require offsetting with permeable surfaces elsewhere on the lot. Our crews calculate coverage ratios before design begins and can recommend permeable paver systems, rain gardens, or reduced-footprint layouts that achieve the homeowner's goals within the city's coverage limits.",
                    'permit_summary' => "Toronto requires a Right-of-Way permit for any new or modified driveway entrance through Toronto Building. The Ravine and Natural Feature Protection By-Law controls development on properties abutting ravines, requiring a permit for grading, tree removal, or structure construction within 10 metres of the ravine top-of-bank. The city's tree preservation by-law protects trees over 30 cm diameter on private property. TRCA regulates development near watercourses and within the regulated flood plain. Retaining walls over 1.0 metre require a building permit.",
                ],
                'local_conditions_json' => [
                    'soil_type' => 'Variable: sandy glacial deposits in Scarborough Bluffs area; heavy clay till in North York and Etobicoke',
                    'frost_depth' => '48 inches (1.2 m)',
                    'drainage' => 'Highly variable by neighbourhood; combined sewer areas require careful stormwater management',
                    'climate_zone' => '6a (urban heat island effect)',
                    'growing_season' => 'Late April to late October',
                    'annual_snowfall' => '108 cm average',
                ],
                'municipal_notes_json' => [
                    'hard_surface_bylaw' => 'Toronto Zoning By-Law 569-2013: lot coverage and soft landscaping minimums by zone',
                    'driveway_width_max' => '3.0 m on lots under 6.0 m wide; 5.2 m on wider lots; varies by zone',
                    'setback_requirements' => 'Front yard: minimum 6.0 m; side yard: 0.9 m to 1.8 m depending on zone',
                    'permit_authority' => 'Toronto Building',
                    'conservation_authority' => 'Toronto and Region Conservation Authority (TRCA)',
                    'stormwater_requirements' => 'City Green Standard Tier 1 required; lot-level retention encouraged for all new impervious surfaces',
                ],
                'default_meta_title' => 'Landscaping Services in Toronto, Ontario',
                'default_meta_description' => 'Professional landscaping construction in Toronto. Interlocking, concrete, retaining walls, and softscaping. Ravine-compliant with consultation-led planning.',
                'default_og_title' => 'Toronto Landscaping Services | Lush Landscape Service',
                'default_og_description' => 'Premium hardscaping and softscaping for Toronto homeowners. Urban lot expertise, ravine compliance, and heritage sensitivity.',
            ],

            // ─── Vaughan ────────────────────────────────────────────────────
            'Vaughan' => [
                'city_summary' => 'Professional landscaping construction in Vaughan, Ontario. From Kleinburg estates to Woodbridge custom homes, our crews deliver hardscaping and softscaping built for York Region conditions.',
                'city_body' => [
                    'local_intro_extended' => "Vaughan is one of the fastest-growing cities in the Greater Toronto Area, with a residential landscape that ranges from the heritage village character of Kleinburg to the large custom-built homes of Woodbridge and the modern family subdivisions of Vellore Village and Maple. The city's homeowners have a strong appetite for premium outdoor living, particularly Mediterranean-inspired courtyards, expansive pool surrounds, and detailed front-yard hardscaping.\n\nOur Vaughan division understands the community's aesthetic preferences and engineering requirements equally. The Humber River watershed runs through the city, placing many properties under Toronto and Region Conservation Authority regulation. Vaughan's clay-dominant soils require deeper base preparations than sandier regions, and our specifications reflect that reality on every project.",
                    'neighborhoods_served' => [
                        'Woodbridge', 'Kleinburg', 'Maple', 'Thornhill',
                        'Concord', 'Vellore Village', 'Patterson', 'Sonoma Heights',
                        'Islington Woods', 'Elder Mills', 'Carrville', 'Brownridge',
                    ],
                    'why_local_para' => "Vaughan's Italian-Canadian community, particularly concentrated in Woodbridge, has a strong tradition of investing in high-quality front-yard hardscaping and elaborate backyard entertaining spaces. Our crews understand the material preferences, design patterns, and quality standards that this community expects. We also know that many Vaughan lots have rear-yard grading challenges due to the subdivision-era stormwater management ponds and swales that restrict backyard layouts.",
                    'permit_summary' => "Vaughan requires a driveway entrance and boulevard works permit through the City's Engineering Department. The Humber River watershed and several tributary corridors place many properties within TRCA-regulated areas, requiring a permit for development, grading, or fill placement. The City of Vaughan Zoning By-Law 1-88 sets front yard coverage and setback requirements. Retaining walls over 1.0 metre in retained height require a building permit.",
                ],
                'local_conditions_json' => [
                    'soil_type' => 'Heavy clay till (Newmarket Till) dominant across most of the city',
                    'frost_depth' => '48 inches (1.2 m)',
                    'drainage' => 'Poor natural drainage on clay soils; many lots have rear-yard swales and catch basins',
                    'climate_zone' => '5b',
                    'growing_season' => 'Early May to early October',
                    'annual_snowfall' => '130 cm average',
                ],
                'municipal_notes_json' => [
                    'hard_surface_bylaw' => 'Zoning By-Law 1-88: front yard coverage limits; corner lots have additional restrictions',
                    'driveway_width_max' => '6.0 m residential single; subject to boulevard frontage ratio',
                    'setback_requirements' => 'Front yard: 7.5 m typical; side yard: 1.2 m minimum; garage setback: 6.0 m',
                    'permit_authority' => 'City of Vaughan Building Standards Department',
                    'conservation_authority' => 'Toronto and Region Conservation Authority (TRCA)',
                    'stormwater_requirements' => 'Most subdivisions have SWM ponds; rear-yard grading restrictions common; LID encouraged',
                ],
                'default_meta_title' => 'Landscaping Services in Vaughan, Ontario',
                'default_meta_description' => 'Professional landscaping construction in Vaughan. Interlocking, concrete, retaining walls, and softscaping for Woodbridge, Kleinburg, and Maple.',
                'default_og_title' => 'Vaughan Landscaping Services | Lush Landscape Service',
                'default_og_description' => 'Premium hardscaping and softscaping for Vaughan homeowners. Custom designs for Woodbridge, Kleinburg, Maple, and Thornhill.',
            ],

            // ─── Richmond Hill ──────────────────────────────────────────────
            'Richmond Hill' => [
                'city_summary' => 'Professional landscaping construction in Richmond Hill, Ontario. From South Richvale estates to Oak Ridges Moraine properties, our crews deliver hardscaping and softscaping tailored to York Region conditions.',
                'city_body' => [
                    'local_intro_extended' => "Richmond Hill stretches from the urban density of Highway 7 corridor northward to the environmentally sensitive Oak Ridges Moraine, creating a residential landscape that shifts dramatically from compact townhouse communities in the south to estate-scale lots in the north. The town's homeowners are increasingly investing in outdoor living upgrades as property values continue to rise.\n\nOur Richmond Hill projects account for the town's split personality. Southern properties on former agricultural clay require deep base installations and aggressive drainage solutions. Northern properties near the Oak Ridges Moraine sit on sandy glacial deposits that drain exceptionally well but may require additional compaction effort. The TRCA's regulatory authority over the Rouge River and Don River headwaters adds permitting considerations for many properties on the town's eastern edge.",
                    'neighborhoods_served' => [
                        'South Richvale', 'Oak Ridges', 'Bayview Hill', 'Jefferson',
                        'Westbrook', 'Mill Pond', 'Crosby', 'Harding',
                        'Langstaff', 'Observatory', 'Doncrest', 'Palmer',
                    ],
                    'why_local_para' => "Richmond Hill's Oak Ridges Moraine Conservation Plan places strict development controls on properties within the moraine boundary, affecting grading, fill placement, and impervious surface coverage. Our crews know which properties fall within the moraine plan area and which do not, and we adjust project scope and permitting strategies accordingly. Properties just south of the moraine boundary have different soil conditions and fewer regulatory constraints, and we tailor specifications to match.",
                    'permit_summary' => 'Richmond Hill requires a boulevard and entrance permit for driveway modifications. The Oak Ridges Moraine Conservation Plan regulates development on properties within the moraine boundary, with restrictions on impervious surface coverage and grading. TRCA regulates development near the Rouge River, Don River headwaters, and their tributaries. The City of Richmond Hill Zoning By-Law sets front-yard coverage and setback requirements. Retaining walls over 1.0 metre require a building permit.',
                ],
                'local_conditions_json' => [
                    'soil_type' => 'Heavy clay in southern areas; sandy glacial deposits on Oak Ridges Moraine',
                    'frost_depth' => '48 inches (1.2 m)',
                    'drainage' => 'Poor on southern clay; excellent on moraine sands (but groundwater protection applies)',
                    'climate_zone' => '5b',
                    'growing_season' => 'Early May to early October',
                    'annual_snowfall' => '135 cm average',
                ],
                'municipal_notes_json' => [
                    'hard_surface_bylaw' => 'Zoning By-Law 2523: front yard coverage limits; Oak Ridges Moraine has additional impervious surface restrictions',
                    'driveway_width_max' => '6.0 m residential',
                    'setback_requirements' => 'Front yard: 7.5 m typical; side yard: 1.2 m minimum',
                    'permit_authority' => 'City of Richmond Hill Planning and Building Services',
                    'conservation_authority' => 'Toronto and Region Conservation Authority (TRCA)',
                    'stormwater_requirements' => 'Oak Ridges Moraine: zero increase in impervious coverage; rest of town: LID encouraged',
                ],
                'default_meta_title' => 'Landscaping Services in Richmond Hill, Ontario',
                'default_meta_description' => 'Professional landscaping construction in Richmond Hill. Interlocking, concrete, retaining walls, and softscaping for York Region properties.',
                'default_og_title' => 'Richmond Hill Landscaping Services | Lush Landscape Service',
                'default_og_description' => 'Premium hardscaping and softscaping for Richmond Hill homeowners. Oak Ridges Moraine expertise and York Region compliance.',
            ],

            // ─── Georgetown ─────────────────────────────────────────────────
            'Georgetown' => [
                'city_summary' => 'Professional landscaping construction in Georgetown (Halton Hills), Ontario. Small-town character with premium hardscaping and softscaping built for Credit River watershed conditions.',
                'city_body' => [
                    'local_intro_extended' => "Georgetown is the largest community within the Town of Halton Hills, combining small-town charm with growing suburban development. The Credit River runs through the heart of Georgetown, making Credit Valley Conservation Authority regulations a factor on many residential properties. The town's mix of historic downtown homes, mid-century established neighbourhoods, and modern subdivisions in the south end provides a diverse range of project types.\n\nOur Georgetown crews appreciate the community's character and scale. Projects here tend to be more personal than in larger suburban centres. Homeowners know their neighbours, take pride in their streetscapes, and expect work that enhances the neighbourhood's overall appearance. We deliver the same material quality and engineering standards as our larger-city projects while respecting Georgetown's small-town feel.",
                    'neighborhoods_served' => [
                        'Georgetown Downtown', 'Georgetown South', 'Hungry Hollow',
                        'Glen Williams', 'Silver Creek', 'Cedarvale', 'Georgetown North',
                        'Stewarttown', 'Limehouse', 'Norval',
                    ],
                    'why_local_para' => "Georgetown's Credit River corridor means that many residential properties fall within CVC-regulated areas where grading, fill placement, and stormwater management require conservation authority approval before construction begins. Our crews handle CVC pre-consultation and permit applications as part of the project scope, preventing the costly delays that homeowners experience when they discover regulatory requirements mid-project.",
                    'permit_summary' => 'Halton Hills requires a driveway entrance permit for new or modified curb cuts. Credit Valley Conservation Authority regulates development near the Credit River, Silver Creek, and their tributaries, requiring permits for grading, fill, and construction within regulated areas. The Niagara Escarpment Development Control Area covers portions of the Halton Hills, particularly near Glen Williams and Limehouse. Retaining walls over 1.0 metre require a building permit from Halton Hills Building Department.',
                ],
                'local_conditions_json' => [
                    'soil_type' => 'Silty clay loam with glacial cobble deposits near Credit River corridor',
                    'frost_depth' => '48 inches (1.2 m)',
                    'drainage' => 'Moderate near river corridor; poor on clay-heavy lots away from natural drainage',
                    'climate_zone' => '5b',
                    'growing_season' => 'Early May to early October',
                    'annual_snowfall' => '140 cm average',
                ],
                'municipal_notes_json' => [
                    'hard_surface_bylaw' => 'Halton Hills Zoning By-Law 2010-0050: front yard coverage limits by zone',
                    'driveway_width_max' => '6.0 m residential',
                    'setback_requirements' => 'Front yard: 6.0 m to 7.5 m depending on zone; side yard: 1.2 m',
                    'permit_authority' => 'Town of Halton Hills Building Services',
                    'conservation_authority' => 'Credit Valley Conservation (CVC)',
                    'stormwater_requirements' => 'CVC regulated areas require stormwater management plans; LID encouraged throughout',
                ],
                'default_meta_title' => 'Landscaping Services in Georgetown, Ontario',
                'default_meta_description' => 'Professional landscaping construction in Georgetown (Halton Hills). Interlocking, concrete, retaining walls, and softscaping. CVC-compliant.',
                'default_og_title' => 'Georgetown Landscaping Services | Lush Landscape Service',
                'default_og_description' => 'Premium hardscaping and softscaping for Georgetown and Halton Hills homeowners. Credit River watershed expertise.',
            ],

            // ─── Brampton ───────────────────────────────────────────────────
            'Brampton' => [
                'city_summary' => 'Professional landscaping construction across Brampton, Ontario. From Mount Pleasant new builds to Heart Lake established homes, our crews deliver hardscaping and softscaping engineered for Peel Region clay soils.',
                'city_body' => [
                    'local_intro_extended' => "Brampton is one of Canada's fastest-growing cities, with new subdivisions expanding steadily northward and westward. The city's residential landscape is dominated by relatively new housing stock built in the last 20 to 30 years, which means thousands of homes with builder-grade concrete driveways and minimal backyard landscaping that homeowners are ready to upgrade.\n\nOur Brampton division handles the full spectrum of projects: removing and replacing failed builder-grade concrete with premium interlocking pavers, building backyard patios on the clay-heavy lots that characterize most Brampton subdivisions, and installing retaining walls to address the grade changes that developers leave for homeowners to resolve. Brampton's clay soils are among the most challenging in the GTA for hardscape installation, and our specifications reflect that reality with deeper bases and more aggressive drainage solutions than sandier regions require.",
                    'neighborhoods_served' => [
                        'Heart Lake', 'Mount Pleasant', 'Castlemore', 'Brampton North',
                        'Springdale', 'Sandalwood', 'Fletchers Creek', 'Bram West',
                        'Bramalea', 'Central Brampton', 'Gore Meadows', 'Credit Valley',
                    ],
                    'why_local_para' => "Brampton's heavy clay soils have the highest shrink-swell potential in the GTA. During wet seasons, clay expands and heaves paver surfaces upward. During dry summers, it shrinks and creates voids under base material. Contractors unfamiliar with this cycle build to standard specifications and see callbacks within two years. Our Brampton crews specify extra base depth, use open-graded aggregate that allows clay moisture to dissipate rather than accumulate, and install edge restraints anchored below the frost line to prevent seasonal migration.",
                    'permit_summary' => "Brampton requires a driveway entrance permit through the City's Works and Transportation Department for any new or modified curb cut. The City of Brampton Zoning By-Law sets front-yard hard-surface coverage limits. Properties near the Etobicoke Creek, Credit River tributaries, and Humber River headwaters fall within CVC or TRCA regulated areas requiring conservation authority permits for grading and development. Retaining walls over 1.0 metre in retained height require a building permit.",
                ],
                'local_conditions_json' => [
                    'soil_type' => 'Heavy clay till (Halton Till) dominant across the city; high shrink-swell potential',
                    'frost_depth' => '48 inches (1.2 m)',
                    'drainage' => 'Poor; clay soils require engineered drainage on virtually all hardscape projects',
                    'climate_zone' => '5b',
                    'growing_season' => 'Early May to early October',
                    'annual_snowfall' => '125 cm average',
                ],
                'municipal_notes_json' => [
                    'hard_surface_bylaw' => 'Brampton Zoning By-Law 270-2004: max 50% front yard coverage in most residential zones',
                    'driveway_width_max' => '6.0 m single residential; 7.3 m double-wide with approval',
                    'setback_requirements' => 'Front yard: 6.0 m to 7.5 m; side yard: 1.2 m minimum',
                    'permit_authority' => 'City of Brampton Building Division',
                    'conservation_authority' => 'Credit Valley Conservation (CVC) / Toronto and Region Conservation Authority (TRCA)',
                    'stormwater_requirements' => 'Peel Region encourages on-site retention; CVC and TRCA require stormwater management plans in regulated areas',
                ],
                'default_meta_title' => 'Landscaping Services in Brampton, Ontario',
                'default_meta_description' => 'Professional landscaping construction in Brampton. Interlocking, concrete, retaining walls, and softscaping engineered for Peel Region clay soils.',
                'default_og_title' => 'Brampton Landscaping Services | Lush Landscape Service',
                'default_og_description' => 'Premium hardscaping and softscaping for Brampton homeowners. Clay-soil expertise, new build upgrades, and full-yard transformations.',
            ],

        ];
    }
}
