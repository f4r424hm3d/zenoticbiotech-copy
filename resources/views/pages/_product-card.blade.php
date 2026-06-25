<article class="group overflow-hidden rounded-lg bg-white shadow-lg transition hover:-translate-y-1 hover:shadow-2xl">
    @if ($product->image_url)
        <div class="relative overflow-hidden">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-48 w-full object-cover transition duration-500 group-hover:scale-105">
            <span class="absolute right-3 top-3 rounded-full bg-white/90 px-3 py-1 text-xs font-bold tracking-wide text-green-700 shadow-md">Zenotic Biotech</span>
        </div>
    @endif
    <div class="p-8">
        <span class="mb-3 inline-block rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">{{ $product->category?->name }}</span>
        <h3 class="mb-3 text-2xl font-bold text-gray-900 transition group-hover:text-green-600">{{ $product->name }}</h3>
        <p class="mb-6 leading-relaxed text-gray-600">{{ $product->description }}</p>
        @if (! empty($product->features))
            <h4 class="mb-3 font-semibold text-gray-900">Key Features</h4>
            <ul class="mb-6 space-y-2">
                @foreach ($product->features as $feature)
                    <li class="flex items-start gap-2 text-gray-600"><span class="mt-1 font-bold text-green-600">&bull;</span><span>{{ $feature }}</span></li>
                @endforeach
            </ul>
        @endif
        <a href="{{ route('products.show', $product) }}" class="block rounded-lg bg-green-600 py-3 text-center font-semibold text-white shadow-lg shadow-green-100 transition hover:bg-green-700">Learn More</a>
    </div>
</article>
