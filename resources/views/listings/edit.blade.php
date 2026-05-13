@extends('layouts.admin')

@section('admin_content')
<div>

    <h1 class="text-2xl font-bold mb-6">Edit Listing</h1>

    <div class="bg-white shadow rounded p-6">

        <form action="{{ route('listings.update',$listing->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">

            <div>
                <label class="block font-medium mb-1">Judul</label>
                <input type="text" name="title"
                    value="{{ $listing->title }}"
                    class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block font-medium mb-1">Kategori</label>
                <select name="category_id" class="w-full border rounded p-2">
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ $listing->category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">Harga</label>
                <input type="text"
                    data-rupiah-input
                    data-target="price"
                    value="{{ old('price', $listing->price) ? 'Rp '.number_format((int) old('price', $listing->price), 0, ',', '.') : '' }}"
                    class="w-full border rounded p-2"
                    placeholder="Contoh: Rp 250.000.000">
                <input type="hidden" name="price" id="price" value="{{ old('price', $listing->price) }}">
            </div>

            <div>
                <label class="block font-medium mb-1">Harga Diskon</label>
                <input type="text"
                    data-rupiah-input
                    data-target="discount_price"
                    value="{{ old('discount_price', $listing->discount_price) ? 'Rp '.number_format((int) old('discount_price', $listing->discount_price), 0, ',', '.') : '' }}"
                    class="w-full border rounded p-2"
                    placeholder="Contoh: Rp 225.000.000">
                <input type="hidden" name="discount_price" id="discount_price" value="{{ old('discount_price', $listing->discount_price) }}">
            </div>

            <div>
                <label class="block font-medium mb-1">Lokasi</label>
                <input type="text" name="location"
                    value="{{ $listing->location }}"
                    class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block font-medium mb-1">Kondisi</label>
                <select name="condition" class="w-full border rounded p-2">
                    <option value="baru" {{ $listing->condition=='baru'?'selected':'' }}>Baru</option>
                    <option value="bekas" {{ $listing->condition=='bekas'?'selected':'' }}>Bekas</option>
                </select>
            </div>

        </div>

        <div class="mt-4">
            <label class="block font-medium mb-1">Deskripsi</label>
            <textarea name="description"
                class="w-full border rounded p-2"
                rows="4">{{ $listing->description }}</textarea>
        </div>

        <div class="mt-6 rounded border border-emerald-100 bg-emerald-50/40 p-4">
            <h3 class="font-bold mb-3">Data Administrasi Excel</h3>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <input type="text" name="contact_name" value="{{ old('contact_name', $listing->contact_name) }}" placeholder="Atas Nama Yang Dihubungi" class="border p-2 rounded">
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $listing->contact_phone) }}" placeholder="Nomor Yang Dihubungi" class="border p-2 rounded">
                <input type="text" name="ownership_name" value="{{ old('ownership_name', $listing->ownership_name) }}" placeholder="Atas Nama Kepemilikan Unit" class="border p-2 rounded">
                <input type="text" name="document_status" value="{{ old('document_status', $listing->document_status) }}" placeholder="Kelengkapan Surat" class="border p-2 rounded">
                <input type="text" name="annual_tax_status" value="{{ old('annual_tax_status', $listing->annual_tax_status) }}" placeholder="Status Pajak Tahunan" class="border p-2 rounded">
                <input type="text" name="five_year_tax_status" value="{{ old('five_year_tax_status', $listing->five_year_tax_status) }}" placeholder="Status Pajak 5 Tahunan" class="border p-2 rounded">
                <input type="text" name="ownership_status" value="{{ old('ownership_status', $listing->ownership_status) }}" placeholder="Status Kepemilikan" class="border p-2 rounded">
                <input type="text" name="plate_number" value="{{ old('plate_number', $listing->plate_number) }}" placeholder="TNKB / Plat Nomor" class="border p-2 rounded">
                <input type="number" name="seller_price" value="{{ old('seller_price', $listing->seller_price) }}" placeholder="Harga Penjual" class="border p-2 rounded">
                <input type="number" name="offer_price" value="{{ old('offer_price', $listing->offer_price) }}" placeholder="Harga Penawaran" class="border p-2 rounded">
                <input type="number" name="sold_price" value="{{ old('sold_price', $listing->sold_price) }}" placeholder="Harga Terjual" class="border p-2 rounded">
                <input type="number" name="minimum_dp" value="{{ old('minimum_dp', $listing->minimum_dp) }}" placeholder="Minimal DP" class="border p-2 rounded">
                <input type="number" name="minimum_nego_price" value="{{ old('minimum_nego_price', $listing->minimum_nego_price) }}" placeholder="Minimal Nego / Minimal Harga" class="border p-2 rounded">
                <input type="number" name="showroom_dp_in" value="{{ old('showroom_dp_in', $listing->showroom_dp_in) }}" placeholder="DP Masuk" class="border p-2 rounded">
                <input type="number" name="installment" value="{{ old('installment', $listing->installment) }}" placeholder="Angsuran" class="border p-2 rounded">
                <input type="text" name="tenor" value="{{ old('tenor', $listing->tenor) }}" placeholder="Tenor" class="border p-2 rounded">
                <input type="number" name="commission" value="{{ old('commission', $listing->commission) }}" placeholder="Komisi" class="border p-2 rounded">
                <input type="text" name="progress_status" value="{{ old('progress_status', $listing->progress_status) }}" placeholder="Progress Status" class="border p-2 rounded">
            </div>

            <div class="mt-3">
                <textarea name="notes" class="w-full border p-2 rounded" rows="3" placeholder="Keterangan">{{ old('notes', $listing->notes) }}</textarea>
            </div>
        </div>

        <!-- Gambar lama -->
        <div class="mt-6">
            <h3 class="font-bold mb-2">Gambar Saat Ini</h3>

            <div class="grid grid-cols-5 gap-2">
                @foreach($listing->images as $img)
                <div class="relative">
                    <img src="{{ asset('storage/'.$img->image) }}"
                        class="h-24 w-full object-cover rounded">

                    <a href="{{ route('images.delete',$img->id) }}"
                        data-swal-confirm="Hapus gambar?"
                        data-swal-confirm-button="Ya, hapus"
                        class="absolute top-1 right-1 bg-red-500 text-white px-2 py-1 text-xs rounded">
                        X
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Upload baru -->
        <div class="mt-6">
            <label class="block font-medium mb-2">Tambah Gambar Baru</label>

            <div id="image-wrapper">
                <input type="file" name="images[]" class="w-full border p-2 rounded mb-2">
            </div>

            <button type="button" onclick="addImage()"
                class="bg-green-500 text-white px-3 py-1 rounded">
                + Tambah Gambar
            </button>
        </div>

        <div class="mt-6 flex gap-2">
            <button class="bg-blue-600 text-white px-6 py-2 rounded">
                Update
            </button>

            <a href="{{ route('admin.dashboard') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded">
                Kembali
            </a>
        </div>

        </form>

    </div>
</div>

<script>
function formatRupiah(value) {
    let number = String(value).replace(/\D/g, '');

    if (!number) {
        return '';
    }

    return 'Rp ' + number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function bindRupiahInputs() {
    document.querySelectorAll('[data-rupiah-input]').forEach(function(input) {
        const target = document.getElementById(input.dataset.target);
        if (!target) return;

        input.addEventListener('input', function() {
            const raw = this.value.replace(/\D/g, '');
            target.value = raw;
            this.value = formatRupiah(raw);
        });
    });
}

document.addEventListener('DOMContentLoaded', bindRupiahInputs);

function addImage(){
    let wrapper = document.getElementById('image-wrapper');

    let input = document.createElement('input');
    input.type = 'file';
    input.name = 'images[]';
    input.className = 'w-full border p-2 rounded mb-2';

    wrapper.appendChild(input);
}
</script>

@endsection
