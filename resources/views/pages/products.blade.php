@extends('layouts.site')
@section('title', 'Products | Zenotic Biotech')
@section('content')
@include('pages._hero', ['title' => 'Our Products', 'subtitle' => 'Scientifically formulated microbiome solutions for optimal health'])
<section class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        @if ($products->isEmpty())
            <div class="py-12 text-center"><p class="mb-4 text-xl text-gray-600">Our product catalog will be available soon.</p><p class="text-gray-600">Contact us at info@zenoticbiotech.com for more information.</p></div>
        @else
            <div class="mb-12 flex justify-center">
                <div class="inline-flex rounded-full bg-gray-100 p-1.5">
                    <button type="button" data-tab-target="human" class="tab-button flex items-center gap-3 rounded-full bg-green-600 px-8 py-3 text-base font-semibold text-white">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="7" r="3"></circle>
                            <path d="M6.5 20v-2.5A5.5 5.5 0 0 1 12 12a5.5 5.5 0 0 1 5.5 5.5V20"></path>
                        </svg>
                        <span>Human</span>
                        <span data-count-pill class="rounded-full bg-white/25 px-2.5 py-1 text-sm">{{ $humanProducts->count() }}</span>
                    </button>
                    <button type="button" data-tab-target="animal" class="tab-button flex items-center gap-3 rounded-full px-8 py-3 text-base font-semibold text-gray-600">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="6" r="2"></circle>
                            <circle cx="16" cy="8" r="2"></circle>
                            <circle cx="7" cy="10" r="2"></circle>
                            <path d="M9.5 14.5c1.4-2.1 3.6-2.1 5 0l1.2 1.8c1 1.5 0 3.2-1.8 3.2h-3.8c-1.8 0-2.8-1.7-1.8-3.2l1.2-1.8Z"></path>
                        </svg>
                        <span>Animal</span>
                        <span data-count-pill class="rounded-full bg-gray-200 px-2.5 py-1 text-sm text-gray-600">{{ $animalProducts->count() }}</span>
                    </button>
                </div>
            </div>
            <div data-tab="human" class="product-tab grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($humanProducts as $product) @include('pages._product-card', ['product' => $product]) @empty <p class="col-span-full py-12 text-center text-xl text-gray-600">No human products available yet.</p> @endforelse
            </div>
            <div data-tab="animal" class="product-tab hidden grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($animalProducts as $product) @include('pages._product-card', ['product' => $product]) @empty <p class="col-span-full py-12 text-center text-xl text-gray-600">No animal products available yet.</p> @endforelse
            </div>
        @endif
    </div>
</section>
<section class="bg-gray-50 py-20">
    <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
        <h2 class="mb-6 text-3xl font-bold text-gray-900">Custom Solutions Available</h2>
        <p class="mb-8 text-xl text-gray-600">Looking for a tailored microbiome solution? Our team can develop custom products based on your specific requirements.</p>
        <a href="mailto:info@zenoticbiotech.com" class="inline-block rounded-lg bg-green-600 px-8 py-3 font-semibold text-white shadow-lg shadow-green-200 transition hover:bg-green-700">Request Custom Solution</a>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-tab-target]').forEach((button) => {
        button.addEventListener('click', () => {
            const target = button.dataset.tabTarget;
            document.querySelectorAll('.product-tab').forEach(tab => tab.classList.toggle('hidden', tab.dataset.tab !== target));
            document.querySelectorAll('.tab-button').forEach(btn => {
                const active = btn.dataset.tabTarget === target;
                btn.classList.toggle('bg-green-600', active);
                btn.classList.toggle('text-white', active);
                btn.classList.toggle('text-gray-600', !active);
                const pill = btn.querySelector('[data-count-pill]');
                pill?.classList.toggle('bg-white/25', active);
                pill?.classList.toggle('bg-gray-200', !active);
                pill?.classList.toggle('text-gray-600', !active);
            });
        });
    });
});
</script>
@endsection
