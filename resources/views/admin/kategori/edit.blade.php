@extends('layouts.admin')

@section('content')
    <h2 class="text-xl font-semibold mb-4">Edit Kategori Pembayaran</h2>

    <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="nama_kategori" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
            <input type="text" name="nama_kategori" id="nama_kategori" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $kategori->nama_kategori }}" required>
        </div>

        <div class="mb-4">
            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $kategori->deskripsi }}</textarea>
        </div>

        <div class="mb-4">
            <label for="tipe_pembayaran" class="block text-sm font-medium text-gray-700">Tipe Pembayaran</label>
            <select name="tipe_pembayaran" id="tipe_pembayaran" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                <option value="bulanan" {{ $kategori->tipe_pembayaran == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                <option value="tahunan" {{ $kategori->tipe_pembayaran == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                <option value="bebas" {{ $kategori->tipe_pembayaran == 'bebas' ? 'selected' : '' }}>Bebas</option>
            </select>
        </div>

        <button type="submit" class="bg-emerald-600 text-white py-2 px-4 rounded hover:bg-emerald-700">Update</button>
    </form>
@endsection
