@extends('layouts.admin')
@section('title', 'Products | Zenotic Admin')
@section('content')
<div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
    <div><h1 class="text-2xl font-bold">Products</h1><p class="mt-1 text-gray-500">Manage your product catalog</p></div>
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 font-medium text-white hover:bg-green-700"><i data-lucide="plus" class="h-5 w-5"></i> New Product</a>
</div>
<form method="get" class="mb-5 flex flex-col gap-3 sm:flex-row">
    <input name="search" value="{{ request('search') }}" placeholder="Search products..." class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500">
    <select name="segment" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500">
        <option value="">All segments</option><option value="human" @selected(request('segment') === 'human')>Human</option><option value="animal" @selected(request('segment') === 'animal')>Animal</option>
    </select>
    <select name="status" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500">
        <option value="">All statuses</option><option value="published" @selected(request('status') === 'published')>Published</option><option value="draft" @selected(request('status') === 'draft')>Draft</option>
    </select>
    <button class="rounded-lg border border-gray-300 px-4 py-2.5 font-medium text-gray-700 hover:bg-gray-50">Filter</button>
</form>
<div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="border-b border-gray-200 text-left text-gray-500"><th class="px-6 py-3 font-medium">Product</th><th class="px-6 py-3 font-medium">Segment</th><th class="px-6 py-3 font-medium">Category</th><th class="px-6 py-3 font-medium">Content</th><th class="px-6 py-3 font-medium">FAQs</th><th class="px-6 py-3 font-medium">Status</th><th class="px-6 py-3 font-medium">Order</th><th class="px-6 py-3 text-right font-medium">Actions</th></tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3"><div class="flex items-center gap-3"><div class="h-10 w-10 overflow-hidden rounded-lg bg-gray-100">@if ($product->image_url)<img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">@endif</div><span class="font-medium">{{ $product->name }}</span></div></td>
                        <td class="px-6 py-3"><span class="rounded-full px-2.5 py-1 text-xs font-medium capitalize {{ $product->segment === 'animal' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }}">{{ $product->segment }}</span></td>
                        <td class="px-6 py-3 text-gray-600">{{ $product->category?->name }}</td>
                        <td class="px-6 py-3"><a href="{{ route('admin.products.contents', $product) }}" class="inline-flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-xs font-semibold text-green-700 hover:bg-green-100"><i data-lucide="file-text" class="h-4 w-4"></i> Contents <span class="rounded-full bg-white px-2 py-0.5">{{ $product->contents_count }}</span></a></td>
                        <td class="px-6 py-3"><a href="{{ route('admin.products.faqs', $product) }}" class="inline-flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100"><i data-lucide="circle-help" class="h-4 w-4"></i> FAQs <span class="rounded-full bg-white px-2 py-0.5">{{ $product->faqs_count }}</span></a></td>
                        <td class="px-6 py-3"><span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $product->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">{{ $product->status }}</span></td>
                        <td class="px-6 py-3 text-gray-600">{{ $product->order_index }}</td>
                        <td class="px-6 py-3">
                            <div class="flex justify-end gap-1">
                                <form method="post" action="{{ route('admin.products.toggle', $product) }}">@csrf<button title="Toggle status" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100"><i data-lucide="{{ $product->status === 'published' ? 'eye-off' : 'eye' }}" class="h-5 w-5"></i></button></form>
                                <a href="{{ route('admin.products.edit', $product) }}" title="Edit" class="rounded-lg p-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600"><i data-lucide="pencil" class="h-5 w-5"></i></a>
                                <form method="post" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">@csrf @method('delete')<button title="Delete" class="rounded-lg p-2 text-gray-500 hover:bg-red-50 hover:text-red-600"><i data-lucide="trash-2" class="h-5 w-5"></i></button></form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-6 py-16 text-center text-gray-500">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-6">{{ $products->links() }}</div>
@endsection
