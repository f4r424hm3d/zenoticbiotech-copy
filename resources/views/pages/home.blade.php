@extends('layouts.site')
@section('title', 'Zenotic Biotech | Balance Your Microbiome')
@section('content')
<section class="overflow-hidden bg-gradient-to-br from-green-50 via-white to-blue-50 py-20">
    <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
        <div>
            <h1 class="mb-6 text-5xl font-bold leading-tight text-gray-900 lg:text-6xl">Balance Your <span class="text-green-600">Microbiome</span></h1>
            <p class="mb-8 text-xl leading-relaxed text-gray-600">Zenotic Biotech pioneers innovative microbiome solutions for optimal health and wellness. Discover the science of microbial balance.</p>
            <div class="flex flex-col gap-4 sm:flex-row">
                <a href="{{ route('products') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-8 py-3 font-semibold text-white shadow-lg shadow-green-200 transition hover:bg-green-700">Explore Products <i data-lucide="arrow-right" class="h-5 w-5"></i></a>
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-lg border-2 border-green-600 px-8 py-3 font-semibold text-green-600 transition hover:bg-green-50">Get in Touch</a>
            </div>
        </div>
        <div class="flex justify-center">
            <img src="{{ cdn_asset('logo_highquaity.png') }}" alt="Zenotic Biotech" class="w-full max-w-md drop-shadow-2xl">
        </div>
    </div>
</section>

<section class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <h2 class="mb-4 text-4xl font-bold text-gray-900">About Zenotic Biotech</h2>
            <p class="mx-auto max-w-2xl text-lg text-gray-600">Leading the microbiome revolution with science-backed solutions and innovative approaches to health and wellness.</p>
        </div>
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
            <div class="rounded-lg bg-gradient-to-br from-green-50 to-green-100 p-8 shadow-sm">
                <h3 class="mb-4 text-2xl font-bold text-green-900">Our Mission</h3>
                <p class="text-green-800">To revolutionize human health through advanced microbiome research, developing scientifically validated solutions that restore and maintain microbial balance for optimal wellness.</p>
            </div>
            <div class="rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 p-8 shadow-sm">
                <h3 class="mb-4 text-2xl font-bold text-blue-900">Our Vision</h3>
                <p class="text-blue-800">To be the global leader in microbiome biotechnology, recognized for excellence in innovation, quality, and our commitment to improving lives worldwide.</p>
            </div>
        </div>
        <div class="mt-12 text-center">
            <a href="{{ route('about') }}" class="inline-flex items-center gap-2 text-lg font-semibold text-green-600 transition hover:text-green-700">
                Learn More <i data-lucide="arrow-right" class="h-6 w-6"></i>
            </a>
        </div>
    </div>
</section>

<section class="bg-gray-50 py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <h2 class="mb-4 text-4xl font-bold text-gray-900">Our Products</h2>
            <p class="text-lg text-gray-600">Scientifically formulated microbiome solutions</p>
        </div>
        <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
            @forelse ($products as $product)
                @include('pages._product-card', ['product' => $product])
            @empty
                <p class="col-span-full text-center text-gray-600">Our product catalog will be available soon.</p>
            @endforelse
        </div>
        <div class="mt-8 text-center"><a href="{{ route('products') }}" class="inline-flex items-center gap-2 font-semibold text-green-600 hover:text-green-700">View All Products <i data-lucide="arrow-right" class="h-5 w-5"></i></a></div>
    </div>
</section>

<section class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <h2 class="mb-4 text-4xl font-bold text-gray-900">Our Services</h2>
            <p class="text-lg text-gray-600">Comprehensive solutions for your microbiome needs</p>
        </div>
        <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
            @foreach ($services as $service)
                <a href="{{ route('services.show', $service['slug']) }}" class="rounded-lg border border-gray-200 bg-gradient-to-br from-gray-50 to-white p-12 shadow-sm transition hover:-translate-y-1 hover:border-green-200 hover:shadow-lg">
                    <div class="mb-8 text-5xl">{!! $service['emoji'] !!}</div>
                    <h3 class="mb-4 text-2xl font-bold text-gray-900">{{ $service['name'] }}</h3>
                    <p class="text-xl leading-relaxed text-gray-600">{{ $service['description'] }}</p>
                </a>
            @endforeach
        </div>
        <div class="mt-12 text-center">
            <a href="{{ route('services') }}" class="inline-flex items-center gap-2 text-lg font-semibold text-green-600 transition hover:text-green-700">
                View All Services <i data-lucide="arrow-right" class="h-6 w-6"></i>
            </a>
        </div>
    </div>
