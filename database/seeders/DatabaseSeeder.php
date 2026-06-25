<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DefaultOgImage;
use App\Models\DynamicPageSeo;
use App\Models\Product;
use App\Models\ProductContent;
use App\Models\ProductFaq;
use App\Models\Service;
use App\Models\ServiceContent;
use App\Models\ServiceFaq;
use App\Models\StaticPageSeo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdmin();
        $this->seedCatalog();
        $this->seedServices();
        $this->seedSeo();
    }

    private function seedAdmin(): void
    {
        User::updateOrCreate(
            ['email' => strtolower(env('ADMIN_EMAIL', 'prince@gmail.com'))],
            [
                'name' => env('ADMIN_NAME', 'Prince'),
                // 'password' cast = 'hashed', so a plain value is hashed on save.
                'password' => env('ADMIN_PASSWORD', 'Prince@1234'),
                'role' => 'admin',
            ]
        );
    }

    private function seedCatalog(): void
    {
        $products = [
            [
                'name' => 'GUT-PRO Advanced',
                'description' => 'High-potency multi-strain probiotic for optimal digestive health and immune support.',
                'category' => 'Probiotics',
                'segment' => 'human',
                'image_url' => 'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?auto=format&fit=crop&q=80&w=800',
                'features' => ['10 Broad Spectrum Strains', 'Delayed Release Capsules', '50 Billion CFU'],
                'order_index' => 1,
            ],
            [
                'name' => 'ImmunoBoost Elite',
                'description' => 'Synergistic blend of microbiome modulators and essential micronutrients for immune resilience.',
                'category' => 'Immune Health',
                'segment' => 'human',
                'image_url' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?auto=format&fit=crop&q=80&w=800',
                'features' => ['Clinically Proven Ingredients', 'Antioxidant Support', 'Microbiome Focused'],
                'order_index' => 2,
            ],
            [
                'name' => 'DermaPure Pro',
                'description' => 'Targeted probiotic solution for skin health, addressing the gut-skin axis.',
                'category' => 'Skin Health',
                'segment' => 'human',
                'image_url' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?auto=format&fit=crop&q=80&w=800',
                'features' => ['Specific Skin Strains', 'Reduces Inflammation', 'Improves Skin Barrier'],
                'order_index' => 3,
            ],
            [
                'name' => 'VetGut Canine Probiotic',
                'description' => 'Species-specific probiotic blend formulated to support digestive balance and stool quality in dogs.',
                'category' => 'Pet Digestive Health',
                'segment' => 'animal',
                'image_url' => 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&q=80&w=800',
                'features' => ['Canine-Specific Strains', 'Supports Healthy Digestion', 'Palatable Powder'],
                'order_index' => 1,
            ],
            [
                'name' => 'FelineImmune Plus',
                'description' => 'Microbiome modulator with prebiotics designed to strengthen immune resilience in cats.',
                'category' => 'Pet Immune Health',
                'segment' => 'animal',
                'image_url' => 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?auto=format&fit=crop&q=80&w=800',
                'features' => ['Feline-Optimized Formula', 'Prebiotic + Probiotic', 'Immune Support'],
                'order_index' => 2,
            ],
        ];

        // Ensure categories exist (preserve insertion order via order_index).
        $categoryNames = array_values(array_unique(array_column($products, 'category')));
        $categoryIds = [];
        foreach ($categoryNames as $i => $name) {
            $category = Category::updateOrCreate(
                ['name' => $name],
                ['order_index' => $i + 1]
            );
            $categoryIds[$name] = $category->id;
        }

        foreach ($products as $item) {
            Product::updateOrCreate(
                ['name' => $item['name']],
                [
                    'description' => $item['description'],
                    'category_id' => $categoryIds[$item['category']],
                    'segment' => $item['segment'],
                    'image_url' => $item['image_url'],
                    'features' => $item['features'],
                    'order_index' => $item['order_index'],
                    'status' => 'published',
                ]
            );
        }

        $this->seedProductDetails();
    }

    private function seedProductDetails(): void
    {
        $products = Product::query()->get();

        foreach ($products as $product) {
            $product->forceFill([
                'meta_title' => $product->meta_title ?: $product->name.' | Microbiome Product | Zenotic Biotech',
                'meta_keyword' => $product->meta_keyword ?: $product->name.', microbiome product, probiotic solution, Zenotic Biotech',
                'meta_description' => $product->meta_description ?: 'Learn about '.$product->name.', a Zenotic Biotech product developed for targeted microbiome health and wellness support.',
                'seo_rating' => $product->seo_rating ?: 4.8,
                'best_rating' => $product->best_rating ?: 5,
                'review_number' => $product->review_number ?: 84,
            ])->save();

            $overview = ProductContent::updateOrCreate(
                ['product_id' => $product->id, 'title' => 'Product Overview'],
                [
                    'description' => '<p>'.$product->description.'</p><p>This product is designed around Zenotic Biotech microbiome science, quality standards, and practical application needs.</p>',
                    'position' => 1,
                    'parent_id' => null,
                ]
            );

            ProductContent::updateOrCreate(
                ['product_id' => $product->id, 'title' => 'Key Benefits'],
                [
                    'description' => '<p>'.implode('</p><p>', array_map('e', $product->features ?? ['Supports microbiome balance', 'Developed for targeted wellness support'])).'</p>',
                    'position' => 2,
                    'parent_id' => $overview->id,
                ]
            );

            ProductContent::updateOrCreate(
                ['product_id' => $product->id, 'title' => 'Quality and Use'],
                [
                    'description' => '<p>Contact our team for formulation details, intended use guidance, documentation, and availability information.</p>',
                    'position' => 3,
                    'parent_id' => $overview->id,
                ]
            );

            ProductFaq::updateOrCreate(
                ['product_id' => $product->id, 'question' => 'How can I get more information about '.$product->name.'?'],
                ['answer' => '<p>You can contact Zenotic Biotech through the contact page for specifications, availability, and collaboration details.</p>']
            );

            ProductFaq::updateOrCreate(
                ['product_id' => $product->id, 'question' => 'Is this product available for custom requirements?'],
                ['answer' => '<p>Our team can discuss custom microbiome solution requirements based on project goals and target applications.</p>']
            );
        }
    }

    private function seedServices(): void
    {
        $services = [
            [
                'name' => 'Microbiome Analysis',
                'description' => 'Advanced DNA sequencing and profiling of microbial communities for clinical research.',
                'details' => 'Utilizing state-of-the-art metagenomic sequencing to provide comprehensive insights into microbiome composition and function.',
                'icon' => 'dna',
                'emoji' => '&#129516;',
                'order_index' => 1,
            ],
            [
                'name' => 'Custom Formulation',
                'description' => 'Developing tailored probiotic and prebiotic blends based on specific health targets.',
                'details' => 'Expert scientific team dedicated to creating proprietary formulations with optimal stability and efficacy.',
                'icon' => 'flask-conical',
                'emoji' => '&#129514;',
                'order_index' => 2,
            ],
            [
                'name' => 'Clinical Trial Support',
                'description' => 'Providing microbiome-focused solutions and analytics for pharmaceutical trials.',
                'details' => 'Full-spectrum support from study design to data interpretation, ensuring robust microbiome clinical data.',
                'icon' => 'bar-chart-3',
                'emoji' => '&#128202;',
                'order_index' => 3,
            ],
        ];

        foreach ($services as $item) {
            Service::updateOrCreate(
                ['name' => $item['name']],
                [
                    'description' => $item['description'],
                    'details' => $item['details'],
                    'icon' => $item['icon'],
                    'emoji' => $item['emoji'],
                    'order_index' => $item['order_index'],
                    'status' => 'published',
                ]
            );
        }

        $this->seedServiceDetails();
    }

    private function seedServiceDetails(): void
    {
        $details = [
            'Microbiome Analysis' => [
                'contents' => [
                    [
                        'title' => 'Comprehensive Microbiome Profiling',
                        'description' => '<p>We profile microbial communities using advanced sequencing-led workflows designed to reveal composition, diversity, and functional patterns.</p><p>These insights help research teams make clearer decisions across discovery, validation, and product development.</p>',
                        'position' => 1,
                        'children' => [
                            ['title' => 'Sample Planning', 'description' => '<p>Our team helps define sampling strategy, study objectives, and reporting expectations before analysis begins.</p>', 'position' => 2],
                            ['title' => 'Actionable Reporting', 'description' => '<p>Findings are summarized in clear reports that support interpretation, comparison, and next-step planning.</p>', 'position' => 3],
                        ],
                    ],
                ],
                'faqs' => [
                    ['question' => 'Who can use microbiome analysis services?', 'answer' => '<p>Research teams, product developers, clinical partners, and organizations studying microbiome composition can use this service.</p>'],
                    ['question' => 'Can reports be customized?', 'answer' => '<p>Yes. Reporting can be aligned with project objectives, target organisms, study design, and decision-making needs.</p>'],
                ],
            ],
            'Custom Formulation' => [
                'contents' => [
                    [
                        'title' => 'Tailored Probiotic and Prebiotic Development',
                        'description' => '<p>We develop targeted formulation concepts based on strain compatibility, stability, intended benefit, and commercial requirements.</p>',
                        'position' => 1,
                        'children' => [
                            ['title' => 'Ingredient Strategy', 'description' => '<p>Formulation planning includes active selection, excipient compatibility, delivery format, and feasibility review.</p>', 'position' => 2],
                            ['title' => 'Stability Considerations', 'description' => '<p>We consider processing, packaging, and storage requirements so formulations remain practical beyond the concept stage.</p>', 'position' => 3],
                        ],
                    ],
                ],
                'faqs' => [
                    ['question' => 'Can you create custom blends?', 'answer' => '<p>Yes. We can support custom probiotic, prebiotic, or combined microbiome-focused formulation concepts.</p>'],
                    ['question' => 'Do you support commercial launch planning?', 'answer' => '<p>We can assist with formulation documentation, practical feasibility, and development planning for commercial pathways.</p>'],
                ],
            ],
            'Clinical Trial Support' => [
                'contents' => [
                    [
                        'title' => 'Microbiome-Focused Trial Support',
                        'description' => '<p>We support microbiome-oriented studies with planning, data interpretation, and practical coordination across the trial lifecycle.</p>',
                        'position' => 1,
                        'children' => [
                            ['title' => 'Study Design Support', 'description' => '<p>We help align microbiome endpoints, sample workflows, and data needs with broader clinical objectives.</p>', 'position' => 2],
                            ['title' => 'Data Interpretation', 'description' => '<p>Our team helps translate microbiome data into useful scientific and operational insights.</p>', 'position' => 3],
                        ],
                    ],
                ],
                'faqs' => [
                    ['question' => 'Do you support pharmaceutical trials?', 'answer' => '<p>Yes. We support microbiome-focused solutions and analytics for pharmaceutical and nutraceutical study programs.</p>'],
                    ['question' => 'Can support start before protocol finalization?', 'answer' => '<p>Yes. Early involvement can help improve sampling strategy, endpoints, and data planning.</p>'],
                ],
            ],
        ];

        foreach ($details as $serviceName => $data) {
            $service = Service::where('name', $serviceName)->first();
            if (! $service) {
                continue;
            }

            foreach ($data['contents'] as $contentData) {
                $children = $contentData['children'] ?? [];
                unset($contentData['children']);

                $content = ServiceContent::updateOrCreate(
                    ['service_id' => $service->id, 'title' => $contentData['title']],
                    array_merge($contentData, ['parent_id' => null])
                );

                foreach ($children as $childData) {
                    ServiceContent::updateOrCreate(
                        ['service_id' => $service->id, 'title' => $childData['title']],
                        array_merge($childData, ['parent_id' => $content->id])
                    );
                }
            }

            foreach ($data['faqs'] as $faqData) {
                ServiceFaq::updateOrCreate(
                    ['service_id' => $service->id, 'question' => $faqData['question']],
                    ['answer' => $faqData['answer']]
                );
            }
        }
    }

    private function seedSeo(): void
    {
        $defaultOgPath = 'seo/default-og-images/zenotic-biotech-logo.png';
        $logoPath = public_path('logo_highquaity.png');

        if (is_file($logoPath) && ! Storage::disk('public')->exists($defaultOgPath)) {
            Storage::disk('public')->put($defaultOgPath, file_get_contents($logoPath));
        }

        DefaultOgImage::updateOrCreate(
            ['page' => 'all'],
            [
                'file_name' => 'zenotic-biotech-logo.png',
                'file_path' => $defaultOgPath,
                'default' => true,
            ]
        );

        $staticPages = [
            'home' => [
                'meta_title' => 'Zenotic Biotech | Microbiome Biotechnology Solutions {currentyear}',
                'meta_keyword' => 'Zenotic Biotech, microbiome biotechnology, probiotics, gut health, microbiome research',
                'meta_description' => 'Zenotic Biotech develops science-backed microbiome solutions, probiotic products, research services, and quality-focused biotechnology innovations.',
                'seo_rating' => 4.8,
                'best_rating' => 5,
                'review_number' => 126,
            ],
            'about' => [
                'meta_title' => 'About Zenotic Biotech | Microbiome Innovation Company',
                'meta_keyword' => 'about Zenotic Biotech, microbiome company, biotechnology India, probiotic innovation',
                'meta_description' => 'Learn about Zenotic Biotech, our mission, expertise, core values, and commitment to advancing microbiome-based health solutions.',
                'seo_rating' => 4.7,
                'best_rating' => 5,
                'review_number' => 84,
            ],
            'leadership' => [
                'meta_title' => 'Leadership Team | Zenotic Biotech',
                'meta_keyword' => 'Zenotic Biotech leadership, biotech experts, microbiome team, pharma professionals',
                'meta_description' => 'Meet the leadership team guiding Zenotic Biotech with experience across pharmaceuticals, biotechnology, research, and international markets.',
                'seo_rating' => 4.7,
                'best_rating' => 5,
                'review_number' => 71,
            ],
            'products' => [
                'meta_title' => 'Microbiome Products | Human and Animal Probiotics | Zenotic Biotech',
                'meta_keyword' => 'microbiome products, human probiotics, animal probiotics, gut health products, Zenotic Biotech products',
                'meta_description' => 'Explore Zenotic Biotech products for human and animal microbiome support, including probiotic and targeted wellness formulations.',
                'seo_rating' => 4.8,
                'best_rating' => 5,
                'review_number' => 143,
            ],
            'services' => [
                'meta_title' => 'Microbiome Services | Analysis, Formulation and Clinical Trial Support',
                'meta_keyword' => 'microbiome analysis, probiotic formulation, clinical trial support, biotechnology services',
                'meta_description' => 'Zenotic Biotech provides microbiome analysis, custom formulation, and clinical trial support for research and commercial biotechnology needs.',
                'seo_rating' => 4.9,
                'best_rating' => 5,
                'review_number' => 98,
            ],
            'research' => [
                'meta_title' => 'Research and Development | Zenotic Biotech',
                'meta_keyword' => 'microbiome research, biotechnology R&D, probiotic research, microbiome innovation',
                'meta_description' => 'Discover Zenotic Biotech research and innovation in microbiome science, product development, and quality-assured biotechnology.',
                'seo_rating' => 4.8,
                'best_rating' => 5,
                'review_number' => 112,
            ],
            'quality' => [
                'meta_title' => 'Quality Assurance and Compliance | Zenotic Biotech',
                'meta_keyword' => 'quality assurance, regulatory compliance, microbiome product testing, biotech quality',
                'meta_description' => 'Zenotic Biotech follows rigorous quality assurance, transparency, continuous improvement, and regulatory compliance standards.',
                'seo_rating' => 4.9,
                'best_rating' => 5,
                'review_number' => 105,
            ],
            'careers' => [
                'meta_title' => 'Careers at Zenotic Biotech | Biotechnology Jobs',
                'meta_keyword' => 'Zenotic Biotech careers, biotechnology jobs, microbiology jobs, probiotic research jobs',
                'meta_description' => 'Explore career opportunities at Zenotic Biotech for microbiology, research, clinical operations, product management, and biotech innovation.',
                'seo_rating' => 4.6,
                'best_rating' => 5,
                'review_number' => 63,
            ],
            'contact' => [
                'meta_title' => 'Contact Zenotic Biotech | Microbiome Solutions India',
                'meta_keyword' => 'contact Zenotic Biotech, microbiome solutions India, probiotic company contact',
                'meta_description' => 'Contact Zenotic Biotech for product inquiries, careers, microbiome services, research collaborations, and biotechnology solutions.',
                'seo_rating' => 4.7,
                'best_rating' => 5,
                'review_number' => 76,
            ],
        ];

        foreach ($staticPages as $pageName => $seo) {
            StaticPageSeo::updateOrCreate(
                ['page_name' => $pageName],
                array_merge($seo, [
                    'og_image_name' => 'zenotic-biotech-logo.png',
                    'og_image_path' => $defaultOgPath,
                ])
            );
        }

        $dynamicPages = [
            'product-detail-page' => [
                'meta_title' => '{name} | Microbiome Product | Zenotic Biotech',
                'meta_keyword' => '{name}, microbiome product, probiotic product, Zenotic Biotech',
                'meta_description' => 'Learn more about {name}, a Zenotic Biotech microbiome solution developed for targeted health and wellness support.',
                'seo_rating' => 4.8,
                'best_rating' => 5,
                'review_number' => 92,
            ],
            'service-detail-page' => [
                'meta_title' => '{name} | Microbiome Service | Zenotic Biotech',
                'meta_keyword' => '{name}, microbiome service, biotechnology support, Zenotic Biotech',
                'meta_description' => 'Explore {name} from Zenotic Biotech, designed to support microbiome research, development, analytics, and clinical applications.',
                'seo_rating' => 4.8,
                'best_rating' => 5,
                'review_number' => 88,
            ],
        ];

        foreach ($dynamicPages as $pageName => $seo) {
            DynamicPageSeo::updateOrCreate(
                ['page_name' => $pageName],
                array_merge($seo, [
                    'og_image_name' => 'zenotic-biotech-logo.png',
                    'og_image_path' => $defaultOgPath,
                ])
            );
        }
    }
}
