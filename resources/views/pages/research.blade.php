@extends('layouts.site')
@section('title', 'Research & Development | Zenotic Biotech')
@section('content')
@include('pages._hero', ['title' => 'Research & Development', 'subtitle' => 'Pioneering microbiome science through cutting-edge research'])
<section class="bg-white py-20">
  <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
    <div>
      <h2 class="mb-6 text-4xl font-bold">Our Research Focus</h2>
      <p class="mb-4 text-lg leading-relaxed text-gray-600">At Zenotic Biotech, we are dedicated to advancing the understanding of the human microbiome and its impact on health and wellness. Our research teams work on multiple fronts:</p>
      <ul class="space-y-3 text-gray-600">@foreach (['Bacterial taxonomy and microbiome composition analysis','Metabolic profiling and biomarker identification','Probiotic strain selection and fermentation optimization','Clinical efficacy studies and safety assessments','Personalized microbiome interventions'] as $item)<li class="flex gap-3"><span class="font-bold text-green-600">-&gt;</span><span>{{ $item }}</span></li>@endforeach</ul>
    </div>
    <div class="rounded-lg bg-gradient-to-br from-blue-100 to-green-100 p-12"><i data-lucide="beaker" class="h-48 w-48 text-blue-600"></i></div>
  </div>
</section>
<section class="bg-gray-50 py-20">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <h2 class="mb-12 text-center text-4xl font-bold">Research Achievements</h2>
    <div class="grid grid-cols-1 gap-8 md:grid-cols-4">@foreach ([['book-open','50+','Peer-Reviewed Publications'],['award','15+','Research Awards'],['trending-up','30+','Ongoing Research Projects'],['beaker','20+','Patents Registered']] as $card)<div class="rounded-lg bg-white p-8 text-center shadow-md"><i data-lucide="{{ $card[0] }}" class="mx-auto mb-4 h-10 w-10 text-green-600"></i>
        <div class="mb-2 text-3xl font-bold">{{ $card[1] }}</div>
        <p class="text-gray-600">{{ $card[2] }}</p>
      </div>@endforeach</div>
  </div>
</section>
<section class="bg-white py-20">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <h2 class="mb-12 text-center text-4xl font-bold text-gray-900">Research Collaborations</h2>
    <p class="mx-auto mb-12 max-w-2xl text-center text-lg text-gray-600">We collaborate with leading universities, research institutions, and healthcare organizations globally to advance microbiome science.</p>
    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
      @foreach ([['Academic Partners','Collaboration with top tier universities for basic research, clinical trials, and microbiome studies.','green'],['Industry Partners','Strategic partnerships with pharmaceutical and nutraceutical companies for product development and distribution.','blue'],['Healthcare Institutions','Partnerships with hospitals and clinics for clinical research and patient studies.','teal'],['Global Network','Connecting with research centers worldwide to share knowledge and advance microbiome science.','cyan']] as $item)
      <div class="rounded-lg border-2 border-{{ $item[2] }}-200 bg-gradient-to-br from-{{ $item[2] }}-50 to-{{ $item[2] }}-100 p-8">
        <h3 class="mb-4 text-xl font-bold text-{{ $item[2] }}-900">{{ $item[0] }}</h3>
        <p class="text-{{ $item[2] }}-800">{{ $item[1] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>
<section class="bg-gradient-to-r from-green-600 to-blue-600 py-20 text-white">
  <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
    <h2 class="mb-6 text-3xl font-bold">Interested in Research Opportunities?</h2>
    <p class="mb-8 text-lg text-white/90">We welcome collaborations, partnerships, and research inquiries.</p><a href="mailto:info@zenoticbiotech.com" class="inline-block rounded-lg bg-white px-8 py-3 font-semibold text-green-600">Contact Our Research Team</a>
  </div>
</section>
@endsection