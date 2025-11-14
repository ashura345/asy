<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASY PAY - Pembayaran Madrasah</title>
    @vite('resources/css/app.css')

    <style>
        /* Background dari folder public/images */
        body {
            background: url('{{ asset('images/landing.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            margin: 0;
        }

        /* Efek glassmorphism */
        .glass-box {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .glass-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.45);
        }

        /* Navbar */
        nav {
            background-color: rgba(22, 101, 52, 0.7);
            backdrop-filter: blur(10px);
        }

        nav a {
            transition: all 0.3s ease;
        }

        nav a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Efek teks gradasi */
        .gradient-text {
            background: linear-gradient(90deg, #6ee7b7, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Tombol utama */
        .btn-primary {
            background-color: #15803d;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: #fff;
            display: inline-block;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #166534;
            transform: scale(1.05);
        }

        /* Input field */
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            margin: 0.5rem 0 1rem 0;
            border: none;
            border-radius: 0.75rem;
            background-color: rgba(255, 255, 255, 0.9);
            color: #111;
            font-size: 1rem;
        }

        input:focus {
            outline: 2px solid #16a34a;
            background-color: #fff;
        }

        label {
            display: block;
            text-align: left;
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.25rem;
        }

        .error-message {
            color: #f87171;
            font-size: 0.875rem;
            margin-top: -0.5rem;
            margin-bottom: 0.75rem;
        }

        /* Logo agak besar tapi tidak berlebihan */
        img[alt="Logo Pembayaran"] {
            max-width: 120px; /* batas maksimal ukuran logo */
            height: auto;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 w-full p-4 flex justify-between items-center shadow-lg z-10">
        <h1 class="text-2xl font-extrabold gradient-text tracking-wide">ASY PAY</h1>
        <div>
            @auth
                <span class="mr-4">Halo, {{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg font-semibold">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    <!-- Hero / Login Section -->
    <div class="min-h-screen flex items-center justify-center px-6 pt-24">
        <div class="glass-box p-10 text-center max-w-md w-full">
            <!-- Logo lebih besar sedikit -->
            <img src="{{ asset('images/logo.jpg') }}" 
                 alt="Logo Pembayaran" 
                 class="w-24 md:w-28 mx-auto mb-6 opacity-90 rounded-full">

            <h1 class="text-3xl font-bold mb-6 leading-snug">
                Selamat Datang di <span class="gradient-text">Sistem Pembayaran Madrasah</span>
            </h1>

            @auth
                <p class="text-lg mt-4">
                    Anda sudah login sebagai <strong>{{ auth()->user()->name }}</strong>.
                </p>
            @else
                <form action="{{ route('login') }}" method="POST" class="text-left">
                    @csrf

                    <div>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required>
                        @error('email')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Masukkan password Anda" required>
                        @error('password')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary w-full text-center mt-4">
                        Login
                    </button>
                </form>

                <!-- Bagian lupa password -->
                <p class="text-sm text-gray-200 mt-6 text-center">
                    Lupa password?
                    <a href="{{ route('password.request') }}" class="underline text-green-300 hover:text-green-100">
                        Reset di sini
                    </a>
                </p>
            @endauth
        </div>
    </div>

</body>
</html>
