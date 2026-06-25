@extends('layouts.admin')
@section('title', 'Dashboard | Zenotic Admin')
@section('content')
<div class="mb-6"><h1 class="text-2xl font-bold">Dashboard</h1><p class="mt-1 text-gray-500">Catalog overview</p></div>
<div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-4">
    @foreach ([['Products',$productCount,'package'],['Published',$publishedCount,'eye'],['Drafts',$draftCount,'file'],['Categories',$categoryCount,'tags'],['Services',$serviceCount,'briefcase-business']] as $card)
        <div class="rounded-xl border border-gray-200 bg-white p-6"><div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-green-700"><i data-lucide="{{ $card[2] }}" class="h-5 w-5"></i></div><p class="text-sm text-gray-500">{{ $card[0] }}</p><p class="text-3xl font-bold">{{ $card[1] }}</p></div>
    @endforeach
</div>
<div class="rounded-xl border border-gray-200 bg-white">
    <div class="border-b border-gray-100 px-6 py-4"><h2 class="font-semibold">Latest Products</h2></div>
    <div class="divide-y divide-gray-100">@forelse ($latestProducts as $product)<div class="flex items-center justify-between px-6 py-4"><div><p class="font-medium">{{ $product->name }}</p><p class="text-sm text-gray-500">{{ $product->category?->name }} · {{ ucfirst($product->segment) }}</p></div><span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $product->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">{{ $product->status }}</span></div>@empty<div class="px-6 py-12 text-center text-gray-500">No products yet.</div>@endforelse</div>
</div>
@endsection
