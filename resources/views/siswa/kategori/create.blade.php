<form action="{{ route('kategori.store') }}" method="POST">
    @csrf
    <input name="nama" placeholder="Nama Kategori">
    <textarea name="deskripsi" placeholder="Deskripsi"></textarea>
    <select name="tipe">
        <option value="Harian">Harian</option>
        <option value="Bulanan">Bulanan</option>
        <option value="Tahunan">Tahunan</option>
        <option value="Bebas">Bebas</option>
    </select>
    <button type="submit">Simpan</button>
</form>
