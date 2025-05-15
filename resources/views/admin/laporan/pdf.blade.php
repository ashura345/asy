<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembayaran</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h3>Laporan Pembayaran</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Siswa</th>
                <th>Jenis Pembayaran</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Tanggal Bayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembayarans as $item)
                <tr>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->jenis->nama_pembayaran }}</td>
                    <td>{{ $item->jumlah_dibayar }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->tanggal_bayar ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
