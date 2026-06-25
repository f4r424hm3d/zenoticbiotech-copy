@extends('layouts.site')
@section('title', 'Services | Zenotic Biotech')
@section('content')
@include('pages._hero', ['title' => 'Our Services', 'subtitle' => 'Comprehensive microbiome solutions tailored to your needs'])

<section class="bg-white pb-16 pt-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($services as $service)
                <article class="flex min-h-[405px] flex-col rounded-lg border {{ $loop->index === 1 ? 'border-green-200' : 'border-gray-200' }} bg-gradient-to-br from-gray-50 to-white px-7 pb-7 pt-8 shadow-md transition hover:border-green-200 hover:shadow-lg">
                    <div class="mb-6 text-3xl leading-none">{!! $service['emoji'] !!}</div>
                    <h3 class="mb-4 text-xl font-bold leading-tight {{ $loop->index === 1 ? 'text-green-600' : 'text-gray-900' }}">{{ $service['name'] }}</h3>
                    <p class="mb-4 text-base leading-relaxed text-gray-600">{{ $service['description'] }}</p>
                    <p class="mb-6 text-sm leading-relaxed text-gray-500">{{ $service['details'] }}</p>
                    <a href="{{ route('services.show', $service['slug']) }}" class="mt-auto block rounded-lg border-2 border-green-600 py-2.5 text-center text-sm font-semibold text-green-600 transition hover:bg-green-600 hover:text-white">
                        Learn More
                    </a>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-gradient-to-r from-green-600 to-blue-600 py-20 text-white">
    <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
        <h2 class="mb-6 text-3xl font-bold">Need a Specific Solution?</h2>
        <p class="mb-8 text-lg text-white/90">Our team of experts is ready to develop customized microbiome solutions for your organization.</p>
        <a href="mailto:info@zenoticbiotech.com" class="inline-block rounded-lg bg-white px-8 py-3 font-semibold text-green-600 transition hover:bg-gray-100">Get in Touch</a>
    </div>
</section>
@endsection
