@extends('layouts.admin')
@php($isEdit = $faq->exists)
@section('title', 'Service FAQs | Zenotic Admin')

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="mb-5 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <a href="{{ route('admin.services') }}" class="mb-2 inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Back to services
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Service FAQs</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $service->name }}</p>
        </div>
        @if($isEdit)
            <a href="{{ route('admin.services.faqs', $service) }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Add New</a>
        @endif
    </div>

    <form method="post" action="{{ $isEdit ? route('admin.services.faqs.update', [$service, $faq]) : route('admin.services.faqs.store', $service) }}" class="mb-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        @if($isEdit)
            @method('put')
        @endif

        <div class="mb-5">
            <label class="mb-2 block text-sm font-medium text-gray-900">Question <span class="text-red-500">*</span></label>
            <input name="question" value="{{ old('question', $faq->question) }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="Enter question">
        </div>

        <div class="mb-6">
            <label class="mb-2 block text-sm font-medium text-gray-900">Answer <span class="text-red-500">*</span></label>
            <textarea id="answer" name="answer" rows="8" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">{{ old('answer', $faq->answer) }}</textarea>
        </div>

        <div class="flex items-center gap-5">
            <button class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700">
                <i data-lucide="save" class="h-4 w-4"></i>
                {{ $isEdit ? 'Update FAQ' : 'Create FAQ' }}
            </button>
            @if($isEdit)
                <a href="{{ route('admin.services.faqs', $service) }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Cancel</a>
            @endif
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="font-semibold text-gray-900">FAQ List</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-gray-500">
                        <th class="px-6 py-3 font-medium">Question</th>
                        <th class="px-6 py-3 font-medium">Answer</th>
                        <th class="px-6 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($faqs as $row)
                        <tr>
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $row->question }}</td>
                            <td class="px-6 py-4 text-gray-600">
                                <div class="max-w-lg truncate">{{ strip_tags($row->answer) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('admin.services.faqs.edit', [$service, $row]) }}" class="rounded-lg p-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600" title="Edit">
                                        <i data-lucide="pencil" class="h-5 w-5"></i>
                                    </a>
                                    <form method="post" action="{{ route('admin.services.faqs.destroy', [$service, $row]) }}" onsubmit="return confirm('Delete this FAQ?')">
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
                        <tr><td colspan="3" class="px-6 py-12 text-center text-gray-500">No FAQs added yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/f4r424hm3d/ckeditor@master/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (window.CKEDITOR && document.getElementById('answer')) {
        const editor = CKEDITOR.replace('answer');
        editor.on('change', () => editor.updateElement());
    }
});
</script>
@endpush
