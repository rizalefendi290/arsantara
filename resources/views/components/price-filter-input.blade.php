@props(['name', 'value' => null, 'placeholder' => 'Harga'])

@php
    $rawValue = preg_replace('/\D/', '', (string) $value);
    $displayValue = $rawValue !== '' ? 'Rp '.number_format((int) $rawValue, 0, ',', '.') : '';
@endphp

<div {{ $attributes->merge(['class' => 'relative']) }}>
    <input type="text" value="{{ $displayValue }}" placeholder="{{ $placeholder }}"
        inputmode="numeric" data-rupiah-filter
        class="w-full rounded-xl border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
    <input type="hidden" name="{{ $name }}" value="{{ $rawValue }}" data-rupiah-filter-value>
</div>

@once
    <script>
    function formatFilterRupiah(value) {
        const raw = String(value || '').replace(/\D/g, '');

        if (!raw) {
            return '';
        }

        return 'Rp ' + raw.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function bindRupiahFilterInputs() {
        document.querySelectorAll('[data-rupiah-filter]').forEach(function(input) {
            if (input.dataset.rupiahBound === 'true') return;

            input.dataset.rupiahBound = 'true';
            const hidden = input.parentElement.querySelector('[data-rupiah-filter-value]');

            input.addEventListener('input', function() {
                const raw = this.value.replace(/\D/g, '');

                if (hidden) {
                    hidden.value = raw;
                }

                this.value = formatFilterRupiah(raw);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', bindRupiahFilterInputs);
    </script>
@endonce
