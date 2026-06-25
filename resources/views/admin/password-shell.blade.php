<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>{{ $title }} | Zenotic Biotech</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50">
    <div class="flex min-h-screen items-center justify-center px-4">
        <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl">
            <h1 class="text-2xl font-bold">{{ $title }}</h1><p class="mt-1 text-gray-500">{{ $subtitle }}</p>
            @if (session('status'))<div class="mt-6 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">{{ session('status') }}</div>@endif
            @if ($errors->any())<div class="mt-6 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">{{ $errors->first() }}</div>@endif
            <form method="post" action="{{ $route }}" class="mt-6 space-y-5">@csrf
                @if ($fields === 'reset')<input type="hidden" name="token" value="{{ $token }}">@endif
                <div><label class="mb-1.5 block text-sm font-medium">Email</label><input type="email" name="email" value="{{ old('email', $email ?? '') }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                @if ($fields === 'reset')
                    <div><label class="mb-1.5 block text-sm font-medium">New Password</label><input type="password" name="password" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                    <div><label class="mb-1.5 block text-sm font-medium">Confirm Password</label><input type="password" name="password_confirmation" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
                @endif
                <button class="w-full rounded-lg bg-green-600 py-2.5 font-semibold text-white hover:bg-green-700">{{ $fields === 'reset' ? 'Reset Password' : 'Send Reset Link' }}</button>
                <a href="{{ route('admin.login') }}" class="block text-center text-sm font-medium text-green-700">Back to login</a>
            </form>
        </div>
    </div>
</body>
</html>
