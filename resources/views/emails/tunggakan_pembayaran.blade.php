<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengingat Tunggakan Pembayaran</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f7f7f7; padding:20px;">
    <div style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:8px;padding:20px;">
        <h2 style="color:#2e7d32;margin-top:0;">Pengingat Tunggakan Pembayaran</h2>

        <p>Assalamu'alaikum, <strong>{{ $item->nama_siswa }}</strong>.</p>

        <p>
            Kami menginformasikan bahwa masih terdapat tunggakan pembayaran berikut:
        </p>

        <table style="width:100%;border-collapse:collapse;margin:15px 0;">
            <tr>
                <td style="padding:6px 4px;width:35%;">Nama Siswa</td>
                <td style="padding:6px 4px;">: {{ $item->nama_siswa }}</td>
            </tr>
            <tr>
                <td style="padding:6px 4px;">Kelas</td>
                <td style="padding:6px 4px;">: {{ $item->kelas }}</td>
            </tr>
            <tr>
                <td style="padding:6px 4px;">Nama Pembayaran</td>
                <td style="padding:6px 4px;">: {{ $item->nama_pembayaran }}</td>
            </tr>
            <tr>
                <td style="padding:6px 4px;">Jumlah</td>
                <td style="padding:6px 4px;">
                    : Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td style="padding:6px 4px;">Status</td>
                <td style="padding:6px 4px;">: {{ $item->status ?? 'belum bayar' }}</td>
            </tr>
        </table>

        <p>
            Silakan melakukan pembayaran melalui sistem ASY-PAY pada link berikut:
        </p>

        <p style="text-align:center;margin:20px 0;">
            <a href="{{ route('siswa.pembayaran.index') }}"
               style="background:#2e7d32;color:#ffffff;padding:10px 18px;border-radius:5px;text-decoration:none;">
                Buka Halaman Pembayaran
            </a>
        </p>

        <p>
            Jika sudah melakukan pembayaran, mohon abaikan pesan ini.
        </p>

        <p>Terima kasih.<br>Wassalamu'alaikum wr. wb.</p>

        <hr style="margin-top:25px;border:none;border-top:1px solid #ddd;">
        <p style="font-size:12px;color:#777;">
            Email ini dikirim otomatis oleh sistem ASY-PAY. Mohon tidak membalas email ini.
        </p>
    </div>
</body>
</html>
