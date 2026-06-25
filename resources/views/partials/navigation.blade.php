@php
    $links = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'About Us', 'route' => 'about'],
        ['label' => 'Leadership', 'route' => 'leadership'],
        ['label' => 'Products', 'route' => 'products'],
        ['label' => 'Services', 'route' => 'services'],
        ['label' => 'R&D', 'route' => 'research'],
        ['label' => 'Quality', 'route' => 'quality'],
        ['label' => 'Careers', 'route' => 'careers'],
        ['label' => 'Contact', 'route' => 'contact'],
    ];
@endphp
<nav class="fixed inset-x-0 top-0 z-50 border-b border-gray-100 bg-white/85 backdrop-blur-md">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-4 sm:h-24">
            <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3">
                <img src="/logo_highquaity.png" alt="Zenotic Biotech" class="h-16">
                <div class="truncate text-left">
                    <h1 class="whitespace-nowrap bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-xl font-bold text-transparent">Zenotic Biotech</h1>
                    <p class="whitespace-nowrap text-xs font-medium text-gray-600">Balance Your Microbiome</p>
                </div>
            </a>
            <div class="hidden gap-8 md:flex">
                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}" class="relative py-2 text-sm font-medium transition-colors {{ request()->routeIs($link['route']) ? 'text-green-600' : 'text-gray-700 hover:text-green-600' }}">
                        {{ $link['label'] }}
                        @if (request()->routeIs($link['route']))
                            <span class="absolute inset-x-0 bottom-0 h-0.5 bg-green-600"></span>
                        @endif
                    </a>
                @endforeach
            </div>
            <button type="button" data-menu-button class="rounded-full p-2 text-gray-700 transition hover:bg-gray-100 md:hidden" aria-label="Toggle navigation">
                <i data-lucide="menu" class="h-6 w-6"></i>
            </button>
        </div>
        <div data-menu class="hidden border-t border-gray-100 py-4 md:hidden">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}" class="mb-1 block rounded-lg px-4 py-3 transition {{ request()->routeIs($link['route']) ? 'bg-green-600 text-white shadow-lg shadow-green-200' : 'text-gray-700 hover:bg-green-50 hover:text-green-600' }}">{{ $link['label'] }}</a>
            @endforeach
        </div>
    </div>
</nav>
