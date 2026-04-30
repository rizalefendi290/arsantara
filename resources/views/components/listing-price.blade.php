@props(['listing', 'size' => 'normal'])

@php
    $mainClass = $size === 'large'
        ? 'text-3xl text-blue-600 font-bold'
        : 'text-blue-600 font-bold';
@endphp

@if($listing->hasDiscount())
    <div>
        <div class="flex items-center gap-2">
            <p class="{{ $mainClass }}">Rp {{ number_format($listing->finalPrice(), 0, ',', '.') }}</p>
            <span class="bg-red-100 text-red-600 text-xs font-semibold px-2 py-1 rounded">
                -{{ $listing->discountPercent() }}%
            </span>
        </div>
        <p class="text-sm text-gray-400 line-through">
            Rp {{ number_format($listing->price, 0, ',', '.') }}
        </p>
    </div>
@else
    <p class="{{ $mainClass }}">Rp {{ number_format($listing->price, 0, ',', '.') }}</p>
@endif
