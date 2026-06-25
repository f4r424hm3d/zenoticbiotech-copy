<!doctype html>
<html lang="en">
<head>
    @php
        $staticSeo = null;
        $defaultOgImage = null;
        $pageUrl = url()->current();
        $siteName = config('app.name', 'Zenotic Biotech');
        $replaceSeoTags = function (?string $value): ?string {
            if ($value === null) {
                return null;
            }

            $tags = [
                '{currentmonth}' => date('M'),
                '{currentyear}' => date('Y'),
                '{site}' => url('/'),
                '[currentmonth]' => date('M'),
                '[currentyear]' => date('Y'),
                '[site]' => url('/'),
            ];

            return strtr($value, $tags);
        };
        try {
            $routeName = request()->route()?->getName();
            $pathName = trim(request()->path(), '/') ?: 'home';
            $staticSeo = \App\Models\StaticPageSeo::whereIn('page_name', array_filter([$routeName, $pathName, '/'.$pathName]))->first();
            $defaultOgImage = \App\Models\DefaultOgImage::whereIn('page', array_filter([$routeName, $pathName, 'all']))
                ->orderByRaw("CASE WHEN page = ? THEN 0 WHEN page = ? THEN 1 ELSE 2 END", [$routeName, $pathName])
                ->first();
        } catch (\Throwable) {
            $staticSeo = null;
            $defaultOgImage = null;
        }
        $dynamicSeo = $seoMeta ?? [];
        $seoTitle = ($dynamicSeo['title'] ?? $replaceSeoTags($staticSeo?->meta_title)) ?: trim($__env->yieldContent('title', 'Zenotic Biotech'));
        $seoDescription = $dynamicSeo['description'] ?? $replaceSeoTags($staticSeo?->meta_description);
        $seoKeywords = $dynamicSeo['keywords'] ?? $replaceSeoTags($staticSeo?->meta_keyword);
        $seoImage = ($dynamicSeo['image_path'] ?? $staticSeo?->og_image_path) ?: $defaultOgImage?->file_path;
        $seoImageUrl = $dynamicSeo['image_url'] ?? ($seoImage ? asset('storage/'.$seoImage) : null);
        $seoRating = $dynamicSeo['seo_rating'] ?? $staticSeo?->seo_rating;
        $bestRating = ($dynamicSeo['best_rating'] ?? $staticSeo?->best_rating) ?: 5;
        $reviewNumber = (int) (($dynamicSeo['review_number'] ?? $staticSeo?->review_number) ?: 0);
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $seoTitle }}</title>
    @if($seoDescription)
        <meta name="description" content="{{ $seoDescription }}">
    @endif
    @if($seoKeywords)
        <meta name="keywords" content="{{ $seoKeywords }}">
    @endif
    <link rel="canonical" href="{{ $pageUrl }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    @if($seoDescription)
        <meta property="og:description" content="{{ $seoDescription }}">
    @endif
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $pageUrl }}">
    @if($seoImageUrl)
        <meta property="og:image" content="{{ $seoImageUrl }}">
        <meta name="twitter:image" content="{{ $seoImageUrl }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="{{ $siteName }}">
    <meta name="twitter:url" content="{{ $pageUrl }}">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    @if($seoDescription)
        <meta name="twitter:description" content="{{ $seoDescription }}">
    @endif
    @if($seoRating)
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'CreativeWorkSeries',
                'name' => $seoTitle,
                'aggregateRating' => [
                    '@type' => 'AggregateRating',
                    'ratingValue' => (float) $seoRating,
                    'bestRating' => (float) $bestRating,
                    'ratingCount' => $reviewNumber,
                ],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        .reveal { animation: reveal .45s ease both; }
        @keyframes reveal { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
        .prose h2 { font-size: 1.875rem; line-height: 2.25rem; font-weight: 800; margin: 2rem 0 1rem; }
        .prose h3 { font-size: 1.35rem; line-height: 1.9rem; font-weight: 800; margin: 1.5rem 0 .75rem; }
        .prose p { margin: 0 0 1rem; line-height: 1.8; color: #4b5563; }
        .prose ul, .prose ol { margin: 0 0 1rem 1.5rem; color: #4b5563; }
        .prose ul { list-style: disc; }
        .prose ol { list-style: decimal; }
        .prose a { color: #15803d; font-weight: 600; }
    </style>
</head>
<body class="min-h-screen bg-white text-gray-900 antialiased">
    @include('partials.navigation')
    <main class="min-h-screen pt-20 reveal">
        @yield('content')
    </main>
    @include('partials.footer')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            const menu = document.querySelector('[data-menu]');
            const button = document.querySelector('[data-menu-button]');
            button?.addEventListener('click', () => menu?.classList.toggle('hidden'));
        });
    </script>
</body>
</html>
