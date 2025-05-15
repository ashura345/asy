@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Pembayaran via Transfer</h2>
    <p class="mb-2">Klik tombol di bawah untuk melanjutkan pembayaran melalui Midtrans.</p>

    <form action="{{ route('midtrans.pay') }}" method="POST">
        @csrf
        <input type="hidden" name="amount" value="50000"> <!-- Ganti sesuai kebutuhan -->
        <button type="submit"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
            Bayar Sekarang via Transfer
        </button>
    </form>
</div>
@endsection