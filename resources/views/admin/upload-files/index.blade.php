@extends('layouts.admin')
@section('title', 'Upload Files | Zenotic Admin')

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Upload Files</h1>
        <p class="mt-1 text-sm text-gray-500">Upload reusable files and copy their public URLs.</p>
    </div>

    <form method="post" enctype="multipart/form-data" action="{{ route('admin.upload-files.store') }}" class="mb-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        <div class="grid gap-4 md:grid-cols-[1fr,1fr,auto] md:items-end">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">Title</label>
                <input name="title" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="Optional title">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-900">File</label>
                <input type="file" name="file" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
            </div>
            <button class="rounded-lg bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700">Upload</button>
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-gray-500">
                        <th class="px-6 py-3 font-medium">File</th>
                        <th class="px-6 py-3 font-medium">URL</th>
                        <th class="px-6 py-3 font-medium">Date</th>
                        <th class="px-6 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($files as $file)
                        @php($url = storage_file_url($file->file_path))
                        <tr>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $file->title ?: $file->file_name }}</p>
                                <p class="mt-1 text-xs text-gray-500">{{ $file->file_name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex min-w-[320px] gap-2">
                                    <input readonly value="{{ $url }}" class="file-url flex-1 rounded-lg border border-gray-300 px-3 py-2 text-xs text-gray-600">
                                    <button type="button" data-copy-url class="rounded-lg border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">Copy</button>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $file->created_at?->format('d M Y h:i A') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-1">
                                    <a href="{{ $url }}" target="_blank" class="rounded-lg p-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600"><i data-lucide="external-link" class="h-5 w-5"></i></a>
                                    <form method="post" action="{{ route('admin.upload-files.destroy', $file) }}" onsubmit="return confirm('Delete this file?')">
                                        @csrf
                                        @method('delete')
                                        <button class="rounded-lg p-2 text-gray-500 hover:bg-red-50 hover:text-red-600"><i data-lucide="trash-2" class="h-5 w-5"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">No files uploaded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">{{ $files->links() }}</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-copy-url]').forEach((button) => {
        button.addEventListener('click', async () => {
            const input = button.parentElement.querySelector('.file-url');
            await navigator.clipboard.writeText(input.value);
            button.textContent = 'Copied';
            setTimeout(() => button.textContent = 'Copy', 1200);
        });
    });
});
</script>
@endpush
