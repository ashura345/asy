<h2>Edit Kategori Pembayaran</h2>

<form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Nama Kategori:</label>
    <input type="text" name="nama" value="{{ $kategori->nama }}" required>

    <label>Deskripsi:</label>
    <textarea name="deskripsi">{{ $kategori->deskripsi }}</textarea>

    <label>Tipe Pembayaran:</label>
    <select name="tipe" required>
        <option value="Harian" {{ $kategori->tipe == 'Harian' ? 'selected' : '' }}>Harian</option>
        <option value="Bulanan" {{ $kategori->tipe == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
        <option value="Tahunan" {{ $kategori->tipe == 'Tahunan' ? 'selected' : '' }}>Tahunan</option>
        <option value="Bebas" {{ $kategori->tipe == 'Bebas' ? 'selected' : '' }}>Bebas</option>
    </select>

    <button type="submit">Update</button>
</form>
