<div class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 lg:grid-cols-4 lg:gap-6">
    @forelse($listings as $listing)
        <x-card-listing :listing="$listing" />
    @empty
        <div class="col-span-full rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center text-gray-500">
            Data properti tidak ditemukan.
        </div>
    @endforelse
</div>

<div class="mt-6" id="pagination-links">
    {{ $listings->links() }}
</div>
