@extends('layouts.site')
@section('title', 'Contact | Zenotic Biotech')
@section('content')
@include('pages._hero', ['title' => 'Contact Us', 'subtitle' => "Get in touch with our team. We'd love to hear from you."])
<section class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">
            <div class="min-h-[320px] rounded-lg bg-gradient-to-br from-green-50 to-green-100 p-12">
                <i data-lucide="mail" class="mb-10 h-16 w-16 text-green-600"></i>
                <h3 class="mb-6 text-3xl font-bold text-gray-900">Email</h3>
                <p class="mb-4 text-2xl text-gray-600">General inquiries:</p>
                <a href="mailto:info@zenoticbiotech.com" class="mb-9 block text-2xl font-semibold text-green-600 hover:text-green-700">info@zenoticbiotech.com</a>
                <p class="mb-4 text-2xl text-gray-600">Career opportunities:</p>
                <a href="mailto:careers@zenoticbiotech.com" class="block text-2xl font-semibold text-green-600 hover:text-green-700">careers@zenoticbiotech.com</a>
            </div>

            <div class="min-h-[320px] rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 p-12">
                <i data-lucide="phone" class="mb-10 h-16 w-16 text-blue-600"></i>
                <h3 class="mb-6 text-3xl font-bold text-gray-900">Phone</h3>
                <a href="tel:+919445501234" class="mb-9 block text-3xl font-semibold text-blue-600 hover:text-blue-700">+91 9445501234</a>
                <p class="text-xl leading-relaxed text-gray-600">Available Monday - Friday, 9:00 AM to 6:00 PM IST</p>
            </div>

            <div class="min-h-[320px] rounded-lg bg-gradient-to-br from-teal-50 to-teal-100 p-12">
                <i data-lucide="map-pin" class="mb-10 h-16 w-16 text-teal-600"></i>
                <h3 class="mb-6 text-3xl font-bold text-gray-900">Location</h3>
                <p class="text-2xl leading-relaxed text-gray-700">Zenotic Biotech Private Limited<br>India</p>
            </div>
        </div>
    </div>
</section>
<section class="bg-gray-50 py-20"><div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8"><h2 class="mb-4 text-center text-4xl font-bold">Send us a Message</h2><p class="mb-12 text-center text-gray-600">Fill out the form below and your email client will open with the message.</p><form action="mailto:info@zenoticbiotech.com" method="post" enctype="text/plain" class="rounded-lg bg-white p-8 shadow-md"><div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2"><div><label class="mb-2 block text-sm font-semibold">Name</label><input required name="name" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-green-600 focus:outline-none" placeholder="Your name"></div><div><label class="mb-2 block text-sm font-semibold">Email</label><input required type="email" name="email" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-green-600 focus:outline-none" placeholder="your@email.com"></div></div><div class="mb-6"><label class="mb-2 block text-sm font-semibold">Phone (Optional)</label><input type="tel" name="phone" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-green-600 focus:outline-none" placeholder="+91 XXXXXXXXXX"></div><div class="mb-6"><label class="mb-2 block text-sm font-semibold">Message</label><textarea required name="message" rows="6" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-green-600 focus:outline-none" placeholder="Your message..."></textarea></div><button class="w-full rounded-lg bg-green-600 py-3 font-semibold text-white transition hover:bg-green-700">Send Message</button></form></div></section>
<section class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 class="mb-12 text-center text-4xl font-bold text-gray-900">Quick Links</h2>
        <div class="grid grid-cols-1 gap-8 text-center md:grid-cols-3">
            @foreach ([['Product Inquiry','Have questions about our products?','Contact Sales'],['Partnership','Interested in collaboration?','Explore Partnerships'],['Technical Support','Need technical assistance?','Get Support']] as $item)
                <div class="p-6"><h3 class="mb-3 text-xl font-bold text-gray-900">{{ $item[0] }}</h3><p class="mb-4 text-gray-600">{{ $item[1] }}</p><a href="mailto:info@zenoticbiotech.com" class="font-semibold text-green-600 hover:text-green-700">{{ $item[2] }}</a></div>
            @endforeach
        </div>
    </div>
</section>
@endsection
