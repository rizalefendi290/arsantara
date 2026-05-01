@extends('layouts.admin')

@section('admin_content')

<div class="max-w-4xl">

    <h1 class="text-2xl font-bold mb-6">Edit Berita</h1>

    <!-- FORM UTAMA -->
    <form action="{{ route('posts.update',$post->id) }}" 
          method="POST" 
          enctype="multipart/form-data"
          class="bg-white p-6 rounded-xl shadow space-y-4">

        @csrf
        @method('PUT')

        <!-- JUDUL -->
        <input type="text" name="title" 
            value="{{ $post->title }}"
            class="w-full border p-3 rounded"
            placeholder="Judul berita" required>

        <!-- KONTEN -->
        <textarea name="content" rows="6"
            class="w-full border p-3 rounded"
            placeholder="Isi berita" required>{{ $post->content }}</textarea>

        <!-- SOURCE -->
        <input type="text" name="source_name"
            value="{{ $post->source_name }}"
            class="w-full border p-3 rounded"
            placeholder="Sumber (opsional)">

        <input type="text" name="source_url"
            value="{{ $post->source_url }}"
            class="w-full border p-3 rounded"
            placeholder="Link sumber (opsional)">

        <!-- ================= GAMBAR LAMA ================= -->
        <div>
            <h2 class="font-semibold mb-2">Gambar Saat Ini</h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($post->images as $img)
                <div class="relative group" id="img-{{ $img->id }}">

                    <img src="{{ asset('storage/'.$img->image) }}"
                         class="w-full h-32 object-cover rounded">

                    <!-- DELETE BUTTON -->
                    <button type="button"
                        onclick="deleteImage({{ $img->id }})"
                        class="absolute top-1 right-1 bg-red-500 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">
                        ✕
                    </button>

                </div>
                @endforeach
            </div>
        </div>

        <!-- ================= TAMBAH GAMBAR ================= -->
        <div>
            <h2 class="font-semibold mb-2">Tambah Gambar</h2>

            <input type="file" name="images[]" multiple
                class="w-full border p-2 rounded">
        </div>

        <!-- SUBMIT -->
        <button type="submit"
            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
            Update Berita
        </button>

    </form>

</div>

<!-- ================= SCRIPT ================= -->
<script>
function deleteImage(id){
    window.ArsantaraSwal.confirm('Hapus gambar ini?', {
        confirmButtonText: 'Ya, hapus',
        icon: 'warning',
    }).then(function(result) {
        if (!result.isConfirmed) return;

        fetch(`/admin/post-image/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if(!res.ok) throw new Error('Gagal hapus');
            return res.json();
        })
        .then(data => {
            // HAPUS DARI UI TANPA RELOAD
            document.getElementById('img-' + id).remove();
            window.ArsantaraSwal.toast('success', 'Gambar berhasil dihapus');
        })
        .catch(err => {
            window.ArsantaraSwal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Gagal menghapus gambar',
            });
            console.error(err);
        });
    });
}
</script>

@endsection
