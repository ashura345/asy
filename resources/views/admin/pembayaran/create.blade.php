@extends('layouts.app')

@section('content')
    <h1>Tambah Pembayaran</h1>

    <form action="{{ route('admin.pembayaran.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="siswa_id">Siswa</label>
            <select class="form-control" id="siswa_id" name="siswa_id" required>
                @foreach ($siswa as $s)
                    <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->nis }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="kategori_id">Kategori Pembayaran</label>
            <select class="form-control" id="kategori_id" name="kategori_id" required>
                @foreach ($kategoriPembayaran as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="jumlah_tagihan">Jumlah Tagihan</label>
            <input type="number" class="form-control" id="jumlah_tagihan" name="jumlah_tagihan" required>
        </div>

        <div class="form-group">
            <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo</label>
            <input type="date" class="form-control" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" required>
        </div>

        <div class="form-group">
            <label for="gambar_produk">Gambar Produk (Opsional)</label>
            <input type="file" class="form-control" id="gambar_produk" name="gambar_produk">
        </div>

        <div class="form-group" id="kelas_section" style="display: none;">
            <label for="kelas_id">Kelas</label>
            <select class="form-control" id="kelas_id" name="kelas_id">
                @foreach ($kelas as $kelasItem)
                    <option value="{{ $kelasItem->id }}">{{ $kelasItem->nama_kelas }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan Pembayaran</button>
    </form>

    <script>
        // Menampilkan pilihan kelas jika kategori adalah 'bulanan' atau 'tahunan'
        document.getElementById('kategori_id').addEventListener('change', function() {
            var kelasSection = document.getElementById('kelas_section');
            var kategoriId = this.value;
            // Mengubah tampilan kelas berdasarkan tipe kategori
            if (kategoriId == 'bulanan' || kategoriId == 'tahunan') {
                kelasSection.style.display = 'block';
            } else {
                kelasSection.style.display = 'none';
            }
        });
    </script>
@endsection
