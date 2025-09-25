<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .detail { border: 1px solid #333; padding: 20px; }
        .detail p { margin: 5px 0; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Struk Pembayaran</h2>
        </div>

        <div class="detail">
            <p><strong>Nama Siswa:</strong> {{ $data->nama_siswa }}</p>
            <p><strong>Kelas:</strong> {{ $data->kelas }}</p>
            <p><strong>Nama Pembayaran:</strong> {{ $data->nama_pembayaran }}</p>
            <p><strong>Jumlah Bayar:</strong> Rp {{ number_format($data->jumlah_bayar, 0, ',', '.') }}</p>
            <p><strong>Tanggal Bayar:</strong> {{ \Carbon\Carbon::parse($data->tanggal_bayar)->format('d-m-Y H:i') }}</p>
            <p><strong>Metode:</strong> {{ ucfirst($data->metode) }}</p>
        </div>

        <div class="footer">
            <p>Terima kasih telah melakukan pembayaran.</p>
        </div>
    </div>
</body>
</html>
