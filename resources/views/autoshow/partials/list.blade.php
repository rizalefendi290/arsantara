<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    @forelse($listings as $listing)
        <x-card-listing :listing="$listing" />
    @empty
        <div class="col-span-full rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center text-gray-500">
            Data kendaraan tidak ditemukan.
        </div>
    @endforelse
</div>

<div class="mt-6" id="pagination-links">
    {{ $listings->links() }}
</div>
