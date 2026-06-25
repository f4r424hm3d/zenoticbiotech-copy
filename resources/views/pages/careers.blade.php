@extends('layouts.site')
@section('title', 'Careers | Zenotic Biotech')
@section('content')
@include('pages._hero', ['title' => 'Join Our Team', 'subtitle' => 'Build your career in cutting-edge microbiome biotechnology'])

<section class="bg-white py-14">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <h2 class="mb-8 text-center text-2xl font-bold text-gray-900">Why Work With Us</h2>
        <div class="mb-8 grid grid-cols-1 gap-8 sm:grid-cols-3">
            @foreach ([['50+','Team Members Globally'],['100%','Innovation Focused'],['15+','Years in Industry']] as $stat)
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $stat[0] }}</div>
                    <p class="text-xs text-gray-600">{{ $stat[1] }}</p>
                </div>
            @endforeach
        </div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            @foreach ([
                ['Competitive Benefits', 'Attractive salary, health insurance, and professional development opportunities.', 'from-green-50 to-green-100'],
                ['Career Growth', 'Opportunities for advancement in a growing biotech company.', 'from-blue-50 to-blue-100'],
                ['Research Focused', 'Work on cutting-edge microbiome research and product development.', 'from-teal-50 to-teal-100'],
                ['Collaborative Culture', 'Join a team of passionate scientists and innovators.', 'from-cyan-50 to-cyan-100'],
            ] as $benefit)
                <div class="rounded-lg bg-gradient-to-br {{ $benefit[2] }} p-6 shadow-sm">
                    <h3 class="mb-2 text-sm font-bold text-gray-900">{{ $benefit[0] }}</h3>
                    <p class="text-xs leading-relaxed text-gray-700">{{ $benefit[1] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-gray-50 py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 class="mb-8 text-center text-2xl font-bold text-gray-900">Open Positions</h2>
        <div class="mx-auto max-w-3xl space-y-6">
            @foreach ($careers as $job)
                <article class="rounded-lg border-l-4 border-green-600 bg-white p-7 shadow-sm ring-1 ring-gray-200">
                    <div class="mb-4 flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $job['title'] }}</h3>
                            <div class="mt-2 flex flex-wrap gap-4 text-xs text-gray-600">
                                <span class="flex items-center gap-1"><i data-lucide="briefcase" class="h-3.5 w-3.5"></i>{{ $job['department'] }}</span>
                                <span class="flex items-center gap-1"><i data-lucide="map-pin" class="h-3.5 w-3.5"></i>{{ $job['location'] }}</span>
                            </div>
                        </div>
                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Open</span>
                    </div>
                    <p class="mb-4 text-sm leading-relaxed text-gray-600">{{ $job['description'] }}</p>
                    <h4 class="mb-3 text-sm font-semibold text-gray-900">Requirements</h4>
                    <ul class="mb-6 space-y-2">
                        @foreach ($job['requirements'] as $requirement)
                            <li class="flex items-start gap-2 text-xs text-gray-600">
                                <span class="mt-0.5 font-bold text-green-600">&bull;</span>
                                <span>{{ $requirement }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <a href="mailto:careers@zenoticbiotech.com?subject=Application for: {{ rawurlencode($job['title']) }}" class="inline-block rounded-md bg-green-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700">Apply Now</a>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-white py-14">
    <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
        <h2 class="mb-5 text-2xl font-bold text-gray-900">Questions?</h2>
        <p class="mb-8 text-sm text-gray-600">Contact our HR team for more information about career opportunities at Zenotic Biotech.</p>
        <a href="mailto:careers@zenoticbiotech.com" class="inline-block rounded-md bg-green-600 px-7 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700">Get in Touch</a>
    </div>
</section>
@endsection
