@extends('layouts.admin')
@section('title', 'Leads | Zenotic Admin')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Leads</h1>
    <p class="mt-1 text-gray-500">Contact form enquiries from the website</p>
</div>

<form method="get" class="mb-5 flex flex-col gap-3 sm:flex-row">
    <input name="search" value="{{ request('search') }}" placeholder="Search name, email, phone, subject..." class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500">
    <select name="status" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-green-500">
        <option value="">All statuses</option>
        <option value="new" @selected(request('status') === 'new')>New</option>
        <option value="contacted" @selected(request('status') === 'contacted')>Contacted</option>
        <option value="closed" @selected(request('status') === 'closed')>Closed</option>
    </select>
    <button class="rounded-lg border border-gray-300 px-4 py-2.5 font-medium text-gray-700 hover:bg-gray-50">Filter</button>
    @if(request()->hasAny(['search', 'status']))
        <a href="{{ route('admin.leads') }}" class="rounded-lg px-4 py-2.5 text-center font-medium text-gray-600 hover:bg-gray-100">Clear</a>
    @endif
</form>

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 text-left text-gray-500">
                    <th class="px-6 py-3 font-medium">Lead</th>
                    <th class="px-6 py-3 font-medium">Message</th>
                    <th class="px-6 py-3 font-medium">Mail</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium">Date</th>
                    <th class="px-6 py-3 text-right font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($leads as $lead)
                    <tr class="align-top hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">{{ $lead->name }}</p>
                            <a href="mailto:{{ $lead->email }}" class="mt-1 block text-gray-600 hover:text-green-700">{{ $lead->email }}</a>
                            @if($lead->phone)
                                <a href="tel:{{ $lead->phone }}" class="mt-1 block text-gray-600 hover:text-green-700">{{ $lead->phone }}</a>
                            @endif
                            <p class="mt-2 text-xs text-gray-400">{{ $lead->ip_address }}</p>
                        </td>
                        <td class="max-w-xl px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $lead->subject ?: 'Contact enquiry' }}</p>
                            <details class="mt-2">
                                <summary class="cursor-pointer text-sm font-semibold text-green-700">View message</summary>
                                <p class="mt-3 whitespace-pre-line rounded-lg bg-gray-50 p-3 text-gray-700">{{ $lead->message }}</p>
                                @if($lead->source_path)
                                    <p class="mt-2 text-xs text-gray-500">Source: {{ $lead->source_path }}</p>
                                @endif
                                @if($lead->mail_error)
                                    <p class="mt-2 rounded-lg bg-red-50 p-3 text-xs text-red-700">{{ $lead->mail_error }}</p>
                                @endif
                            </details>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-2">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium {{ $lead->team_mail_sent_at ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    <i data-lucide="{{ $lead->team_mail_sent_at ? 'check' : 'x' }}" class="h-3.5 w-3.5"></i> Team
                                </span>
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium {{ $lead->lead_mail_sent_at ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    <i data-lucide="{{ $lead->lead_mail_sent_at ? 'check' : 'x' }}" class="h-3.5 w-3.5"></i> Lead
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <form method="post" action="{{ route('admin.leads.update', $lead) }}">
                                @csrf
                                @method('patch')
                                <select name="status" onchange="this.form.submit()" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm capitalize focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="new" @selected($lead->status === 'new')>New</option>
                                    <option value="contacted" @selected($lead->status === 'contacted')>Contacted</option>
                                    <option value="closed" @selected($lead->status === 'closed')>Closed</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $lead->created_at?->format('d M Y') }}<br>
                            <span class="text-xs text-gray-400">{{ $lead->created_at?->format('h:i A') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end">
                                <form method="post" action="{{ route('admin.leads.destroy', $lead) }}" onsubmit="return confirm('Delete this lead?')">
                                    @csrf
                                    @method('delete')
                                    <button title="Delete" class="rounded-lg p-2 text-gray-500 hover:bg-red-50 hover:text-red-600"><i data-lucide="trash-2" class="h-5 w-5"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-16 text-center text-gray-500">No leads found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $leads->links() }}</div>
@endsection
