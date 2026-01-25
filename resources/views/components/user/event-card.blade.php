@props(['title', 'date', 'location', 'price', 'image', 'href' => null])

@php
// Format Indonesian price
$formattedPrice = $price ? 'Rp ' . number_format($price, 0, ',', '.') : 'Harga tidak tersedia';

$formattedDate = $date
? \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d F Y, H:i')
: 'Tanggal tidak tersedia';

// Safe image URL: use external URL if provided, otherwise use asset (storage path)
$imageUrl = $image
? (filter_var($image, FILTER_VALIDATE_URL)
? $image
: asset('images/events/' . $image))
: asset('images/konser.jpeg');

@endphp

<a href="{{ $href ?? '#' }}" class="block h-full">
    <div class="card bg-base-100 h-96 shadow-sm hover:shadow-md transition-shadow duration-300">
        {{-- <div class="h-48 overflow-hidden bg-gray-100 rounded-t-lg flex items-center justify-center">
        </div> --}}
        <figure class="h-48 overflow-hidden">
            <img 
                src="{{ $imageUrl }}" 
                alt="{{ $title }}" 
                class="max-w-full max-h-full object-contain"
            >
        </figure>

        <div class="card-body">
            <h1 class="card-title text-xl font-bold">
                {{ $title }}
            </h1>

            <p class="text-sm text-gray-500">
                {{ $formattedDate }}
            </p>

            <p class="text-sm">
                📍 {{ $location }}
            </p>
            
            @if ($price)
                <p class="">
                    Harga mulai dari 
                    <p class="font-bold text-lg mt-2">{{ $formattedPrice }}</p>
                </p>
            @elseif (!$price)
                <p class="text-sm text-gray-500">
                    Harga tiket belum tersedia
                </p>
            @endif

        </div>
    </div>
</a>