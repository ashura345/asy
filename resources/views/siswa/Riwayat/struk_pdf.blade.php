<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Pembayaran</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td, th { padding: 8px; border: 1px solid #000; }
        th { background-color: #f2f2f2; text-align: left; }
    </style>
</head>
<body>
    <h2>Struk Pembayaran</h2>
    <table>
        <tr><th>Nama Siswa</th><td>{{ $data->nama_siswa }}</td></tr>
        <tr><th>Kelas</th><td>{{ $data->kelas }}</td></tr>
        <tr><th>Nama Pembayaran</th><td>{{ $data->nama_pembayaran }}</td></tr>
        <tr><th>Total Tagihan</th><td>{{ number_format($data->total_tagihan, 0, ',', '.') }}</td></tr>
        <tr><th>Jumlah Bayar</th><td>{{ number_format($data->jumlah_bayar, 0, ',', '.') }}</td></tr>
        <tr><th>Tanggal Bayar</th><td>{{ date('d/m/Y H:i', strtotime($data->tanggal_bayar)) }}</td></tr>
        <tr><th>Metode</th><td>{{ ucfirst($data->metode) }}</td></tr>
        <tr><th>Status</th><td>{{ ucfirst($data->status) }}</td></tr>
    </table>
</body>
</html>
