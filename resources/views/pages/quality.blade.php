@extends('layouts.site')
@section('title', 'Quality & Certifications | Zenotic Biotech')
@section('content')
@include('pages._hero', ['title' => 'Quality & Certifications', 'subtitle' => 'Ensuring the highest standards of quality and safety'])
<section class="bg-white py-20"><div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8"><h2 class="mb-12 text-center text-4xl font-bold">Certifications & Accreditations</h2><div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">@foreach ([['ISO 9001:2015','Quality Management System'],['ISO 22000:2018','Food Safety Management'],['GMP Certified','Good Manufacturing Practice'],['ISO 14001:2015','Environmental Management'],['OHSAS 18001','Occupational Health & Safety'],['FDA Approved','US Food & Drug Administration']] as $cert)<div class="rounded-lg border-2 border-green-200 bg-gradient-to-br from-green-50 to-green-100 p-8"><i data-lucide="check-circle" class="mb-4 h-10 w-10 text-green-600"></i><h3 class="mb-2 text-xl font-bold">{{ $cert[0] }}</h3><p class="text-gray-600">{{ $cert[1] }}</p></div>@endforeach</div></div></section>
<section class="bg-gray-50 py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 class="mb-12 text-center text-4xl font-bold text-gray-900">Quality Standards</h2>
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-2">
            @foreach ([['Raw Material Testing','green',[['Microbial Analysis','Comprehensive microbial identification and enumeration'],['Purity Testing','Verification of ingredient purity and composition'],['Potency Assessment','Verification of active ingredient concentration'],['Contaminant Screening','Testing for heavy metals and pesticide residues']]],['Product Testing','blue',[['Stability Testing','Long-term stability under various conditions'],['Safety Assessment','Comprehensive safety and toxicity testing'],['Efficacy Studies','Clinical trials and effectiveness validation'],['Label Verification','Accuracy of product labeling and claims']]]] as $group)
                <div>
                    <h3 class="mb-6 text-2xl font-bold text-gray-900">{{ $group[0] }}</h3>
                    <ul class="space-y-4">
                        @foreach ($group[2] as $item)
                            <li class="flex items-start gap-3">
                                <i data-lucide="check-circle" class="mt-1 h-6 w-6 flex-shrink-0 text-{{ $group[1] }}-600"></i>
                                <div><p class="font-semibold text-gray-900">{{ $item[0] }}</p><p class="text-sm text-gray-600">{{ $item[1] }}</p></div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</section>
<section class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 class="mb-16 text-center text-5xl font-bold text-gray-900">Our Commitment</h2>
        <div class="grid grid-cols-1 gap-10 md:grid-cols-2">
            @foreach ([
                ['shield', 'Quality Assurance', 'Every product undergoes rigorous testing at multiple stages of production to ensure consistency, purity, and efficacy.', 'from-green-50 to-green-100', 'text-green-600'],
                ['zap', 'Continuous Improvement', 'We continuously invest in advanced testing equipment and methodologies to enhance our quality standards.', 'from-blue-50 to-blue-100', 'text-blue-600'],
                ['bar-chart-3', 'Transparency', 'We maintain complete transparency with customers through detailed certificates of analysis and test reports.', 'from-teal-50 to-teal-100', 'text-teal-600'],
                ['check-circle', 'Regulatory Compliance', 'Full compliance with all regulatory requirements across multiple countries and regulatory bodies.', 'from-cyan-50 to-cyan-100', 'text-cyan-600'],
            ] as $item)
                <div class="rounded-lg bg-gradient-to-br {{ $item[3] }} p-12">
                    <i data-lucide="{{ $item[0] }}" class="mb-8 h-14 w-14 {{ $item[4] }}"></i>
                    <h3 class="mb-6 text-3xl font-bold text-gray-900">{{ $item[1] }}</h3>
                    <p class="text-2xl leading-relaxed text-gray-700">{{ $item[2] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
<section class="bg-gradient-to-r from-green-600 to-blue-600 py-20 text-white"><div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8"><h2 class="mb-6 text-3xl font-bold">Need Quality Documentation?</h2><p class="mb-8 text-lg text-white/90">Request certificates of analysis or quality documentation for our products.</p><a href="mailto:info@zenoticbiotech.com" class="inline-block rounded-lg bg-white px-8 py-3 font-semibold text-green-600">Request Documentation</a></div></section>
@endsection
