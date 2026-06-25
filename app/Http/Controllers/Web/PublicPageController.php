<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\DynamicPageSeo;
use App\Models\Lead;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class PublicPageController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'products' => $this->publishedProducts()->take(3),
            'services' => $this->serviceItems(),
            'team' => $this->team(),
        ]);
    }

    public function about(): View { return view('pages.about'); }
    public function leadership(): View { return view('pages.leadership', ['team' => $this->team()]); }
    public function services(): View { return view('pages.services', ['services' => $this->serviceItems()]); }
    public function research(): View { return view('pages.research'); }
    public function quality(): View { return view('pages.quality'); }
    public function contact(): View { return view('pages.contact'); }

    public function storeContact(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $lead = Lead::create([
            ...$data,
            'subject' => ($data['subject'] ?? null) ?: 'Contact enquiry',
            'source' => 'Contact Page',
            'source_path' => $request->headers->get('referer') ?: route('contact'),
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        $mailError = null;

        try {
            $settings = active_email_settings();
            $teamToEmail = $settings['to_email'] ?: config('mail.from.address');
            $teamToName = $settings['to_name'] ?: 'Zenotic Biotech';

            Mail::send('emails.leads.team', ['lead' => $lead], function ($message) use ($lead, $settings, $teamToEmail, $teamToName) {
                $message
                    ->to($teamToEmail, $teamToName)
                    ->replyTo($lead->email, $lead->name)
                    ->subject('New website lead: '.$lead->name);

                $this->addOptionalRecipient($message, 'cc', $settings['cc_email'] ?? null, $settings['cc_name'] ?? null, $teamToEmail);
                $this->addOptionalRecipient($message, 'bcc', $settings['bcc_email'] ?? null, $settings['bcc_name'] ?? null, $teamToEmail);
            });

            $lead->forceFill(['team_mail_sent_at' => now()])->save();
        } catch (Throwable $exception) {
            $mailError = 'Team mail: '.$exception->getMessage();
        }

        try {
            Mail::send('emails.leads.acknowledgement', ['lead' => $lead], function ($message) use ($lead) {
                $message
                    ->to($lead->email, $lead->name)
                    ->subject('We received your message | Zenotic Biotech');
            });

            $lead->forceFill(['lead_mail_sent_at' => now()])->save();
        } catch (Throwable $exception) {
            $mailError = trim(($mailError ? $mailError.' | ' : '').'Lead mail: '.$exception->getMessage());
        }

        if ($mailError) {
            $lead->forceFill(['mail_error' => Str::limit($mailError, 1000)])->save();
        }

        return back()->with('status', 'Thank you. Your enquiry has been submitted successfully. Our team will contact you soon.');
    }

    public function products(): View
    {
        $products = $this->publishedProducts();

        return view('pages.products', [
            'products' => $products,
            'humanProducts' => $products->where('segment', 'human'),
            'animalProducts' => $products->where('segment', 'animal'),
        ]);
    }

    public function productDetail(Product $product): View
    {
        abort_unless($product->status === 'published', 404);

        $product->load(['category', 'parentContents.childContents', 'faqs']);

        return view('pages.product-detail', [
            'product' => $product,
            'relatedProducts' => Product::query()
                ->with('category')
                ->published()
                ->whereKeyNot($product->id)
                ->where('segment', $product->segment)
                ->orderBy('order_index')
                ->take(3)
                ->get(),
            'seoMeta' => $this->dynamicSeoMeta('product-detail-page', $product->name, $product->image_url, $product),
        ]);
    }

    public function serviceDetail(Service $service): View
    {
        abort_unless($service->status === 'published', 404);
        $service->load([
            'parentContents.childContents',
            'faqs',
        ]);

        return view('pages.service-detail', [
            'service' => $service,
            'otherServices' => Service::query()
                ->published()
                ->whereKeyNot($service->id)
                ->orderBy('order_index')
                ->take(5)
                ->get(),
            'seoMeta' => $this->dynamicSeoMeta('service-detail-page', $service->name, null, $service),
        ]);
    }

    public function careers(): View
    {
        return view('pages.careers', ['careers' => $this->careerItems()]);
    }

    private function publishedProducts()
    {
        try {
            return Product::query()
                ->with('category')
                ->published()
                ->orderBy('order_index')
                ->orderByDesc('created_at')
                ->get();
        } catch (\Throwable) {
            return collect();
        }
    }

    private function addOptionalRecipient($message, string $method, ?string $email, ?string $name, ?string $primaryEmail): void
    {
        $email = trim((string) $email);

        if ($email === '' || Str::lower($email) === Str::lower((string) $primaryEmail)) {
            return;
        }

        $message->{$method}($email, $name ?: null);
    }

    private function team(): array
    {
        return [
            ['name' => 'Dhandapani E', 'designation' => 'Managing Director', 'bio' => 'Dhandapani E has vast international exposure, has successfully executed cross-country trade operations, and is a highly capable logistics and operations professional. He has a strong presence in the pharmaceutical industry and brings strategic leadership with deep industry expertise.', 'photo_url' => '/images/team/dhandapani.png', 'linkedin_url' => '#'],
            ['name' => 'Veerapandian NR', 'designation' => 'Executive Director', 'bio' => 'Veerapandian NR holds an MBA in International Business and an MFT in Foreign Trade, with vast international exposure and over 23 years of expertise in food products and pharmaceuticals. He has well-established networks across South Asia and the Middle East.', 'photo_url' => '/images/team/Veerapandian.jpeg', 'linkedin_url' => '#'],
            ['name' => 'Vijay Bagavathi RT', 'designation' => 'Chief Marketing Officer', 'bio' => 'Leading global sales strategies and business development initiatives across international pharmaceutical markets.', 'photo_url' => '/images/team/vijay.jpg', 'linkedin_url' => '#'],
        ];
    }

    private function serviceItems(): array
    {
        try {
            return Service::query()
                ->published()
                ->orderBy('order_index')
                ->orderBy('name')
                ->get()
                ->map(fn (Service $service) => [
                    'slug' => $service->slug,
                    'name' => $service->name,
                    'description' => $service->description,
                    'details' => $service->details,
                    'icon' => $service->icon,
                    'emoji' => $service->emoji ?: '&#9679;',
                ])
                ->all();
        } catch (\Throwable) {
            return [];
        }
    }

    private function dynamicSeoMeta(string $pageName, string $title, ?string $imageUrl = null, Product|Service|null $record = null): array
    {
        $seo = DynamicPageSeo::where('page_name', $pageName)->first();
        $tags = [
            '{name}' => $title,
            '{title}' => $title,
            '{currentmonth}' => date('M'),
            '{currentyear}' => date('Y'),
            '{site}' => url('/'),
            '[name]' => $title,
            '[title]' => $title,
            '[currentmonth]' => date('M'),
            '[currentyear]' => date('Y'),
            '[site]' => url('/'),
        ];

        $replace = fn (?string $value): ?string => $value ? strtr($value, $tags) : null;

        return [
            'title' => $replace($record?->meta_title ?? null) ?: $replace($seo?->meta_title) ?: $title.' | Zenotic Biotech',
            'keywords' => $replace($record?->meta_keyword ?? null) ?: $replace($seo?->meta_keyword),
            'description' => $replace($record?->meta_description ?? null) ?: $replace($seo?->meta_description),
            'image_path' => $record?->og_image_path ?: $seo?->og_image_path,
            'image_url' => $imageUrl,
            'seo_rating' => $record?->seo_rating ?: $seo?->seo_rating,
            'best_rating' => $record?->best_rating ?: $seo?->best_rating,
            'review_number' => $record?->review_number ?: $seo?->review_number,
        ];
    }

    private function careerItems(): array
    {
        return [
            ['title' => 'Senior Microbiologist', 'department' => 'Research & Development', 'location' => 'India', 'description' => 'Lead research initiatives in gut microbiome modulation and strain development.', 'requirements' => ['Ph.D. in Microbiology, Biotechnology, or related field', '5+ years of industrial microbiology experience', 'Expertise in anaerobic culturing and metagenomics', 'Strong publication record or patent portfolio']],
            ['title' => 'Clinical Research Coordinator', 'department' => 'Clinical Operations', 'location' => 'India', 'description' => 'Coordinate microbiome-focused clinical trials while ensuring GCP and regulatory compliance.', 'requirements' => ['Master degree in Life Sciences or Clinical Research', '2+ years in clinical trial management', 'Excellent organization and communication skills', 'Knowledge of regulatory submissions']],
            ['title' => 'Product Manager - Probiotics', 'department' => 'Marketing', 'location' => 'Remote / India', 'description' => 'Lead the product lifecycle for probiotic lines from market analysis to commercial launch.', 'requirements' => ['MBA with a Life Sciences background', 'Pharmaceutical or nutraceutical product experience', 'Strong analytical and strategic thinking', 'Ability to translate complex science into market strategy']],
        ];
    }
}
