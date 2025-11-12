<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CDN ICON -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 220px;
            background: linear-gradient(135deg, #a8f0a5, #d2f8d2);
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #007b33;
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 12px;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #333;
            display: flex;
            align-items: center;
            padding: 8px;
            border-radius: 6px;
            transition: background-color 0.2s;
        }

        .sidebar ul li a i {
            margin-right: 8px;
        }

        .sidebar ul li a:hover {
            background-color: #b6f4b0;
            color: #007b33;
        }

        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 25px;
            background-color: #4CAF50;
            border-bottom: 2px solid #388E3C;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar img.logo {
            height: 40px;
        }

        .navbar .user-info {
            font-weight: bold;
            color: white;
        }

        .navbar .user-info form {
            display: inline;
        }

        .navbar .user-info button {
            background: none;
            border: none;
            color: #ffe0e0;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
        }

        .content {
            flex: 1;
            padding: 30px;
            background-color: #ffffff;
        }
    </style>
</head>
<!-- === CHATBOT BUTTON DAN POPUP === -->
<div id="chatButton"
    class="fixed bottom-5 right-5 bg-green-600 text-white p-4 rounded-full shadow-lg text-xl cursor-pointer hover:bg-green-700 transition z-50">
    💬
</div>

<div id="chatPopup"
    class="hidden fixed bottom-20 right-5 bg-white w-80 h-[500px] rounded-xl shadow-2xl flex flex-col overflow-hidden z-50">
    <div class="bg-green-600 text-white px-4 py-3 flex justify-between items-center">
        <span class="font-semibold">Asisten ASY-PAY</span>
        <button id="closeChat" class="text-white text-lg font-bold hover:text-gray-200">×</button>
    </div>
    <iframe src="{{ url('/chatbot') }}" class="flex-1 border-0"></iframe>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const chatBtn = document.getElementById('chatButton');
        const chatPopup = document.getElementById('chatPopup');
        const closeChat = document.getElementById('closeChat');

        chatBtn.addEventListener('click', () => {
            chatPopup.classList.toggle('hidden');
        });

        closeChat.addEventListener('click', () => {
            chatPopup.classList.add('hidden');
        });
    });
</script>
<!-- === END CHATBOT === -->


<body>
    <div class="wrapper">
        <aside class="sidebar">
            <h2>ASY-PAY</h2>
            <ul>
                <li><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                <li><a href="{{ route('kategori.index') }}"><i class="fa fa-tags"></i> Kategori Pembayaran</a></li>
                <li><a href="{{ route('pembayaran.index') }}"><i class="fa fa-money-bill"></i> Pembayaran</a></li>
                <li><a href="{{ route('profile.index') }}"><i class="fa fa-money-bill"></i> Profile Siswa</a></li>
                <li><a href="{{ route('riwayat.index') }}"><i class="fa fa-money-bill"></i> Riwayat Pembayaran</a></li>
            </ul>
        </aside>

        <div class="main-wrapper">
            <div class="navbar">
                <img src="{{ asset('images/logomadrasah.png') }}" alt="Asy Syafi'iyyah" class="logo">
                <div class="user-info">
                    👤 {{ Auth::user()->name ?? 'Siswa' }}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>

            <main class="content">
                @yield('content')
                @yield('scripts')

            </main>
        </div>
    </div>
</body>
</html>
