@extends('layouts.app')

@section('content')
    <h1>Edit Pembayaran</h1>

    <form action="{{ route('admin.pembayaran.update', $pembayaran->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="siswa_id">Siswa</label>
            <select class="form-control" id="siswa_id" name="siswa_id" required>
                @foreach ($siswa as $s)
                    <option value="{{ $s->id }}" {{ $pembayaran->siswa_id == $s->id ? 'selected' : '' }}>{{ $s->nama }} ({{ $s->nis }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="kategori_id">Kategori Pembayaran</label>
            <select class="form-control" id="kategori_id" name="kategori_id" required>
                @foreach ($kategoriPembayaran as $kategori)
                    <option value="{{ $kategori->id }}" {{ $pembayaran->kategori_id == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="jumlah_tagihan">Jumlah Tagihan</label>
            <input type="number" class="form-control" id="jumlah_tagihan" name="jumlah_tagihan" value="{{ old('jumlah_tagihan', $pembayaran->jumlah_tagihan) }}" required>
        </div>

        <div class="form-group">
            <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo</label>
            <input type="date" class="form-control" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo', $pembayaran->tanggal_jatuh_tempo) }}" required>
        </div>

        <div class="form-group">
            <label for="status">Status Pembayaran</label>
            <select class="form-control" id="status" name="status" required>
                <option value="belum lunas" {{ $pembayaran->status == 'belum lunas' ? 'selected' : '' }}>Belum Lunas</option>
                <option value="lunas" {{ $pembayaran->status == 'lunas' ? 'selected' : '' }}>Lunas</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Pembayaran</button>
    </form>
@endsection
