@extends('layouts.site')
@section('title', $product->name.' | Zenotic Biotech')

@section('content')
<section class="bg-gradient-to-r from-green-50 to-blue-50 py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <nav class="mb-8 flex flex-wrap items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-green-700">Home</a>
            <i data-lucide="chevron-right" class="h-4 w-4"></i>
            <a href="{{ route('products') }}" class="hover:text-green-700">Products</a>
            <i data-lucide="chevron-right" class="h-4 w-4"></i>
            <span class="font-medium text-gray-900">{{ $product->name }}</span>
        </nav>

        <div class="grid gap-10 lg:grid-cols-[1fr,1.05fr] lg:items-center">
            <div class="overflow-hidden rounded-lg border border-white bg-white shadow-xl">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-[380px] w-full object-cover">
                @else
                    <div class="flex h-[380px] items-center justify-center bg-gradient-to-br from-green-100 to-blue-100 text-7xl font-bold text-green-600">
                        {{ strtoupper(substr($product->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            <div>
                <div class="mb-5 flex flex-wrap gap-3">
                    @if($product->category)
                        <span class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-green-700 shadow-sm">{{ $product->category->name }}</span>
                    @endif
                    <span class="rounded-full bg-white px-4 py-2 text-sm font-semibold capitalize text-blue-700 shadow-sm">{{ $product->segment }}</span>
                </div>
                <h1 class="mb-5 text-4xl font-bold leading-tight text-gray-900 md:text-5xl">{{ $product->name }}</h1>
                <p class="mb-8 text-xl leading-relaxed text-gray-600">{{ $product->description }}</p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-6 py-3 font-semibold text-white shadow-lg shadow-green-200 hover:bg-green-700">
                        Request Information
                        <i data-lucide="arrow-right" class="h-5 w-5"></i>
                    </a>
                    <a href="{{ route('products') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-3 font-semibold text-gray-700 hover:bg-gray-50">
                        View All Products
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-16">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1fr,360px] lg:px-8">
        <article class="min-w-0">
            @if($product->parentContents->isNotEmpty())
                <div class="mb-8 rounded-lg border border-gray-200 bg-gray-50 p-6">
                    <h2 class="mb-4 text-xl font-bold text-gray-900">Table of Contents</h2>
                    <ol class="space-y-3">
                        @foreach($product->parentContents as $content)
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
                        @if($product->faqs->isNotEmpty())
                            <li>
                                <a href="#faqs" class="font-semibold text-green-700 hover:text-green-800">
                                    {{ $product->parentContents->count() + 1 }}. FAQs
                                </a>
                            </li>
                        @endif
                    </ol>
                </div>
            @endif

            @if($product->parentContents->isNotEmpty())
                <div class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-a:text-green-700 prose-strong:text-gray-900">
                    @foreach($product->parentContents as $content)
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
                <h2 class="mb-5 text-3xl font-bold text-gray-900">Product Details</h2>
                <p class="mb-8 text-lg leading-relaxed text-gray-600">{{ $product->description }}</p>
            @endif

            @if(! empty($product->features))
                <h3 class="mb-4 text-2xl font-bold text-gray-900">Key Features</h3>
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach($product->features as $feature)
                        <div class="flex gap-3 rounded-lg border border-green-100 bg-green-50 p-4">
                            <i data-lucide="check-circle-2" class="mt-0.5 h-5 w-5 flex-none text-green-600"></i>
                            <span class="font-medium text-gray-700">{{ $feature }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($product->faqs->isNotEmpty())
                <section id="faqs" class="mt-14 scroll-mt-28">
                    <h2 class="mb-6 text-3xl font-bold text-gray-900">FAQs</h2>
                    <div class="space-y-4">
                        @foreach($product->faqs as $faq)
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

        <aside class="h-fit rounded-lg border border-gray-200 bg-gray-50 p-6">
            <h2 class="mb-4 text-xl font-bold text-gray-900">Product Snapshot</h2>
            <dl class="space-y-4 text-sm">
                <div>
                    <dt class="font-semibold text-gray-900">Category</dt>
                    <dd class="mt-1 text-gray-600">{{ $product->category?->name ?? 'Microbiome Solution' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-gray-900">Segment</dt>
                    <dd class="mt-1 capitalize text-gray-600">{{ $product->segment }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-gray-900">Availability</dt>
                    <dd class="mt-1 text-gray-600">Contact our team for formulation and supply details.</dd>
                </div>
            </dl>
        </aside>
    </div>
</section>

@if($relatedProducts->isNotEmpty())
    <section class="bg-gray-50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="mb-8 text-3xl font-bold text-gray-900">Related Products</h2>
            <div class="grid gap-8 md:grid-cols-3">
                @foreach($relatedProducts as $related)
                    @include('pages._product-card', ['product' => $related])
                @endforeach
            </div>
        </div>
    </section>
@endif

<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $product->name,
    'description' => $product->description,
    'image' => $product->image_url,
    'brand' => [
        '@type' => 'Brand',
        'name' => 'Zenotic Biotech',
    ],
    'category' => $product->category?->name,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

@if($product->faqs->isNotEmpty())
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => $product->faqs->map(fn ($faq) => [
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
