@props([
    'url',
    'title' => 'Bagikan listing',
    'text' => null,
    'available' => true,
])

@php
    $shareText = $text ?: 'Lihat listing ini: '.$title;
    $encodedUrl = rawurlencode($url);
    $encodedText = rawurlencode($shareText.' '.$url);
@endphp

<div {{ $attributes->merge(['class' => 'rounded-lg border border-blue-100 bg-blue-50 p-4']) }}>
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="font-semibold text-blue-900">{{ $title }}</p>
            <p class="text-sm text-blue-700">
                @if($available)
                    Link share sudah siap. Bagikan ke sosial media atau salin link listing.
                @else
                    Link share sudah dibuat, tetapi listing baru bisa dibuka publik setelah admin approve.
                @endif
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            @if($available)
                <button type="button"
                    data-share-button
                    data-share-url="{{ $url }}"
                    data-share-title="{{ $title }}"
                    data-share-text="{{ $shareText }}"
                    class="rounded bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    Share
                </button>

                <a href="https://wa.me/?text={{ $encodedText }}" target="_blank" rel="noopener"
                    class="rounded bg-green-600 px-3 py-2 text-sm font-medium text-white hover:bg-green-700">
                    WhatsApp
                </a>

                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}" target="_blank" rel="noopener"
                    class="rounded bg-blue-800 px-3 py-2 text-sm font-medium text-white hover:bg-blue-900">
                    Facebook
                </a>

                <a href="https://twitter.com/intent/tweet?text={{ rawurlencode($shareText) }}&url={{ $encodedUrl }}" target="_blank" rel="noopener"
                    class="rounded bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-black">
                    X
                </a>
            @endif

            <button type="button"
                data-copy-share-url="{{ $url }}"
                class="rounded bg-white px-3 py-2 text-sm font-medium text-blue-700 ring-1 ring-blue-200 hover:bg-blue-100">
                Salin Link
            </button>
        </div>
    </div>

    <input type="text" value="{{ $url }}" readonly
        class="mt-3 w-full rounded border-blue-200 bg-white px-3 py-2 text-sm text-gray-700">
</div>

@once
    <script>
    function copyShareText(text, done) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(done).catch(function() {});
            return;
        }

        const input = document.createElement('input');
        input.value = text;
        input.style.position = 'fixed';
        input.style.opacity = '0';
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        document.body.removeChild(input);
        done();
    }

    document.addEventListener('click', function(event) {
        const shareButton = event.target.closest('[data-share-button]');
        if (shareButton) {
            const shareData = {
                title: shareButton.dataset.shareTitle,
                text: shareButton.dataset.shareText,
                url: shareButton.dataset.shareUrl
            };

            if (navigator.share) {
                navigator.share(shareData).catch(function() {});
            } else {
                copyShareText(shareData.url, function() {
                    shareButton.textContent = 'Link Tersalin';
                    setTimeout(function() {
                        shareButton.textContent = 'Share';
                    }, 1600);
                });
            }
        }

        const copyButton = event.target.closest('[data-copy-share-url]');
        if (copyButton) {
            copyShareText(copyButton.dataset.copyShareUrl, function() {
                copyButton.textContent = 'Tersalin';
                setTimeout(function() {
                    copyButton.textContent = 'Salin Link';
                }, 1600);
            });
        }
    });
    </script>
@endonce
