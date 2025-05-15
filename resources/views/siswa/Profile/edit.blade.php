<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Profil</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
            background: #f0f2f5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .btn {
            padding: 6px 10px;
            border: none;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        .edit {
            background-color: #ffc107;
        }

        .delete {
            background-color: #dc3545;
        }

        .create {
            background-color: #28a745;
            padding: 8px 12px;
            margin-bottom: 10px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <h2>Daftar Profil Siswa</h2>

    <a href="{{ route('profile.create') }}" class="btn create">+ Tambah</a>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Kelas</th>
                <th>NIS</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($profiles as $profile)
            <tr>
                <td>{{ $profile->nama }}</td>
                <td>{{ $profile->kelas }}</td>
                <td>{{ $profile->nis }}</td>
                <td>{{ $profile->alamat }}</td>
                <td>
                    <a href="{{ route('profile.edit', $profile->id) }}" class="btn edit">Edit</a>
                    <form action="{{ route('profile.destroy', $profile->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn delete" onclick="return confirm('Yakin?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
