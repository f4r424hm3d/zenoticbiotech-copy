@extends('layouts.admin')
@php($isEdit = $service->exists)
@section('title', ($isEdit ? 'Edit Service' : 'New Service').' | Zenotic Admin')
@section('content')
<div class="mx-auto max-w-3xl">
    <a href="{{ route('admin.services') }}" class="mb-5 inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900">
        <i data-lucide="arrow-left" class="h-4 w-4"></i>
        Back to services
    </a>

    <div class="mb-5">
        <h1 class="text-2xl font-bold text-gray-900">{{ $isEdit ? 'Edit Service' : 'New Service' }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ $isEdit ? 'Update service details.' : 'Fill in the details to add a new service.' }}</p>
    </div>

    <form method="post" enctype="multipart/form-data" action="{{ $isEdit ? route('admin.services.update', $service) : route('admin.services.store') }}" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        @if($isEdit)
            @method('put')
        @endif

        <div class="mb-5">
            <label class="mb-2 block text-sm font-medium text-gray-900">Service Name <span class="text-red-500">*</span></label>
            <input name="name" value="{{ old('name', $service->name) }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="e.g. Microbiome Analysis">
        </div>

        <div class="mb-5">
            <label class="mb-2 block text-sm font-medium text-gray-900">Short Description <span class="text-red-500">*</span></label>
            <textarea name="description" rows="3" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="Shown as the main service copy...">{{ old('description', $service->description) }}</textarea>
        </div>

        <div class="mb-5">
            <label class="mb-2 block text-sm font-medium text-gray-900">Details</label>
            <textarea name="details" rows="4" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="Additional details shown below the description...">{{ old('details', $service->details) }}</textarea>
        </div>

        <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">Emoji / Entity</label>
                <input name="emoji" value="{{ old('emoji', $service->emoji) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="&#129516;">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">Lucide Icon</label>
                <input name="icon" value="{{ old('icon', $service->icon) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="dna">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">Status</label>
                <select name="status" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100">
                    <option value="published" @selected(old('status', $service->status) === 'published')>Published</option>
                    <option value="draft" @selected(old('status', $service->status) === 'draft')>Draft</option>
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">Order</label>
                <input type="number" name="order_index" value="{{ old('order_index', $service->order_index ?? 0) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100">
            </div>
        </div>

        <div class="mb-6 rounded-xl border border-gray-200 bg-gray-50 p-5">
            <h2 class="mb-4 text-lg font-bold text-gray-900">SEO Fields</h2>

            <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900">Meta Title</label>
                    <input name="meta_title" value="{{ old('meta_title', $service->meta_title) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="Enter meta title">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900">Meta Keyword</label>
                    <input name="meta_keyword" value="{{ old('meta_keyword', $service->meta_keyword) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="keyword one, keyword two">
                </div>
            </div>

            <div class="mb-5">
                <label class="mb-2 block text-sm font-medium text-gray-900">Meta Description</label>
                <textarea name="meta_description" rows="4" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="Enter meta description">{{ old('meta_description', $service->meta_description) }}</textarea>
            </div>

            <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900">SEO Rating</label>
                    <input type="number" name="seo_rating" min="1" max="5" step=".1" value="{{ old('seo_rating', $service->seo_rating) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900">Best Rating</label>
                    <input type="number" name="best_rating" min="1" max="5" step=".1" value="{{ old('best_rating', $service->best_rating) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900">Number of Reviews</label>
                    <input type="number" name="review_number" min="0" value="{{ old('review_number', $service->review_number) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100">
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">OG Image</label>
                <input type="file" name="og_image" accept="image/*" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm">
                @if($service->og_image_path)
                    <a href="{{ asset('storage/'.$service->og_image_path) }}" target="_blank" class="mt-2 inline-flex text-xs font-semibold text-green-700">View current image</a>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-5">
            <button class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700">
                <i data-lucide="save" class="h-4 w-4"></i>
                {{ $isEdit ? 'Save Service' : 'Create Service' }}
            </button>
            <a href="{{ route('admin.services') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Cancel</a>
        </div>
    </form>
</div>
@endsection
