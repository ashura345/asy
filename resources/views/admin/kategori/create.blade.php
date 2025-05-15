@extends('layouts.admin')

@section('content')
    <h2 class="text-xl font-semibold mb-4">Tambah Kategori Pembayaran</h2>

    <form action="{{ route('admin.kategori.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="nama_kategori" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
            <input type="text" name="nama_kategori" id="nama_kategori" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
        </div>

        <div class="mb-4">
            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
        </div>

        <div class="mb-4">
            <label for="tipe_pembayaran" class="block text-sm font-medium text-gray-700">Tipe Pembayaran</label>
            <select name="tipe_pembayaran" id="tipe_pembayaran" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                <option value="bulanan">Bulanan</option>
                <option value="tahunan">Tahunan</option>
                <option value="bebas">Bebas</option>
            </select>
        </div>

        <button type="submit" class="bg-emerald-600 text-white py-2 px-4 rounded hover:bg-emerald-700">Simpan</button>
    </form>
@endsection
