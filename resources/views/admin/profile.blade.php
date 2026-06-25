@extends('layouts.admin')
@section('title', 'Profile | Zenotic Admin')
@section('content')
<div class="mb-6"><h1 class="text-2xl font-bold">Profile</h1><p class="mt-1 text-gray-500">Update your password</p></div>
<form method="post" action="{{ route('admin.profile.password') }}" class="max-w-xl rounded-xl border border-gray-200 bg-white p-6">@csrf
    <div class="mb-5"><label class="mb-1.5 block text-sm font-medium">Current Password</label><input type="password" name="current_password" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
    <div class="mb-5"><label class="mb-1.5 block text-sm font-medium">New Password</label><input type="password" name="password" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
    <div class="mb-5"><label class="mb-1.5 block text-sm font-medium">Confirm Password</label><input type="password" name="password_confirmation" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500"></div>
    <button class="rounded-lg bg-green-600 px-5 py-2.5 font-semibold text-white hover:bg-green-700">Change Password</button>
</form>
@endsection
