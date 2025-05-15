<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #b0e2c1;
            padding: 50px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .siswa-info {
            text-align: center;
            margin-bottom: 30px;
        }

        .siswa-info img {
            width: 80px;
            height: 100px;
            object-fit: cover;
            background: #ccc;
            display: block;
            margin: 0 auto 10px;
        }

        .siswa-info .nama {
            font-weight: bold;
        }

        .siswa-info .nis {
            font-size: 14px;
            color: #444;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 20px;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background: #eee;
        }

        .metode-container {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .metode-card {
            flex: 1;
            background-color: #f1f1f1;
            margin: 5px;
            padding: 15px;
            text-align: center;
            border-radius: 10px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: 0.3s;
        }

        .metode-card:hover {
            border-color: #4CAF50;
            background-color: #e0ffe0;
        }

        .metode-card.active {
            border-color: #4CAF50;
            background-color: #c6f7c6;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 30px;
            background-color: #1e70ff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #155bd4;
        }

        input[type="radio"] {
            display: none;
        }

        .total {
            margin-top: 20px;
            font-weight: bold;
            font-size: 18px;
            color: #333;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pembayaran</h2>

        <div class="siswa-info">
            <img src="https://via.placeholder.com/80x100?text=Foto+siswa" alt="Foto Siswa">
            <div class="nama">Tsaltsa Sifa Bilqis Salaamah</div>
            <div class="nis">NIS : 1234567 | Kelas 1</div>
        </div>

        <form method="POST" action="{{ route('pembayaran.proses') }}">
            @csrf

            <label for="kategori">Pilih Jenis Pembayaran</label>
            <select name="kategori" id="kategori" required onchange="updateTotal()">
                <option value="SPP">SPP - Rp 300.000</option>
                <option value="Seragam">Seragam - Rp 450.000</option>
                <option value="Ijazah">Ijazah - Rp 200.000</option>
            </select>

            <div class="total" id="totalHarga">Total: Rp 300.000</div>

            <label for="metode">Pilih Metode Pembayaran</label>
            <div class="metode-container">
                <label class="metode-card" id="tunaiCard">
                    <input type="radio" name="metode" value="tunai" required>
                    Tunai
                </label>
                <label class="metode-card" id="transferCard">
                    <input type="radio" name="metode" value="transfer" required>
                    Transfer
                </label>
            </div>

            <button type="submit">Konfirmasi</button>
        </form>
    </div>

    <script>
        const hargaMap = {
            'SPP': 300000,
            'Seragam': 450000,
            'Ijazah': 200000
        };

        const kategoriSelect = document.getElementById('kategori');
        const totalHarga = document.getElementById('totalHarga');

        function updateTotal() {
            const selected = kategoriSelect.value;
            const harga = hargaMap[selected] || 0;
            totalHarga.innerText = 'Total: Rp ' + harga.toLocaleString('id-ID');
        }

        // Inisialisasi awal
        updateTotal();

        const cards = document.querySelectorAll('.metode-card');
        cards.forEach(card => {
            card.addEventListener('click', () => {
                cards.forEach(c => c.classList.remove('active'));
                card.classList.add('active');
                card.querySelector('input').checked = true;
            });
        });
    </script>
</body>
</html>
