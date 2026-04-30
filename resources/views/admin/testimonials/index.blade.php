@extends('layouts.admin')

@section('admin_content')

<div>

    <h1 class="text-2xl font-bold mb-6">Manajemen Testimoni</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-3">User</th>
                    <th>Pesan</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach($testimonials as $item)
                <tr class="border-t">

                    <!-- USER -->
                    <td class="p-3 flex items-center gap-3">

                        <img src="{{ $item->photo 
                            ? asset('storage/'.$item->photo) 
                            : 'https://ui-avatars.com/api/?name='.urlencode($item->name) }}"
                            class="w-10 h-10 rounded-full object-cover">

                        <div>
                            <p class="font-semibold">{{ $item->name }}</p>
                            <p class="text-xs text-gray-500">{{ $item->job }}</p>
                        </div>

                    </td>

                    <!-- MESSAGE -->
                    <td class="max-w-xs truncate">
                        {{ $item->message }}
                    </td>

                    <!-- RATING -->
                    <td class="text-yellow-400">
                        @for($i=1; $i<=5; $i++)
                            @if($i <= $item->rating)
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </td>

                    <!-- STATUS -->
                    <td>
                        @if($item->is_active)
                            <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-xs">
                                Aktif
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-500 px-2 py-1 rounded text-xs">
                                Nonaktif
                            </span>
                        @endif
                    </td>

                    <!-- AKSI -->
                    <td class="text-center flex gap-2 justify-center p-3">

                        <!-- TOGGLE -->
                        <form action="{{ route('admin.testimonials.toggle',$item->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <button class="px-3 py-1 rounded text-white 
                                {{ $item->is_active ? 'bg-yellow-500' : 'bg-green-600' }}">
                                {{ $item->is_active ? 'Nonaktifkan' : 'Tampilkan' }}
                            </button>
                        </form>

                        <!-- DELETE -->
                        <form action="{{ route('admin.testimonials.destroy',$item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button onclick="return confirm('Hapus testimoni?')" 
                                class="bg-red-500 text-white px-3 py-1 rounded">
                                Hapus
                            </button>
                        </form>

                    </td>

                </tr>
                @endforeach

            </tbody>

        </table>

    </div>

    <!-- PAGINATION -->
    <div class="mt-4">
        {{ $testimonials->links() }}
    </div>

</div>

@endsection
