@extends('layouts.admin')
@section('title', 'Daftar Siswa')

@section('content')
<div class="container">
    <h1>Daftar Pengguna</h1>

    <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah Pengguna</a>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>NIS</th>
                <th>Role</th>
                <th>Tahun Ajaran</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->nis }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ $user->tahun_ajaran }}</td>
                <td>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection