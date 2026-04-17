<?php

namespace Database\Seeders;

use App\Models\StaticPage;
use App\Services\BlockBuilderService;
use Illuminate\Database\Seeder;

class StaticPageContentSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            'about' => ['method' => 'aboutUs',            'excerpt' => 'Learn about Lush Landscape Service, our team, and our commitment to delivering premium outdoor living spaces across Southern Ontario since 2018.', 'meta_title' => 'About Us | Lush Landscape Service', 'meta_description' => 'Lush Landscape Service has been creating premium outdoor spaces across the Greater Toronto and Hamilton Area since 2018. Landscape Ontario member. 500+ projects completed.', 'og_title' => 'About Lush Landscape Service | Premium Landscaping in Ontario', 'og_description' => 'Learn about our team, our values, and why 500+ Ontario homeowners trust Lush Landscape Service for their outdoor transformations.'],
            'process' => ['method' => 'ourProcess',         'excerpt' => 'From your first consultation to final walkthrough, discover the six-step process that ensures every Lush Landscape project is completed on time, on budget, and beyond expectations.', 'meta_title' => 'Our Process | How We Work | Lush Landscape Service', 'meta_description' => 'Discover our proven six-step landscaping process: on-site consultation, custom design, permits, expert installation, inspection, and warranty support across Ontario.', 'og_title' => 'How Lush Landscape Works | Our Six-Step Process', 'og_description' => 'From on-site consultation to warranty support, learn exactly how your landscaping project moves from concept to completion.'],
            'warranty' => ['method' => 'warrantyMaintenance', 'excerpt' => 'Every Lush Landscape project is protected by our written workmanship warranty covering up to 10 years. Learn what is covered, seasonal maintenance tips, and how to file a claim.', 'meta_title' => 'Warranty & Maintenance | Lush Landscape Service', 'meta_description' => 'Up to 10-year workmanship warranty on all Lush Landscape projects. Learn about coverage details, seasonal maintenance schedules, and our warranty claim process.', 'og_title' => 'Warranty & Maintenance | Lush Landscape Service', 'og_description' => 'Your investment is protected. Learn about our industry-leading warranty coverage and seasonal maintenance programs.'],
            'financing' => ['method' => 'financing',          'excerpt' => 'Flexible payment options to bring your landscaping vision to life. Learn about our payment milestones, financing availability, and project planning approach.', 'meta_title' => 'Financing & Payment Options | Lush Landscape Service', 'meta_description' => 'Flexible payment plans and milestone-based billing for your landscaping project. Serving Ontario homeowners since 2018.', 'og_title' => 'Landscaping Financing Options | Lush Landscape Service', 'og_description' => 'Make your dream landscape a reality with flexible payment options and milestone-based billing.'],
            'permits' => ['method' => 'permitsRegulations', 'excerpt' => 'We handle the permits and regulatory requirements for your landscaping project across Ontario municipalities, so you can focus on enjoying the results.', 'meta_title' => 'Permits & Regulations | Ontario Landscaping | Lush Landscape', 'meta_description' => 'Lush Landscape handles all Ontario building permits, municipal approvals, and conservation authority requirements for your landscaping project. Full compliance guaranteed.', 'og_title' => 'Permits & Regulations for Ontario Landscaping Projects', 'og_description' => 'From municipal permits to conservation authority approvals, we handle all regulatory requirements for your landscaping project.'],
            'awards' => ['method' => 'awardsCertifications', 'excerpt' => 'Recognized for excellence in landscape design and installation. Learn about our industry certifications, professional memberships, and commitment to the highest standards.', 'meta_title' => 'Awards & Certifications | Lush Landscape Service', 'meta_description' => 'Landscape Ontario member, fully licensed, WSIB certified, and committed to industry-leading standards. Learn about the credentials behind every Lush Landscape project.', 'og_title' => 'Awards & Certifications | Lush Landscape Service', 'og_description' => 'Industry-certified, fully insured, and recognized for excellence. See the credentials that back every Lush Landscape project.'],
            'careers' => ['method' => 'careers',            'excerpt' => 'Join the Lush Landscape team. We are always looking for skilled landscapers, project leads, and crew members who share our commitment to quality craftsmanship.', 'meta_title' => 'Careers | Join Our Team | Lush Landscape Service', 'meta_description' => 'Build your career with Lush Landscape Service. We are hiring skilled landscapers, project leads, and design consultants across the Greater Toronto and Hamilton Area.', 'og_title' => 'Careers at Lush Landscape Service', 'og_description' => 'Join a growing team of skilled landscaping professionals. Competitive pay, year-round opportunities, and a culture that values craftsmanship.'],
            'referral-program' => ['method' => 'referralProgram',    'excerpt' => 'Refer a friend or neighbour to Lush Landscape and earn rewards. Our referral program is our way of thanking you for spreading the word.', 'meta_title' => 'Referral Program | Earn Rewards | Lush Landscape Service', 'meta_description' => 'Earn rewards when you refer friends and neighbours to Lush Landscape Service. Simple, generous, and available to all past and current clients across Ontario.', 'og_title' => 'Lush Landscape Referral Program | Earn Rewards', 'og_description' => 'Love your new landscape? Refer a friend and earn rewards. Our referral program thanks you for spreading the word.'],
            'privacy-policy' => ['method' => 'privacyPolicy',     'excerpt' => 'This Privacy Policy describes how Lush Landscape Service collects, uses, and protects your personal information in compliance with Canadian privacy legislation.', 'meta_title' => 'Privacy Policy | Lush Landscape Service', 'meta_description' => 'Read the Lush Landscape Service privacy policy. Learn how we collect, use, and protect your personal information in accordance with PIPEDA and Ontario privacy law.', 'og_title' => 'Privacy Policy | Lush Landscape Service', 'og_description' => 'How Lush Landscape Service protects your personal information under Canadian privacy law.'],
            'terms' => ['method' => 'termsConditions',    'excerpt' => 'These Terms and Conditions govern your use of the Lush Landscape Service website and the landscaping services we provide across Ontario.', 'meta_title' => 'Terms & Conditions | Lush Landscape Service', 'meta_description' => 'Review the terms and conditions for Lush Landscape Service. Covers service agreements, payment terms, warranty conditions, and your rights under Ontario consumer protection law.', 'og_title' => 'Terms & Conditions | Lush Landscape Service', 'og_description' => 'Terms governing Lush Landscape services, including payment, warranties, and consumer protections under Ontario law.'],
        ];

        foreach ($pages as $slug => $cfg) {
            $page = StaticPage::where('slug', $slug)->first();
            if (! $page) {
                continue;
            }

            $page->update([
                'excerpt' => $cfg['excerpt'],
                'meta_title' => $cfg['meta_title'],
                'meta_description' => $cfg['meta_description'],
                'og_title' => $cfg['og_title'],
                'og_description' => $cfg['og_description'],
                'status' => 'published',
                'is_indexable' => true,
            ]);

            $method = $cfg['method'];
            $blocks = $this->{$method}();
            BlockBuilderService::saveUnifiedBlocks('static_page', $page->id, $blocks);
        }
    }

    // =========================================================================
    // ABOUT US
    // =========================================================================
    private function aboutUs(): array
    {
        return [
            [
                'block_type' => 'section_header',
                'section_key' => 'about_header',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Building Outdoor Spaces That Bring Families Together',
                    'subtitle' => 'Since 2018, Lush Landscape Service has been helping Ontario homeowners create the outdoor living spaces they have always wanted. Every project we take on reflects our belief that your yard should be a place where life happens.',
                    'align' => 'center',
                    'tag' => 'Our Story',
                    'show_line' => true,
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'about_story',
                'is_enabled' => true,
                'content' => [
                    'html' => '<p>Lush Landscape Service was founded in 2018 with a straightforward idea: Ontario homeowners deserve a landscaping company that treats their property with the same care and attention they would give their own home. What started as a small operation serving Hamilton and Burlington has grown into a full-service landscaping company completing projects across 10 cities in the Greater Toronto and Hamilton Area.</p>'
                        .'<p>That growth did not happen by accident. It happened because we kept our promises. We showed up when we said we would. We built every patio, driveway, and retaining wall as if our own family would be using it. And we stood behind our work with a written warranty that actually means something.</p>'
                        .'<p>Today, our team includes experienced project leads, skilled installers, and dedicated design consultants who all share the same commitment to quality. We are proud to be active members of Landscape Ontario, and we carry comprehensive liability insurance and WSIB clearance on every job site. These are not just credentials we list on our website. They are standards we uphold on every project, every day.</p>',
                ],
            ],
            [
                'block_type' => 'number_counter',
                'section_key' => 'about_stats',
                'is_enabled' => true,
                'content' => [
                    'bg' => 'forest',
                    'counters' => [
                        ['target' => '500',  'suffix' => '+', 'label' => 'Projects Completed',         'icon' => 'check-circle'],
                        ['target' => '10',   'suffix' => '',  'label' => 'Cities Served',               'icon' => 'map-pin'],
                        ['target' => '4.9',  'suffix' => '/5', 'label' => 'Average Google Rating',       'icon' => 'star'],
                        ['target' => '8',    'suffix' => '+', 'label' => 'Years of Outdoor Expertise',  'icon' => 'calendar'],
                    ],
                ],
            ],
            [
                'block_type' => 'section_header',
                'section_key' => 'about_values_header',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'The Values That Guide Every Project',
                    'subtitle' => 'These are not corporate slogans. They are the principles our team lives by, from the first phone call through your final walkthrough.',
                    'align' => 'center',
                    'tag' => 'Our Values',
                    'show_line' => true,
                ],
            ],
            [
                'block_type' => 'icon_grid',
                'section_key' => 'about_values',
                'is_enabled' => true,
                'content' => [
                    'heading' => '',
                    'columns' => '3',
                    'style' => 'card',
                    'items' => [
                        [
                            'icon' => 'hammer',
                            'title' => 'Craftsmanship First',
                            'description' => 'We never cut corners on base preparation, material quality, or finishing details. Proper excavation, graded gravel base, and compacted bedding come standard on every job because they are what make the difference between a project that lasts and one that does not.',
                        ],
                        [
                            'icon' => 'eye',
                            'title' => 'Total Transparency',
                            'description' => 'Your quote covers materials, labour, permits, and site cleanup. We do not add surprise charges mid-project, and we communicate proactively if anything changes. You will always know exactly where your project stands.',
                        ],
                        [
                            'icon' => 'users',
                            'title' => 'Community Commitment',
                            'description' => 'We live and work in the same neighbourhoods we serve. Our reputation in communities from Oakville to Richmond Hill matters to us personally, which is why we treat every yard as if it belongs to a neighbour.',
                        ],
                        [
                            'icon' => 'leaf',
                            'title' => 'Environmental Responsibility',
                            'description' => 'We incorporate permeable paving solutions, native plantings, and responsible drainage design into our projects. Sustainable landscaping is not a trend for us. It is a responsibility we take seriously.',
                        ],
                        [
                            'icon' => 'shield-check',
                            'title' => 'Safety and Compliance',
                            'description' => 'Every crew member follows strict safety protocols. We carry full liability coverage and WSIB clearance, pull required permits, and comply with all municipal bylaws and Ontario building codes.',
                        ],
                        [
                            'icon' => 'heart-handshake',
                            'title' => 'Long-Term Relationships',
                            'description' => 'Many of our clients come back year after year for new projects and seasonal maintenance. We earn that trust by delivering results that exceed expectations and by being genuinely easy to work with.',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'timeline',
                'section_key' => 'about_timeline',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Our Journey So Far',
                    'items' => [
                        [
                            'date' => '2018',
                            'title' => 'Founded in Hamilton',
                            'description' => 'Lush Landscape Service launched with a small crew and a commitment to doing things the right way. Our first projects focused on interlocking driveways and patios in Hamilton and Burlington.',
                            'icon' => 'flag',
                        ],
                        [
                            'date' => '2019',
                            'title' => 'Expanded to the Golden Horseshoe',
                            'description' => 'Growing demand from satisfied clients led us to extend our service area to Oakville, Milton, and Mississauga. We added softscape and garden design to our service offerings.',
                            'icon' => 'map',
                        ],
                        [
                            'date' => '2020',
                            'title' => 'Landscape Ontario Membership',
                            'description' => 'We earned our Landscape Ontario membership, affirming our commitment to the highest industry standards for installation quality, environmental stewardship, and professional conduct.',
                            'icon' => 'award',
                        ],
                        [
                            'date' => '2021',
                            'title' => 'Serving the Greater Toronto Area',
                            'description' => 'We expanded into Toronto, Vaughan, Richmond Hill, and Brampton, bringing our full suite of landscaping services to homeowners across the GTA.',
                            'icon' => 'trending-up',
                        ],
                        [
                            'date' => '2023',
                            'title' => '500 Projects Milestone',
                            'description' => 'We completed our 500th project and maintained a 4.9-star average Google rating. Our team grew to include dedicated project leads and design consultants.',
                            'icon' => 'star',
                        ],
                        [
                            'date' => '2026',
                            'title' => 'Continuing to Grow',
                            'description' => 'Today we serve 10 cities across Southern Ontario with 14 specialized services. Our commitment to quality craftsmanship and honest service remains exactly the same as day one.',
                            'icon' => 'rocket',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'feature_list',
                'section_key' => 'about_differentiators',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'What Makes Lush Landscape Different',
                    'columns' => '2',
                    'features' => [
                        [
                            'icon' => 'user-check',
                            'title' => 'Direct Communication with Your Project Lead',
                            'description' => 'You work directly with the person managing your project from start to finish. No call centres, no hand-offs between departments, and no communication gaps.',
                        ],
                        [
                            'icon' => 'truck',
                            'title' => 'Our Own Crews, Not Subcontractors',
                            'description' => 'Every installer on your site is part of our team. We train, supervise, and stand behind their work. You will never be surprised by an unfamiliar crew showing up at your property.',
                        ],
                        [
                            'icon' => 'clipboard-check',
                            'title' => 'Detailed Project Plans Before Any Work Begins',
                            'description' => 'Your consultation includes a comprehensive project plan with material specifications, a clear timeline, and a fixed price. We finalize every detail before we break ground.',
                        ],
                        [
                            'icon' => 'recycle',
                            'title' => 'Clean Job Sites and Responsible Disposal',
                            'description' => 'We clean up at the end of every work day and haul away all debris when the project is complete. Recyclable materials are sorted and disposed of responsibly.',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'area_served',
                'section_key' => 'about_areas',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Communities We Are Proud to Serve',
                    'description' => 'From the lakefront properties of Burlington to the growing neighbourhoods of Brampton, our crews deliver the same standard of craftsmanship to every community.',
                    'columns' => '5',
                    'areas' => [
                        ['name' => 'Hamilton',      'url' => '/landscaping-hamilton'],
                        ['name' => 'Burlington',    'url' => '/landscaping-burlington'],
                        ['name' => 'Oakville',      'url' => '/landscaping-oakville'],
                        ['name' => 'Mississauga',   'url' => '/landscaping-mississauga'],
                        ['name' => 'Milton',        'url' => '/landscaping-milton'],
                        ['name' => 'Toronto',       'url' => '/landscaping-toronto'],
                        ['name' => 'Vaughan',       'url' => '/landscaping-vaughan'],
                        ['name' => 'Richmond Hill', 'url' => '/landscaping-richmond-hill'],
                        ['name' => 'Georgetown',    'url' => '/landscaping-georgetown'],
                        ['name' => 'Brampton',      'url' => '/landscaping-brampton'],
                    ],
                ],
            ],
            [
                'block_type' => 'testimonial_card',
                'section_key' => 'about_testimonial',
                'is_enabled' => true,
                'content' => [
                    'quote' => 'From the very first consultation, the Lush Landscape team made us feel like our project was their top priority. They explained everything clearly, stuck to the timeline, and the finished patio is even better than we imagined. We have already recommended them to two of our neighbours.',
                    'author' => 'Michael T.',
                    'role' => 'Homeowner, Oakville',
                    'media_id' => null,
                    'rating' => '5',
                    'style' => 'featured',
                ],
            ],
            [
                'block_type' => 'cta_banner',
                'section_key' => 'about_cta',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Ready to Start Your Outdoor Project?',
                    'subheading' => 'Book an on-site consultation and see why hundreds of Ontario homeowners trust Lush Landscape Service with their outdoor living spaces.',
                    'button_text' => 'Book a Consultation',
                    'button_url' => '/contact',
                    'style' => 'forest',
                ],
            ],
        ];
    }

    // =========================================================================
    // OUR PROCESS
    // =========================================================================
    private function ourProcess(): array
    {
        return [
            [
                'block_type' => 'section_header',
                'section_key' => 'process_header',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'A Clear, Proven Process from Start to Finish',
                    'subtitle' => 'We have refined our project workflow over hundreds of installations to eliminate surprises and keep your project on track. Here is exactly what to expect when you work with Lush Landscape.',
                    'align' => 'center',
                    'tag' => 'How We Work',
                    'show_line' => true,
                ],
            ],
            [
                'block_type' => 'steps_process',
                'section_key' => 'process_steps',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Your Project in Six Steps',
                    'layout' => 'vertical',
                    'steps' => [
                        [
                            'title' => 'On-Site Consultation',
                            'description' => 'We visit your property to understand your vision, assess the site conditions, and discuss timeline and priorities. We review drainage patterns, soil conditions, sun exposure, and existing features to ensure our recommendations are practical and well-suited to your property.',
                            'icon' => 'message-circle',
                        ],
                        [
                            'title' => 'Scope & Proposal',
                            'description' => 'Based on our site visit, we prepare a custom scope plan with material specifications, layout direction, and a detailed proposal. We walk through the plan with you and refine it until it matches your vision.',
                            'icon' => 'pencil-ruler',
                        ],
                        [
                            'title' => 'Permits and Pre-Construction',
                            'description' => 'If your project requires a municipal building permit, conservation authority approval, or utility locates, we handle all of it. We prepare and submit the applications, coordinate with local authorities, and schedule any required inspections. We also notify you of any neighbourhood considerations and confirm the project start date once all approvals are in place.',
                            'icon' => 'file-check',
                        ],
                        [
                            'title' => 'Expert Installation',
                            'description' => 'Our experienced crews arrive on the scheduled date with all materials and equipment. Installation follows industry best practices: proper excavation depth, graded and compacted gravel base, screeded bedding layer, and precision cutting for a clean, professional finish. Your project lead oversees every phase and is your single point of contact throughout. We maintain a clean, organized job site and communicate daily progress updates.',
                            'icon' => 'hard-hat',
                        ],
                        [
                            'title' => 'Quality Inspection and Walkthrough',
                            'description' => 'Before we consider the project complete, your project lead conducts a detailed quality inspection. We then schedule a walkthrough with you to review every element of the finished work. If there is anything that does not meet our standards or yours, we address it immediately. You do not sign off on the project until you are completely satisfied.',
                            'icon' => 'search-check',
                        ],
                        [
                            'title' => 'Warranty Activation and Ongoing Support',
                            'description' => 'Once the walkthrough is complete, we activate your written workmanship warranty covering up to 10 years. We also provide you with a seasonal maintenance guide tailored to your specific installation. Our team remains available for questions, maintenance advice, and future projects. Many of our clients come back season after season to add new features to their outdoor spaces.',
                            'icon' => 'shield-check',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'feature_list',
                'section_key' => 'process_expectations',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'What You Can Expect from Every Lush Landscape Project',
                    'columns' => '2',
                    'features' => [
                        [
                            'icon' => 'clock',
                            'title' => 'On-Time Delivery',
                            'description' => 'We provide a realistic project timeline before any work begins and hold ourselves to it. Most residential projects are completed within the planned schedule, weather permitting.',
                        ],
                        [
                            'icon' => 'dollar-sign',
                            'title' => 'No Hidden Costs',
                            'description' => 'Your approved scope and proposal is the basis for your project. If an unforeseen issue arises, we discuss options with you before any additional cost is incurred.',
                        ],
                        [
                            'icon' => 'phone',
                            'title' => 'Responsive Communication',
                            'description' => 'Your project lead is available by phone, email, or text throughout the project. You will receive daily progress updates and advance notice of any scheduling changes.',
                        ],
                        [
                            'icon' => 'sparkles',
                            'title' => 'Clean and Respectful Worksite',
                            'description' => 'We protect your lawn, driveway, and landscaping during construction. At the end of every work day, we clean the site and stage materials neatly. Final cleanup includes removal of all debris and construction waste.',
                        ],
                        [
                            'icon' => 'layers',
                            'title' => 'Premium Materials',
                            'description' => 'We source pavers, natural stone, aggregates, and plantings from reputable Canadian suppliers. Every material is selected for durability, aesthetic quality, and suitability to Ontario climate conditions.',
                        ],
                        [
                            'icon' => 'award',
                            'title' => 'Industry Best Practices',
                            'description' => 'As Landscape Ontario members, we follow established standards for base preparation, drainage, grading, and installation. These practices are what separate a project that lasts a decade from one that needs repair in two years.',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'number_counter',
                'section_key' => 'process_stats',
                'is_enabled' => true,
                'content' => [
                    'bg' => 'forest',
                    'counters' => [
                        ['target' => '500', 'suffix' => '+', 'label' => 'Successful Project Completions', 'icon' => 'check-circle'],
                        ['target' => '98',  'suffix' => '%', 'label' => 'On-Time Delivery Rate',          'icon' => 'clock'],
                        ['target' => '4.9', 'suffix' => '',  'label' => 'Average Client Satisfaction',    'icon' => 'star'],
                        ['target' => '10',  'suffix' => '',  'label' => 'Year Workmanship Warranty',      'icon' => 'shield'],
                    ],
                ],
            ],
            [
                'block_type' => 'accordion',
                'section_key' => 'process_faq',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Common Questions About Our Process',
                    'items' => [
                        [
                            'question' => 'How long does the consultation and planning process take?',
                            'answer' => 'Most consultations take 30 to 60 minutes on site. We typically deliver your scope plan and proposal within 3 to 5 business days, depending on project complexity. For larger or multi-phase projects, the design and planning stage may take longer.',
                        ],
                        [
                            'question' => 'Do I need to be home during installation?',
                            'answer' => 'You do not need to be home for every day of installation, but we do ask that you are available for the project kick-off meeting and the final walkthrough. Your project lead will keep you updated daily via your preferred communication method.',
                        ],
                        [
                            'question' => 'What happens if there is bad weather during my project?',
                            'answer' => 'We monitor weather forecasts closely and plan around significant weather events. If rain or extreme conditions delay your project, your project lead will notify you immediately and provide an updated timeline. We never install in conditions that could compromise quality.',
                        ],
                        [
                            'question' => 'Can I make changes after the project has started?',
                            'answer' => 'Minor adjustments can often be accommodated during installation. For larger scope changes, we will discuss the impact on timeline and cost with you and provide an updated scope plan before proceeding. We will never make changes without your approval.',
                        ],
                        [
                            'question' => 'How do payment milestones work?',
                            'answer' => 'Payments are tied to project phases, not arbitrary dates. A typical project includes a deposit upon signing, a mid-project payment when base preparation is complete, and a final payment after your walkthrough and approval. The exact milestones are outlined in your contract before work begins.',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'cta_banner',
                'section_key' => 'process_cta',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Ready to Get Started?',
                    'subheading' => 'Book your consultation and take the first step toward the outdoor space you have been dreaming about.',
                    'button_text' => 'Book a Consultation',
                    'button_url' => '/contact',
                    'style' => 'forest',
                ],
            ],
        ];
    }

    // =========================================================================
    // WARRANTY & MAINTENANCE
    // =========================================================================
    private function warrantyMaintenance(): array
    {
        return [
            [
                'block_type' => 'section_header',
                'section_key' => 'warranty_header',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Your Investment is Protected',
                    'subtitle' => 'Every Lush Landscape project comes with a written workmanship warranty because we believe you should have complete confidence in the work we do on your property.',
                    'align' => 'center',
                    'tag' => 'Warranty Coverage',
                    'show_line' => true,
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'warranty_intro',
                'is_enabled' => true,
                'content' => [
                    'html' => '<p>At Lush Landscape Service, we stand behind our work. Every project we complete is backed by a written workmanship warranty that covers installation defects and material failures resulting from our work. This is not a vague promise. It is a documented commitment with clear terms, and it is included at no additional cost with every project.</p>'
                        .'<p>Our warranty reflects the confidence we have in our installation methods. We invest the time and effort in proper base preparation, quality materials, and industry-standard techniques specifically because they produce results that hold up to Ontario weather conditions year after year.</p>',
                ],
            ],
            [
                'block_type' => 'cards_grid',
                'section_key' => 'warranty_tiers',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Warranty Coverage by Service Type',
                    'columns' => '3',
                    'cards' => [
                        [
                            'icon' => 'layers',
                            'media_id' => null,
                            'title' => 'Hardscape Installations',
                            'description' => 'Interlocking pavers, retaining walls, natural stone patios, steps, and concrete work are covered for up to 10 years against settling, shifting, and installation defects when proper maintenance guidelines are followed.',
                            'link_url' => '',
                            'link_text' => '',
                        ],
                        [
                            'icon' => 'trees',
                            'media_id' => null,
                            'title' => 'Softscape and Plantings',
                            'description' => 'Trees, shrubs, perennials, and sod installed by our team are covered for one full growing season. If any plant material fails to establish under normal conditions and with recommended care, we will replace it at no cost.',
                            'link_url' => '',
                            'link_text' => '',
                        ],
                        [
                            'icon' => 'droplets',
                            'media_id' => null,
                            'title' => 'Drainage and Grading',
                            'description' => 'Drainage solutions, grading work, and water management systems are covered for up to 5 years. This includes French drains, catch basins, downspout extensions, and surface grading designed to direct water away from your foundation.',
                            'link_url' => '',
                            'link_text' => '',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'feature_list',
                'section_key' => 'warranty_covered',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'What Your Warranty Covers',
                    'columns' => '2',
                    'features' => [
                        [
                            'icon' => 'check-circle',
                            'title' => 'Base and Structural Settling',
                            'description' => 'If the base material or installation settles, shifts, or fails due to improper preparation by our crew, we will return and correct the issue at no cost.',
                        ],
                        [
                            'icon' => 'check-circle',
                            'title' => 'Material Defects from Installation',
                            'description' => 'If a material defect is caused by improper installation methods used by our team, the repair or replacement is fully covered under your warranty terms.',
                        ],
                        [
                            'icon' => 'check-circle',
                            'title' => 'Drainage and Water Flow Issues',
                            'description' => 'If drainage systems we installed fail to perform as designed under normal weather conditions, we will assess and correct the problem within the warranty period.',
                        ],
                        [
                            'icon' => 'check-circle',
                            'title' => 'Plant Establishment Failure',
                            'description' => 'Trees, shrubs, and perennials that fail to establish within the first growing season despite following our recommended care instructions will be replaced.',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'alert_box',
                'section_key' => 'warranty_exclusions',
                'is_enabled' => true,
                'content' => [
                    'title' => 'Important: What the Warranty Does Not Cover',
                    'text' => 'Our warranty does not cover damage caused by acts of nature (floods, ice storms, falling trees), modifications made by third parties after our installation, failure to follow recommended maintenance procedures, normal weathering and colour fading of natural materials, or damage resulting from snow removal equipment or de-icing salts. Specific exclusions are outlined in your project warranty document.',
                    'type' => 'info',
                    'dismissible' => false,
                ],
            ],
            [
                'block_type' => 'steps_process',
                'section_key' => 'warranty_claim',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'How to File a Warranty Claim',
                    'layout' => 'horizontal',
                    'steps' => [
                        [
                            'title' => 'Contact Our Team',
                            'description' => 'Call us or send an email to info@lushlandscape.ca with your project details and a description of the issue. Include photos if possible.',
                            'icon' => 'phone',
                        ],
                        [
                            'title' => 'Site Inspection',
                            'description' => 'We will schedule a site visit within 5 business days to assess the issue and determine if it falls within warranty coverage.',
                            'icon' => 'search',
                        ],
                        [
                            'title' => 'Resolution',
                            'description' => 'If the claim is approved, we will schedule the repair work and complete it promptly at no cost to you.',
                            'icon' => 'wrench',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'section_header',
                'section_key' => 'maintenance_header',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Seasonal Maintenance Guide',
                    'subtitle' => 'Proper maintenance helps your landscape investment look its best and last longer. Here are our recommendations for Ontario homeowners throughout the year.',
                    'align' => 'center',
                    'tag' => 'Maintenance Tips',
                    'show_line' => true,
                ],
            ],
            [
                'block_type' => 'accordion',
                'section_key' => 'maintenance_seasons',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Maintenance by Season',
                    'items' => [
                        [
                            'question' => 'Spring (March to May)',
                            'answer' => 'Inspect all hardscape surfaces for any winter damage or frost heaving. Sweep sand back into paver joints and apply polymeric sand if needed. Clear debris from drainage channels and catch basins. Cut back dead perennial growth and apply a balanced fertilizer to lawns and garden beds. This is also the best time to apply a fresh layer of mulch to garden beds, maintaining a depth of 2 to 3 inches.',
                        ],
                        [
                            'question' => 'Summer (June to August)',
                            'answer' => 'Water newly planted trees and shrubs deeply once per week during dry periods. Established lawns benefit from 1 to 1.5 inches of water per week, applied in the early morning. Pull weeds from paver joints before they establish deep roots. Inspect retaining walls for any signs of movement. If you notice pooling water or drainage concerns, contact us before the fall rains begin.',
                        ],
                        [
                            'question' => 'Fall (September to November)',
                            'answer' => 'Remove fallen leaves from paver and stone surfaces promptly to prevent staining. Apply a pre-emergent weed treatment to paver joints. Disconnect and drain any above-ground water features or irrigation lines before the first frost. Plant spring-blooming bulbs in garden beds. Late fall is an excellent time to aerate and overseed your lawn.',
                        ],
                        [
                            'question' => 'Winter (December to February)',
                            'answer' => 'Use a plastic shovel or snow blower on paver surfaces. Avoid metal-edged shovels, which can chip or scratch pavers. Do not use rock salt or calcium chloride de-icers on interlocking or natural stone surfaces, as they can cause surface damage and efflorescence. Sand or non-chloride alternatives are recommended. Keep drainage channels clear of ice and snow buildup.',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'cta_banner',
                'section_key' => 'warranty_cta',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Questions About Your Warranty?',
                    'subheading' => 'Contact our team for warranty inquiries, maintenance advice, or to schedule a seasonal inspection of your landscape.',
                    'button_text' => 'Contact Us',
                    'button_url' => '/contact',
                    'style' => 'cream',
                ],
            ],
        ];
    }

    // =========================================================================
    // FINANCING
    // =========================================================================
    private function financing(): array
    {
        return [
            [
                'block_type' => 'section_header',
                'section_key' => 'financing_header',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Flexible Payment Options for Your Project',
                    'subtitle' => 'We believe your dream outdoor space should be accessible. That is why we offer milestone-based payments and flexible options to fit your budget.',
                    'align' => 'center',
                    'tag' => 'Payment Options',
                    'show_line' => true,
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'financing_intro',
                'is_enabled' => true,
                'content' => [
                    'html' => '<p>Investing in professional landscaping adds lasting value to your property and quality of life. At Lush Landscape Service, we work with you to find a payment structure that makes your project comfortable and stress-free. Every project begins with a detailed scope plan and proposal so you know what to expect. No surprise add-ons, and no pressure.</p>',
                ],
            ],
            [
                'block_type' => 'cards_grid',
                'section_key' => 'financing_options',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'How We Structure Payments',
                    'columns' => '3',
                    'cards' => [
                        [
                            'icon' => 'wallet',
                            'media_id' => null,
                            'title' => 'Milestone-Based Payments',
                            'description' => 'For most projects, payments are tied to completion of key phases: deposit upon contract signing, a mid-project payment after base preparation is complete, and a final payment after your walkthrough and approval. You only pay as value is delivered.',
                            'link_url' => '',
                            'link_text' => '',
                        ],
                        [
                            'icon' => 'calendar-range',
                            'media_id' => null,
                            'title' => 'Extended Payment Plans',
                            'description' => 'For larger projects, we can arrange extended payment schedules that spread costs over a longer period. Terms are discussed during your consultation and customized based on your project scope and budget.',
                            'link_url' => '',
                            'link_text' => '',
                        ],
                        [
                            'icon' => 'credit-card',
                            'media_id' => null,
                            'title' => 'Multiple Payment Methods',
                            'description' => 'We accept cheques, e-transfers, bank drafts, and major credit cards. Choose the method that works best for you. All payment terms and accepted methods are confirmed in your project contract.',
                            'link_url' => '',
                            'link_text' => '',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'feature_list',
                'section_key' => 'financing_transparency',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Clear Scope & Payment Milestones',
                    'columns' => '2',
                    'features' => [
                        [
                            'icon' => 'file-text',
                            'title' => 'Itemized Scope',
                            'description' => 'Your scope plan and proposal includes a breakdown of materials, labour, equipment, permits, and site cleanup.',
                        ],
                        [
                            'icon' => 'lock',
                            'title' => 'Fixed Pricing',
                            'description' => 'Once you approve the scope and proposal, that is the basis for your project. Any scope changes are discussed and approved in writing first.',
                        ],
                        [
                            'icon' => 'ban',
                            'title' => 'No Pressure Sales',
                            'description' => 'Our consultations are designed to help you make an informed decision. You will never be pressured to commit on the spot.',
                        ],
                        [
                            'icon' => 'receipt',
                            'title' => 'Clear Invoicing',
                            'description' => 'Every invoice is clearly tied to a project milestone. You can see exactly what work has been completed, what materials have been used, and what remains in the project scope.',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'steps_process',
                'section_key' => 'financing_how_it_works',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Getting Started Is Simple',
                    'layout' => 'horizontal',
                    'steps' => [
                        [
                            'title' => 'Book Your Consultation',
                            'description' => 'Contact us to schedule an on-site visit. We assess your property, discuss your goals, and listen to your budget considerations.',
                            'icon' => 'calendar',
                        ],
                        [
                            'title' => 'Receive Your Scope Proposal',
                            'description' => 'We deliver a comprehensive scope plan with a clear payment schedule and milestone structure.',
                            'icon' => 'file-text',
                        ],
                        [
                            'title' => 'Choose Your Payment Plan',
                            'description' => 'We work together to determine a payment structure that fits your budget and aligns with the project timeline.',
                            'icon' => 'settings',
                        ],
                        [
                            'title' => 'We Build Your Dream Space',
                            'description' => 'With the plan and payment schedule agreed upon, our crew gets to work bringing your vision to life.',
                            'icon' => 'hard-hat',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'accordion',
                'section_key' => 'financing_faq',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Payment and Financing Questions',
                    'items' => [
                        [
                            'question' => 'What is the typical deposit amount?',
                            'answer' => 'Deposits typically range from 15% to 30% of the total project cost, depending on the scope and materials required. The exact deposit amount is outlined in your contract. For projects requiring specialty materials that need to be ordered in advance, the deposit may cover those material costs.',
                        ],
                        [
                            'question' => 'Do you offer financing through a third-party lender?',
                            'answer' => 'We can connect you with reputable lending partners who offer home improvement financing. Terms, rates, and eligibility are determined directly between you and the lender. We are happy to provide the documentation they require to process your application.',
                        ],
                        [
                            'question' => 'What happens if I need to pause or cancel a project?',
                            'answer' => 'If you need to pause a project, we will work with you to find a reasonable timeline for resumption. Cancellation terms, including any applicable charges for work completed and materials ordered, are outlined in your contract in accordance with Ontario consumer protection regulations.',
                        ],
                        [
                            'question' => 'Are there any hidden fees?',
                            'answer' => 'No. Your approved scope and proposal covers materials, labour, equipment, permits, delivery, and site cleanup. If an unforeseen site condition arises during installation, we discuss it with you and provide a written change order before any additional work or cost is incurred.',
                        ],
                        [
                            'question' => 'Can I split a large project into phases to spread out costs?',
                            'answer' => 'Yes. Phased projects are a practical way to achieve your complete vision over time while managing costs. We can design a multi-phase plan where each phase delivers a functional, finished result. This allows you to invest incrementally without compromising quality.',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'alert_box',
                'section_key' => 'financing_notice',
                'is_enabled' => true,
                'content' => [
                    'title' => 'Ontario Consumer Protection',
                    'text' => 'All Lush Landscape service agreements comply with the Ontario Consumer Protection Act, 2002. You are entitled to a 10-day cooling-off period on contracts signed at your home. Full contract terms, including cancellation rights, are provided in writing before any work begins.',
                    'type' => 'info',
                    'dismissible' => false,
                ],
            ],
            [
                'block_type' => 'cta_banner',
                'section_key' => 'financing_cta',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Let Us Build a Plan That Works for You',
                    'subheading' => 'Contact us to schedule a consultation. We will discuss your project vision and create a payment plan that fits your budget.',
                    'button_text' => 'Book a Consultation',
                    'button_url' => '/contact',
                    'style' => 'forest',
                ],
            ],
        ];
    }

    // =========================================================================
    // PERMITS & REGULATIONS
    // =========================================================================
    private function permitsRegulations(): array
    {
        return [
            [
                'block_type' => 'section_header',
                'section_key' => 'permits_header',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Permits and Regulations for Ontario Landscaping Projects',
                    'subtitle' => 'Navigating municipal permits, building codes, and conservation authority requirements can be complex. We handle the paperwork so you do not have to.',
                    'align' => 'center',
                    'tag' => 'Compliance',
                    'show_line' => true,
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'permits_intro',
                'is_enabled' => true,
                'content' => [
                    'html' => '<p>Many landscaping projects in Ontario require permits, approvals, or compliance with specific regulations before work can begin. The requirements vary by municipality, project type, and proximity to regulated areas such as conservation lands or waterways. At Lush Landscape Service, we manage the entire permit process on your behalf, ensuring your project is fully compliant before, during, and after construction.</p>'
                        .'<p>Our experience working across 10 municipalities in the Greater Toronto and Hamilton Area means we know the local requirements, the typical processing timelines, and the right contacts at each municipal office and conservation authority.</p>',
                ],
            ],
            [
                'block_type' => 'cards_grid',
                'section_key' => 'permits_types',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Common Permit Requirements by Project Type',
                    'columns' => '2',
                    'cards' => [
                        [
                            'icon' => 'home',
                            'media_id' => null,
                            'title' => 'Retaining Walls Over 1 Metre',
                            'description' => 'Under the Ontario Building Code, retaining walls exceeding 1 metre (approximately 3.3 feet) in exposed height typically require a building permit. Engineering drawings and site plans may be required as part of the application.',
                            'link_url' => '',
                            'link_text' => '',
                        ],
                        [
                            'icon' => 'fence',
                            'media_id' => null,
                            'title' => 'Fences and Privacy Screens',
                            'description' => 'Most Ontario municipalities regulate fence height, setback requirements, and material standards. Heights are generally limited to 1.2 metres in front yards and 1.8 metres in rear and side yards, though specific bylaws vary by city.',
                            'link_url' => '',
                            'link_text' => '',
                        ],
                        [
                            'icon' => 'shovel',
                            'media_id' => null,
                            'title' => 'Grading and Drainage Changes',
                            'description' => 'Altering the grading or drainage pattern of your property may require a grading permit, particularly if the changes affect neighbouring properties or municipal stormwater systems. Some municipalities also enforce lot grading certificates.',
                            'link_url' => '',
                            'link_text' => '',
                        ],
                        [
                            'icon' => 'trees',
                            'media_id' => null,
                            'title' => 'Tree Removal and Protection',
                            'description' => 'Many municipalities in the GTA have tree preservation bylaws that require permits before removing trees above a certain size. Protected species and heritage trees may have additional restrictions. We assess your property and handle any required permits.',
                            'link_url' => '',
                            'link_text' => '',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'feature_list',
                'section_key' => 'permits_what_we_handle',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'How We Handle Permits for You',
                    'columns' => '2',
                    'features' => [
                        [
                            'icon' => 'file-search',
                            'title' => 'Requirement Assessment',
                            'description' => 'During your consultation, we assess which permits and approvals your specific project requires based on its scope, your property location, and the applicable municipal bylaws.',
                        ],
                        [
                            'icon' => 'file-plus',
                            'title' => 'Application Preparation',
                            'description' => 'We prepare all required documents, including site plans, grading plans, engineering drawings (when applicable), and permit applications.',
                        ],
                        [
                            'icon' => 'send',
                            'title' => 'Submission and Follow-Up',
                            'description' => 'We submit applications to the appropriate municipal offices and conservation authorities, and follow up regularly until approvals are granted.',
                        ],
                        [
                            'icon' => 'check-square',
                            'title' => 'Inspection Coordination',
                            'description' => 'For projects requiring inspections during or after construction, we schedule and coordinate all inspections with municipal building officials.',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'accordion',
                'section_key' => 'permits_by_city',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Municipality-Specific Notes',
                    'items' => [
                        [
                            'question' => 'Hamilton',
                            'answer' => 'The City of Hamilton requires building permits for retaining walls over 1 metre and decks attached to a dwelling. The Hamilton Conservation Authority regulates work near the Niagara Escarpment, Cootes Paradise, and several designated watercourses. Permit processing times are typically 10 to 15 business days for standard applications.',
                        ],
                        [
                            'question' => 'Burlington and Oakville (Halton Region)',
                            'answer' => 'Halton Region municipalities enforce specific lot grading requirements and tree preservation bylaws. Conservation Halton regulates development near watercourses, wetlands, and the Niagara Escarpment Plan area. Oakville has particularly stringent heritage district guidelines in the Old Oakville area.',
                        ],
                        [
                            'question' => 'Mississauga and Brampton (Peel Region)',
                            'answer' => 'The City of Mississauga requires site alteration permits for significant grading changes. Brampton enforces lot grading certificates for new construction. Both cities have tree removal bylaws with specific diameter thresholds. The Toronto and Region Conservation Authority (TRCA) and Credit Valley Conservation (CVC) regulate work near major watercourses in the region.',
                        ],
                        [
                            'question' => 'Toronto',
                            'answer' => 'Toronto has comprehensive bylaws covering tree protection (any privately owned tree with a trunk diameter of 30 cm or more), ravine and natural feature protection, and specific fence and retaining wall requirements. Permit processing times in Toronto are typically longer than surrounding municipalities.',
                        ],
                        [
                            'question' => 'Vaughan, Richmond Hill, and York Region',
                            'answer' => 'York Region municipalities enforce specific stormwater management requirements for hardscape projects. The TRCA regulates development near the Humber River, Don River, and Rouge River watersheds. Richmond Hill has additional requirements for properties near the Oak Ridges Moraine.',
                        ],
                        [
                            'question' => 'Milton and Georgetown (Halton Hills)',
                            'answer' => 'Halton Hills enforces site alteration bylaws and has specific requirements for properties within the Niagara Escarpment Plan area. Conservation Halton is the primary conservation authority. Milton and Georgetown have distinct heritage area requirements that may apply to landscaping modifications.',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'alert_box',
                'section_key' => 'permits_utility',
                'is_enabled' => true,
                'content' => [
                    'title' => 'Utility Locates Are Required Before Excavation',
                    'text' => 'Ontario law requires that utility locates be completed before any excavation work begins. This identifies the location of underground gas lines, electrical cables, water mains, and telecommunications infrastructure. Lush Landscape arranges all utility locates through Ontario One Call before starting any project that involves digging. This is a mandatory safety requirement, not optional.',
                    'type' => 'warning',
                    'dismissible' => false,
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'permits_obc',
                'is_enabled' => true,
                'content' => [
                    'html' => '<h2>Ontario Building Code Requirements</h2>'
                        .'<p>The Ontario Building Code (OBC) sets province-wide standards for construction, including certain landscaping structures. Key requirements that commonly apply to residential landscaping projects include:</p>'
                        .'<ul>'
                        .'<li><strong>Retaining walls</strong> over 1 metre in exposed height require a building permit and may require engineered drawings.</li>'
                        .'<li><strong>Decks and raised platforms</strong> attached to a dwelling or higher than 0.6 metres above grade require a building permit.</li>'
                        .'<li><strong>Pool enclosures and fencing</strong> around swimming pools must comply with OBC barrier requirements, including minimum heights and gate specifications.</li>'
                        .'<li><strong>Site grading</strong> must maintain proper drainage away from building foundations, conforming to the approved lot grading plan where applicable.</li>'
                        .'</ul>'
                        .'<p>Our team is well-versed in these requirements and ensures that all work complies with the applicable codes and standards.</p>',
                ],
            ],
            [
                'block_type' => 'cta_banner',
                'section_key' => 'permits_cta',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Not Sure If Your Project Needs a Permit?',
                    'subheading' => 'Contact us for a free consultation. We will assess your project requirements and handle all necessary permits and approvals.',
                    'button_text' => 'Book Free Consultation',
                    'button_url' => '/contact',
                    'style' => 'forest',
                ],
            ],
        ];
    }

    // =========================================================================
    // AWARDS & CERTIFICATIONS
    // =========================================================================
    private function awardsCertifications(): array
    {
        return [
            [
                'block_type' => 'section_header',
                'section_key' => 'awards_header',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Credentials That Back Every Project',
                    'subtitle' => 'Our certifications, memberships, and professional standards are not just badges on a website. They represent the training, accountability, and commitment to quality that go into every project we complete.',
                    'align' => 'center',
                    'tag' => 'Our Credentials',
                    'show_line' => true,
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'awards_intro',
                'is_enabled' => true,
                'content' => [
                    'html' => '<p>Choosing a landscaping company is an important decision, and credentials matter. At Lush Landscape Service, we invest in the certifications, memberships, and training that ensure our work meets the highest industry standards. When you hire us, you are working with a team that is licensed, insured, trained, and accountable to the professional organizations that set the bar for landscaping excellence in Ontario.</p>',
                ],
            ],
            [
                'block_type' => 'cards_grid',
                'section_key' => 'awards_certifications',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Professional Certifications and Memberships',
                    'columns' => '3',
                    'cards' => [
                        [
                            'icon' => 'award',
                            'media_id' => null,
                            'title' => 'Landscape Ontario Member',
                            'description' => 'As active members of Landscape Ontario, the leading horticultural trades association in the province, we adhere to a strict code of ethics and uphold industry standards for design, installation, and environmental stewardship. Membership requires demonstrated competency and a commitment to ongoing professional development.',
                            'link_url' => '',
                            'link_text' => '',
                        ],
                        [
                            'icon' => 'shield-check',
                            'media_id' => null,
                            'title' => 'WSIB Certified',
                            'description' => 'We maintain active Workplace Safety and Insurance Board (WSIB) clearance on every project. This means our workers are covered by workplace insurance, and you, as the property owner, are protected from liability for any workplace injuries that occur on your property.',
                            'link_url' => '',
                            'link_text' => '',
                        ],
                        [
                            'icon' => 'umbrella',
                            'media_id' => null,
                            'title' => 'Fully Licensed and Insured',
                            'description' => 'We carry comprehensive commercial general liability insurance with coverage limits that protect your property and your investment. Proof of insurance is available upon request and is provided with every project contract.',
                            'link_url' => '',
                            'link_text' => '',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'feature_list',
                'section_key' => 'awards_standards',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Our Professional Standards',
                    'columns' => '2',
                    'features' => [
                        [
                            'icon' => 'book-open',
                            'title' => 'Ongoing Training and Development',
                            'description' => 'Our team participates in industry training programs, manufacturer certification courses, and Landscape Ontario educational events to stay current with best practices, new materials, and evolving installation techniques.',
                        ],
                        [
                            'icon' => 'check-square',
                            'title' => 'ICPI and NCMA Standards',
                            'description' => 'Our hardscape installations follow the guidelines established by the Interlocking Concrete Pavement Institute (ICPI) and the National Concrete Masonry Association (NCMA) for base preparation, installation, and joint stabilization.',
                        ],
                        [
                            'icon' => 'leaf',
                            'title' => 'Environmental Best Practices',
                            'description' => 'We incorporate sustainable practices into our work, including permeable paving options, responsible water management, native and adaptive plant selections, and waste reduction through proper material estimation and recycling.',
                        ],
                        [
                            'icon' => 'hard-hat',
                            'title' => 'Workplace Safety Program',
                            'description' => 'We maintain a formal workplace safety program that includes regular safety meetings, job site inspections, and compliance with the Ontario Occupational Health and Safety Act. Every crew member is trained in safe equipment operation and emergency procedures.',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'icon_grid',
                'section_key' => 'awards_why_matter',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Why Credentials Matter for Your Project',
                    'columns' => '3',
                    'style' => 'card',
                    'items' => [
                        [
                            'icon' => 'shield',
                            'title' => 'Your Property is Protected',
                            'description' => 'Our insurance and WSIB coverage mean you are never personally liable for accidents or damage during your project.',
                        ],
                        [
                            'icon' => 'badge-check',
                            'title' => 'Verified Quality Standards',
                            'description' => 'Landscape Ontario membership means our work is held to measurable industry standards, not just our own internal benchmarks.',
                        ],
                        [
                            'icon' => 'scale',
                            'title' => 'Accountability and Recourse',
                            'description' => 'Working with a credentialed, registered company gives you recourse through industry associations and consumer protection if any issues arise.',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'testimonial_card',
                'section_key' => 'awards_testimonial',
                'is_enabled' => true,
                'content' => [
                    'quote' => 'We interviewed three landscaping companies before choosing Lush Landscape. They were the only ones who proactively showed us their insurance certificates, WSIB clearance, and Landscape Ontario membership. That level of professionalism carried through to every detail of our project.',
                    'author' => 'Sarah and James K.',
                    'role' => 'Homeowners, Burlington',
                    'media_id' => null,
                    'rating' => '5',
                    'style' => 'featured',
                ],
            ],
            [
                'block_type' => 'cta_banner',
                'section_key' => 'awards_cta',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Work with a Team You Can Trust',
                    'subheading' => 'Book a consultation and see why Ontario homeowners choose a certified, insured, and Landscape Ontario-member company for their projects.',
                    'button_text' => 'Book a Consultation',
                    'button_url' => '/contact',
                    'style' => 'forest',
                ],
            ],
        ];
    }

    // =========================================================================
    // CAREERS
    // =========================================================================
    private function careers(): array
    {
        return [
            [
                'block_type' => 'section_header',
                'section_key' => 'careers_header',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Build Your Career with Lush Landscape',
                    'subtitle' => 'We are a growing team of skilled professionals who take pride in creating outdoor spaces that transform how families live. If you share that passion, we want to hear from you.',
                    'align' => 'center',
                    'tag' => 'Join Our Team',
                    'show_line' => true,
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'careers_intro',
                'is_enabled' => true,
                'content' => [
                    'html' => '<p>Lush Landscape Service is growing, and we are always looking for dedicated people who want to do meaningful work outdoors. Whether you are an experienced landscaper, a recent graduate, or someone looking to transition into a skilled trade, we offer a work environment where quality craftsmanship is valued and career development is supported.</p>'
                        .'<p>We believe that great projects start with great teams. That is why we invest in our people through competitive compensation, ongoing training, and a culture that respects every crew member as a vital contributor to our success.</p>',
                ],
            ],
            [
                'block_type' => 'feature_list',
                'section_key' => 'careers_benefits',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Why People Choose to Work at Lush Landscape',
                    'columns' => '2',
                    'features' => [
                        [
                            'icon' => 'dollar-sign',
                            'title' => 'Competitive Pay',
                            'description' => 'We offer competitive hourly rates and salary packages that reflect your skill level and experience. Performance bonuses are available for project leads and senior crew members.',
                        ],
                        [
                            'icon' => 'calendar',
                            'title' => 'Year-Round Opportunities',
                            'description' => 'While our peak season runs from spring through fall, we offer winter work opportunities in snow management and off-season project planning for qualified team members.',
                        ],
                        [
                            'icon' => 'graduation-cap',
                            'title' => 'Training and Development',
                            'description' => 'We invest in your growth through manufacturer training programs, Landscape Ontario courses, safety certifications, and on-the-job mentorship from experienced project leads.',
                        ],
                        [
                            'icon' => 'users',
                            'title' => 'Team Culture',
                            'description' => 'We are a close-knit team that takes pride in our work and supports each other. You will work alongside people who care about quality and who show up every day ready to do their best.',
                        ],
                        [
                            'icon' => 'trending-up',
                            'title' => 'Career Growth',
                            'description' => 'Many of our project leads and senior installers started as crew members. We promote from within and provide clear paths for advancement as you develop your skills.',
                        ],
                        [
                            'icon' => 'hard-hat',
                            'title' => 'Safety First',
                            'description' => 'Your safety matters. We maintain a comprehensive workplace safety program, provide all required personal protective equipment, and hold regular safety training sessions.',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'cards_grid',
                'section_key' => 'careers_positions',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Positions We Typically Hire For',
                    'columns' => '3',
                    'cards' => [
                        [
                            'icon' => 'hard-hat',
                            'media_id' => null,
                            'title' => 'Landscape Crew Members',
                            'description' => 'Entry-level to experienced installers who work on hardscape and softscape projects. Physical fitness, reliability, and a willingness to learn are essential. Experience with interlocking pavers, retaining walls, or garden installation is an asset.',
                            'link_url' => '/contact',
                            'link_text' => 'Apply Now',
                        ],
                        [
                            'icon' => 'clipboard-list',
                            'media_id' => null,
                            'title' => 'Project Leads',
                            'description' => 'Experienced landscapers who can manage a crew, communicate with clients, and oversee projects from start to finish. Strong organizational skills, leadership ability, and a minimum of 3 years of landscaping experience are required.',
                            'link_url' => '/contact',
                            'link_text' => 'Apply Now',
                        ],
                        [
                            'icon' => 'pen-tool',
                            'media_id' => null,
                            'title' => 'Design Consultants',
                            'description' => 'Client-facing professionals who conduct site consultations, develop project plans, and guide homeowners through the design process. A background in landscape design, horticulture, or a related field is preferred.',
                            'link_url' => '/contact',
                            'link_text' => 'Apply Now',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'steps_process',
                'section_key' => 'careers_hiring_process',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Our Hiring Process',
                    'layout' => 'horizontal',
                    'steps' => [
                        [
                            'title' => 'Submit Your Application',
                            'description' => 'Send us your resume and a brief message about your experience and what interests you about joining our team. Email info@lushlandscape.ca with the subject line "Career Application".',
                            'icon' => 'mail',
                        ],
                        [
                            'title' => 'Initial Conversation',
                            'description' => 'We will review your application and schedule a phone or in-person conversation to learn more about your background, skills, and career goals.',
                            'icon' => 'phone',
                        ],
                        [
                            'title' => 'Working Interview',
                            'description' => 'For field positions, we invite qualified candidates to join a crew for a paid working day. This gives both of us a chance to see if the fit is right.',
                            'icon' => 'briefcase',
                        ],
                        [
                            'title' => 'Welcome to the Team',
                            'description' => 'Successful candidates receive a formal offer, complete safety training, and are paired with an experienced team member for onboarding.',
                            'icon' => 'user-check',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'accordion',
                'section_key' => 'careers_faq',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Career Questions',
                    'items' => [
                        [
                            'question' => 'Do I need previous landscaping experience to apply?',
                            'answer' => 'Not necessarily. For crew member positions, we value reliability, physical fitness, and a willingness to learn. We provide on-the-job training and mentorship. For project lead and design positions, relevant experience is required.',
                        ],
                        [
                            'question' => 'What is a typical work schedule?',
                            'answer' => 'During peak season (April through November), crew members typically work Monday to Friday, starting early morning. Overtime and occasional Saturday work may be available. Winter schedules vary based on snow management contracts and off-season project availability.',
                        ],
                        [
                            'question' => 'Do you provide tools and equipment?',
                            'answer' => 'Yes. We provide all tools, equipment, and personal protective equipment required for the job. You are expected to arrive with appropriate work attire, including steel-toed boots.',
                        ],
                        [
                            'question' => 'Is there potential for year-round employment?',
                            'answer' => 'Yes, for qualified and reliable team members. We offer winter work through snow management contracts and off-season planning activities. Year-round positions are typically available for project leads and senior crew members.',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'cta_banner',
                'section_key' => 'careers_cta',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Ready to Join a Team That Values Your Skills?',
                    'subheading' => 'Send your resume to info@lushlandscape.ca or use the contact form below to get in touch. We look forward to hearing from you.',
                    'button_text' => 'Apply Now',
                    'button_url' => '/contact',
                    'style' => 'forest',
                ],
            ],
        ];
    }

    // =========================================================================
    // REFERRAL PROGRAM
    // =========================================================================
    private function referralProgram(): array
    {
        return [
            [
                'block_type' => 'section_header',
                'section_key' => 'referral_header',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Share the Lush Landscape Experience',
                    'subtitle' => 'Love your new outdoor space? Refer a friend, family member, or neighbour and you both benefit. Our referral program is our way of thanking you for spreading the word.',
                    'align' => 'center',
                    'tag' => 'Referral Program',
                    'show_line' => true,
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'referral_intro',
                'is_enabled' => true,
                'content' => [
                    'html' => '<p>The majority of our new clients come from personal referrals, and that is something we take great pride in. It means our clients are happy enough with their results to recommend us to the people they care about. Our referral program formalizes that gratitude with tangible rewards for both you and the person you refer.</p>'
                        .'<p>There is no limit to the number of people you can refer. Whether you are a past client, a current client with a project in progress, or simply someone who has experienced our work first-hand, you are eligible to participate.</p>',
                ],
            ],
            [
                'block_type' => 'steps_process',
                'section_key' => 'referral_how_it_works',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'How Our Referral Program Works',
                    'layout' => 'horizontal',
                    'steps' => [
                        [
                            'title' => 'Refer Someone You Know',
                            'description' => 'Tell a friend, family member, or neighbour about your experience with Lush Landscape. Give them our contact information or let us know their name and we will reach out directly.',
                            'icon' => 'users',
                        ],
                        [
                            'title' => 'They Book a Consultation',
                            'description' => 'When your referral contacts us and mentions your name, we note the referral and schedule their free on-site consultation.',
                            'icon' => 'calendar',
                        ],
                        [
                            'title' => 'Their Project Gets Completed',
                            'description' => 'Once your referral signs a contract and their project is completed, both of you earn your referral reward.',
                            'icon' => 'check-circle',
                        ],
                        [
                            'title' => 'You Both Get Rewarded',
                            'description' => 'You receive your referral reward as a thank-you, and your referral receives a welcome benefit on their project. Everyone wins.',
                            'icon' => 'gift',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'feature_list',
                'section_key' => 'referral_benefits',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Referral Program Benefits',
                    'columns' => '2',
                    'features' => [
                        [
                            'icon' => 'infinity',
                            'title' => 'No Referral Limit',
                            'description' => 'Refer as many people as you like. Each successful referral earns you a reward. Some of our top referrers have earned rewards on multiple projects.',
                        ],
                        [
                            'icon' => 'clock',
                            'title' => 'No Expiry Date',
                            'description' => 'There is no time limit on when your referral needs to book. Whether they contact us next week or next year, your referral credit remains active.',
                        ],
                        [
                            'icon' => 'handshake',
                            'title' => 'Both Parties Benefit',
                            'description' => 'Your referral is not just helping you earn a reward. They also receive a benefit on their project, making it a genuine win for both sides.',
                        ],
                        [
                            'icon' => 'repeat',
                            'title' => 'Stackable with Other Offers',
                            'description' => 'Referral rewards can be combined with any seasonal promotions or offers that may be available at the time of your referral completing their project.',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'accordion',
                'section_key' => 'referral_faq',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Referral Program Questions',
                    'items' => [
                        [
                            'question' => 'Who is eligible to make referrals?',
                            'answer' => 'Anyone who has worked with Lush Landscape Service or experienced our work first-hand is eligible to participate. You do not need to have a completed project to make a referral. Past clients, current clients, and members of the community are all welcome.',
                        ],
                        [
                            'question' => 'How does the referral get credited to me?',
                            'answer' => 'When your referral contacts us, they simply need to mention your name during the initial inquiry or consultation. We track all referrals internally and ensure both parties receive their rewards once the referred project is completed.',
                        ],
                        [
                            'question' => 'What kind of rewards are offered?',
                            'answer' => 'Referral rewards may include gift cards, credits toward future services, or other benefits. The specific reward details are available upon inquiry and may vary by season. Contact us to learn about the current referral reward offering.',
                        ],
                        [
                            'question' => 'Is there a minimum project size for the referral to qualify?',
                            'answer' => 'The referral program applies to landscaping projects that meet our standard minimum project threshold. Most residential projects qualify. Contact our team if you have questions about a specific referral situation.',
                        ],
                        [
                            'question' => 'Can I refer someone who has already contacted Lush Landscape?',
                            'answer' => 'To qualify as a referral, the person must be a new contact who has not previously inquired with or received a quote from Lush Landscape Service. The referral must be established before the person contacts us for their consultation.',
                        ],
                    ],
                ],
            ],
            [
                'block_type' => 'testimonial_card',
                'section_key' => 'referral_testimonial',
                'is_enabled' => true,
                'content' => [
                    'quote' => 'After Lush Landscape finished our backyard patio, three of our neighbours asked who did the work. We referred all three, and every one of them had a great experience. The referral rewards were a nice bonus, but honestly, we just wanted our neighbours to have the same quality experience we had.',
                    'author' => 'David and Lisa M.',
                    'role' => 'Homeowners, Mississauga',
                    'media_id' => null,
                    'rating' => '5',
                    'style' => 'featured',
                ],
            ],
            [
                'block_type' => 'cta_banner',
                'section_key' => 'referral_cta',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Know Someone Who Would Love a New Outdoor Space?',
                    'subheading' => 'Share our name and we will take care of the rest. Contact us to submit your referral or to learn more about the current rewards.',
                    'button_text' => 'Submit a Referral',
                    'button_url' => '/contact',
                    'style' => 'forest',
                ],
            ],
        ];
    }

    // =========================================================================
    // PRIVACY POLICY
    // =========================================================================
    private function privacyPolicy(): array
    {
        return [
            [
                'block_type' => 'section_header',
                'section_key' => 'privacy_header',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Privacy Policy',
                    'subtitle' => 'Lush Landscape Service is committed to protecting the privacy and security of your personal information. This policy explains how we collect, use, disclose, and safeguard your data.',
                    'align' => 'center',
                    'tag' => '',
                    'show_line' => true,
                ],
            ],
            [
                'block_type' => 'alert_box',
                'section_key' => 'privacy_updated',
                'is_enabled' => true,
                'content' => [
                    'title' => 'Last Updated: March 2026',
                    'text' => 'This Privacy Policy was last updated on March 24, 2026. We may update this policy from time to time to reflect changes in our practices or applicable legislation. When we make significant changes, we will post a notice on our website.',
                    'type' => 'info',
                    'dismissible' => false,
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'privacy_overview',
                'is_enabled' => true,
                'content' => [
                    'html' => '<h2>1. Overview</h2>'
                        .'<p>Lush Landscape Service ("we", "us", "our") operates the website lushlandscape.ca and provides landscaping services across Southern Ontario. This Privacy Policy describes how we collect, use, and protect the personal information of our website visitors, clients, and prospective clients in accordance with the Personal Information Protection and Electronic Documents Act (PIPEDA) and applicable provincial privacy legislation.</p>'
                        .'<p>By using our website or engaging our services, you consent to the collection, use, and disclosure of your personal information as described in this policy. If you do not agree with any part of this policy, please do not use our website or provide us with your personal information.</p>',
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'privacy_collection',
                'is_enabled' => true,
                'content' => [
                    'html' => '<h2>2. Information We Collect</h2>'
                        .'<p>We collect personal information that you voluntarily provide to us and information that is collected automatically when you visit our website.</p>'
                        .'<h3>Information You Provide Directly</h3>'
                        .'<ul>'
                        .'<li><strong>Contact information</strong>: your name, email address, phone number, and mailing address when you submit a contact form, request a consultation, or otherwise communicate with us.</li>'
                        .'<li><strong>Property information</strong>: details about your property, including address and project specifications, provided during consultations and project planning.</li>'
                        .'<li><strong>Payment information</strong>: payment details necessary to process transactions related to our services. Payment processing is handled through secure, PCI-compliant third-party processors.</li>'
                        .'<li><strong>Communications</strong>: the content of emails, messages, and other correspondence you send to us.</li>'
                        .'<li><strong>Referral information</strong>: if you participate in our referral program, the name and contact information of the person you refer, with their knowledge and consent.</li>'
                        .'</ul>'
                        .'<h3>Information Collected Automatically</h3>'
                        .'<ul>'
                        .'<li><strong>Device and browser information</strong>: your IP address, browser type, operating system, device type, and screen resolution.</li>'
                        .'<li><strong>Usage data</strong>: pages visited, time spent on pages, referring URLs, and navigation patterns on our website.</li>'
                        .'<li><strong>Cookies and similar technologies</strong>: we use cookies to improve your browsing experience, analyze website traffic, and understand usage patterns. You can manage cookie preferences through your browser settings.</li>'
                        .'</ul>',
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'privacy_use',
                'is_enabled' => true,
                'content' => [
                    'html' => '<h2>3. How We Use Your Information</h2>'
                        .'<p>We use the personal information we collect for the following purposes:</p>'
                        .'<ul>'
                        .'<li><strong>Providing services</strong>: to respond to your inquiries, schedule consultations, prepare quotes, manage projects, and deliver the landscaping services you have requested.</li>'
                        .'<li><strong>Communication</strong>: to send you project updates, respond to your questions, and provide customer support.</li>'
                        .'<li><strong>Business operations</strong>: to process payments, maintain project records, and manage our contractual relationships.</li>'
                        .'<li><strong>Marketing</strong>: with your consent, to send you information about our services, seasonal promotions, and maintenance tips. You can opt out of marketing communications at any time.</li>'
                        .'<li><strong>Website improvement</strong>: to analyze usage patterns, improve our website functionality, and enhance user experience.</li>'
                        .'<li><strong>Legal compliance</strong>: to comply with applicable laws, regulations, and legal processes.</li>'
                        .'</ul>',
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'privacy_disclosure',
                'is_enabled' => true,
                'content' => [
                    'html' => '<h2>4. Disclosure of Your Information</h2>'
                        .'<p>We do not sell, rent, or trade your personal information to third parties. We may share your information in the following limited circumstances:</p>'
                        .'<ul>'
                        .'<li><strong>Service providers</strong>: we may share information with trusted third-party service providers who assist us in operating our website, processing payments, or delivering services. These providers are contractually obligated to protect your information and use it only for the purposes we specify.</li>'
                        .'<li><strong>Legal requirements</strong>: we may disclose your information when required by law, court order, or government regulation, or when we believe disclosure is necessary to protect our rights, your safety, or the safety of others.</li>'
                        .'<li><strong>Business transfers</strong>: in the event of a merger, acquisition, or sale of all or a portion of our business, your personal information may be transferred to the successor entity.</li>'
                        .'</ul>'
                        .'<h2>5. Data Security</h2>'
                        .'<p>We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. These measures include encrypted data transmission (SSL/TLS), secure server infrastructure, access controls, and regular security assessments. However, no method of electronic transmission or storage is completely secure, and we cannot guarantee absolute security.</p>',
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'privacy_rights',
                'is_enabled' => true,
                'content' => [
                    'html' => '<h2>6. Your Privacy Rights Under PIPEDA</h2>'
                        .'<p>Under the Personal Information Protection and Electronic Documents Act (PIPEDA), you have the following rights regarding your personal information:</p>'
                        .'<ul>'
                        .'<li><strong>Access</strong>: you have the right to request access to the personal information we hold about you.</li>'
                        .'<li><strong>Correction</strong>: you have the right to request correction of any inaccurate or incomplete personal information.</li>'
                        .'<li><strong>Withdrawal of consent</strong>: you may withdraw your consent for the collection, use, or disclosure of your personal information at any time, subject to legal or contractual restrictions. Withdrawing consent may affect our ability to provide certain services.</li>'
                        .'<li><strong>Complaint</strong>: if you believe your privacy rights have been violated, you have the right to file a complaint with the Office of the Privacy Commissioner of Canada.</li>'
                        .'</ul>'
                        .'<p>To exercise any of these rights, please contact our Privacy Officer using the contact information provided below.</p>'
                        .'<h2>7. Data Retention</h2>'
                        .'<p>We retain your personal information only for as long as necessary to fulfill the purposes for which it was collected, to comply with legal obligations, resolve disputes, and enforce our agreements. Project records, including contracts and correspondence, are retained for a minimum of seven years in accordance with applicable Canadian tax and business regulations. Marketing consent records are maintained for the duration of the consent and for a reasonable period thereafter.</p>',
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'privacy_cookies',
                'is_enabled' => true,
                'content' => [
                    'html' => '<h2>8. Cookies and Tracking Technologies</h2>'
                        .'<p>Our website uses cookies and similar technologies to enhance your browsing experience and gather usage analytics. The types of cookies we use include:</p>'
                        .'<ul>'
                        .'<li><strong>Essential cookies</strong>: required for the website to function properly, including session management and security features.</li>'
                        .'<li><strong>Analytics cookies</strong>: help us understand how visitors interact with our website by collecting information about pages visited, time on site, and navigation patterns. This data is aggregated and anonymized.</li>'
                        .'<li><strong>Preference cookies</strong>: remember your settings and preferences to provide a more personalized experience on return visits.</li>'
                        .'</ul>'
                        .'<p>You can manage cookie preferences through your browser settings. Disabling certain cookies may affect the functionality of our website.</p>'
                        .'<h2>9. Third-Party Links</h2>'
                        .'<p>Our website may contain links to third-party websites or services that are not operated by us. We are not responsible for the privacy practices of these third parties. We encourage you to review the privacy policies of any third-party sites you visit.</p>'
                        .'<h2>10. Children\'s Privacy</h2>'
                        .'<p>Our website and services are not directed to individuals under the age of 18. We do not knowingly collect personal information from children. If we become aware that we have collected personal information from a child, we will take steps to delete that information promptly.</p>',
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'privacy_contact',
                'is_enabled' => true,
                'content' => [
                    'html' => '<h2>11. Contact Us</h2>'
                        .'<p>If you have questions about this Privacy Policy, wish to exercise your privacy rights, or have a concern about how we handle your personal information, please contact our Privacy Officer:</p>'
                        .'<p><strong>Lush Landscape Service</strong><br>'
                        .'Email: <a href="mailto:info@lushlandscape.ca">info@lushlandscape.ca</a><br>'
                        .'Website: <a href="/contact">lushlandscape.ca/contact</a></p>'
                        .'<p>If you are not satisfied with our response, you may contact the <strong>Office of the Privacy Commissioner of Canada</strong> at <a href="https://www.priv.gc.ca" target="_blank" rel="noopener noreferrer">www.priv.gc.ca</a> or by calling 1-800-282-1376.</p>',
                ],
            ],
        ];
    }

    // =========================================================================
    // TERMS & CONDITIONS
    // =========================================================================
    private function termsConditions(): array
    {
        return [
            [
                'block_type' => 'section_header',
                'section_key' => 'terms_header',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Terms and Conditions',
                    'subtitle' => 'Please read these terms carefully before using our website or engaging our services. By accessing our website or entering into a service agreement with Lush Landscape Service, you agree to be bound by these terms.',
                    'align' => 'center',
                    'tag' => '',
                    'show_line' => true,
                ],
            ],
            [
                'block_type' => 'alert_box',
                'section_key' => 'terms_updated',
                'is_enabled' => true,
                'content' => [
                    'title' => 'Last Updated: March 2026',
                    'text' => 'These Terms and Conditions were last updated on March 24, 2026. We reserve the right to update these terms at any time. Continued use of our website or services after changes are posted constitutes acceptance of the updated terms.',
                    'type' => 'info',
                    'dismissible' => false,
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'terms_general',
                'is_enabled' => true,
                'content' => [
                    'html' => '<h2>1. General</h2>'
                        .'<p>These Terms and Conditions ("Terms") govern your use of the website operated by Lush Landscape Service ("we", "us", "our") at lushlandscape.ca and the landscaping services we provide. These Terms, together with any service agreement or contract you sign with us, form the complete agreement between you and Lush Landscape Service.</p>'
                        .'<p>Our services are available to residents and property owners in the Province of Ontario, Canada. By using our website or engaging our services, you represent that you are at least 18 years of age and have the legal capacity to enter into binding agreements.</p>',
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'terms_website',
                'is_enabled' => true,
                'content' => [
                    'html' => '<h2>2. Website Use</h2>'
                        .'<p>You may use our website for lawful purposes only. You agree not to:</p>'
                        .'<ul>'
                        .'<li>Use the website in any way that violates applicable federal, provincial, or municipal laws or regulations.</li>'
                        .'<li>Attempt to gain unauthorized access to any part of the website, server, or database connected to the website.</li>'
                        .'<li>Transmit any material that is defamatory, offensive, or otherwise objectionable.</li>'
                        .'<li>Use automated systems, bots, or scrapers to access or collect data from the website without our written permission.</li>'
                        .'</ul>'
                        .'<p>We reserve the right to restrict or terminate your access to the website at our discretion if we believe you are violating these Terms.</p>'
                        .'<h2>3. Intellectual Property</h2>'
                        .'<p>All content on this website, including text, images, graphics, logos, design elements, and software, is the property of Lush Landscape Service or our licensors and is protected by Canadian copyright and trademark laws. You may not reproduce, distribute, modify, or create derivative works from any content on this website without our prior written consent.</p>'
                        .'<p>Project photographs displayed on our website may depict work completed on private properties. These images are used with the permission of the property owners and remain the intellectual property of Lush Landscape Service.</p>',
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'terms_services',
                'is_enabled' => true,
                'content' => [
                    'html' => '<h2>4. Service Agreements</h2>'
                        .'<p>All landscaping services are governed by a written service agreement (contract) that is separate from these website Terms. The service agreement specifies the scope of work, materials, timeline, payment terms, warranty coverage, and other project-specific details. In the event of any conflict between these Terms and a signed service agreement, the service agreement shall prevail.</p>'
                        .'<h3>Consultations and Estimates</h3>'
                        .'<p>Estimates provided by Lush Landscape Service are valid for 30 days from the date of issue unless otherwise stated. Estimates are based on the information available at the time of the site assessment and are subject to change if site conditions differ from what was observed. Any changes to the scope of work after contract signing will be documented in a written change order, which requires your approval before additional work proceeds.</p>'
                        .'<h3>Project Timeline</h3>'
                        .'<p>Estimated project timelines are provided in good faith based on the anticipated scope of work. Timelines may be affected by weather conditions, permit processing delays, material availability, or unforeseen site conditions. We will communicate any anticipated delays promptly and provide updated timelines.</p>',
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'terms_payment',
                'is_enabled' => true,
                'content' => [
                    'html' => '<h2>5. Payment Terms</h2>'
                        .'<p>Payment terms are outlined in your service agreement and typically follow a milestone-based schedule. Key payment terms include:</p>'
                        .'<ul>'
                        .'<li>A deposit is required upon signing the service agreement to secure your project date and order materials.</li>'
                        .'<li>Progress payments are tied to the completion of defined project milestones.</li>'
                        .'<li>Final payment is due upon completion of the project walkthrough and your approval of the finished work.</li>'
                        .'</ul>'
                        .'<p>We accept cheques, electronic transfers, bank drafts, and major credit cards. Late payments may be subject to interest charges as specified in your service agreement, in accordance with Ontario law.</p>'
                        .'<h2>6. Cancellation and Refund Policy</h2>'
                        .'<p>In accordance with the Ontario Consumer Protection Act, 2002, you are entitled to a 10-day cooling-off period for contracts signed at your home (direct agreements). During this period, you may cancel the contract without penalty.</p>'
                        .'<p>After the cooling-off period, cancellation terms are as follows:</p>'
                        .'<ul>'
                        .'<li>If you cancel before work has begun, you are responsible for any non-refundable material orders and a reasonable administration fee as outlined in your contract.</li>'
                        .'<li>If you cancel after work has commenced, you are responsible for payment of all work completed to date, materials delivered, and any non-cancellable material orders.</li>'
                        .'</ul>'
                        .'<p>All cancellations must be submitted in writing to info@lushlandscape.ca.</p>',
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'terms_warranty',
                'is_enabled' => true,
                'content' => [
                    'html' => '<h2>7. Warranty</h2>'
                        .'<p>Lush Landscape Service provides a written workmanship warranty on all completed projects. Warranty terms, coverage periods, and exclusions are detailed in your service agreement and on our <a href="/warranty">Warranty and Maintenance</a> page. The warranty covers defects in workmanship and installation. It does not cover damage caused by acts of nature, third-party modifications, neglect, or failure to follow recommended maintenance procedures.</p>'
                        .'<h2>8. Limitation of Liability</h2>'
                        .'<p>To the fullest extent permitted by Ontario law:</p>'
                        .'<ul>'
                        .'<li>Our total liability for any claim arising from our services shall not exceed the total amount paid by you for the specific project giving rise to the claim.</li>'
                        .'<li>We shall not be liable for any indirect, incidental, consequential, or punitive damages, including loss of use, loss of profits, or damage to property not directly related to the services provided.</li>'
                        .'<li>We are not liable for delays or failures in performance caused by circumstances beyond our reasonable control, including severe weather, natural disasters, labour disputes, material shortages, government actions, or pandemic-related restrictions.</li>'
                        .'</ul>'
                        .'<p>Nothing in these Terms limits or excludes liability that cannot be limited or excluded under Ontario law, including liability for death or personal injury caused by negligence.</p>',
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'terms_indemnity',
                'is_enabled' => true,
                'content' => [
                    'html' => '<h2>9. Property Access and Site Conditions</h2>'
                        .'<p>By engaging our services, you grant Lush Landscape Service reasonable access to your property for the purpose of completing the agreed-upon work. You are responsible for informing us of any known underground utilities, easements, or other conditions that may affect the project. While we arrange utility locates through Ontario One Call as required by law, accurate information from you helps ensure a safe and efficient project.</p>'
                        .'<h2>10. Dispute Resolution</h2>'
                        .'<p>If a dispute arises between you and Lush Landscape Service, we encourage you to contact us first to resolve the matter directly. If we are unable to reach a resolution, either party may pursue the following remedies:</p>'
                        .'<ul>'
                        .'<li><strong>Mediation</strong>: both parties agree to attempt mediation through a mutually agreed-upon mediator before initiating legal proceedings.</li>'
                        .'<li><strong>Legal proceedings</strong>: any legal action arising from these Terms or a service agreement shall be brought in the courts of the Province of Ontario, and both parties agree to submit to the jurisdiction of those courts.</li>'
                        .'</ul>'
                        .'<p>Nothing in this section limits your rights under the Ontario Consumer Protection Act, 2002, or any other applicable consumer protection legislation.</p>',
                ],
            ],
            [
                'block_type' => 'rich_text',
                'section_key' => 'terms_governing',
                'is_enabled' => true,
                'content' => [
                    'html' => '<h2>11. Governing Law</h2>'
                        .'<p>These Terms are governed by and construed in accordance with the laws of the Province of Ontario and the federal laws of Canada applicable therein. Any legal proceedings relating to these Terms shall be instituted in the courts of Ontario.</p>'
                        .'<h2>12. Severability</h2>'
                        .'<p>If any provision of these Terms is found to be invalid, illegal, or unenforceable by a court of competent jurisdiction, the remaining provisions shall continue in full force and effect.</p>'
                        .'<h2>13. Entire Agreement</h2>'
                        .'<p>These Terms, together with our Privacy Policy and any signed service agreement, constitute the entire agreement between you and Lush Landscape Service regarding the subject matter herein. Any prior agreements, representations, or understandings, whether written or oral, are superseded by these Terms.</p>'
                        .'<h2>14. Contact Information</h2>'
                        .'<p>If you have questions about these Terms and Conditions, please contact us:</p>'
                        .'<p><strong>Lush Landscape Service</strong><br>'
                        .'Email: <a href="mailto:info@lushlandscape.ca">info@lushlandscape.ca</a><br>'
                        .'Website: <a href="/contact">lushlandscape.ca/contact</a></p>',
                ],
            ],
            [
                'block_type' => 'accordion',
                'section_key' => 'terms_faq',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Frequently Asked Questions About Our Terms',
                    'items' => [
                        [
                            'question' => 'What is the cooling-off period mentioned in the cancellation policy?',
                            'answer' => 'Under the Ontario Consumer Protection Act, 2002, when you sign a contract at your home (a "direct agreement"), you have a 10-day cooling-off period during which you can cancel the contract for any reason without penalty. This right applies to most residential landscaping contracts signed on your property.',
                        ],
                        [
                            'question' => 'What happens if you discover unexpected site conditions during my project?',
                            'answer' => 'If we encounter unexpected conditions such as underground rock, buried debris, or soil issues that affect the project scope, we will stop work on the affected area, document the condition, and discuss your options. Any additional work or cost will be outlined in a written change order that requires your approval before we proceed.',
                        ],
                        [
                            'question' => 'Can I request changes to the scope of work after signing the contract?',
                            'answer' => 'Yes. Changes to the scope of work are handled through a formal change order process. We will provide a written description of the change, its impact on the timeline, and any cost adjustment. The change order must be approved and signed by both parties before the additional work begins.',
                        ],
                        [
                            'question' => 'How long are your estimates valid?',
                            'answer' => 'Unless otherwise stated, estimates are valid for 30 days from the date of issue. After 30 days, material prices and availability may have changed, and an updated estimate may be required. We will always confirm pricing before asking you to sign a contract.',
                        ],
                    ],
                ],
            ],
        ];
    }
}