</section>

<section class="bg-gradient-to-r from-green-600 to-blue-600 py-20 text-white">
    <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
        <h2 class="mb-4 text-4xl font-bold">Research & Innovation</h2>
        <p class="mb-12 text-lg text-white/90">Advancing microbiome science through cutting-edge research</p>
        <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
            <div><div class="mb-2 text-5xl font-bold">50+</div><p class="text-white/80">Research Publications</p></div>
            <div><div class="mb-2 text-5xl font-bold">15+</div><p class="text-white/80">Years of Expertise</p></div>
            <div><div class="mb-2 text-5xl font-bold">100%</div><p class="text-white/80">Quality Assured</p></div>
        </div>
        <div class="mt-12">
            <a href="{{ route('research') }}" class="inline-flex items-center gap-3 rounded-lg bg-white px-12 py-5 text-lg font-bold text-green-600 shadow-xl transition hover:bg-gray-100">
                Explore R&D <i data-lucide="arrow-right" class="h-6 w-6"></i>
            </a>
        </div>
    </div>
</section>

<section class="bg-gray-50 py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <h2 class="mb-4 text-4xl font-bold text-gray-900">Leadership Team</h2>
            <p class="text-lg text-gray-600">Experienced leaders driving innovation</p>
        </div>
        <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
            @foreach ($team as $member)
                <article class="overflow-hidden rounded-lg bg-white shadow-md transition hover:shadow-2xl">
                    <img src="{{ $member['photo_url'] }}" alt="{{ $member['name'] }}" class="h-[500px] w-full object-cover">
                    <div class="p-6">
                        <h3 class="mb-1 text-xl font-bold text-gray-900">{{ $member['name'] }}</h3>
                        <p class="mb-3 text-sm font-bold uppercase tracking-wider text-green-600">{{ $member['designation'] }}</p>
                        <p class="text-sm leading-relaxed text-gray-600">{{ $member['bio'] }}</p>
                        <a href="{{ $member['linkedin_url'] }}" class="mt-5 inline-block font-semibold text-blue-600 hover:text-blue-700">View Profile</a>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="mt-12 text-center">
            <a href="{{ route('leadership') }}" class="inline-flex items-center gap-2 text-lg font-semibold text-green-600 transition hover:text-green-700">
                View Full Team <i data-lucide="arrow-right" class="h-6 w-6"></i>
            </a>
        </div>
    </div>
</section>

<section class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <h2 class="mb-4 text-4xl font-bold text-gray-900">Why Choose Zenotic Biotech</h2>
            <p class="text-lg text-gray-600">The leader in microbiome innovation</p>
        </div>
        <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
            @foreach ([
                ['microscope', 'Advanced Research', 'Cutting-edge microbiome research and development'],
                ['award', 'Quality Certified', 'ISO certified and internationally recognized standards'],
                ['users', 'Expert Team', 'Experienced scientists and industry professionals'],
                ['zap', 'Innovation Driven', 'Continuous innovation in biotechnology solutions'],
                ['shield', 'Regulated & Safe', 'Compliant with all regulatory requirements'],
                ['heart-handshake', 'Customer Focused', 'Dedicated support and personalized solutions'],
            ] as $item)
                <div class="rounded-lg border border-gray-200 bg-gradient-to-br from-gray-50 to-white p-8 shadow-sm transition hover:border-green-200 hover:bg-green-50">
                    <i data-lucide="{{ $item[0] }}" class="mb-5 h-10 w-10 text-green-600"></i>
                    <h3 class="mb-3 text-xl font-bold text-gray-900">{{ $item[1] }}</h3>
                    <p class="leading-relaxed text-gray-600">{{ $item[2] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-gradient-to-r from-blue-600 to-green-600 py-24 text-white">
    <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
        <h2 class="mb-6 text-4xl font-bold">Ready to Get Started?</h2>
        <p class="mb-10 text-xl text-white/90">Contact us today to learn more about our innovative microbiome solutions.</p>
        <a href="{{ route('contact') }}" class="inline-flex items-center gap-3 rounded-lg bg-white px-12 py-5 text-lg font-bold text-green-600 shadow-xl transition hover:bg-gray-100">
            Contact Us <i data-lucide="arrow-right" class="h-6 w-6"></i>
        </a>
    </div>
</section>
@endsection
