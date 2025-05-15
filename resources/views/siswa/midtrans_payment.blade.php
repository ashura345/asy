@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Proses Pembayaran Midtrans</h2>
    <p>Silakan tunggu, Anda akan diarahkan ke halaman pembayaran...</p>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('MIDTRANS.CLIENT_KEY') }}"></script>
<script type="text/javascript">
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            console.log('success', result);
            window.location.href = '/dashboard';
        },
        onPending: function(result) {
            console.log('pending', result);
            window.location.href = '/dashboard';
        },
        onError: function(result) {
            console.log('error', result);
            alert('Terjadi kesalahan saat memproses pembayaran.');
        },
        onClose: function() {
            alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
        }
    });
</script>
@endsection
