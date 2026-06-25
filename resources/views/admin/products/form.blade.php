@extends('layouts.admin')
@php($isEdit = $product->exists)
@section('title', ($isEdit ? 'Edit Product' : 'New Product').' | Zenotic Admin')
@section('content')
<div class="mx-auto max-w-3xl">
    <a href="{{ route('admin.products') }}" class="mb-5 inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900">
        <i data-lucide="arrow-left" class="h-4 w-4"></i>
        Back to products
    </a>

    <div class="mb-5">
        <h1 class="text-2xl font-bold text-gray-900">{{ $isEdit ? 'Edit Product' : 'New Product' }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ $isEdit ? 'Update product details.' : 'Fill in the details to add a new product.' }}</p>
    </div>

    <form method="post" enctype="multipart/form-data" action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        @if($isEdit)
            @method('put')
        @endif

        <div class="mb-5">
            <label class="mb-2 block text-sm font-medium text-gray-900">Product Name <span class="text-red-500">*</span></label>
            <input
                name="name"
                value="{{ old('name', $product->name) }}"
                required
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100"
                placeholder="e.g. GUT-PRO Advanced"
            >
        </div>

        <div class="mb-5">
            <label class="mb-2 block text-sm font-medium text-gray-900">Description <span class="text-red-500">*</span></label>
            <textarea
                name="description"
                rows="4"
                required
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100"
                placeholder="Short product description..."
            >{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">Segment <span class="text-red-500">*</span></label>
                <select name="segment" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100">
                    <option value="human" @selected(old('segment', $product->segment) === 'human')>Human</option>
                    <option value="animal" @selected(old('segment', $product->segment) === 'animal')>Animal</option>
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">Category <span class="text-red-500">*</span></label>
                <select name="category_id" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100">
                    <option value="">Select category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id) === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">Status</label>
                <select name="status" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100">
                    <option value="draft" @selected(old('status', $product->status) === 'draft')>Draft (hidden)</option>
                    <option value="published" @selected(old('status', $product->status) === 'published')>Published</option>
                </select>
            </div>
        </div>

        <div class="mb-5">
            <label class="mb-2 block text-sm font-medium text-gray-900">Product Image</label>
            <div class="mb-3 inline-flex rounded-md bg-gray-100 p-1">
                <button type="button" data-image-tab="url" class="image-tab rounded px-4 py-2 text-sm font-medium text-green-700 bg-white shadow-sm">URL</button>
                <button type="button" data-image-tab="upload" class="image-tab rounded px-4 py-2 text-sm font-medium text-gray-600">Upload</button>
            </div>
            <div data-image-panel="url">
                <input
                    name="image_url"
                    value="{{ old('image_url', $product->image_url) }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100"
                    placeholder="https://..."
                >
            </div>
            <div data-image-panel="upload" class="hidden">
                <input type="file" name="image" accept="image/*" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
            </div>
            @if ($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="mt-4 h-32 w-full rounded-lg object-cover">
            @endif
        </div>

        <div class="mb-5">
            <label class="mb-2 block text-sm font-medium text-gray-900">Key Features <span class="ml-2 text-xs font-normal text-gray-400">One feature per line</span></label>
            <textarea
                name="features"
                rows="5"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 font-mono text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100"
                placeholder="10 Broad Spectrum Strains&#10;Delayed Release Capsules&#10;50 Billion CFU"
            >{{ old('features', implode("\n", $product->features ?? [])) }}</textarea>
        </div>

        <div class="mb-6">
            <label class="mb-2 block text-sm font-medium text-gray-900">Display Order <span class="ml-2 text-xs font-normal text-gray-400">Lower numbers appear first</span></label>
            <input
                type="number"
                name="order_index"
                value="{{ old('order_index', $product->order_index ?? 0) }}"
                class="w-32 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100"
            >
        </div>

        <div class="mb-6 rounded-xl border border-gray-200 bg-gray-50 p-5">
            <h2 class="mb-4 text-lg font-bold text-gray-900">SEO Fields</h2>

            <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900">Meta Title</label>
                    <input name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="Enter meta title">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900">Meta Keyword</label>
                    <input name="meta_keyword" value="{{ old('meta_keyword', $product->meta_keyword) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="keyword one, keyword two">
                </div>
            </div>

            <div class="mb-5">
                <label class="mb-2 block text-sm font-medium text-gray-900">Meta Description</label>
                <textarea name="meta_description" rows="4" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="Enter meta description">{{ old('meta_description', $product->meta_description) }}</textarea>
            </div>

            <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900">SEO Rating</label>
                    <input type="number" name="seo_rating" min="1" max="5" step=".1" value="{{ old('seo_rating', $product->seo_rating) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900">Best Rating</label>
                    <input type="number" name="best_rating" min="1" max="5" step=".1" value="{{ old('best_rating', $product->best_rating) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900">Number of Reviews</label>
                    <input type="number" name="review_number" min="0" value="{{ old('review_number', $product->review_number) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100">
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">OG Image</label>
                <input type="file" name="og_image" accept="image/*" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm">
                @if($product->og_image_path)
                    <a href="{{ asset('storage/'.$product->og_image_path) }}" target="_blank" class="mt-2 inline-flex text-xs font-semibold text-green-700">View current image</a>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-5">
            <button class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700">
                <i data-lucide="save" class="h-4 w-4"></i>
                {{ $isEdit ? 'Save Product' : 'Create Product' }}
            </button>
            <a href="{{ route('admin.products') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('[data-image-tab]');
    const panels = document.querySelectorAll('[data-image-panel]');

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const target = tab.dataset.imageTab;
            panels.forEach((panel) => panel.classList.toggle('hidden', panel.dataset.imagePanel !== target));
            tabs.forEach((button) => {
                const active = button.dataset.imageTab === target;
                button.classList.toggle('bg-white', active);
                button.classList.toggle('shadow-sm', active);
                button.classList.toggle('text-green-700', active);
                button.classList.toggle('text-gray-600', !active);
            });
        });
    });
});
</script>
@endsection
