@extends('layouts.admin')
@section('title', 'Services | Zenotic Admin')
@section('content')
<div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Services</h1>
        <p class="mt-1 text-gray-500">Manage services shown on the website</p>
    </div>
    <a href="{{ route('admin.services.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700">
        <i data-lucide="plus" class="h-5 w-5"></i>
        New Service
    </a>
</div>

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 text-left text-gray-500">
                    <th class="px-6 py-3 font-medium">Service</th>
                    <th class="px-6 py-3 font-medium">Icon</th>
                    <th class="px-6 py-3 font-medium">Content</th>
                    <th class="px-6 py-3 font-medium">FAQs</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium">Order</th>
                    <th class="px-6 py-3 text-right font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($services as $service)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">{{ $service->name }}</p>
                            <p class="mt-1 max-w-xl text-gray-500">{{ $service->description }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-2xl">{!! $service->emoji !!}</span>
                            <span class="ml-2 text-xs text-gray-400">{{ $service->icon }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.services.contents', $service) }}" class="inline-flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-xs font-semibold text-green-700 hover:bg-green-100">
                                <i data-lucide="file-text" class="h-4 w-4"></i>
                                Contents
                                <span class="rounded-full bg-white px-2 py-0.5">{{ $service->contents_count }}</span>
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.services.faqs', $service) }}" class="inline-flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100">
                                <i data-lucide="circle-help" class="h-4 w-4"></i>
                                FAQs
                                <span class="rounded-full bg-white px-2 py-0.5">{{ $service->faqs_count }}</span>
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $service->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">{{ $service->status }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $service->order_index }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-1">
                                <form method="post" action="{{ route('admin.services.toggle', $service) }}">
                                    @csrf
                                    <button title="Toggle status" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700">
                                        <i data-lucide="{{ $service->status === 'published' ? 'eye-off' : 'eye' }}" class="h-5 w-5"></i>
                                    </button>
                                </form>
                                <a href="{{ route('admin.services.edit', $service) }}" title="Edit" class="rounded-lg p-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600">
                                    <i data-lucide="pencil" class="h-5 w-5"></i>
                                </a>
                                <form method="post" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Delete this service?')">
                                    @csrf
                                    @method('delete')
                                    <button title="Delete" class="rounded-lg p-2 text-gray-500 hover:bg-red-50 hover:text-red-600">
                                        <i data-lucide="trash-2" class="h-5 w-5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-16 text-center text-gray-500">No services found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
