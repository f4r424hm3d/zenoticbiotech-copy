<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin | Zenotic Biotech')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    @stack('styles')
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">
    <div class="flex min-h-screen">
        <aside class="hidden w-64 border-r border-gray-200 bg-white lg:block">
            <div class="flex h-20 items-center gap-3 border-b border-gray-100 px-6">
                <img src="{{ cdn_asset('logo_highquaity.png') }}" alt="Zenotic Biotech" class="h-12">
                <div><p class="font-bold">Zenotic Admin</p><p class="text-xs text-gray-500">Product Management</p></div>
            </div>
            <nav class="space-y-1 p-4">
                @foreach ([['admin.dashboard','layout-dashboard','Dashboard'],['admin.products','package','Products'],['admin.categories','tags','Categories'],['admin.services','briefcase-business','Services'],['admin.leads','inbox','Leads'],['admin.static-page-seos','file-search','Static SEO'],['admin.dynamic-page-seos','panel-top','Dynamic SEO'],['admin.default-og-images','image','Default OG'],['admin.upload-files','upload','Upload Files'],['admin.cdn-settings','cloud','CDN Settings'],['admin.email-settings','mail','Email Settings'],['admin.profile','user','Profile']] as $item)
                    <a href="{{ route($item[0]) }}" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs($item[0].'*') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i data-lucide="{{ $item[1] }}" class="h-5 w-5"></i>{{ $item[2] }}
                    </a>
                @endforeach
            </nav>
        </aside>
        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex h-20 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-6 lg:px-8">
                <div>
                    <p class="text-sm text-gray-500">Signed in as</p>
                    <p class="font-semibold">{{ auth()->user()->name ?? 'Admin' }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">View Site</a>
                    <form method="post" action="{{ route('admin.logout') }}">@csrf<button class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">Logout</button></form>
                </div>
            </header>
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @if (session('status'))
                    <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('status') }}</div>
                @endif
                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    <script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
    @stack('scripts')
</body>
</html>
