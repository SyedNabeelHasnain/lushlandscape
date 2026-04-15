<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FaqGeneralSeeder extends Seeder
{
    public function run(): void
    {
        $generalCatId = $this->ensureCategory('General FAQs', 'general-faqs', 0);
        $complianceCatId = $this->ensureCategory('Permits & Regulations', 'permits-regulations', 5);
        $billingCatId = $this->ensureCategory('Pricing & Payment', 'pricing-payment', 6);
        $bookingCatId = $this->ensureCategory('Booking & Process', 'booking-process', 7);
        $localCatId = $this->ensureCategory('Local & City-Specific', 'local-city-specific', 8);

        $this->seedGeneralFaqs($generalCatId);
        $this->seedComplianceFaqs($complianceCatId);
        $this->seedBillingFaqs($billingCatId);
        $this->seedBookingFaqs($bookingCatId);
        $this->seedCityFaqs($localCatId);
    }

    private function ensureCategory(string $name, string $slug, int $order): int
    {
        return FaqCategory::updateOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'status' => 'published', 'sort_order' => $order]
        )->id;
    }

    private function seedFaq(array $data): void
    {
        $slug = Str::limit(Str::slug($data['question']), 250, '');
        if (isset($data['city_relevance'])) {
            $slug .= '-'.Str::slug($data['city_relevance']);
            $slug = Str::limit($slug, 250, '');
        }

        Faq::updateOrCreate(
            ['slug' => $slug],
            array_merge([
                'answer_format' => 'text',
                'audience_type' => 'customer',
                'status' => 'published',
                'schema_eligible' => true,
                'published_at' => now(),
                'local_relevance' => false,
                'city_relevance' => null,
                'region_relevance' => null,
            ], $data)
        );
    }

    // ─── General Business FAQs ──────────────────────────────────────────────

    private function seedGeneralFaqs(int $catId): void
    {
        $faqs = [
            [
                'question' => 'What areas in Ontario does Lush Landscape Service cover?',
                'short_answer' => 'We serve homeowners across the Greater Toronto and Hamilton Area including Hamilton, Burlington, Oakville, Mississauga, Milton, Toronto, Vaughan, Richmond Hill, Georgetown, and Brampton. Our crews are based locally in these communities, so we understand the soil conditions, bylaws, and climate challenges specific to each city.',
                'answer' => 'We serve homeowners across the Greater Toronto and Hamilton Area including Hamilton, Burlington, Oakville, Mississauga, Milton, Toronto, Vaughan, Richmond Hill, Georgetown, and Brampton. Our crews are based locally in these communities, so we understand the soil conditions, bylaws, and climate challenges specific to each city. If your property falls outside these areas but is within the Golden Horseshoe, contact us to confirm availability.',
                'is_featured' => true,
                'display_order' => 1,
            ],
            [
                'question' => 'What types of landscaping services do you offer?',
                'short_answer' => 'We specialize in four core categories: Interlock and Specialty Paving (driveways, patios, walkways, pool decks), Concrete Services (stamped, exposed aggregate, plain concrete), Structural Hardscape and Repair (retaining walls, steps, foundation waterproofing), and Softscaping and Lifestyle Enhancements (garden design, sod installation, outdoor kitchens, landscape lighting). Each service is tailored to your property and local conditions.',
                'answer' => 'We specialize in four core categories: Interlock and Specialty Paving (driveways, patios, walkways, pool decks), Concrete Services (stamped, exposed aggregate, plain concrete), Structural Hardscape and Repair (retaining walls, steps, foundation waterproofing), and Softscaping and Lifestyle Enhancements (garden design, sod installation, outdoor kitchens, landscape lighting). Each service is tailored to your property and local conditions. Visit our Services page for detailed information about each offering.',
                'is_featured' => true,
                'display_order' => 2,
            ],
            [
                'question' => 'Are you licensed and insured for landscaping work in Ontario?',
                'short_answer' => 'Yes. Lush Landscape Service carries full commercial general liability insurance and WSIB (Workplace Safety and Insurance Board) coverage for all our crews. We are members of Landscape Ontario, the provincial trade association that sets industry standards for quality and professionalism. You can verify Landscape Ontario membership at landscapeontario.com.',
                'answer' => 'Yes. Lush Landscape Service carries full commercial general liability insurance and WSIB (Workplace Safety and Insurance Board) coverage for all our crews. We are members of Landscape Ontario, the provincial trade association that sets industry standards for quality and professionalism. You can verify Landscape Ontario membership at landscapeontario.com. We provide proof of insurance to every client before work begins.',
                'is_featured' => true,
                'display_order' => 3,
            ],
            [
                'question' => 'What warranty do you provide on your landscaping work?',
                'short_answer' => 'We provide a 10-year workmanship warranty on all hardscaping installations including interlocking, concrete, and retaining walls. This covers structural integrity, base settlement, and joint stability under normal residential use. Paver and material manufacturers such as Unilock, Techo-Bloc, and Permacon provide additional product warranties, often lifetime, that supplement our workmanship coverage.',
                'answer' => 'We provide a 10-year workmanship warranty on all hardscaping installations including interlocking, concrete, and retaining walls. This covers structural integrity, base settlement, and joint stability under normal residential use. Paver and material manufacturers such as Unilock, Techo-Bloc, and Permacon provide additional product warranties, often lifetime, that supplement our workmanship coverage. Softscaping installations carry a 1-year plant health guarantee with proper watering compliance.',
                'is_featured' => true,
                'display_order' => 4,
            ],
            [
                'question' => 'Do you offer on-site consultations before work begins?',
                'short_answer' => 'Yes. We start with an on-site consultation so we can assess your property, understand your goals, and confirm the right scope and material direction. After the visit, we follow up with a clear scope plan and proposal.',
                'answer' => 'Yes. We start with an on-site consultation so we can assess your property, understand your goals, and confirm the right scope and material direction. After the visit, we follow up with a clear scope plan and proposal. You can request a consultation through our website or by calling our office.',
                'display_order' => 5,
            ],
            [
                'question' => 'What is the best time of year to start a landscaping project in Ontario?',
                'short_answer' => 'The ideal construction season in Ontario runs from late April through November. Spring (April to June) is best for hardscaping projects like driveways and patios, as the ground has thawed and you get maximum enjoyment through summer. Fall (September to November) is excellent for softscaping, garden installations, and sod because cooler temperatures and more rainfall help plants establish roots before winter.',
                'answer' => 'The ideal construction season in Ontario runs from late April through November. Spring (April to June) is best for hardscaping projects like driveways and patios, as the ground has thawed and you get maximum enjoyment through summer. Fall (September to November) is excellent for softscaping, garden installations, and sod because cooler temperatures and more rainfall help plants establish roots before winter. We recommend booking your project 4 to 6 weeks in advance during peak season (May to August) to secure your preferred timeline.',
                'display_order' => 6,
            ],
            [
                'question' => 'How do you handle Ontario winter conditions in your installations?',
                'short_answer' => 'Every installation is engineered for Ontario freeze-thaw cycles. We excavate to a minimum 16 to 18 inches below grade to exceed the 48-inch frost penetration depth when combined with base material. Our compacted Granular A aggregate base and proper drainage prevent frost heaving. We use polymeric sand rated for Canadian climates and specify concrete mix designs with air entrainment for freeze-thaw durability.',
                'answer' => 'Every installation is engineered for Ontario freeze-thaw cycles. We excavate to a minimum 16 to 18 inches below grade to exceed the 48-inch frost penetration depth when combined with base material. Our compacted Granular A aggregate base and proper drainage prevent frost heaving. We use polymeric sand rated for Canadian climates and specify concrete mix designs with air entrainment for freeze-thaw durability. Our 10-year warranty covers any winter-related structural failures.',
                'display_order' => 7,
            ],
            [
                'question' => 'What paver brands do you work with?',
                'short_answer' => 'We are authorized installers for leading Canadian paver manufacturers including Unilock, Techo-Bloc, Permacon, and Oaks by Brampton Brick. Each brand offers different aesthetic styles, textures, and price points. During your consultation, we help you select the product that best matches your design vision, budget, and the architectural style of your home.',
                'answer' => 'We are authorized installers for leading Canadian paver manufacturers including Unilock, Techo-Bloc, Permacon, and Oaks by Brampton Brick. Each brand offers different aesthetic styles, textures, and price points. During your consultation, we help you select the product that best matches your design vision, budget, and the architectural style of your home. We can also source specialty products from European manufacturers for unique design requirements.',
                'display_order' => 8,
            ],
            [
                'question' => 'Can you work with my existing landscape design or do you only use your own designs?',
                'short_answer' => 'We work both ways. If you have an existing landscape plan from an architect or designer, we are happy to execute it precisely as specified. If you need design assistance, our team creates custom plans based on your preferences, property assessment, and budget. We also collaborate with landscape architects on complex projects to ensure the design intent is fully realized during construction.',
                'answer' => 'We work both ways. If you have an existing landscape plan from an architect or designer, we are happy to execute it precisely as specified. If you need design assistance, our team creates custom plans based on your preferences, property assessment, and budget. We also collaborate with landscape architects on complex projects to ensure the design intent is fully realized during construction.',
                'display_order' => 9,
            ],
            [
                'question' => 'How do I maintain my interlocking pavers after installation?',
                'short_answer' => 'Interlocking pavers require minimal maintenance. We recommend sweeping regularly to prevent organic matter buildup, re-applying polymeric sand every 3 to 5 years, and sealing pavers every 3 to 4 years if you want enhanced color and stain protection. Avoid using salt-based de-icers in the first winter; use sand or calcium chloride-based alternatives instead. If a paver becomes damaged, individual units can be replaced without disturbing the surrounding area.',
                'answer' => 'Interlocking pavers require minimal maintenance. We recommend sweeping regularly to prevent organic matter buildup, re-applying polymeric sand every 3 to 5 years, and sealing pavers every 3 to 4 years if you want enhanced color and stain protection. Avoid using salt-based de-icers in the first winter; use sand or calcium chloride-based alternatives instead. If a paver becomes damaged, individual units can be replaced without disturbing the surrounding area. We provide a detailed care guide with every completed project.',
                'display_order' => 10,
            ],
            [
                'question' => 'Do you handle utility locates before digging?',
                'short_answer' => 'Yes. Before any excavation work, we contact Ontario One Call (ontario1call.com) to arrange underground utility locates. This is a legal requirement in Ontario and protects against accidental damage to buried gas, electric, water, and telecommunications lines. Utility locates are arranged at no cost to you and are typically completed within 5 business days of our request.',
                'answer' => 'Yes. Before any excavation work, we contact Ontario One Call (ontario1call.com) to arrange underground utility locates. This is a legal requirement in Ontario and protects against accidental damage to buried gas, electric, water, and telecommunications lines. Utility locates are arranged at no cost to you and are typically completed within 5 business days of our request. We do not begin excavation until all utility markings are confirmed.',
                'display_order' => 11,
            ],
            [
                'question' => 'What happens if it rains during my landscaping project?',
                'short_answer' => 'Light rain typically does not affect hardscaping work. However, heavy rainfall can delay excavation and base compaction because saturated soil cannot be properly compacted. We monitor weather forecasts closely and communicate any schedule adjustments in advance. Rain delays do not affect the quality of your finished project as we only proceed when conditions allow proper installation. Our contracts account for reasonable weather delays.',
                'answer' => 'Light rain typically does not affect hardscaping work. However, heavy rainfall can delay excavation and base compaction because saturated soil cannot be properly compacted. We monitor weather forecasts closely and communicate any schedule adjustments in advance. Rain delays do not affect the quality of your finished project as we only proceed when conditions allow proper installation. Our contracts account for reasonable weather delays.',
                'display_order' => 12,
            ],
            [
                'question' => 'Can you remove my old driveway or patio before installing a new one?',
                'short_answer' => 'Yes. Full demolition and removal of existing surfaces is included in our project scope. We handle removal of old asphalt, concrete, interlocking pavers, and natural stone. Demolished material is hauled away and disposed of at licensed recycling facilities. We also handle any necessary re-grading and base preparation to ensure the new installation starts on a solid foundation.',
                'answer' => 'Yes. Full demolition and removal of existing surfaces is included in our project scope. We handle removal of old asphalt, concrete, interlocking pavers, and natural stone. Demolished material is hauled away and disposed of at licensed recycling facilities. We also handle any necessary re-grading and base preparation to ensure the new installation starts on a solid foundation.',
                'display_order' => 13,
            ],
            [
                'question' => 'Do you provide references from past clients?',
                'short_answer' => 'Absolutely. We are happy to provide references from homeowners in your area who have had similar projects completed. You can also browse our online portfolio for photos of completed projects, and read verified reviews on our Google Business profile. Many of our clients are willing to show their properties to prospective customers.',
                'answer' => 'Absolutely. We are happy to provide references from homeowners in your area who have had similar projects completed. You can also browse our online portfolio for photos of completed projects, and read verified reviews on our Google Business profile. Many of our clients are willing to show their properties to prospective customers.',
                'display_order' => 14,
            ],
            [
                'question' => 'What makes Lush Landscape different from other landscaping companies?',
                'short_answer' => 'Three things set us apart: First, we are local operators who live and work in the communities we serve, so we understand specific soil conditions, bylaws, and climate challenges. Second, our 10-year workmanship warranty is among the strongest in Ontario. Third, we use only CSA-certified materials and follow ICPI (Interlocking Concrete Pavement Institute) standards for every installation, ensuring your project is built to last through decades of Ontario weather.',
                'answer' => 'Three things set us apart: First, we are local operators who live and work in the communities we serve, so we understand specific soil conditions, bylaws, and climate challenges. Second, our 10-year workmanship warranty is among the strongest in Ontario. Third, we use only CSA-certified materials and follow ICPI (Interlocking Concrete Pavement Institute) standards for every installation, ensuring your project is built to last through decades of Ontario weather.',
                'is_featured' => true,
                'display_order' => 15,
            ],
        ];

        foreach ($faqs as $faq) {
            $this->seedFaq(array_merge($faq, [
                'category_id' => $catId,
                'faq_type' => 'general',
            ]));
        }
    }

    // ─── Compliance / Permits FAQs ──────────────────────────────────────────

    private function seedComplianceFaqs(int $catId): void
    {
        $faqs = [
            [
                'question' => 'Do I need a building permit for a patio or driveway in Ontario?',
                'short_answer' => 'In most Ontario municipalities, a standard interlocking paver patio or driveway replacement within the existing footprint does not require a building permit. However, you will need a permit if you are changing the driveway width, modifying the curb cut, building structures over a certain height, or working within a regulated conservation area. Requirements vary by municipality, so we verify permit needs for every project before starting work. You can check general Ontario Building Code requirements at ontario.ca/page/building-code.',
                'answer' => 'In most Ontario municipalities, a standard interlocking paver patio or driveway replacement within the existing footprint does not require a building permit. However, you will need a permit if you are changing the driveway width, modifying the curb cut, building structures over a certain height, or working within a regulated conservation area. Requirements vary by municipality, so we verify permit needs for every project before starting work. You can check general Ontario Building Code requirements at ontario.ca/page/building-code.',
                'display_order' => 1,
                'is_featured' => true,
            ],
            [
                'question' => 'What is the Ontario Building Code requirement for retaining wall permits?',
                'short_answer' => 'Under the Ontario Building Code (OBC), retaining walls that exceed 1.0 metre (approximately 3.3 feet) in exposed height typically require a building permit and engineered drawings. Walls that retain a surcharge load, such as a driveway or structure above, may require a permit regardless of height. The structural design must account for soil lateral pressure, hydrostatic pressure, and frost depth. We work with licensed structural engineers for all walls requiring permits and handle the complete permit application process.',
                'answer' => 'Under the Ontario Building Code (OBC), retaining walls that exceed 1.0 metre (approximately 3.3 feet) in exposed height typically require a building permit and engineered drawings. Walls that retain a surcharge load, such as a driveway or structure above, may require a permit regardless of height. The structural design must account for soil lateral pressure, hydrostatic pressure, and frost depth. We work with licensed structural engineers for all walls requiring permits and handle the complete permit application process. Full OBC structural requirements are available at ontario.ca/page/building-code.',
                'display_order' => 2,
            ],
            [
                'question' => 'Do I need Conservation Authority approval for my landscaping project?',
                'short_answer' => 'If your property is within a regulated area of a Conservation Authority (such as Conservation Halton, Credit Valley Conservation, Toronto and Region Conservation Authority, or Hamilton Conservation Authority), you may need a permit under Ontario Regulation 97/04. Regulated areas include flood plains, wetlands, shorelines, and lands within 120 metres of these features. We check conservation mapping for every project during our site assessment. You can verify your property status through your local Conservation Authority website.',
                'answer' => 'If your property is within a regulated area of a Conservation Authority (such as Conservation Halton, Credit Valley Conservation, Toronto and Region Conservation Authority, or Hamilton Conservation Authority), you may need a permit under Ontario Regulation 97/04. Regulated areas include flood plains, wetlands, shorelines, and lands within 120 metres of these features. We check conservation mapping for every project during our site assessment. You can verify your property status through your local Conservation Authority website. Conservation Halton: conservationhalton.ca. Credit Valley Conservation: cvc.ca. TRCA: trca.ca.',
                'display_order' => 3,
            ],
            [
                'question' => 'What are Ontario lot coverage and impervious surface bylaws?',
                'short_answer' => 'Most Ontario municipalities have bylaws limiting the percentage of your lot that can be covered by impervious surfaces such as concrete, asphalt, and standard interlocking pavers. This typically ranges from 50% to 70% of the front yard. Exceeding these limits may require a minor variance application. Permeable interlocking pavers are an excellent alternative as they allow stormwater to infiltrate the ground and often qualify for exemptions from lot coverage calculations. We factor these regulations into every project design.',
                'answer' => 'Most Ontario municipalities have bylaws limiting the percentage of your lot that can be covered by impervious surfaces such as concrete, asphalt, and standard interlocking pavers. This typically ranges from 50% to 70% of the front yard. Exceeding these limits may require a minor variance application. Permeable interlocking pavers are an excellent alternative as they allow stormwater to infiltrate the ground and often qualify for exemptions from lot coverage calculations. We factor these regulations into every project design.',
                'display_order' => 4,
            ],
            [
                'question' => 'Are there tree protection bylaws that affect landscaping work?',
                'short_answer' => 'Yes. Many Ontario municipalities have tree protection bylaws that restrict the removal of trees above a certain size (typically 20 cm diameter at breast height). Toronto, Mississauga, Oakville, and other municipalities require permits to remove protected trees and may require replacement planting. Excavation near protected trees must maintain a Tree Protection Zone (TPZ) around the trunk. We assess tree impact during our site evaluation and include arborist consultation when required.',
                'answer' => 'Yes. Many Ontario municipalities have tree protection bylaws that restrict the removal of trees above a certain size (typically 20 cm diameter at breast height). Toronto, Mississauga, Oakville, and other municipalities require permits to remove protected trees and may require replacement planting. Excavation near protected trees must maintain a Tree Protection Zone (TPZ) around the trunk. We assess tree impact during our site evaluation and include arborist consultation when required. Toronto tree bylaw information: toronto.ca/trees.',
                'display_order' => 5,
            ],
            [
                'question' => 'What is Ontario One Call and why is it required before excavation?',
                'short_answer' => 'Ontario One Call is a province-wide service that coordinates underground utility locates before any excavation. Under the Ontario Underground Infrastructure Notification System Act (2012), anyone planning to dig must request locates at least 5 business days before excavation begins. This service is free and identifies buried gas, hydro, water, telecommunications, and other utility lines on your property. We submit locate requests for every project as part of our standard process. Request locates at ontario1call.com or by calling 1-800-400-2255.',
                'answer' => 'Ontario One Call is a province-wide service that coordinates underground utility locates before any excavation. Under the Ontario Underground Infrastructure Notification System Act (2012), anyone planning to dig must request locates at least 5 business days before excavation begins. This service is free and identifies buried gas, hydro, water, telecommunications, and other utility lines on your property. We submit locate requests for every project as part of our standard process. Request locates at ontario1call.com or by calling 1-800-400-2255.',
                'display_order' => 6,
            ],
            [
                'question' => 'Do I need a permit for an outdoor kitchen or fire pit in Ontario?',
                'short_answer' => 'Outdoor kitchens with gas connections require a gas permit and must be installed by a TSSA-licensed gas contractor. Wood-burning fire pits are regulated by local fire bylaws, which typically require a minimum setback distance from structures and property lines (usually 3 metres). Many municipalities require fire pits to meet CSA or ULC standards. Covered outdoor structures such as pergolas may require building permits if they exceed certain size thresholds. We coordinate all required permits as part of our project management.',
                'answer' => 'Outdoor kitchens with gas connections require a gas permit and must be installed by a TSSA-licensed gas contractor. Wood-burning fire pits are regulated by local fire bylaws, which typically require a minimum setback distance from structures and property lines (usually 3 metres). Many municipalities require fire pits to meet CSA or ULC standards. Covered outdoor structures such as pergolas may require building permits if they exceed certain size thresholds. We coordinate all required permits as part of our project management. TSSA (Technical Standards and Safety Authority) information: tssa.org.',
                'display_order' => 7,
            ],
            [
                'question' => 'What drainage and grading regulations apply to residential landscaping?',
                'short_answer' => 'Ontario municipalities require that surface water drains away from building foundations and must not be redirected onto neighbouring properties. The Ontario Building Code requires a minimum 10% slope (approximately 1 inch per foot) away from foundations for the first 6 feet. Lot grading certificates are required for new construction and sometimes for major renovations. If your project changes existing drainage patterns, you may need a grading plan approved by your municipality.',
                'answer' => 'Ontario municipalities require that surface water drains away from building foundations and must not be redirected onto neighbouring properties. The Ontario Building Code requires a minimum 10% slope (approximately 1 inch per foot) away from foundations for the first 6 feet. Lot grading certificates are required for new construction and sometimes for major renovations. If your project changes existing drainage patterns, you may need a grading plan approved by your municipality. We incorporate proper drainage design into every hardscaping project.',
                'display_order' => 8,
            ],
        ];

        foreach ($faqs as $faq) {
            $this->seedFaq(array_merge($faq, [
                'category_id' => $catId,
                'faq_type' => 'compliance',
            ]));
        }
    }

    // ─── Billing / Pricing FAQs ─────────────────────────────────────────────

    private function seedBillingFaqs(int $catId): void
    {
        $faqs = [
            [
                'question' => 'How much does a typical landscaping project cost in Ontario?',
                'short_answer' => 'Costs vary significantly by project type and scope. As general ranges: interlocking driveways run $22 to $38 per square foot, patios $20 to $35 per square foot, stamped concrete $15 to $25 per square foot, retaining walls $40 to $80 per linear foot per foot of height, and sod installation $2.50 to $4.50 per square foot. These ranges include materials, labour, excavation, and base preparation. We provide detailed, itemized quotes specific to your property after an on-site assessment.',
                'answer' => 'Costs vary significantly by project type and scope. As general ranges: interlocking driveways run $22 to $38 per square foot, patios $20 to $35 per square foot, stamped concrete $15 to $25 per square foot, retaining walls $40 to $80 per linear foot per foot of height, and sod installation $2.50 to $4.50 per square foot. These ranges include materials, labour, excavation, and base preparation. We provide detailed, itemized quotes specific to your property after an on-site assessment.',
                'display_order' => 1,
                'is_featured' => true,
            ],
            [
                'question' => 'What payment methods do you accept?',
                'short_answer' => 'We accept cash, cheque, e-Transfer (Interac), and major credit cards. For larger projects, we offer structured payment plans with a deposit at signing, progress payments tied to project milestones, and a final payment upon completion and your satisfaction. We provide detailed invoices at each payment stage.',
                'answer' => 'We accept cash, cheque, e-Transfer (Interac), and major credit cards. For larger projects, we offer structured payment plans with a deposit at signing, progress payments tied to project milestones, and a final payment upon completion and your satisfaction. We provide detailed invoices at each payment stage. All payment terms are clearly outlined in our written contract before work begins.',
                'display_order' => 2,
            ],
            [
                'question' => 'How much deposit do you require to start a project?',
                'short_answer' => 'We typically require a 20% to 30% deposit upon contract signing to secure your project date and order materials. For larger projects, the remaining balance is divided into milestone payments as work progresses. The final 10% is due upon project completion and your walkthrough approval. We never ask for full payment upfront.',
                'answer' => 'We typically require a 20% to 30% deposit upon contract signing to secure your project date and order materials. For larger projects, the remaining balance is divided into milestone payments as work progresses. The final 10% is due upon project completion and your walkthrough approval. We never ask for full payment upfront. All payment schedules are documented in your contract.',
                'display_order' => 3,
            ],
            [
                'question' => 'Do you offer financing for landscaping projects?',
                'short_answer' => 'Yes. We partner with third-party financing providers to offer flexible payment plans for qualifying homeowners. Options include 6-month and 12-month interest-free financing on approved credit, as well as longer-term plans with competitive rates. This allows you to invest in your property without the burden of a large upfront payment. Ask about financing options during your consultation.',
                'answer' => 'Yes. We partner with third-party financing providers to offer flexible payment plans for qualifying homeowners. Options include 6-month and 12-month interest-free financing on approved credit, as well as longer-term plans with competitive rates. This allows you to invest in your property without the burden of a large upfront payment. Ask about financing options during your consultation.',
                'display_order' => 4,
            ],
            [
                'question' => 'What factors affect the cost of my landscaping project?',
                'short_answer' => 'Several factors influence project costs: the size and scope of work, material selection (premium pavers cost more than standard options), site accessibility (narrow side yards or rear-only access add labour), demolition requirements, soil conditions (clay-heavy soils require deeper excavation and more base material), grading complexity, permit requirements, and pattern complexity for paver installations. We explain every cost factor during your estimate so there are no surprises.',
                'answer' => 'Several factors influence project costs: the size and scope of work, material selection (premium pavers cost more than standard options), site accessibility (narrow side yards or rear-only access add labour), demolition requirements, soil conditions (clay-heavy soils require deeper excavation and more base material), grading complexity, permit requirements, and pattern complexity for paver installations. We explain every cost factor during your estimate so there are no surprises.',
                'display_order' => 5,
            ],
            [
                'question' => 'Does landscaping increase my home value in Ontario?',
                'short_answer' => 'Yes. Well-executed landscaping typically returns 150% to 200% of the investment in added property value, according to the Appraisal Institute of Canada. A professionally designed and installed front yard can increase curb appeal and home value by 10% to 15%. Functional outdoor living spaces such as patios and outdoor kitchens are among the highest ROI home improvements. Quality hardscaping also differentiates your home during resale.',
                'answer' => 'Yes. Well-executed landscaping typically returns 150% to 200% of the investment in added property value, according to the Appraisal Institute of Canada. A professionally designed and installed front yard can increase curb appeal and home value by 10% to 15%. Functional outdoor living spaces such as patios and outdoor kitchens are among the highest ROI home improvements. Quality hardscaping also differentiates your home during resale in competitive Ontario real estate markets.',
                'display_order' => 6,
            ],
            [
                'question' => 'Are there any hidden costs I should know about?',
                'short_answer' => 'No. Our scope and proposal is itemized and clear. We include all costs: demolition, excavation, base material, surface material, labour, equipment, polymeric sand, edge restraints, cleanup, and disposal. If unexpected conditions are discovered during excavation (such as buried debris, utility conflicts, or unsuitable soil), we communicate the issue immediately and provide a written change order before proceeding. You approve every change before it is incurred.',
                'answer' => 'No. Our scope and proposal is itemized and clear. We include all costs: demolition, excavation, base material, surface material, labour, equipment, polymeric sand, edge restraints, cleanup, and disposal. If unexpected conditions are discovered during excavation (such as buried debris, utility conflicts, or unsuitable soil), we communicate the issue immediately and provide a written change order before proceeding. You approve every change before it is incurred.',
                'display_order' => 7,
            ],
        ];

        foreach ($faqs as $faq) {
            $this->seedFaq(array_merge($faq, [
                'category_id' => $catId,
                'faq_type' => 'billing',
            ]));
        }
    }

    // ─── Booking / Process FAQs ─────────────────────────────────────────────

    private function seedBookingFaqs(int $catId): void
    {
        $faqs = [
            [
                'question' => 'How do I get started with my landscaping project?',
                'short_answer' => 'Getting started is simple. Submit a project inquiry through our website or call our office. We schedule an on-site consultation, assess your property, and align on goals and constraints. After the visit, you receive a clear scope plan and proposal. Once you approve and sign the agreement, we schedule your project and coordinate materials.',
                'answer' => 'Getting started is simple. Submit a project inquiry through our website or call our office. We schedule an on-site consultation, assess your property, and align on goals and constraints. After the visit, you receive a clear scope plan and proposal. Once you approve and sign the agreement, we schedule your project and coordinate materials. From initial contact to project start typically depends on season and scope.',
                'display_order' => 1,
                'is_featured' => true,
            ],
            [
                'question' => 'How long does a typical landscaping project take to complete?',
                'short_answer' => 'Project timelines vary by scope: a standard driveway takes 3 to 5 working days, a backyard patio 3 to 7 days, a retaining wall 2 to 5 days, and a full front-and-back landscape transformation 2 to 4 weeks. Complex projects involving multiple elements may take longer. We provide a detailed timeline in your contract and keep you updated throughout the construction process.',
                'answer' => 'Project timelines vary by scope: a standard driveway takes 3 to 5 working days, a backyard patio 3 to 7 days, a retaining wall 2 to 5 days, and a full front-and-back landscape transformation 2 to 4 weeks. Complex projects involving multiple elements may take longer. We provide a detailed timeline in your contract and keep you updated throughout the construction process. Weather delays are communicated proactively.',
                'display_order' => 2,
            ],
            [
                'question' => 'Do I need to be home during the landscaping work?',
                'short_answer' => 'You do not need to be home during the work as long as we have access to the work area and any necessary utilities (outdoor water, electrical). We schedule a walkthrough at the start and end of the project. Our crew leads provide daily updates via text or phone, and you are always welcome to check in on progress. We maintain a clean and organized worksite throughout the project.',
                'answer' => 'You do not need to be home during the work as long as we have access to the work area and any necessary utilities (outdoor water, electrical). We schedule a walkthrough at the start and end of the project. Our crew leads provide daily updates via text or phone, and you are always welcome to check in on progress. We maintain a clean and organized worksite throughout the project.',
                'display_order' => 3,
            ],
            [
                'question' => 'What should I do to prepare my property before work begins?',
                'short_answer' => 'Before our crew arrives, we ask that you remove personal items such as planters, furniture, and decorations from the work area. If applicable, arrange temporary parking for vehicles not on the driveway. Mark any underground sprinkler lines, invisible fencing, or private utilities that may not be covered by Ontario One Call locates. We handle all other preparation including utility locates, material delivery coordination, and neighbour notification if needed.',
                'answer' => 'Before our crew arrives, we ask that you remove personal items such as planters, furniture, and decorations from the work area. If applicable, arrange temporary parking for vehicles not on the driveway. Mark any underground sprinkler lines, invisible fencing, or private utilities that may not be covered by Ontario One Call locates. We handle all other preparation including utility locates, material delivery coordination, and neighbour notification if needed.',
                'display_order' => 4,
            ],
            [
                'question' => 'How far in advance should I book my project?',
                'short_answer' => 'We recommend booking 4 to 8 weeks in advance during our peak season (May through September). Spring and early summer fill up fastest, so projects planned for May or June are best booked by March. Fall projects (September through November) should be booked by July or August. Off-peak bookings (late fall for spring start) often receive priority scheduling and may qualify for early-booking discounts.',
                'answer' => 'We recommend booking 4 to 8 weeks in advance during our peak season (May through September). Spring and early summer fill up fastest, so projects planned for May or June are best booked by March. Fall projects (September through November) should be booked by July or August. Off-peak bookings (late fall for spring start) often receive priority scheduling and may qualify for early-booking discounts.',
                'display_order' => 5,
            ],
            [
                'question' => 'What happens during the final walkthrough?',
                'short_answer' => 'When your project is complete, we schedule a final walkthrough with you. During this inspection, we review every aspect of the installation: paver alignment, joint consistency, edge restraints, drainage patterns, grade transitions, and overall finish quality. If any adjustments are needed, we address them immediately. We also provide a maintenance care guide, warranty documentation, and before-and-after photos of your project. The final payment is due only after your walkthrough approval.',
                'answer' => 'When your project is complete, we schedule a final walkthrough with you. During this inspection, we review every aspect of the installation: paver alignment, joint consistency, edge restraints, drainage patterns, grade transitions, and overall finish quality. If any adjustments are needed, we address them immediately. We also provide a maintenance care guide, warranty documentation, and before-and-after photos of your project. The final payment is due only after your walkthrough approval.',
                'display_order' => 6,
            ],
            [
                'question' => 'Can I make changes to the project after work has started?',
                'short_answer' => 'Yes, but changes after construction begins may affect timeline and cost. Minor adjustments such as paver pattern direction or border color can often be accommodated without delay. Larger scope changes (adding square footage, changing materials) require a written change order that details the cost difference and timeline impact. We communicate clearly about what is and is not feasible at each stage of construction.',
                'answer' => 'Yes, but changes after construction begins may affect timeline and cost. Minor adjustments such as paver pattern direction or border color can often be accommodated without delay. Larger scope changes (adding square footage, changing materials) require a written change order that details the cost difference and timeline impact. We communicate clearly about what is and is not feasible at each stage of construction.',
                'display_order' => 7,
            ],
        ];

        foreach ($faqs as $faq) {
            $this->seedFaq(array_merge($faq, [
                'category_id' => $catId,
                'faq_type' => 'booking',
            ]));
        }
    }

    // ─── City-Specific FAQs ─────────────────────────────────────────────────

    private function seedCityFaqs(int $catId): void
    {
        $cities = [
            'Hamilton' => [
                [
                    'question' => 'What soil conditions should I know about for landscaping in Hamilton?',
                    'short_answer' => 'Hamilton sits on clay-heavy glacial till over Queenston shale, with significant variations between the upper and lower city divided by the Niagara Escarpment. Clay soils in areas like Westdale, Dundas, and Stoney Creek have high shrink-swell potential, meaning they expand when wet and crack when dry. This directly affects foundation stability for hardscaping. We address this by excavating deeper (18 to 20 inches) and installing robust granular bases with proper drainage to prevent frost heaving and settlement.',
                    'answer' => 'Hamilton sits on clay-heavy glacial till over Queenston shale, with significant variations between the upper and lower city divided by the Niagara Escarpment. Clay soils in areas like Westdale, Dundas, Ancaster, and Stoney Creek have high shrink-swell potential, meaning they expand when wet and crack when dry. This directly affects foundation stability for hardscaping. We address this by excavating deeper (18 to 20 inches) and installing robust granular bases with proper drainage to prevent frost heaving and settlement.',
                    'display_order' => 1,
                ],
                [
                    'question' => 'Do I need a landscaping permit in Hamilton?',
                    'short_answer' => 'Hamilton Building Division requires permits for retaining walls over 1 metre, new driveway curb cuts, and structures requiring footings. Properties near the Niagara Escarpment fall under Hamilton Conservation Authority (HCA) regulations and may need additional approval. The HCA regulates development within the escarpment protection area to prevent erosion and environmental damage. We handle all permit coordination through the City of Hamilton Planning and Economic Development Department and HCA. Visit hamilton.ca/building-permits for current requirements.',
                    'answer' => 'Hamilton Building Division requires permits for retaining walls over 1 metre, new driveway curb cuts, and structures requiring footings. Properties near the Niagara Escarpment fall under Hamilton Conservation Authority (HCA) regulations and may need additional approval. The HCA regulates development within the escarpment protection area to prevent erosion and environmental damage. We handle all permit coordination through the City of Hamilton Planning and Economic Development Department and HCA. Visit hamilton.ca/building-permits for current requirements.',
                    'display_order' => 2,
                ],
                [
                    'question' => 'What unique landscaping challenges does the Hamilton Escarpment create?',
                    'short_answer' => 'The Niagara Escarpment creates significant elevation changes across Hamilton properties, particularly in Ancaster, Dundas, and the upper city boundary. These slopes require engineered retaining wall systems, proper drainage management to prevent erosion, and terraced designs that work with the natural grade rather than against it. Properties on the escarpment brow also face higher wind exposure. We have extensive experience designing and building on sloped properties throughout Hamilton-Wentworth.',
                    'answer' => 'The Niagara Escarpment creates significant elevation changes across Hamilton properties, particularly in Ancaster, Dundas, and the upper city boundary. These slopes require engineered retaining wall systems, proper drainage management to prevent erosion, and terraced designs that work with the natural grade rather than against it. Properties on the escarpment brow also face higher wind exposure. We have extensive experience designing and building on sloped properties throughout Hamilton-Wentworth.',
                    'display_order' => 3,
                ],
                [
                    'question' => 'What are Hamilton average snowfall and frost depth considerations for landscaping?',
                    'short_answer' => 'Hamilton receives approximately 150 cm of snow annually, with frost penetration reaching 48 inches in Zone 6a conditions. We engineer all installations to withstand these freeze-thaw cycles by using air-entrained concrete, proper base depths, and drainage systems that prevent water from pooling beneath paved surfaces. Driveway installations include consideration for snow plow clearance and salt exposure on edge restraints.',
                    'answer' => 'Hamilton receives approximately 150 cm of snow annually, with frost penetration reaching 48 inches in Zone 6a conditions. We engineer all installations to withstand these freeze-thaw cycles by using air-entrained concrete, proper base depths, and drainage systems that prevent water from pooling beneath paved surfaces. Driveway installations include consideration for snow plow clearance and salt exposure on edge restraints.',
                    'display_order' => 4,
                ],
                [
                    'question' => 'Which Hamilton neighbourhoods do you serve most frequently?',
                    'short_answer' => 'We work throughout Hamilton-Wentworth including Westdale, Dundas, Ancaster, Stoney Creek, Binbrook, Waterdown, Flamborough, and the Hamilton Mountain. Each area has distinct characteristics. Westdale properties often feature mature lots with established trees requiring careful excavation. Ancaster and Dundas homes near the escarpment need slope-specific solutions. Newer Binbrook and Stoney Creek subdivisions typically have builder-grade landscapes ready for upgrading.',
                    'answer' => 'We work throughout Hamilton-Wentworth including Westdale, Dundas, Ancaster, Stoney Creek, Binbrook, Waterdown, Flamborough, and the Hamilton Mountain. Each area has distinct characteristics. Westdale properties often feature mature lots with established trees requiring careful excavation. Ancaster and Dundas homes near the escarpment need slope-specific solutions. Newer Binbrook and Stoney Creek subdivisions typically have builder-grade landscapes ready for upgrading.',
                    'display_order' => 5,
                ],
            ],

            'Burlington' => [
                [
                    'question' => 'What are the soil conditions for landscaping in Burlington?',
                    'short_answer' => 'Burlington soil transitions from sandy loam near the Lake Ontario shoreline to silty clay as you move inland toward the escarpment. Lakefront properties in Aldershot and LaSalle Park benefit from better-draining sandy soils, while inland areas like Millcroft and Tyandaga have denser clay requiring more extensive base preparation. We soil-test challenging properties and adjust our base specifications accordingly.',
                    'answer' => 'Burlington soil transitions from sandy loam near the Lake Ontario shoreline to silty clay as you move inland toward the escarpment. Lakefront properties in Aldershot and LaSalle Park benefit from better-draining sandy soils, while inland areas like Millcroft and Tyandaga have denser clay requiring more extensive base preparation. We soil-test challenging properties and adjust our base specifications accordingly.',
                    'display_order' => 1,
                ],
                [
                    'question' => 'Do I need permits for landscaping work in Burlington?',
                    'short_answer' => 'Burlington Building and By-Law Department requires permits for new or widened driveway entrances, retaining walls over 1 metre, and structures with footings. Properties within Conservation Halton regulated areas (near Bronte Creek, Grindstone Creek, or Lake Ontario shoreline) require additional conservation permits. Burlington also has a private tree bylaw protecting trees over 20 cm diameter. We coordinate all required approvals. Visit burlington.ca/building for permit information.',
                    'answer' => 'Burlington Building and By-Law Department requires permits for new or widened driveway entrances, retaining walls over 1 metre, and structures with footings. Properties within Conservation Halton regulated areas (near Bronte Creek, Grindstone Creek, or Lake Ontario shoreline) require additional conservation permits. Burlington also has a private tree bylaw protecting trees over 20 cm diameter. We coordinate all required approvals. Visit burlington.ca/building for permit information. Conservation Halton: conservationhalton.ca.',
                    'display_order' => 2,
                ],
                [
                    'question' => 'What landscaping styles work best for Burlington lakefront properties?',
                    'short_answer' => 'Burlington lakefront properties in Aldershot, LaSalle, and Roseland benefit from low-maintenance, wind-resistant designs. Natural stone and large-format pavers handle lake-effect moisture well. Salt-tolerant plantings are important for properties near Burlington Beach. We recommend permeable paving systems for lakefront lots to manage stormwater runoff, which is increasingly important under Conservation Halton regulations.',
                    'answer' => 'Burlington lakefront properties in Aldershot, LaSalle, and Roseland benefit from low-maintenance, wind-resistant designs. Natural stone and large-format pavers handle lake-effect moisture well. Salt-tolerant plantings are important for properties near Burlington Beach. We recommend permeable paving systems for lakefront lots to manage stormwater runoff, which is increasingly important under Conservation Halton regulations.',
                    'display_order' => 3,
                ],
                [
                    'question' => 'What are Burlington average snowfall and frost considerations?',
                    'short_answer' => 'Burlington receives approximately 120 cm of snow annually with frost penetration reaching 48 inches in Zone 6a. Lake Ontario provides slight temperature moderation for lakefront properties, but all installations must be engineered for full Ontario freeze-thaw conditions. We use minimum 16-inch excavation depths and compacted granular bases throughout Burlington.',
                    'answer' => 'Burlington receives approximately 120 cm of snow annually with frost penetration reaching 48 inches in Zone 6a. Lake Ontario provides slight temperature moderation for lakefront properties, but all installations must be engineered for full Ontario freeze-thaw conditions. We use minimum 16-inch excavation depths and compacted granular bases throughout Burlington.',
                    'display_order' => 4,
                ],
                [
                    'question' => 'Which Burlington neighbourhoods do you serve?',
                    'short_answer' => 'We serve all of Burlington including Aldershot, Millcroft, Tyandaga, Roseland, Palmer, Orchard, LaSalle, Brant Hills, and Burlington Downtown. Millcroft homes often feature large lots ideal for comprehensive landscape projects. Tyandaga and Roseland have established properties ready for hardscape upgrades. Aldershot has unique character homes that benefit from custom design approaches.',
                    'answer' => 'We serve all of Burlington including Aldershot, Millcroft, Tyandaga, Roseland, Palmer, Orchard, LaSalle, Brant Hills, and Burlington Downtown. Millcroft homes often feature large lots ideal for comprehensive landscape projects. Tyandaga and Roseland have established properties ready for hardscape upgrades. Aldershot has unique character homes that benefit from custom design approaches.',
                    'display_order' => 5,
                ],
            ],

            'Oakville' => [
                [
                    'question' => 'What soil conditions affect landscaping in Oakville?',
                    'short_answer' => 'Oakville soil ranges from sandy clay loam near the Lake Ontario shoreline in Old Oakville and Bronte to heavy clay inland around Glen Abbey and River Oaks. The Sixteen Mile Creek and Bronte Creek watersheds create variable soil conditions even within short distances. Clay-dominant inland soils require deeper excavation and more robust drainage solutions. We assess soil conditions at every Oakville property to determine the optimal base design.',
                    'answer' => 'Oakville soil ranges from sandy clay loam near the Lake Ontario shoreline in Old Oakville and Bronte to heavy clay inland around Glen Abbey and River Oaks. The Sixteen Mile Creek and Bronte Creek watersheds create variable soil conditions even within short distances. Clay-dominant inland soils require deeper excavation and more robust drainage solutions. We assess soil conditions at every Oakville property to determine the optimal base design.',
                    'display_order' => 1,
                ],
                [
                    'question' => 'What permits do I need for landscaping in Oakville?',
                    'short_answer' => 'Town of Oakville Building Services requires permits for driveway widening, retaining walls over 1 metre, and any work within the Heritage Conservation District (Old Oakville). Properties near Sixteen Mile Creek, Bronte Creek, or Lake Ontario shoreline may fall under Conservation Halton jurisdiction. Oakville also has strict tree preservation bylaws protecting trees over 20 cm diameter. We handle all permit applications and conservation authority coordination. Visit oakville.ca/building for details.',
                    'answer' => 'Town of Oakville Building Services requires permits for driveway widening, retaining walls over 1 metre, and any work within the Heritage Conservation District (Old Oakville). Properties near Sixteen Mile Creek, Bronte Creek, or Lake Ontario shoreline may fall under Conservation Halton jurisdiction. Oakville also has strict tree preservation bylaws protecting trees over 20 cm diameter. We handle all permit applications and conservation authority coordination. Visit oakville.ca/building for details. Conservation Halton: conservationhalton.ca.',
                    'display_order' => 2,
                ],
                [
                    'question' => 'Are there heritage district restrictions for landscaping in Old Oakville?',
                    'short_answer' => 'Yes. Properties within the Old Oakville Heritage Conservation District have additional design guidelines that may affect front yard landscaping, driveway materials, and walkway designs. Changes to the streetscape appearance may require approval from the Heritage Oakville Advisory Committee. Natural stone and traditional paver styles are generally preferred within the heritage district. We have experience navigating these requirements and can recommend designs that satisfy heritage guidelines while meeting your functional needs.',
                    'answer' => 'Yes. Properties within the Old Oakville Heritage Conservation District have additional design guidelines that may affect front yard landscaping, driveway materials, and walkway designs. Changes to the streetscape appearance may require approval from the Heritage Oakville Advisory Committee. Natural stone and traditional paver styles are generally preferred within the heritage district. We have experience navigating these requirements and can recommend designs that satisfy heritage guidelines while meeting your functional needs.',
                    'display_order' => 3,
                ],
                [
                    'question' => 'What are typical landscaping project costs in Oakville?',
                    'short_answer' => 'Oakville projects tend to fall in the mid-to-upper range due to larger lot sizes and premium material preferences common in the area. Standard driveways run $15,000 to $30,000, backyard patios $12,000 to $35,000, and full front yard transformations $20,000 to $50,000. Glen Abbey and River Oaks homes often invest in comprehensive landscape packages combining multiple elements. We provide detailed estimates specific to your Oakville property.',
                    'answer' => 'Oakville projects tend to fall in the mid-to-upper range due to larger lot sizes and premium material preferences common in the area. Standard driveways run $15,000 to $30,000, backyard patios $12,000 to $35,000, and full front yard transformations $20,000 to $50,000. Glen Abbey and River Oaks homes often invest in comprehensive landscape packages combining multiple elements. We provide detailed estimates specific to your Oakville property.',
                    'display_order' => 4,
                ],
                [
                    'question' => 'Which Oakville neighbourhoods do you serve?',
                    'short_answer' => 'We serve all Oakville neighbourhoods including Old Oakville, Bronte, Glen Abbey, River Oaks, Clearview, Eastlake, Joshua Creek, Palermo, and Oakville North. Old Oakville heritage homes require heritage-sensitive approaches. Glen Abbey and River Oaks feature executive lots ideal for premium hardscaping. Bronte Village properties blend lakeside living with upscale outdoor spaces.',
                    'answer' => 'We serve all Oakville neighbourhoods including Old Oakville, Bronte, Glen Abbey, River Oaks, Clearview, Eastlake, Joshua Creek, Palermo, and Oakville North. Old Oakville heritage homes require heritage-sensitive approaches. Glen Abbey and River Oaks feature executive lots ideal for premium hardscaping. Bronte Village properties blend lakeside living with upscale outdoor spaces.',
                    'display_order' => 5,
                ],
            ],

            'Mississauga' => [
                [
                    'question' => 'What soil conditions should I know about for landscaping in Mississauga?',
                    'short_answer' => 'Mississauga sits primarily on Halton Till clay with sandy pockets near the Credit River. Properties in Port Credit and Lorne Park benefit from sandier, better-draining soil near the lakefront. Inland areas like Erin Mills and Meadowvale have heavier clay that holds water and is prone to frost heaving. The Credit River valley creates additional drainage considerations for nearby properties. We adjust our base preparation and drainage design based on your specific Mississauga neighbourhood.',
                    'answer' => 'Mississauga sits primarily on Halton Till clay with sandy pockets near the Credit River. Properties in Port Credit and Lorne Park benefit from sandier, better-draining soil near the lakefront. Inland areas like Erin Mills and Meadowvale have heavier clay that holds water and is prone to frost heaving. The Credit River valley creates additional drainage considerations for nearby properties. We adjust our base preparation and drainage design based on your specific Mississauga neighbourhood.',
                    'display_order' => 1,
                ],
                [
                    'question' => 'What permits are required for landscaping in Mississauga?',
                    'short_answer' => 'City of Mississauga Building Division requires permits for driveway widening or new curb cuts, retaining walls over 1 metre, and accessory structures with footings. Properties along the Credit River or Lake Ontario shoreline may require permits from Credit Valley Conservation (CVC). Mississauga has a private tree bylaw protecting trees over 15 cm diameter on residential properties. We coordinate all required permits. Visit mississauga.ca/building for current requirements. CVC: cvc.ca.',
                    'answer' => 'City of Mississauga Building Division requires permits for driveway widening or new curb cuts, retaining walls over 1 metre, and accessory structures with footings. Properties along the Credit River or Lake Ontario shoreline may require permits from Credit Valley Conservation (CVC). Mississauga has a private tree bylaw protecting trees over 15 cm diameter on residential properties. We coordinate all required permits. Visit mississauga.ca/building for current requirements. CVC: cvc.ca.',
                    'display_order' => 2,
                ],
                [
                    'question' => 'What landscaping styles are popular in Port Credit and Lorne Park?',
                    'short_answer' => 'Port Credit and Lorne Park homeowners often favour upscale, transitional designs that blend natural stone elements with modern clean lines. Lakefront proximity makes salt-tolerant plantings and weather-resistant materials important. Natural flagstone patios, premium interlocking with exposed aggregate accents, and low-maintenance garden beds are popular choices. Outdoor living spaces including fire features and lighting are in high demand in these established neighbourhoods.',
                    'answer' => 'Port Credit and Lorne Park homeowners often favour upscale, transitional designs that blend natural stone elements with modern clean lines. Lakefront proximity makes salt-tolerant plantings and weather-resistant materials important. Natural flagstone patios, premium interlocking with exposed aggregate accents, and low-maintenance garden beds are popular choices. Outdoor living spaces including fire features and lighting are in high demand in these established neighbourhoods.',
                    'display_order' => 3,
                ],
                [
                    'question' => 'What are Mississauga snowfall and frost depth facts for landscaping?',
                    'short_answer' => 'Mississauga receives approximately 108 cm of snowfall annually with frost penetration reaching 48 inches in Zone 6a. The Credit River watershed can intensify localized freeze-thaw effects. All our Mississauga installations use minimum 16-inch excavation depths, compacted granular bases, and air-entrained concrete where applicable to ensure decades of performance through Ontario winters.',
                    'answer' => 'Mississauga receives approximately 108 cm of snowfall annually with frost penetration reaching 48 inches in Zone 6a. The Credit River watershed can intensify localized freeze-thaw effects. All our Mississauga installations use minimum 16-inch excavation depths, compacted granular bases, and air-entrained concrete where applicable to ensure decades of performance through Ontario winters.',
                    'display_order' => 4,
                ],
                [
                    'question' => 'Which Mississauga neighbourhoods do you serve?',
                    'short_answer' => 'We serve all of Mississauga including Port Credit, Lorne Park, Erin Mills, Meadowvale, Streetsville, Clarkson, Cooksville, Churchill Meadows, Mineola, and Mississauga City Centre. Port Credit and Lorne Park are premium residential areas with distinctive landscape needs. Erin Mills and Meadowvale homes often feature larger lots ideal for backyard living spaces. Streetsville village has heritage charm requiring thoughtful design approaches.',
                    'answer' => 'We serve all of Mississauga including Port Credit, Lorne Park, Erin Mills, Meadowvale, Streetsville, Clarkson, Cooksville, Churchill Meadows, Mineola, and Mississauga City Centre. Port Credit and Lorne Park are premium residential areas with distinctive landscape needs. Erin Mills and Meadowvale homes often feature larger lots ideal for backyard living spaces. Streetsville village has heritage charm requiring thoughtful design approaches.',
                    'display_order' => 5,
                ],
            ],

            'Milton' => [
                [
                    'question' => 'What are the soil conditions for landscaping in Milton?',
                    'short_answer' => 'Milton has clay loam on the lowlands transitioning to thin soil over shale near the Niagara Escarpment edge. Newer subdivisions in areas like Timberlea and Harrison are built on heavy clay fill that requires careful compaction and drainage planning. Properties near the escarpment in Old Milton face rocky conditions that increase excavation complexity. We adjust our base specifications and equipment selection based on your specific Milton property conditions.',
                    'answer' => 'Milton has clay loam on the lowlands transitioning to thin soil over shale near the Niagara Escarpment edge. Newer subdivisions in areas like Timberlea and Harrison are built on heavy clay fill that requires careful compaction and drainage planning. Properties near the escarpment in Old Milton face rocky conditions that increase excavation complexity. We adjust our base specifications and equipment selection based on your specific Milton property conditions.',
                    'display_order' => 1,
                ],
                [
                    'question' => 'What permits do I need for landscaping in Milton?',
                    'short_answer' => 'Town of Milton Building Division requires permits for driveway entrance modifications, retaining walls over 1 metre, and structures with footings. Milton has areas regulated by both Conservation Halton and Hamilton Conservation Authority. The escarpment protection zone imposes additional restrictions on grading and tree removal. We verify all regulatory requirements during our site assessment. Visit milton.ca/building for permit information. Conservation Halton: conservationhalton.ca.',
                    'answer' => 'Town of Milton Building Division requires permits for driveway entrance modifications, retaining walls over 1 metre, and structures with footings. Milton has areas regulated by both Conservation Halton and Hamilton Conservation Authority. The escarpment protection zone imposes additional restrictions on grading and tree removal. We verify all regulatory requirements during our site assessment. Visit milton.ca/building for permit information. Conservation Halton: conservationhalton.ca.',
                    'display_order' => 2,
                ],
                [
                    'question' => 'What landscaping challenges do new Milton subdivisions face?',
                    'short_answer' => 'New Milton subdivisions in Timberlea, Harrison, and Bristol typically have builder-grade landscapes with minimal topsoil over compacted clay fill. Common issues include poor drainage, grade settling around foundations, and lack of privacy screening. We help Milton homeowners upgrade from basic builder landscapes to functional outdoor living spaces with proper grading, drainage solutions, quality hardscaping, and mature planting installations.',
                    'answer' => 'New Milton subdivisions in Timberlea, Harrison, and Bristol typically have builder-grade landscapes with minimal topsoil over compacted clay fill. Common issues include poor drainage, grade settling around foundations, and lack of privacy screening. We help Milton homeowners upgrade from basic builder landscapes to functional outdoor living spaces with proper grading, drainage solutions, quality hardscaping, and mature planting installations.',
                    'display_order' => 3,
                ],
                [
                    'question' => 'What are Milton snowfall and frost depth facts?',
                    'short_answer' => 'Milton receives approximately 130 cm of snow annually, above the GTA average due to escarpment-enhanced snowfall. Frost penetration reaches 48 inches in Zone 5b to 6a conditions. Higher elevation areas receive more snow and experience slightly colder temperatures than lakefront cities. We engineer all Milton installations for these conditions with deeper bases and enhanced drainage.',
                    'answer' => 'Milton receives approximately 130 cm of snow annually, above the GTA average due to escarpment-enhanced snowfall. Frost penetration reaches 48 inches in Zone 5b to 6a conditions. Higher elevation areas receive more snow and experience slightly colder temperatures than lakefront cities. We engineer all Milton installations for these conditions with deeper bases and enhanced drainage.',
                    'display_order' => 4,
                ],
                [
                    'question' => 'Which Milton neighbourhoods do you serve?',
                    'short_answer' => 'We serve all of Milton including Old Milton, Timberlea, Harrison, Bristol, Scott, Willmott, Dempsey, and Bronte Meadows. Old Milton has established properties with mature landscaping and character homes. Timberlea and Harrison feature newer homes with builder-grade landscapes ready for upgrading. Bristol and Dempsey are among the newest areas with opportunities for comprehensive landscape design.',
                    'answer' => 'We serve all of Milton including Old Milton, Timberlea, Harrison, Bristol, Scott, Willmott, Dempsey, and Bronte Meadows. Old Milton has established properties with mature landscaping and character homes. Timberlea and Harrison feature newer homes with builder-grade landscapes ready for upgrading. Bristol and Dempsey are among the newest areas with opportunities for comprehensive landscape design.',
                    'display_order' => 5,
                ],
            ],

            'Toronto' => [
                [
                    'question' => 'What soil conditions affect landscaping in Toronto?',
                    'short_answer' => 'Toronto has variable glacial deposits ranging from sandy soils near the Don and Humber River valleys to heavy clay till across much of the inner suburbs. Properties in Etobicoke tend toward clay, while Lawrence Park and Forest Hill may have better-draining sandy loam. Urban lots often contain fill material from previous construction. Ravine-adjacent properties face strict tree protection and grading regulations. We assess every Toronto property individually due to this soil variability.',
                    'answer' => 'Toronto has variable glacial deposits ranging from sandy soils near the Don and Humber River valleys to heavy clay till across much of the inner suburbs. Properties in Etobicoke tend toward clay, while Lawrence Park and Forest Hill may have better-draining sandy loam. Urban lots often contain fill material from previous construction. Ravine-adjacent properties face strict tree protection and grading regulations. We assess every Toronto property individually due to this soil variability.',
                    'display_order' => 1,
                ],
                [
                    'question' => 'What permits are required for landscaping in Toronto?',
                    'short_answer' => 'Toronto Building requires permits for driveway widening, retaining walls over 1 metre, and any work within ravine protection zones. Toronto has one of Ontario most comprehensive tree protection bylaws covering private and city trees over 30 cm diameter (10 cm in ravine areas). The Toronto and Region Conservation Authority (TRCA) regulates development near ravines, rivers, and the waterfront. We navigate these requirements for every Toronto project. Visit toronto.ca/building for current information. TRCA: trca.ca.',
                    'answer' => 'Toronto Building requires permits for driveway widening, retaining walls over 1 metre, and any work within ravine protection zones. Toronto has one of Ontario most comprehensive tree protection bylaws covering private and city trees over 30 cm diameter (10 cm in ravine areas). The Toronto and Region Conservation Authority (TRCA) regulates development near ravines, rivers, and the waterfront. We navigate these requirements for every Toronto project. Visit toronto.ca/building for current information. TRCA: trca.ca.',
                    'display_order' => 2,
                ],
                [
                    'question' => 'What are the landscaping challenges for urban Toronto properties?',
                    'short_answer' => 'Toronto urban lots present unique challenges including limited access for equipment, tight property setbacks, underground utility density, mature tree root zones, and lot coverage restrictions. Rear yard access through narrow side yards may require smaller equipment or hand-carrying materials. Front yard parking pad regulations are strict. We specialize in maximizing limited urban spaces with creative design solutions and efficient construction methods adapted for city properties.',
                    'answer' => 'Toronto urban lots present unique challenges including limited access for equipment, tight property setbacks, underground utility density, mature tree root zones, and lot coverage restrictions. Rear yard access through narrow side yards may require smaller equipment or hand-carrying materials. Front yard parking pad regulations are strict. We specialize in maximizing limited urban spaces with creative design solutions and efficient construction methods adapted for city properties.',
                    'display_order' => 3,
                ],
                [
                    'question' => 'What are Toronto snowfall and frost depth facts for landscaping?',
                    'short_answer' => 'Toronto receives approximately 108 cm of snow annually with frost penetration reaching 48 inches in Zone 6a. Lake Ontario effect provides slight temperature moderation for lakefront neighbourhoods but all installations must be fully engineered for Ontario freeze-thaw cycles. City salt use on roads and sidewalks can affect adjacent paver installations, so we specify salt-resistant edge restraints and recommend appropriate sealers for exposed surfaces.',
                    'answer' => 'Toronto receives approximately 108 cm of snow annually with frost penetration reaching 48 inches in Zone 6a. Lake Ontario effect provides slight temperature moderation for lakefront neighbourhoods but all installations must be fully engineered for Ontario freeze-thaw cycles. City salt use on roads and sidewalks can affect adjacent paver installations, so we specify salt-resistant edge restraints and recommend appropriate sealers for exposed surfaces.',
                    'display_order' => 4,
                ],
                [
                    'question' => 'Which Toronto neighbourhoods do you serve?',
                    'short_answer' => 'We serve residential properties across Toronto including Etobicoke, The Kingsway, Forest Hill, Lawrence Park, Leaside, Rosedale, The Bridle Path, North York, Scarborough, and Mimico. Each area has distinct characteristics. The Kingsway and Forest Hill feature executive estates with large lots. Leaside and Lawrence Park have established mid-century homes with mature landscapes. Etobicoke offers a mix of bungalows and newer builds.',
                    'answer' => 'We serve residential properties across Toronto including Etobicoke, The Kingsway, Forest Hill, Lawrence Park, Leaside, Rosedale, The Bridle Path, North York, Scarborough, and Mimico. Each area has distinct characteristics. The Kingsway and Forest Hill feature executive estates with large lots. Leaside and Lawrence Park have established mid-century homes with mature landscapes. Etobicoke offers a mix of bungalows and newer builds.',
                    'display_order' => 5,
                ],
            ],

            'Vaughan' => [
                [
                    'question' => 'What soil conditions affect landscaping in Vaughan?',
                    'short_answer' => 'Vaughan sits predominantly on heavy Newmarket Till clay, one of the densest clay soils in the GTA. This clay is extremely hard to excavate and has high shrink-swell characteristics. Properties in Woodbridge, Kleinburg, Maple, and Thornhill all face similar clay challenges. Proper drainage is essential as this clay holds water rather than allowing it to percolate. We use deeper excavation (18 to 20 inches minimum) and enhanced drainage systems for all Vaughan installations.',
                    'answer' => 'Vaughan sits predominantly on heavy Newmarket Till clay, one of the densest clay soils in the GTA. This clay is extremely hard to excavate and has high shrink-swell characteristics. Properties in Woodbridge, Kleinburg, Maple, and Thornhill all face similar clay challenges. Proper drainage is essential as this clay holds water rather than allowing it to percolate. We use deeper excavation (18 to 20 inches minimum) and enhanced drainage systems for all Vaughan installations.',
                    'display_order' => 1,
                ],
                [
                    'question' => 'What permits do I need for landscaping in Vaughan?',
                    'short_answer' => 'City of Vaughan Building Standards Department requires permits for driveway entrance modifications, retaining walls over 1 metre, and accessory structures. Properties near the Humber River are regulated by the Toronto and Region Conservation Authority (TRCA). Vaughan has municipal tree preservation bylaws and lot coverage restrictions. We handle all permit coordination for Vaughan projects. Visit vaughan.ca/building for permit details. TRCA: trca.ca.',
                    'answer' => 'City of Vaughan Building Standards Department requires permits for driveway entrance modifications, retaining walls over 1 metre, and accessory structures. Properties near the Humber River are regulated by the Toronto and Region Conservation Authority (TRCA). Vaughan has municipal tree preservation bylaws and lot coverage restrictions. We handle all permit coordination for Vaughan projects. Visit vaughan.ca/building for permit details. TRCA: trca.ca.',
                    'display_order' => 2,
                ],
                [
                    'question' => 'What landscaping styles are popular in Vaughan neighbourhoods?',
                    'short_answer' => 'Vaughan homeowners, particularly in Woodbridge and Kleinburg, often favour grand, European-inspired landscape designs with premium materials. Large-format pavers, natural stone accents, circular driveways, ornamental columns, and formal garden elements are popular. Kleinburg rural estate properties demand expansive designs with extensive hardscaping. Maple and Thornhill feature modern suburban homes that benefit from contemporary clean-line designs with outdoor living spaces.',
                    'answer' => 'Vaughan homeowners, particularly in Woodbridge and Kleinburg, often favour grand, European-inspired landscape designs with premium materials. Large-format pavers, natural stone accents, circular driveways, ornamental columns, and formal garden elements are popular. Kleinburg rural estate properties demand expansive designs with extensive hardscaping. Maple and Thornhill feature modern suburban homes that benefit from contemporary clean-line designs with outdoor living spaces.',
                    'display_order' => 3,
                ],
                [
                    'question' => 'What are Vaughan snowfall and climate considerations?',
                    'short_answer' => 'Vaughan receives approximately 130 cm of snow annually with frost penetration reaching 48 inches in Zone 5b. Being further from Lake Ontario than Toronto, Vaughan experiences colder winter temperatures and more consistent freeze-thaw cycles. We engineer all Vaughan installations with enhanced base depths and drainage to handle these conditions. Snow plowing considerations are built into driveway designs.',
                    'answer' => 'Vaughan receives approximately 130 cm of snow annually with frost penetration reaching 48 inches in Zone 5b. Being further from Lake Ontario than Toronto, Vaughan experiences colder winter temperatures and more consistent freeze-thaw cycles. We engineer all Vaughan installations with enhanced base depths and drainage to handle these conditions. Snow plowing considerations are built into driveway designs.',
                    'display_order' => 4,
                ],
                [
                    'question' => 'Which Vaughan neighbourhoods do you serve?',
                    'short_answer' => 'We serve all of Vaughan including Woodbridge, Kleinburg, Maple, Thornhill, Vellore Village, Sonoma Heights, Carrville, and Concord. Woodbridge is known for premium residential properties with grand landscape designs. Kleinburg features rural estate lots with expansive outdoor spaces. Maple and Thornhill have growing suburban communities with new homes ready for landscape upgrading.',
                    'answer' => 'We serve all of Vaughan including Woodbridge, Kleinburg, Maple, Thornhill, Vellore Village, Sonoma Heights, Carrville, and Concord. Woodbridge is known for premium residential properties with grand landscape designs. Kleinburg features rural estate lots with expansive outdoor spaces. Maple and Thornhill have growing suburban communities with new homes ready for landscape upgrading.',
                    'display_order' => 5,
                ],
            ],

            'Richmond Hill' => [
                [
                    'question' => 'What soil conditions affect landscaping in Richmond Hill?',
                    'short_answer' => 'Richmond Hill has heavy clay in the southern urban areas transitioning to sandy glacial deposits on the Oak Ridges Moraine in the north. Properties in South Richvale and Bayview Hill sit on dense clay requiring extensive base preparation. Northern areas near Oak Ridges benefit from better-draining sandy soils but face different drainage challenges. The Oak Ridges Moraine is a critical groundwater recharge area with strict development regulations.',
                    'answer' => 'Richmond Hill has heavy clay in the southern urban areas transitioning to sandy glacial deposits on the Oak Ridges Moraine in the north. Properties in South Richvale and Bayview Hill sit on dense clay requiring extensive base preparation. Northern areas near Oak Ridges benefit from better-draining sandy soils but face different drainage challenges. The Oak Ridges Moraine is a critical groundwater recharge area with strict development regulations.',
                    'display_order' => 1,
                ],
                [
                    'question' => 'What permits do I need for landscaping in Richmond Hill?',
                    'short_answer' => 'City of Richmond Hill Planning and Building Services requires permits for driveway modifications, retaining walls over 1 metre, and accessory structures. Properties on or near the Oak Ridges Moraine face additional environmental regulations under the Oak Ridges Moraine Conservation Plan. The Toronto and Region Conservation Authority (TRCA) regulates development near watercourses. We verify all requirements during our assessment. Visit richmondhill.ca/building for details. TRCA: trca.ca.',
                    'answer' => 'City of Richmond Hill Planning and Building Services requires permits for driveway modifications, retaining walls over 1 metre, and accessory structures. Properties on or near the Oak Ridges Moraine face additional environmental regulations under the Oak Ridges Moraine Conservation Plan. The Toronto and Region Conservation Authority (TRCA) regulates development near watercourses. We verify all requirements during our assessment. Visit richmondhill.ca/building for details. TRCA: trca.ca.',
                    'display_order' => 2,
                ],
                [
                    'question' => 'How does the Oak Ridges Moraine affect landscaping in Richmond Hill?',
                    'short_answer' => 'The Oak Ridges Moraine Conservation Plan regulates development on and near the moraine to protect groundwater recharge and natural heritage features. Properties in northern Richmond Hill may face restrictions on impervious surface coverage, grading, and tree removal. Permeable paving systems are particularly beneficial in moraine areas as they allow water to infiltrate rather than running off. We design with these environmental requirements in mind.',
                    'answer' => 'The Oak Ridges Moraine Conservation Plan regulates development on and near the moraine to protect groundwater recharge and natural heritage features. Properties in northern Richmond Hill may face restrictions on impervious surface coverage, grading, and tree removal. Permeable paving systems are particularly beneficial in moraine areas as they allow water to infiltrate rather than running off. We design with these environmental requirements in mind.',
                    'display_order' => 3,
                ],
                [
                    'question' => 'What are Richmond Hill snowfall and frost considerations?',
                    'short_answer' => 'Richmond Hill receives approximately 135 cm of snow annually with frost penetration reaching 48 inches in Zone 5b. Higher elevations on the Oak Ridges Moraine can receive additional snowfall compared to southern areas. All installations are engineered for full Ontario freeze-thaw conditions with proper base depths and drainage systems.',
                    'answer' => 'Richmond Hill receives approximately 135 cm of snow annually with frost penetration reaching 48 inches in Zone 5b. Higher elevations on the Oak Ridges Moraine can receive additional snowfall compared to southern areas. All installations are engineered for full Ontario freeze-thaw conditions with proper base depths and drainage systems.',
                    'display_order' => 4,
                ],
                [
                    'question' => 'Which Richmond Hill neighbourhoods do you serve?',
                    'short_answer' => 'We serve all of Richmond Hill including South Richvale, Oak Ridges, Bayview Hill, Jefferson, Langstaff, Mill Pond, Westbrook, and Harding. South Richvale and Bayview Hill feature premium residential properties with executive-scale landscape projects. Oak Ridges offers larger lots with rural character. Jefferson and Langstaff have newer developments ready for landscape customization.',
                    'answer' => 'We serve all of Richmond Hill including South Richvale, Oak Ridges, Bayview Hill, Jefferson, Langstaff, Mill Pond, Westbrook, and Harding. South Richvale and Bayview Hill feature premium residential properties with executive-scale landscape projects. Oak Ridges offers larger lots with rural character. Jefferson and Langstaff have newer developments ready for landscape customization.',
                    'display_order' => 5,
                ],
            ],

            'Georgetown' => [
                [
                    'question' => 'What soil conditions affect landscaping in Georgetown?',
                    'short_answer' => 'Georgetown has silty clay loam with glacial cobble near the Credit River. The Credit River valley creates variable soil conditions with some areas having rocky substrata that affects excavation. New development areas may have compacted clay fill. Older Georgetown properties near downtown tend to have more established, workable soil. We assess site conditions at every Georgetown property and adjust our approach accordingly.',
                    'answer' => 'Georgetown has silty clay loam with glacial cobble near the Credit River. The Credit River valley creates variable soil conditions with some areas having rocky substrata that affects excavation. New development areas may have compacted clay fill. Older Georgetown properties near downtown tend to have more established, workable soil. We assess site conditions at every Georgetown property and adjust our approach accordingly.',
                    'display_order' => 1,
                ],
                [
                    'question' => 'What permits do I need for landscaping in Georgetown?',
                    'short_answer' => 'Town of Halton Hills Building Services handles permits for Georgetown including driveway modifications, retaining walls, and accessory structures. Properties near the Credit River are regulated by Credit Valley Conservation (CVC). Georgetown downtown Heritage Conservation District may have additional design guidelines. We coordinate all required approvals. Visit haltonhills.ca/building for details. CVC: cvc.ca.',
                    'answer' => 'Town of Halton Hills Building Services handles permits for Georgetown including driveway modifications, retaining walls, and accessory structures. Properties near the Credit River are regulated by Credit Valley Conservation (CVC). Georgetown downtown Heritage Conservation District may have additional design guidelines. We coordinate all required approvals. Visit haltonhills.ca/building for details. CVC: cvc.ca.',
                    'display_order' => 2,
                ],
                [
                    'question' => 'What landscaping styles suit Georgetown small-town character?',
                    'short_answer' => 'Georgetown homeowners often prefer designs that complement the town small-community character. Natural stone, classic paver patterns, and established gardens align with Georgetown traditional feel. Heritage properties near downtown require period-appropriate materials and designs. Newer areas like Silver Creek and Hungry Hollow offer opportunities for contemporary landscape designs. We work with Georgetown unique blend of heritage and modern residential character.',
                    'answer' => 'Georgetown homeowners often prefer designs that complement the town small-community character. Natural stone, classic paver patterns, and established gardens align with Georgetown traditional feel. Heritage properties near downtown require period-appropriate materials and designs. Newer areas like Silver Creek and Hungry Hollow offer opportunities for contemporary landscape designs. We work with Georgetown unique blend of heritage and modern residential character.',
                    'display_order' => 3,
                ],
                [
                    'question' => 'What are Georgetown snowfall and frost considerations?',
                    'short_answer' => 'Georgetown receives approximately 140 cm of snow annually, among the highest in our service area due to its inland elevation and distance from Lake Ontario. Frost penetration reaches 48 inches in Zone 5b conditions. We engineer all Georgetown installations for these more demanding conditions with enhanced base depths and drainage systems designed for higher precipitation volumes.',
                    'answer' => 'Georgetown receives approximately 140 cm of snow annually, among the highest in our service area due to its inland elevation and distance from Lake Ontario. Frost penetration reaches 48 inches in Zone 5b conditions. We engineer all Georgetown installations for these more demanding conditions with enhanced base depths and drainage systems designed for higher precipitation volumes.',
                    'display_order' => 4,
                ],
                [
                    'question' => 'Which Georgetown neighbourhoods do you serve?',
                    'short_answer' => 'We serve all of Georgetown and Halton Hills including Georgetown Downtown, Hungry Hollow, Glen Williams, Silver Creek, Cedarvale, Stewarttown, and Acton. Georgetown Downtown has heritage homes with character. Hungry Hollow and Silver Creek feature newer family homes ready for landscape upgrades. Glen Williams is a charming village community with unique properties along the Credit River.',
                    'answer' => 'We serve all of Georgetown and Halton Hills including Georgetown Downtown, Hungry Hollow, Glen Williams, Silver Creek, Cedarvale, Stewarttown, and Acton. Georgetown Downtown has heritage homes with character. Hungry Hollow and Silver Creek feature newer family homes ready for landscape upgrades. Glen Williams is a charming village community with unique properties along the Credit River.',
                    'display_order' => 5,
                ],
            ],

            'Brampton' => [
                [
                    'question' => 'What soil conditions affect landscaping in Brampton?',
                    'short_answer' => 'Brampton sits on heavy Halton Till clay with high shrink-swell potential, making it one of the most challenging soil environments in the GTA for hardscaping. This clay expands significantly when saturated and contracts when dry, creating frost heave risk for improperly installed surfaces. Properties in Heart Lake, Mount Pleasant, Castlemore, and Springdale all face similar clay challenges. We use enhanced base depths (18 to 20 inches), geotextile fabric separation, and robust drainage systems for all Brampton installations.',
                    'answer' => 'Brampton sits on heavy Halton Till clay with high shrink-swell potential, making it one of the most challenging soil environments in the GTA for hardscaping. This clay expands significantly when saturated and contracts when dry, creating frost heave risk for improperly installed surfaces. Properties in Heart Lake, Mount Pleasant, Castlemore, and Springdale all face similar clay challenges. We use enhanced base depths (18 to 20 inches), geotextile fabric separation, and robust drainage systems for all Brampton installations.',
                    'display_order' => 1,
                ],
                [
                    'question' => 'What permits are required for landscaping in Brampton?',
                    'short_answer' => 'City of Brampton Building Division requires permits for driveway widening or new curb cuts, retaining walls over 1 metre, and accessory structures with footings. Parts of Brampton are regulated by Credit Valley Conservation (CVC) and the Toronto and Region Conservation Authority (TRCA). Brampton has municipal tree preservation requirements. We handle all permit coordination for Brampton projects. Visit brampton.ca/building for details. CVC: cvc.ca. TRCA: trca.ca.',
                    'answer' => 'City of Brampton Building Division requires permits for driveway widening or new curb cuts, retaining walls over 1 metre, and accessory structures with footings. Parts of Brampton are regulated by Credit Valley Conservation (CVC) and the Toronto and Region Conservation Authority (TRCA). Brampton has municipal tree preservation requirements. We handle all permit coordination for Brampton projects. Visit brampton.ca/building for details. CVC: cvc.ca. TRCA: trca.ca.',
                    'display_order' => 2,
                ],
                [
                    'question' => 'What landscaping challenges do Brampton new subdivisions face?',
                    'short_answer' => 'Brampton rapidly expanding suburban development means many homes have builder-grade landscapes on heavily compacted clay fill. Common issues include poor drainage, lawn subsidence, basement moisture from improper grading, and lack of privacy between closely spaced homes. We help Brampton homeowners solve these functional problems while transforming their properties with quality hardscaping, proper drainage, privacy screening, and outdoor living spaces.',
                    'answer' => 'Brampton rapidly expanding suburban development means many homes have builder-grade landscapes on heavily compacted clay fill. Common issues include poor drainage, lawn subsidence, basement moisture from improper grading, and lack of privacy between closely spaced homes. We help Brampton homeowners solve these functional problems while transforming their properties with quality hardscaping, proper drainage, privacy screening, and outdoor living spaces.',
                    'display_order' => 3,
                ],
                [
                    'question' => 'What are Brampton snowfall and frost depth facts?',
                    'short_answer' => 'Brampton receives approximately 125 cm of snow annually with frost penetration reaching 48 inches in Zone 5b conditions. Heavy clay soils combined with freeze-thaw cycles make proper base preparation especially critical in Brampton. We engineer every installation to withstand these conditions with compacted granular bases, proper drainage, and materials rated for Canadian freeze-thaw cycles.',
                    'answer' => 'Brampton receives approximately 125 cm of snow annually with frost penetration reaching 48 inches in Zone 5b conditions. Heavy clay soils combined with freeze-thaw cycles make proper base preparation especially critical in Brampton. We engineer every installation to withstand these conditions with compacted granular bases, proper drainage, and materials rated for Canadian freeze-thaw cycles.',
                    'display_order' => 4,
                ],
                [
                    'question' => 'Which Brampton neighbourhoods do you serve?',
                    'short_answer' => 'We serve all of Brampton including Heart Lake, Mount Pleasant, Castlemore, Springdale, Sandalwood, Fletcher Meadow, Gore Meadows, Vales of Humber, Credit Valley, and Bramalea. Castlemore features premium estate homes with grand landscape potential. Mount Pleasant and Heart Lake have established homes ready for upgrades. Springdale and Fletcher Meadow are newer areas with builder-grade landscapes that benefit from professional transformation.',
                    'answer' => 'We serve all of Brampton including Heart Lake, Mount Pleasant, Castlemore, Springdale, Sandalwood, Fletcher Meadow, Gore Meadows, Vales of Humber, Credit Valley, and Bramalea. Castlemore features premium estate homes with grand landscape potential. Mount Pleasant and Heart Lake have established homes ready for upgrades. Springdale and Fletcher Meadow are newer areas with builder-grade landscapes that benefit from professional transformation.',
                    'display_order' => 5,
                ],
            ],
        ];

        foreach ($cities as $cityName => $faqs) {
            foreach ($faqs as $faq) {
                $this->seedFaq(array_merge($faq, [
                    'category_id' => $catId,
                    'faq_type' => 'local',
                    'local_relevance' => true,
                    'city_relevance' => $cityName,
                    'region_relevance' => $this->getRegion($cityName),
                ]));
            }
        }
    }

    private function getRegion(string $city): string
    {
        return match ($city) {
            'Hamilton' => 'Hamilton-Wentworth',
            'Burlington', 'Oakville', 'Milton' => 'Halton Region',
            'Mississauga', 'Brampton' => 'Peel Region',
            'Toronto' => 'City of Toronto',
            'Vaughan', 'Richmond Hill' => 'York Region',
            'Georgetown' => 'Halton Hills',
            default => 'Ontario',
        };
    }
}
