<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\MediaAsset;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MediaMetadataSeeder extends Seeder
{
    public function run(): void
    {
        $services = Service::all();
        $cities = City::where('status', 'published')->get();

        foreach ($cities as $city) {
            foreach ($services as $service) {
                $this->createServiceCityMedia($service, $city);
            }
        }

        // Process step images (shared across cities, one set per service)
        foreach ($services as $service) {
            $this->createProcessStepMedia($service);
        }
    }

    // 1 hero + 4 gallery images per service-city combination
    private function createServiceCityMedia(Service $service, City $city): void
    {
        $serviceSlug = Str::slug($service->name);
        $citySlug = Str::slug($city->name);
        $cityName = $city->name;
        $serviceName = $service->name;

        // Hero image
        MediaAsset::updateOrCreate(
            ['canonical_filename' => "{$serviceSlug}-{$citySlug}-hero.jpg"],
            [
                'internal_title' => "{$serviceName} in {$cityName} - Hero",
                'disk' => 'public',
                'path' => "services/{$serviceSlug}/{$citySlug}/hero.jpg",
                'media_type' => 'image',
                'editorial_class' => 'hero',
                'mime_type' => 'image/jpeg',
                'extension' => 'jpg',
                'description' => "Hero image for {$serviceName} service page in {$cityName}, Ontario. Showcases a completed project in {$cityName}.",
                'default_alt_text' => "{$serviceName} project completed in {$cityName}, Ontario by Lush Landscape Service",
                'default_caption' => "Professional {$serviceName} in {$cityName} by Lush Landscape Service",
                'image_purpose' => 'hero',
                'location_city' => $cityName,
                'location_region' => 'Ontario',
                'social_preview_eligible' => true,
                'schema_eligible' => true,
                'tags' => [$serviceSlug, $citySlug, 'hero', 'landscaping'],
                'status' => 'draft',
            ]
        );

        // Gallery images (4 per service-city)
        $galleryDescriptions = $this->getGalleryDescriptions($serviceName, $cityName);
        for ($i = 1; $i <= 4; $i++) {
            MediaAsset::updateOrCreate(
                ['canonical_filename' => "{$serviceSlug}-{$citySlug}-gallery-{$i}.jpg"],
                [
                    'internal_title' => "{$serviceName} in {$cityName} - Gallery {$i}",
                    'disk' => 'public',
                    'path' => "services/{$serviceSlug}/{$citySlug}/gallery-{$i}.jpg",
                    'media_type' => 'image',
                    'editorial_class' => 'gallery',
                    'mime_type' => 'image/jpeg',
                    'extension' => 'jpg',
                    'description' => $galleryDescriptions[$i - 1] ?? "Gallery image {$i} for {$serviceName} in {$cityName}",
                    'default_alt_text' => $this->getGalleryAlt($serviceName, $cityName, $i),
                    'default_caption' => $this->getGalleryCaption($serviceName, $cityName, $i),
                    'image_purpose' => 'gallery',
                    'location_city' => $cityName,
                    'location_region' => 'Ontario',
                    'social_preview_eligible' => false,
                    'schema_eligible' => true,
                    'tags' => [$serviceSlug, $citySlug, 'gallery', "gallery-{$i}"],
                    'status' => 'draft',
                ]
            );
        }
    }

    // 4 process step images per service (shared across all cities)
    private function createProcessStepMedia(Service $service): void
    {
        $serviceSlug = Str::slug($service->name);
        $serviceName = $service->name;
        $steps = $this->getProcessSteps($serviceName);

        foreach ($steps as $i => $step) {
            $num = $i + 1;
            MediaAsset::updateOrCreate(
                ['canonical_filename' => "{$serviceSlug}-process-step-{$num}.jpg"],
                [
                    'internal_title' => "{$serviceName} - Process Step {$num}: {$step['title']}",
                    'disk' => 'public',
                    'path' => "services/{$serviceSlug}/process/step-{$num}.jpg",
                    'media_type' => 'image',
                    'editorial_class' => 'informative',
                    'mime_type' => 'image/jpeg',
                    'extension' => 'jpg',
                    'description' => $step['description'],
                    'default_alt_text' => "{$serviceName} process step {$num}: {$step['title']}",
                    'default_caption' => "Step {$num}: {$step['title']}",
                    'image_purpose' => 'informative',
                    'social_preview_eligible' => false,
                    'schema_eligible' => false,
                    'tags' => [$serviceSlug, 'process', "step-{$num}"],
                    'status' => 'draft',
                ]
            );
        }
    }

    // Context-aware gallery descriptions per service
    private function getGalleryDescriptions(string $service, string $city): array
    {
        $map = [
            'Interlocking Driveways' => [
                "Before and after of an interlocking driveway replacement in {$city}",
                "Herringbone pattern interlocking driveway with soldier course border in {$city}",
                "Close-up detail of premium paver joints and edge restraint on a {$city} driveway",
                "Completed interlocking driveway with integrated walkway in {$city} neighbourhood",
            ],
            'Interlocking Patios & Backyard Living' => [
                "Multi-level interlocking patio with fire pit and seating wall in {$city}",
                "Outdoor kitchen island integrated into paver patio in {$city} backyard",
                "Overhead view of interlocking patio layout with lighting in {$city}",
                "Evening shot of a completed patio with landscape lighting in {$city}",
            ],
            'Walkways & Steps' => [
                "Front entry walkway with matching steps and landing in {$city}",
                "Curved garden walkway with accent border pavers in {$city}",
                "Paver steps with non-slip tread and handrail in {$city}",
                "Completed walkway connecting driveway to backyard patio in {$city}",
            ],
            'Natural Stone & Flagstone' => [
                "Hand-cut flagstone patio with natural stone dust joints in {$city}",
                "Armour stone retaining feature with integrated planting in {$city}",
                "Ontario limestone walkway with irregular edges in {$city} garden",
                "Natural stone accent wall with integrated lighting in {$city}",
            ],
            'Porcelain Pavers' => [
                "Modern grey porcelain paver patio with clean lines in {$city}",
                "Wood-look porcelain pavers on pool surround in {$city}",
                "Pedestal-mounted porcelain pavers on rooftop terrace in {$city}",
                "Close-up of 20mm porcelain paver texture and colour in {$city} installation",
            ],
            'Concrete Driveways' => [
                "Stamped concrete driveway in ashlar slate pattern in {$city}",
                "Exposed aggregate concrete driveway with broom-finish borders in {$city}",
                "Fresh concrete pour with rebar reinforcement visible on {$city} project",
                "Completed broom-finish concrete driveway with control joints in {$city}",
            ],
            'Concrete Patios & Walkways' => [
                "Stamped concrete patio in random stone pattern with colour hardener in {$city}",
                "Exposed aggregate concrete walkway with steel edging in {$city}",
                "Acid-stained concrete patio with multi-toned finish in {$city} backyard",
                "Concrete patio with expansion joint detail at house foundation in {$city}",
            ],
            'Interlock Restoration & Sealing' => [
                "Before and after of interlock restoration on a {$city} driveway",
                "Hot-water pressure washing removing algae from pavers in {$city}",
                "Polymeric sand application during interlock restoration in {$city}",
                "Wet-look sealer applied to restored interlocking patio in {$city}",
            ],
            'Interlock Repair (Lift & Relay)' => [
                "Sunken pavers before lift-and-relay repair on {$city} driveway",
                "Exposed HPB base during paver repair process in {$city}",
                "Compaction of new base material during interlock repair in {$city}",
                "Completed lift-and-relay repair with seamless paver match in {$city}",
            ],
            'Retaining Walls' => [
                "Armour stone retaining wall with terraced garden beds in {$city}",
                "Precast concrete block retaining wall with geogrid layers in {$city}",
                "Completed retaining wall with cap stones and drainage outlet in {$city}",
                "Multi-tier retaining wall creating usable backyard space in {$city}",
            ],
            'Sod Installation & Grading' => [
                "Precision grading with laser level on {$city} lawn renovation",
                "Triple-Mix topsoil spread and raked to final grade in {$city}",
                "Fresh Kentucky Bluegrass sod being laid on prepared grade in {$city}",
                "Completed sod installation with established root contact in {$city}",
            ],
            'Artificial Turf' => [
                "Backyard artificial turf play area installation in {$city}",
                "Pet-friendly artificial turf with antimicrobial infill in {$city}",
                "Putting green artificial turf with contoured surface in {$city}",
                "Front yard artificial turf accent with natural stone border in {$city}",
            ],
            'Garden Design & Planting' => [
                "Four-season perennial garden bed with steel edging in {$city}",
                "Pollinator garden with native Ontario species in {$city}",
                "Foundation planting upgrade with ornamental grasses in {$city}",
                "Privacy screening hedge planting along property line in {$city}",
            ],
            'Landscape Lighting' => [
                "Path lighting along interlocking walkway at dusk in {$city}",
                "Uplighting on mature tree creating canopy glow in {$city}",
                "Architectural wall wash lighting on stone feature in {$city}",
                "Complete landscape lighting design showcasing garden and patio in {$city}",
            ],
        ];

        return $map[$service] ?? [
            "Project photo 1 for {$service} in {$city}",
            "Project photo 2 for {$service} in {$city}",
            "Project photo 3 for {$service} in {$city}",
            "Project photo 4 for {$service} in {$city}",
        ];
    }

    // SEO-optimized alt text for gallery images
    private function getGalleryAlt(string $service, string $city, int $num): string
    {
        $alts = [
            1 => "{$service} project before and after in {$city}, Ontario",
            2 => "Detail view of {$service} materials and craftsmanship in {$city}",
            3 => "Work in progress on {$service} project in {$city}, Ontario",
            4 => "Completed {$service} installation by Lush Landscape in {$city}",
        ];

        return $alts[$num] ?? "{$service} gallery image {$num} in {$city}, Ontario";
    }

    // Contextual captions for gallery images
    private function getGalleryCaption(string $service, string $city, int $num): string
    {
        $captions = [
            1 => "Transformation: {$service} project in {$city}",
            2 => "Quality materials and precision installation in {$city}",
            3 => "Our {$city} crew at work on this {$service} project",
            4 => "The finished result: {$service} in {$city}",
        ];

        return $captions[$num] ?? "{$service} in {$city} - Photo {$num}";
    }

    // Process step definitions per service (4 steps each)
    private function getProcessSteps(string $service): array
    {
        $steps = [
            'Interlocking Driveways' => [
                ['title' => 'Site Assessment & Excavation', 'description' => 'Evaluate soil conditions, establish grades, and excavate to the required depth for the aggregate base.'],
                ['title' => 'Base Preparation & Compaction', 'description' => 'Install and compact Granular A sub-base in lifts, followed by HPB levelling course screeded to final grade.'],
                ['title' => 'Paver Installation & Pattern', 'description' => 'Lay pavers in the selected bond pattern with soldier-course border and edge restraint anchored below frost line.'],
                ['title' => 'Finishing & Compaction', 'description' => 'Apply polymeric sand to all joints, compact the surface, and clean excess sand for a finished result.'],
            ],
            'Interlocking Patios & Backyard Living' => [
                ['title' => 'Design Consultation', 'description' => 'On-site meeting to discuss layout, features, materials, and budget for your outdoor living space.'],
                ['title' => 'Excavation & Drainage', 'description' => 'Excavate to required depth, install drainage provisions, and compact aggregate base with proper grade.'],
                ['title' => 'Feature Construction', 'description' => 'Build seating walls, fire pit pad, kitchen island base, and other features before paver installation.'],
                ['title' => 'Paver Installation & Finishing', 'description' => 'Install pavers, apply polymeric sand, compact, and complete lighting and final grading.'],
            ],
            'Walkways & Steps' => [
                ['title' => 'Layout & Excavation', 'description' => 'Mark the walkway path, excavate to required depth, and prepare step footings below the frost line.'],
                ['title' => 'Step Construction', 'description' => 'Pour reinforced concrete step cores, cure, and prepare for paver or stone cladding.'],
                ['title' => 'Walkway Base & Paving', 'description' => 'Compact aggregate base, screed levelling course, and install pavers in the selected pattern.'],
                ['title' => 'Finishing Details', 'description' => 'Apply polymeric sand, install edge restraint, compact, and add non-slip treads to steps.'],
            ],
            'Natural Stone & Flagstone' => [
                ['title' => 'Stone Selection', 'description' => 'Visit the quarry yard to select flagstone, armour stone, or ledgerock pieces for colour and thickness consistency.'],
                ['title' => 'Base Preparation', 'description' => 'Excavate and compact aggregate base to the depth required for the stone thickness and setting method.'],
                ['title' => 'Stone Placement', 'description' => 'Hand-cut and fit each stone piece, set in mortar or on levelling screed depending on application.'],
                ['title' => 'Jointing & Finishing', 'description' => 'Fill joints with polymeric sand or stone dust, clean stone surfaces, and seal if specified.'],
            ],
            'Porcelain Pavers' => [
                ['title' => 'Site Assessment', 'description' => 'Evaluate the installation surface, drainage requirements, and determine pedestal vs aggregate base system.'],
                ['title' => 'Base System Installation', 'description' => 'Install pedestal supports or compacted aggregate base with precision levelling for tight tolerances.'],
                ['title' => 'Porcelain Paver Placement', 'description' => 'Place 20mm porcelain pavers with consistent joint spacing using spacer tabs and levelling systems.'],
                ['title' => 'Joint Filling & Inspection', 'description' => 'Fill joints with open-graded material, inspect for level consistency, and clean tile surfaces.'],
            ],
            'Concrete Driveways' => [
                ['title' => 'Excavation & Forming', 'description' => 'Excavate to required depth, install compacted aggregate base, and set lumber forms to grade and shape.'],
                ['title' => 'Reinforcement', 'description' => 'Place 10M rebar on 400mm centres with chairs, add fibre-mesh to the mix order, and set control joint locations.'],
                ['title' => 'Pour & Finish', 'description' => 'Pour 32 MPa air-entrained concrete, screed, float, and apply the selected decorative finish.'],
                ['title' => 'Cure & Seal', 'description' => 'Apply curing compound, allow proper hydration period, and seal with UV-resistant sealer if specified.'],
            ],
            'Concrete Patios & Walkways' => [
                ['title' => 'Design & Layout', 'description' => 'Finalize pattern, colours, and layout on site. Set forms with proper drainage grade away from the house.'],
                ['title' => 'Base & Reinforcement', 'description' => 'Compact aggregate base, install vapour barrier, and place rebar reinforcement grid.'],
                ['title' => 'Pour & Stamp', 'description' => 'Pour concrete, apply colour hardener and release agent, and stamp the selected pattern while concrete is workable.'],
                ['title' => 'Detail & Seal', 'description' => 'Cut control joints, wash release agent residue, apply two coats of UV-resistant sealer, and final cleanup.'],
            ],
            'Interlock Restoration & Sealing' => [
                ['title' => 'Assessment', 'description' => 'Inspect the existing paver surface for structural issues, staining, weed growth, and joint condition.'],
                ['title' => 'Pressure Washing', 'description' => 'Hot-water pressure wash at 3,000+ PSI with surface-cleaning attachments to remove all embedded contaminants.'],
                ['title' => 'Joint Restoration', 'description' => 'Allow surface to dry, then apply new polymeric sand to all joints and activate per manufacturer specifications.'],
                ['title' => 'Sealer Application', 'description' => 'Apply UV-resistant sealer in the selected finish (matte, satin, or wet-look) using professional spray equipment.'],
            ],
            'Interlock Repair (Lift & Relay)' => [
                ['title' => 'Diagnosis', 'description' => 'Identify the root cause of paver displacement: base failure, drainage issue, root intrusion, or compaction inadequacy.'],
                ['title' => 'Paver Removal', 'description' => 'Carefully extract affected pavers and set aside for re-use, preserving their original layout and orientation.'],
                ['title' => 'Base Correction', 'description' => 'Excavate failed base material, install fresh HPB aggregate, and compact in lifts to 95% Standard Proctor density.'],
                ['title' => 'Relay & Finish', 'description' => 'Re-lay original pavers in their original pattern, apply new polymeric sand, and compact to match surrounding surface.'],
            ],
            'Retaining Walls' => [
                ['title' => 'Engineering & Design', 'description' => 'Assess the grade change, design the wall structure, and prepare engineering drawings for walls over 1.0 metre.'],
                ['title' => 'Excavation & Footing', 'description' => 'Excavate the wall trench, compact the footing base, and install the first course level and plumb.'],
                ['title' => 'Wall Construction', 'description' => 'Build wall courses with geogrid reinforcement at calculated intervals, backfill with clear stone, and install filter fabric.'],
                ['title' => 'Drainage & Capping', 'description' => 'Connect weeping tile to outlet, install cap stones, backfill and compact behind the wall, and final grading.'],
            ],
            'Sod Installation & Grading' => [
                ['title' => 'Grade Assessment', 'description' => 'Survey existing grades, identify drainage issues, and establish finished grade elevations with laser level.'],
                ['title' => 'Subgrade Preparation', 'description' => 'Strip existing material, shape subgrade to design elevations, and compact to prevent future settlement.'],
                ['title' => 'Topsoil & Final Grade', 'description' => 'Spread 4 to 6 inches of Triple-Mix, rake to final grade with proper drainage slope, and roll to firm surface.'],
                ['title' => 'Sod Installation', 'description' => 'Lay fresh-cut Kentucky Bluegrass sod, roll for root contact, apply starter fertilizer, and initial watering.'],
            ],
            'Artificial Turf' => [
                ['title' => 'Excavation', 'description' => 'Remove existing soil or turf to the required depth to accommodate aggregate base and turf thickness.'],
                ['title' => 'Base Installation', 'description' => 'Install compacted aggregate base with drainage grade, lay geotextile separation fabric over subgrade.'],
                ['title' => 'Turf Installation', 'description' => 'Roll out turf, trim to shape, seam sections, and secure with landscape spikes around the perimeter.'],
                ['title' => 'Infill & Finishing', 'description' => 'Spread infill material evenly, brush turf fibres upright, and inspect drainage performance.'],
            ],
            'Garden Design & Planting' => [
                ['title' => 'Site Assessment & Design', 'description' => 'Evaluate sun exposure, soil type, drainage, and existing vegetation. Create a planting plan with cultivar specifications.'],
                ['title' => 'Bed Preparation', 'description' => 'Excavate compacted subsoil, amend with premium planting mix, and install steel or aluminum edging for clean bed lines.'],
                ['title' => 'Planting', 'description' => 'Install trees, shrubs, perennials, and groundcovers at proper spacing and depth per the planting plan specifications.'],
                ['title' => 'Mulching & Watering', 'description' => 'Apply 3-inch mulch layer for moisture retention and weed suppression, set up initial watering schedule.'],
            ],
            'Landscape Lighting' => [
                ['title' => 'Lighting Design', 'description' => 'Create a lighting plan specifying fixture types, beam angles, colour temperatures, and lumen output for each location.'],
                ['title' => 'Wiring & Transformer', 'description' => 'Install commercial-grade transformer, trench direct-burial cable to each fixture location below grade depth.'],
                ['title' => 'Fixture Installation', 'description' => 'Mount brass or marine-grade aluminum fixtures, aim and adjust beam angles, and connect to low-voltage wiring.'],
                ['title' => 'Programming & Testing', 'description' => 'Configure WiFi smart controls, set timer schedules, test all zones, and demonstrate the system to the homeowner.'],
            ],
        ];

        return $steps[$service] ?? [
            ['title' => 'Assessment', 'description' => 'Evaluate site conditions and project requirements.'],
            ['title' => 'Preparation', 'description' => 'Prepare the site with proper base and drainage.'],
            ['title' => 'Installation', 'description' => 'Install materials according to engineering specifications.'],
            ['title' => 'Finishing', 'description' => 'Complete finishing details and final quality inspection.'],
        ];
    }
}
