<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Kategori</title>
    <style>
        /* --- CSS di sini --- */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            background-color: #f8fafc;
        }

        h2 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        a {
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 16px;
            text-align: left;
        }

        th {
            background-color: #f1f5f9;
            font-size: 14px;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        tr:hover {
            background-color: #e0f2fe;
        }

        button {
            background-color: #ef4444;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: #dc2626;
        }

        form {
            display: inline;
        }

        .success-message {
            color: green;
            margin-top: 10px;
            font-weight: bold;
        }

        .top-link {
            display: inline-block;
            margin-bottom: 12px;
            background-color: #3b82f6;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
        }

        .top-link:hover {
            background-color: #2563eb;
        }
    </style>
</head>
<body>
    <h2>Daftar Kategori</h2>
    <a href="{{ route('kategori.create') }}" class="top-link">Tambah Kategori</a>

    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Tipe</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kategori as $item)
            <tr>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->deskripsi }}</td>
                <td>{{ $item->tipe }}</td>
                <td>
                    <a href="{{ route('kategori.edit', $item->id) }}">Edit</a>
                    <form action="{{ route('kategori.destroy', $item->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
