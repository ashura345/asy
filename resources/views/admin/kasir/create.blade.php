@extends('layouts.app')

@section('content')
    <h1>Kasir - Pembayaran Manual</h1>

    <form action="{{ route('admin.kasir.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="siswa_id">Pilih Siswa</label>
            <select class="form-control" id="siswa_id" name="siswa_id" required>
                @foreach ($siswa as $s)
                    <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->nis }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="kategori_pembayaran_id">Pilih Kategori Pembayaran</label>
            <select class="form-control" id="kategori_pembayaran_id" name="kategori_pembayaran_id" required>
                @foreach ($kategoriPembayaran as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="nominal">Nominal</label>
            <input type="number" class="form-control" id="nominal" name="nominal" required>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="lunas">Lunas</option>
                <option value="belum lunas">Belum Lunas</option>
            </select>
        </div>

        <div class="form-group">
            <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo</label>
            <input type="date" class="form-control" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan Pembayaran</button>
    </form>
@endsection
