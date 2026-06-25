<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Admin Login | Zenotic Biotech</title><script src="https://cdn.tailwindcss.com"></script><script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script></head>
<body class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50">
    <div class="flex min-h-screen items-center justify-center px-4">
        <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl">
            <div class="mb-8 text-center"><div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-full bg-green-100"><i data-lucide="lock" class="h-6 w-6 text-green-600"></i></div><h1 class="text-2xl font-bold">Admin Login</h1><p class="mt-1 text-gray-500">Zenotic Biotech Product Management</p></div>
            @if (session('status'))<div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">{{ session('status') }}</div>@endif
            @if ($errors->any())<div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">{{ $errors->first() }}</div>@endif
            <form method="post" action="{{ route('admin.login.store') }}" class="space-y-5">@csrf
                <div><label class="mb-1.5 block text-sm font-medium text-gray-700">Email</label><input type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="you@example.com"></div>
                <div><div class="mb-1.5 flex items-center justify-between gap-3"><label class="block text-sm font-medium text-gray-700">Password</label><a href="{{ route('admin.forgot-password') }}" class="text-sm font-medium text-green-700 hover:text-green-800">Forgot password?</a></div><input type="password" name="password" required autocomplete="current-password" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Password"></div>
                <label class="flex items-center gap-2 text-sm text-gray-600"><input type="checkbox" name="remember" value="1" class="rounded border-gray-300 text-green-600"> Remember me</label>
                <button class="w-full rounded-lg bg-green-600 py-2.5 font-semibold text-white hover:bg-green-700">Sign In</button>
            </form>
        </div>
    </div>
    <script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
</body>
</html>
