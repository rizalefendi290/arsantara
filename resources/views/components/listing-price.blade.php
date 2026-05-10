@props(['listing', 'size' => 'normal'])

@php
    $mainClass = $size === 'large'
        ? 'text-3xl text-blue-600 font-bold'
        : 'text-sm text-blue-600 font-bold sm:text-base';
@endphp

@if($listing->hasDiscount())
    <div>
        <div class="flex items-center gap-2">
            <p class="{{ $mainClass }}">Rp {{ number_format($listing->finalPrice(), 0, ',', '.') }}</p>
            <span class="bg-red-100 text-red-600 text-[10px] font-semibold px-1.5 py-0.5 rounded sm:px-2 sm:py-1 sm:text-xs">
                -{{ $listing->discountPercent() }}%
            </span>
        </div>
        <p class="text-xs text-gray-400 line-through sm:text-sm">
            Rp {{ number_format($listing->price, 0, ',', '.') }}
        </p>
    </div>
@else
    <p class="{{ $mainClass }}">Rp {{ number_format($listing->price, 0, ',', '.') }}</p>
@endif
