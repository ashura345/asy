<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>ASY PAY - Pembayaran Madrasah</title>
    @vite('resources/css/app.css')
    <style>
        body {
            background-color: #a5d8b3;
        }

        .glow-box {
            border: 2px solid white;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .glow-box:hover {
            box-shadow: 0 0 25px rgba(255, 255, 255, 0.3);
        }

        nav a {
            transition: all 0.3s ease;
        }

        nav a:hover {
            text-decoration: underline;
            color: #d1fae5;
        }
    </style>
</head>
<body class="text-white">

    <!-- Navbar -->
    <nav class="p-4 flex justify-between items-center bg-green-800 bg-opacity-75 shadow-lg">
        <h1 class="text-2xl font-bold tracking-wide">ASY PAY</h1>
        <div>
            @auth
                <span class="mr-4">Halo, {{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 hover:bg-green-700 rounded">Login</a>
                
            @endauth
        </div>
    </nav>

    <!-- Hero -->
    <div class="min-h-screen flex items-center justify-center px-4 pt-16">
        <div class="glow-box bg-green-900 bg-opacity-70 rounded-2xl p-10 shadow-2xl max-w-xl text-center">
            <h1 class="text-3xl font-bold mb-4 leading-snug">Selamat Datang di Sistem Pembayaran Madrasah</h1>
            @auth
                <p class="mt-4">Anda sudah login sebagai <strong>{{ auth()->user()->name }}</strong>.</p>
            @else
                <p class="mt-4">Silakan login untuk melanjutkan.</p>
            @endauth
        </div>
    </div>

</body>
</html>
