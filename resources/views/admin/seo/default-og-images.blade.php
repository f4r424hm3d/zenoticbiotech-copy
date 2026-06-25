@extends('layouts.admin')
@php($isEdit = $record->exists)
@section('title', 'Default OG Images | Zenotic Admin')

@section('content')
<div class="mx-auto max-w-5xl">
    <div class="mb-6 flex flex-col justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Default OG Images</h1>
            <p class="mt-1 text-sm text-gray-500">Set fallback Open Graph images for pages.</p>
        </div>
        @if ($isEdit)
            <a href="{{ route('admin.default-og-images') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Add New
            </a>
        @endif
    </div>

    <form method="post" enctype="multipart/form-data" action="{{ $isEdit ? route('admin.default-og-images.update', $record) : route('admin.default-og-images.store') }}" class="mb-8 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        @csrf
        @if($isEdit)
            @method('put')
        @endif

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">Page <span class="text-red-500">*</span></label>
                <input name="page" value="{{ old('page', $record->page) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="all, home, products">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">OG Image <span class="text-red-500">{{ $isEdit ? '' : '*' }}</span></label>
                <input type="file" name="og_image" accept="image/*" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @if($record->file_path)
                    <a href="{{ asset('storage/'.$record->file_path) }}" target="_blank" class="mt-2 inline-flex text-xs font-medium text-green-700">View current image</a>
                @endif
            </div>
        </div>

        <div class="mt-6 flex items-center gap-4">
            <button class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                <i data-lucide="save" class="h-4 w-4"></i>
                {{ $isEdit ? 'Update Image' : 'Create Image' }}
            </button>
            @if($isEdit)
                <a href="{{ route('admin.default-og-images') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Cancel</a>
            @endif
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
        <div class="border-b border-gray-200 px-5 py-4">
            <h2 class="font-semibold text-gray-900">Default OG Image List</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-gray-500">
                        <th class="px-5 py-3 font-medium">Page</th>
                        <th class="px-5 py-3 font-medium">Image</th>
                        <th class="px-5 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($records as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4 font-semibold text-gray-900">{{ $row->page }}</td>
                            <td class="px-5 py-4">
                                <img src="{{ asset('storage/'.$row->file_path) }}" alt="{{ $row->page }}" class="h-14 w-24 rounded object-cover">
                                <p class="mt-1 text-xs text-gray-400">{{ $row->file_name }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('admin.default-og-images.edit', $row) }}" class="rounded-lg p-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600" title="Edit">
                                        <i data-lucide="pencil" class="h-5 w-5"></i>
                                    </a>
                                    <form method="post" action="{{ route('admin.default-og-images.destroy', $row) }}" onsubmit="return confirm('Delete this default OG image?')">
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
                        <tr><td colspan="3" class="px-5 py-12 text-center text-gray-500">No default OG images found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
