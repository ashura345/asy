<!DOCTYPE html>
<html>
<head>
    <title>Profil</title>
    <style>
        body {
            font-family: Arial;
            background: #f3f3f3;
            padding: 20px;
        }

        .box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 450px;
            margin: auto;
        }

        .foto {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
        }

        table td {
            padding: 5px 0;
        }

        .button-group {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: block;
            text-align: center;
            color: white;
            font-size: 14px;
        }

        /* Tombol Kembali – warna oranye */
        .btn-back {
            background: #ff7043;
        }

        /* Tombol Edit Profil – warna hijau */
        .btn-edit {
            background: #4caf50;
        }

        /* Tombol Download PDF – warna biru */
        .btn-pdf {
            background: #2196f3;
        }
    </style>
</head>
<body>

<div class="box">

    <h2 style="text-align:center; margin-bottom:15px;">Profil</h2>

    <div style="text-align:center; margin-bottom:20px;">
        <img src="{{ $user->foto ? asset('foto_siswa/'.$user->foto) : asset('default-avatar.png') }}"
             class="foto">
    </div>

    <table width="100%">
        <tr>
            <td><b>Nama</b></td>
            <td>: {{ $user->name }}</td>
        </tr>
        <tr>
            <td><b>NIS</b></td>
            <td>: {{ $user->nis }}</td>
        </tr>
        <tr>
            <td><b>Kelas</b></td>
            <td>: {{ $user->kelas }}</td>
        </tr>
        <tr>
            <td><b>Tahun Ajaran</b></td>
            <td>: {{ $user->tahun_ajaran }}</td>
        </tr>
        <tr>
            <td><b>Email</b></td>
            <td>: {{ $user->email }}</td>
        </tr>
    </table>

    <!-- Button Actions -->
    <div class="button-group">
        @if(auth()->user()->role === 'siswa')
            <a href="{{ route('siswa.dashboard') }}" class="btn btn-back">
                &#8592; Kembali
            </a>
        @else
            <a href="{{ route('admin.dashboard') }}" class="btn btn-back">
                &#8592; Kembali
            </a>
        @endif

        <a href="{{ route('profile.edit') }}" class="btn btn-edit">
            Edit Profil
        </a>

        <a href="{{ route('profile.downloadPdf') }}" class="btn btn-pdf">
            Download Profil (PDF)
        </a>
    </div>

</div>

</body>
</html>
