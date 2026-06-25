@extends('layouts.admin')
@section('title', 'Dashboard | Zenotic Admin')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Dashboard</h1>
    <p class="mt-1 text-gray-500">Catalog and enquiry overview</p>
</div>

<div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-6">
    @foreach ([['Products',$productCount,'package'],['Published',$publishedCount,'eye'],['Drafts',$draftCount,'file'],['Categories',$categoryCount,'tags'],['Services',$serviceCount,'briefcase-business'],['Leads',$leadCount,'inbox']] as $card)
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-green-700">
                <i data-lucide="{{ $card[2] }}" class="h-5 w-5"></i>
            </div>
            <p class="text-sm text-gray-500">{{ $card[0] }}</p>
            <p class="text-3xl font-bold">{{ $card[1] }}</p>
        </div>
    @endforeach
</div>

<div class="grid gap-6 xl:grid-cols-2">
    <div class="rounded-xl border border-gray-200 bg-white">
        <div class="border-b border-gray-100 px-6 py-4">
            <h2 class="font-semibold">Latest Products</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse ($latestProducts as $product)
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <p class="font-medium">{{ $product->name }}</p>
                        <p class="text-sm text-gray-500">{{ $product->category?->name }} · {{ ucfirst($product->segment) }}</p>
                    </div>
                    <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $product->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">{{ $product->status }}</span>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-500">No products yet.</div>
            @endforelse
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
            <h2 class="font-semibold">Latest Leads</h2>
            <a href="{{ route('admin.leads') }}" class="text-sm font-semibold text-green-700 hover:text-green-800">View all</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse ($latestLeads as $lead)
                <div class="px-6 py-4">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-medium">{{ $lead->name }}</p>
                            <p class="text-sm text-gray-500">{{ $lead->email }}{{ $lead->phone ? ' · '.$lead->phone : '' }}</p>
                        </div>
                        <span class="rounded-full px-2.5 py-1 text-xs font-medium capitalize {{ $lead->status === 'closed' ? 'bg-gray-100 text-gray-700' : ($lead->status === 'contacted' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">{{ $lead->status }}</span>
                    </div>
                    <p class="mt-2 line-clamp-2 text-sm text-gray-600">{{ $lead->message }}</p>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-500">No leads yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
