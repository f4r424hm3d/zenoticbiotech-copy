@extends('layouts.admin')
@section('title', 'CDN Settings | Zenotic Admin')

@section('content')
<div class="mx-auto max-w-5xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">CDN Settings</h1>
        <p class="mt-1 text-sm text-gray-500">Control whether front assets are served locally or from your CDN domain.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1fr,320px]">
        <form method="post" action="{{ route('admin.cdn-settings.update') }}" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            @csrf
            <div class="mb-5 rounded-lg {{ $cdnEnabled && $cdnUrl ? 'bg-green-50 text-green-800' : 'bg-amber-50 text-amber-800' }} p-4">
                <p class="font-semibold">{{ $cdnEnabled && $cdnUrl ? 'CDN Active' : 'Local Storage Active' }}</p>
                <p class="mt-1 text-sm">{{ $cdnStatus['message'] }}</p>
            </div>

            <label class="mb-5 flex items-start gap-3">
                <input type="checkbox" name="cdn_enabled" value="1" @checked($cdnEnabled) class="mt-1 h-5 w-5 rounded border-gray-300 text-green-600 focus:ring-green-500">
                <span>
                    <span class="block font-semibold text-gray-900">Enable CDN</span>
                    <span class="text-sm text-gray-500">When enabled, public assets use the configured CDN domain.</span>
                </span>
            </label>

            <div class="mb-6">
                <label class="mb-2 block text-sm font-medium text-gray-900">CDN Domain</label>
                <input name="cdn_url" value="{{ old('cdn_url', $cdnUrl) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100" placeholder="cdn.example.com">
                <p class="mt-2 text-xs text-gray-500">Enter domain only, without `http://` or `https://`.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <button class="rounded-lg bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700">Save Settings</button>
                <button type="button" data-test-cdn class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Test CDN</button>
            </div>
            <div data-test-result class="mt-4 hidden rounded-lg border px-4 py-3 text-sm"></div>
        </form>

        <aside class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-3 font-bold text-gray-900">How It Works</h2>
            <ul class="space-y-3 text-sm text-gray-600">
                <li>Assets use local `/storage` and public paths by default.</li>
                <li>When CDN is enabled, `cdn_asset()` returns protocol-relative CDN URLs.</li>
                <li>If CDN is disabled, the front site falls back to local assets.</li>
            </ul>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('[data-test-cdn]')?.addEventListener('click', async () => {
        const result = document.querySelector('[data-test-result]');
        result.className = 'mt-4 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700';
        result.textContent = 'Testing CDN...';
        const response = await fetch('{{ route('admin.cdn-settings.test') }}', {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'}
        });
        const data = await response.json();
        result.className = `mt-4 rounded-lg border px-4 py-3 text-sm ${data.reachable ? 'border-green-200 bg-green-50 text-green-700' : 'border-amber-200 bg-amber-50 text-amber-700'}`;
        result.textContent = data.message;
    });
});
</script>
@endpush
