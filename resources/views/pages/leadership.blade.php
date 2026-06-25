@extends('layouts.site')
@section('title', 'Leadership | Zenotic Biotech')
@section('content')
@include('pages._hero', ['title' => 'Leadership Team', 'subtitle' => 'Experienced leaders driving innovation in microbiome biotechnology'])
<section class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($team as $member)
                <article class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-md transition hover:shadow-2xl">
                    <img src="{{ $member['photo_url'] }}" alt="{{ $member['name'] }}" class="h-[500px] w-full object-cover">
                    <div class="p-6">
                        <h3 class="mb-1 text-xl font-bold text-gray-900">{{ $member['name'] }}</h3>
                        <p class="mb-4 text-sm font-bold uppercase tracking-wide text-green-600">{{ $member['designation'] }}</p>
                        <p class="mb-6 text-sm leading-relaxed text-gray-600">{{ $member['bio'] }}</p>
                        <a href="{{ $member['linkedin_url'] }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-blue-600">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M6.94 8.98H3.71V20h3.23V8.98ZM5.33 4a1.87 1.87 0 1 0 0 3.74 1.87 1.87 0 0 0 0-3.74Zm14.96 9.68c0-3.01-1.61-4.41-3.75-4.41-1.73 0-2.5.95-2.93 1.62V8.98h-3.1V20h3.23v-5.45c0-1.44.27-2.83 2.05-2.83 1.76 0 1.78 1.65 1.78 2.92V20h3.23v-6.32h-.01Z"/>
                            </svg>
                            <span class="text-xs font-semibold">LinkedIn</span>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
<section class="bg-gray-50 py-20"><div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8"><h2 class="mb-6 text-3xl font-bold">Join Our Growing Team</h2><p class="mb-8 text-xl text-gray-600">We're always looking for talented individuals passionate about microbiome research and innovation.</p><a href="{{ route('careers') }}" class="rounded-lg bg-green-600 px-8 py-3 font-semibold text-white shadow-lg shadow-green-200">View Career Opportunities</a></div></section>
@endsection
