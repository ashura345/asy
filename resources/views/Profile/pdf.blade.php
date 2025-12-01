<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Profil Siswa - PDF</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        .box { padding: 10px; }
        .header { text-align: center; margin-bottom: 15px; }
        .header h2 { margin: 0 0 5px 0; }
        .foto { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; }
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 4px 0; vertical-align: top; }
        .label { width: 30%; font-weight: bold; }
    </style>
</head>
<body>
<div class="box">

    <div class="header">
        <h2>Profil Siswa</h2>
        @if($user->foto)
            <img src="{{ public_path('foto_siswa/' . $user->foto) }}" class="foto">
        @else
            <img src="{{ public_path('default-avatar.png') }}" class="foto">
        @endif
    </div>

    <table>
        <tr>
            <td class="label">Nama</td>
            <td>: {{ $user->name }}</td>
        </tr>
        <tr>
            <td class="label">NIS</td>
            <td>: {{ $user->nis }}</td>
        </tr>
        <tr>
            <td class="label">Kelas</td>
            <td>: {{ $user->kelas }}</td>
        </tr>
        <tr>
            <td class="label">Tahun Ajaran</td>
            <td>: {{ $user->tahun_ajaran }}</td>
        </tr>
        <tr>
            <td class="label">Email</td>
            <td>: {{ $user->email }}</td>
        </tr>
    </table>

</div>
</body>
</html>
