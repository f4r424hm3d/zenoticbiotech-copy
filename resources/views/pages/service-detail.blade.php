@extends('layouts.site')
@section('title', $service->name.' | Zenotic Biotech')

@section('content')
<section class="bg-gradient-to-r from-green-50 to-blue-50 py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <nav class="mb-8 flex flex-wrap items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-green-700">Home</a>
            <i data-lucide="chevron-right" class="h-4 w-4"></i>
            <a href="{{ route('services') }}" class="hover:text-green-700">Services</a>
            <i data-lucide="chevron-right" class="h-4 w-4"></i>
            <span class="font-medium text-gray-900">{{ $service->name }}</span>
        </nav>

        <div class="max-w-4xl">
            <div class="mb-6 text-6xl leading-none">{!! $service->emoji ?: '&#9679;' !!}</div>
            <h1 class="mb-5 text-4xl font-bold leading-tight text-gray-900 md:text-5xl">{{ $service->name }}</h1>
            <p class="text-xl leading-relaxed text-gray-600">{{ $service->description }}</p>
        </div>
    </div>
</section>

<section class="bg-white py-16">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1fr,360px] lg:px-8">
        <article class="min-w-0">
            @if($service->parentContents->isNotEmpty())
                <div class="mb-8 rounded-lg border border-gray-200 bg-gray-50 p-6">
                    <h2 class="mb-4 text-xl font-bold text-gray-900">Table of Contents</h2>
                    <ol class="space-y-3">
                        @foreach($service->parentContents as $content)
                            <li>
                                <a href="#{{ $content->slug }}" class="font-semibold text-green-700 hover:text-green-800">
                                    {{ $loop->iteration }}. {{ $content->title }}
                                </a>
                                @if($content->childContents->isNotEmpty())
                                    <ol class="mt-2 space-y-2 pl-5">
                                        @foreach($content->childContents as $child)
                                            <li>
                                                <a href="#{{ $child->slug }}" class="text-sm text-gray-600 hover:text-green-700">
                                                    {{ $loop->parent->iteration }}.{{ $loop->iteration }} {{ $child->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ol>
                                @endif
                            </li>
                        @endforeach
                        @if($service->faqs->isNotEmpty())
                            <li>
                                <a href="#faqs" class="font-semibold text-green-700 hover:text-green-800">
                                    {{ $service->parentContents->count() + 1 }}. FAQs
                                </a>
                            </li>
                        @endif
                    </ol>
                </div>
            @endif

            @if($service->parentContents->isNotEmpty())
                <div class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-a:text-green-700 prose-strong:text-gray-900">
                    @foreach($service->parentContents as $content)
                        <section class="mb-10 scroll-mt-28">
                            <h2 id="{{ $content->slug }}">{{ $content->title }}</h2>
                            {!! $content->description !!}

                            @foreach($content->childContents as $child)
                                <div class="mt-8 scroll-mt-28">
                                    <h3 id="{{ $child->slug }}">{{ $loop->parent->iteration }}.{{ $loop->iteration }} {{ $child->title }}</h3>
                                    {!! $child->description !!}
                                </div>
                            @endforeach
                        </section>
                    @endforeach
                </div>
            @else
                <div class="prose prose-lg max-w-none prose-headings:text-gray-900">
                    <h2>Service Details</h2>
                    <p>{{ $service->details ?: $service->description }}</p>
                    <p>Our team supports the full workflow from scientific planning and formulation strategy through quality review, documentation, and implementation guidance.</p>
                </div>
            @endif

            @if($service->faqs->isNotEmpty())
                <section id="faqs" class="mt-14 scroll-mt-28">
                    <h2 class="mb-6 text-3xl font-bold text-gray-900">FAQs</h2>
                    <div class="space-y-4">
                        @foreach($service->faqs as $faq)
                            <details class="group rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-gray-900">
                                    <span>{{ $faq->question }}</span>
                                    <i data-lucide="chevron-down" class="h-5 w-5 text-gray-500 transition group-open:rotate-180"></i>
                                </summary>
                                <div class="prose mt-4 max-w-none text-gray-600">
                                    {!! $faq->answer !!}
                                </div>
                            </details>
                        @endforeach
                    </div>
                </section>
            @endif
        </article>

        <aside class="space-y-6">
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h2 class="mb-4 text-xl font-bold text-gray-900">Discuss This Service</h2>
                <p class="mb-5 text-sm leading-relaxed text-gray-600">Share your goals with our team and we will help identify the right microbiome solution.</p>
                <a href="{{ route('contact') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-5 py-3 font-semibold text-white hover:bg-green-700">
                    Contact Us
                    <i data-lucide="arrow-right" class="h-5 w-5"></i>
                </a>
            </div>

            @if($otherServices->isNotEmpty())
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-xl font-bold text-gray-900">Other Services</h2>
                    <div class="space-y-3">
                        @foreach($otherServices as $item)
                            <a href="{{ route('services.show', $item) }}" class="flex items-center justify-between rounded-lg bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-700 transition hover:bg-green-50 hover:text-green-700">
                                <span>{{ $item->name }}</span>
                                <i data-lucide="arrow-right" class="h-4 w-4"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </div>
</section>

<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Service',
    'name' => $service->name,
    'description' => $service->description,
    'provider' => [
        '@type' => 'Organization',
        'name' => 'Zenotic Biotech',
    ],
    'areaServed' => 'Global',
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

@if($service->faqs->isNotEmpty())
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => $service->faqs->map(fn ($faq) => [
        '@type' => 'Question',
        'name' => $faq->question,
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => trim(preg_replace('/\s+/', ' ', strip_tags($faq->answer))),
        ],
    ])->values()->all(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif
@endsection
