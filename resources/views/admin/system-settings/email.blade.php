@extends('layouts.admin')
@section('title', 'Email Settings | Zenotic Admin')

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Email Settings</h1>
        <p class="mt-1 text-sm text-gray-500">Choose main/testing recipients for system emails.</p>
    </div>

    <form method="post" action="{{ route('admin.email-settings.update') }}" class="mb-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        <h2 class="mb-4 text-lg font-bold text-gray-900">Active Email Mode</h2>
        <div class="grid gap-3 sm:grid-cols-2">
            <label class="rounded-lg border p-4 {{ $emailMode === 'main' ? 'border-green-300 bg-green-50' : 'border-gray-200' }}">
                <input type="radio" name="email_mode" value="main" @checked($emailMode === 'main') class="mr-2 text-green-600">
                <span class="font-semibold">Main Configuration</span>
            </label>
            <label class="rounded-lg border p-4 {{ $emailMode === 'testing' ? 'border-amber-300 bg-amber-50' : 'border-gray-200' }}">
                <input type="radio" name="email_mode" value="testing" @checked($emailMode === 'testing') class="mr-2 text-green-600">
                <span class="font-semibold">Testing Configuration</span>
            </label>
        </div>
        <button class="mt-4 rounded-lg bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700">Update Mode</button>
    </form>

    <div class="grid gap-6 lg:grid-cols-2">
        @foreach (['main' => $mainSettings, 'testing' => $testingSettings] as $mode => $settings)
            <form method="post" action="{{ $mode === 'main' ? route('admin.email-settings.update-main') : route('admin.email-settings.update-testing') }}" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                @csrf
                <h2 class="mb-4 text-lg font-bold text-gray-900">{{ ucfirst($mode) }} Email Configuration</h2>
                @foreach (['to' => 'To', 'cc' => 'CC', 'bcc' => 'BCC'] as $key => $label)
                    <div class="mb-4 grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-900">{{ $label }} Email</label>
                            <input type="email" name="{{ $mode }}_{{ $key }}_email" value="{{ old($mode.'_'.$key.'_email', $settings[$key.'_email']) }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-900">{{ $label }} Name</label>
                            <input name="{{ $mode }}_{{ $key }}_name" value="{{ old($mode.'_'.$key.'_name', $settings[$key.'_name']) }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-100">
                        </div>
                    </div>
                @endforeach
                <button class="rounded-lg bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700">Save {{ ucfirst($mode) }}</button>
            </form>
        @endforeach
    </div>
</div>
@endsection
