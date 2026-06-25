@extends('layouts.admin')
@php($isEdit = $record->exists)
@section('title', $title.' | Zenotic Admin')

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="mb-6 flex flex-col justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
        </div>
        @if ($isEdit)
            <a href="{{ route($routePrefix) }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Add New
            </a>
        @endif
    </div>

    <form method="post" enctype="multipart/form-data" action="{{ $isEdit ? route($routePrefix.'.update', $record) : route($routePrefix.'.store') }}" class="mb-8 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        @csrf
        @if($isEdit)
            @method('put')
        @endif

        <div class="mb-5 grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">Page Name <span class="text-red-500">*</span></label>
                <input name="page_name" value="{{ old('page_name', $record->page_name) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="{{ $type === 'static' ? 'home, about, contact' : 'product-detail-page' }}">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">OG Image</label>
                <input type="file" name="og_image" accept="image/*" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @if($record->og_image_path)
                    <a href="{{ asset('storage/'.$record->og_image_path) }}" target="_blank" class="mt-2 inline-flex text-xs font-medium text-green-700">View current image</a>
                @endif
            </div>
        </div>

        <div class="mb-5 grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">Meta Title</label>
                <input name="meta_title" value="{{ old('meta_title', $record->meta_title) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="Enter meta title">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">Meta Keyword</label>
                <input name="meta_keyword" value="{{ old('meta_keyword', $record->meta_keyword) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="keyword one, keyword two">
            </div>
        </div>

        <div class="mb-5">
            <label class="mb-2 block text-sm font-medium text-gray-900">Meta Description</label>
            <textarea name="meta_description" rows="4" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="Enter meta description">{{ old('meta_description', $record->meta_description) }}</textarea>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">SEO Rating</label>
                <input type="number" name="seo_rating" min="1" max="5" step=".1" value="{{ old('seo_rating', $record->seo_rating) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">Best Rating</label>
                <input type="number" name="best_rating" min="1" max="5" step=".1" value="{{ old('best_rating', $record->best_rating) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">Number of Reviews</label>
                <input type="number" name="review_number" min="0" value="{{ old('review_number', $record->review_number) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100">
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                <i data-lucide="save" class="h-4 w-4"></i>
                {{ $isEdit ? 'Update Record' : 'Create Record' }}
            </button>
            @if($isEdit)
                <a href="{{ route($routePrefix) }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Cancel</a>
            @else
                <button type="reset" class="text-sm font-medium text-gray-700 hover:text-gray-900">Reset</button>
            @endif
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
        <div class="border-b border-gray-200 px-5 py-4">
            <h2 class="font-semibold text-gray-900">{{ $title }} List</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-gray-500">
                        <th class="px-5 py-3 font-medium">Page</th>
                        <th class="px-5 py-3 font-medium">SEO</th>
                        <th class="px-5 py-3 font-medium">OG Image</th>
                        <th class="px-5 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($records as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4 font-semibold text-gray-900">{{ $row->page_name }}</td>
                            <td class="px-5 py-4">
                                <p class="max-w-md truncate font-medium text-gray-900">{{ $row->meta_title ?: 'No meta title' }}</p>
                                <p class="mt-1 max-w-md truncate text-gray-500">{{ $row->meta_description ?: 'No meta description' }}</p>
                                @if($row->seo_rating || $row->best_rating || $row->review_number)
                                    <p class="mt-2 text-xs text-gray-400">Rating {{ $row->seo_rating ?: '-' }} / {{ $row->best_rating ?: '-' }} · Reviews {{ $row->review_number ?: 0 }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($row->og_image_path)
                                    <img src="{{ asset('storage/'.$row->og_image_path) }}" alt="{{ $row->page_name }}" class="h-12 w-20 rounded object-cover">
                                @else
                                    <span class="text-gray-400">None</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route($routePrefix.'.edit', $row) }}" class="rounded-lg p-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600" title="Edit">
                                        <i data-lucide="pencil" class="h-5 w-5"></i>
                                    </a>
                                    <form method="post" action="{{ route($routePrefix.'.destroy', $row) }}" onsubmit="return confirm('Delete this SEO record?')">
                                        @csrf
                                        @method('delete')
                                        <button class="rounded-lg p-2 text-gray-500 hover:bg-red-50 hover:text-red-600" title="Delete">
                                            <i data-lucide="trash-2" class="h-5 w-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-12 text-center text-gray-500">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
