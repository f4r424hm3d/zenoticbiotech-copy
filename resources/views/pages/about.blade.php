@extends('layouts.site')
@section('title', 'About | Zenotic Biotech')
@section('content')
<section class="bg-gradient-to-r from-green-50 to-blue-50 py-14">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <h1 class="mb-4 text-4xl font-bold text-gray-900">About Zenotic Biotech</h1>
        <p class="max-w-3xl text-base text-gray-600">Leading the microbiome revolution with innovative biotechnology solutions</p>
    </div>
</section>

<section class="bg-white py-16">
    <div class="mx-auto grid max-w-5xl grid-cols-1 items-center gap-12 px-4 sm:px-6 md:grid-cols-2 lg:px-8">
        <div>
            <h2 class="mb-5 text-2xl font-bold text-gray-900">Our Story</h2>
            <div class="space-y-4 text-sm leading-relaxed text-gray-600">
                <p>Zenotic Biotech was founded with a mission to revolutionize human health through advanced microbiome research and innovation. With over 15 years of combined expertise in biotechnology and microbiome science, our team has developed groundbreaking solutions that restore and maintain microbial balance.</p>
                <p>We believe that a balanced microbiome is the foundation of optimal health. Our products are backed by rigorous scientific research and are designed to help individuals achieve their wellness goals naturally and effectively.</p>
                <p>Today, Zenotic Biotech serves customers across India and is expanding globally, committed to delivering excellence in every product and service.</p>
            </div>
        </div>
        <div class="mx-auto w-full max-w-md rounded-lg bg-gradient-to-br from-green-100 to-blue-100 p-14">
            <img src="/logo_highquaity.png" alt="Zenotic Biotech" class="w-full">
        </div>
    </div>
</section>

<section class="bg-gray-50 py-14">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <h2 class="mb-10 text-center text-2xl font-bold text-gray-900">Core Values</h2>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([['leaf','Sustainability','Committed to environmentally responsible practices in all operations.','text-green-600'],['beaker','Innovation','Continuously advancing microbiome science through cutting-edge research.','text-blue-600'],['check-circle','Quality','Maintaining the highest standards in product development and testing.','text-green-600'],['globe','Global Vision','Expanding microbiome solutions worldwide for universal health benefits.','text-blue-600']] as $value)
                <div class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <i data-lucide="{{ $value[0] }}" class="mb-4 h-7 w-7 {{ $value[3] }}"></i>
                    <h3 class="mb-2 text-sm font-bold text-gray-900">{{ $value[1] }}</h3>
                    <p class="text-xs leading-relaxed text-gray-600">{{ $value[2] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-white py-14">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <h2 class="mb-10 text-center text-2xl font-bold text-gray-900">Our Expertise</h2>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="rounded-lg bg-gradient-to-br from-green-50 to-green-100 p-6">
                <h3 class="mb-3 text-base font-bold text-green-900">Microbiome Research</h3>
                <p class="text-xs leading-relaxed text-green-800">Decades of combined research experience in microbiome science, bacterial taxonomy, and metabolic profiling.</p>
            </div>
            <div class="rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 p-6">
                <h3 class="mb-3 text-base font-bold text-blue-900">Product Development</h3>
                <p class="text-xs leading-relaxed text-blue-800">State-of-the-art facilities for formulation, testing, and manufacturing of biotechnology products.</p>
            </div>
            <div class="rounded-lg bg-gradient-to-br from-teal-50 to-teal-100 p-6">
                <h3 class="mb-3 text-base font-bold text-teal-900">Clinical Excellence</h3>
                <p class="text-xs leading-relaxed text-teal-800">Evidence-based product development with rigorous clinical trials and regulatory compliance.</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-gradient-to-r from-green-600 to-blue-600 py-14 text-white">
    <div class="mx-auto max-w-5xl px-4 text-center sm:px-6 lg:px-8">
        <h2 class="mb-10 text-2xl font-bold">By The Numbers</h2>
        <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
            @foreach ([['15+','Years of Experience'],['50+','Research Publications'],['20+','Products Developed'],['10K+','Satisfied Customers']] as $stat)
                <div>
                    <div class="mb-1 text-3xl font-bold">{{ $stat[0] }}</div>
                    <p class="text-xs font-medium text-white/90">{{ $stat[1] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-white py-14">
    <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
        <h2 class="mb-4 text-2xl font-bold text-gray-900">Want to Learn More?</h2>
        <p class="mb-7 text-sm text-gray-600">Explore our leadership team and discover the experts behind Zenotic Biotech.</p>
        <a href="{{ route('leadership') }}" class="inline-block rounded-md bg-green-600 px-7 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700">Meet Our Team</a>
    </div>
</section>
@endsection
