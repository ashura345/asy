<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ASY-PAY - @yield('title', 'Dashboard')</title>

    <!-- External CSS & JS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/dashboard-charts.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Styles -->
    <style>
        :root{
            --brand-green:#4CAF50;
            --brand-green-dark:#388E3C;
            --sidebar-w:220px;
            --sidebar-w-mini:72px;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
            overflow: hidden;
        }

        /* ===== Sidebar (Normal) ===== */
        .sidebar{
            width: var(--sidebar-w);
            background: linear-gradient(135deg, #a8f0a5, #d2f8d2);
            padding: 20px 16px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.06);
            position: relative;
            transition: width .18s ease, padding .18s ease;
            display: flex;
            flex-direction: column;
        }

        .sidebar .brand{
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            margin-bottom: 20px;
        }
        .sidebar .brand .brand-logo{
            width: 36px; height: 36px; object-fit: contain;
        }
        .sidebar .brand .brand-text{
            font-size: 20px; font-weight: 700; color:#007b33; white-space: nowrap;
        }

        .sidebar ul { list-style: none; padding: 0; margin: 0; }
        .sidebar li { margin-bottom: 8px; }

        /* Link menu rapi & sejajar */
        .sidebar a{
            text-decoration: none;
            color: #223;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            transition: background-color .15s ease, color .15s ease;
            position: relative;
            line-height: 1.2;
        }
        .sidebar a i{
            width: 24px;                 /* ikon lebar tetap → teks tak geser */
            text-align: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .menu-text{
            white-space: nowrap;
            display: inline-block;
            vertical-align: middle;
        }

        .sidebar a:hover { background-color: #b6f4b0; color: #007b33; }

        /* ===== Mini Mode ===== */
        .sidebar.mini{
            width: var(--sidebar-w-mini);
            padding: 20px 8px;
        }
        .sidebar.mini .brand .brand-text{ display: none; }
        .sidebar.mini .menu-text{
            opacity: 0;
            width: 0;
            overflow: hidden;
            visibility: hidden;
        }
        .sidebar.mini a{ justify-content: center; }

        /* Tooltip saat mini */
        .sidebar.mini a[data-title]:hover::after{
            content: attr(data-title);
            position: absolute;
            left: calc(100% + 10px);
            top: 50%;
            transform: translateY(-50%);
            background: #223; color:#fff;
            font-size: 12px; padding: 6px 8px;
            border-radius: 6px; white-space: nowrap;
            box-shadow: 0 4px 12px rgba(0,0,0,.15);
            z-index: 20;
        }

        /* ===== Auto-expand saat hover ===== */
        .sidebar.mini:hover,
        .sidebar.mini.hover-open{                 /* hover-open = fallback JS untuk sentuh/keyboard */
            width: var(--sidebar-w);
            padding: 20px 16px;
        }
        .sidebar.mini:hover .brand .brand-text,
        .sidebar.mini.hover-open .brand .brand-text{
            display:inline;
        }
        .sidebar.mini:hover .menu-text,
        .sidebar.mini.hover-open .menu-text{
            opacity: 1;
            width: auto;
            visibility: visible;
        }
        .sidebar.mini:hover a,
        .sidebar.mini.hover-open a{
            justify-content: flex-start;
        }
        /* Saat melebar karena hover, tooltip jangan tampil */
        .sidebar.mini:hover a[data-title]::after,
        .sidebar.mini.hover-open a[data-title]::after{
            display: none;
        }

        /* ===== Main wrapper ===== */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .navbar {
            display: flex;
            gap:12px;
            justify-content: space-between;
            align-items: center;
            padding: 12px 18px;
            background-color: var(--brand-green);
            border-bottom: 2px solid var(--brand-green-dark);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
            position: sticky;
            top:0; z-index:10;
        }

        /* Toggle button */
        .toggle-btn{
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width:38px; height:38px;
            border:none;
            border-radius:10px;
            background: rgba(255,255,255,.15);
            color:#fff;
            cursor:pointer;
            transition: background .18s ease, transform .06s ease-in;
        }
        .toggle-btn:hover{ background: rgba(255,255,255,.25); }
        .toggle-btn:active{ transform: scale(.98); }

        .navbar .left-group{ display:flex; align-items:center; gap:12px; }
        .navbar img.logo { height: 40px; }

        .navbar .user-info {
            font-weight: bold; color: white;
            display: flex; align-items: center; gap: 10px;
        }
        .navbar .user-info form { display: inline; }
        .navbar .user-info button {
            background: none; border: none; color: #ffe0e0; cursor: pointer; font-size: 14px;
        }

        .content {
            flex: 1;
            padding: 24px;
            background-color: #ffffff;
        }

        .alert { padding: 10px; border-radius: 8px; margin-bottom: 15px; }
        .alert-success { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .alert-error { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }

        /* ===== Responsive ===== */
        @media (max-width: 1024px){
            .sidebar{ position: sticky; top:0; height: 100vh; }
        }
        @media (max-width: 768px){
            /* default mini di mobile, tetap bisa hover-open via JS (focus) */
            .sidebar{ width: var(--sidebar-w-mini); padding: 20px 8px; }
            .sidebar .brand .brand-text{ display:none; }
            .menu-text{ opacity:0; width:0; visibility:hidden; }
        }
    </style>

    <!-- CSRF Setup for AJAX -->
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>

    @stack('head')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="appSidebar">
            <div class="brand">
                <img src="{{ asset('images/logo.jpg') }}" class="brand-logo" alt="Logo">
                <span class="brand-text">ASY-PAY</span>
            </div>

            <ul>
                @php $role = auth()->user()->role ?? 'guest'; @endphp

                @if($role === 'admin')
                    <li>
                        <a href="{{ route('admin.dashboard') }}" data-title="Dashboard">
                            <i class="fa fa-tachometer-alt"></i>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.siswa.index') }}" data-title="Siswa">
                            <i class="fa fa-users"></i>
                            <span class="menu-text">Siswa</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.kategori.index') }}" data-title="Kategori Pembayaran">
                            <i class="fa fa-tags"></i>
                            <span class="menu-text">Kategori</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.pembayaran.index') }}" data-title="Pembayaran">
                            <i class="fa fa-credit-card"></i>
                            <span class="menu-text">Pembayaran</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.laporan.index') }}" data-title="Laporan">
                            <i class="fa fa-file-alt"></i>
                            <span class="menu-text">Laporan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.kasir.index') }}" data-title="Kasir">
                            <i class="fa fa-cash-register"></i>
                            <span class="menu-text">Kasir</span>
                        </a>
                    </li>
                @elseif($role === 'siswa')
                    <li>
                        <a href="{{ route('siswa.dashboard') }}" data-title="Dashboard">
                            <i class="fa fa-tachometer-alt"></i>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('siswa.pembayaran.index') }}" data-title="Pembayaran">
                            <i class="fa fa-money-bill-wave"></i>
                            <span class="menu-text">Pembayaran</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('siswa.riwayat.index') }}" data-title="Riwayat">
                            <i class="fa fa-history"></i>
                            <span class="menu-text">Riwayat</span>
                        </a>
                    </li>
                @endif
            </ul>
        </aside>

        <!-- Main Wrapper -->
        <div class="main-wrapper">
            <!-- Navbar -->
            <div class="navbar">
                <div class="left-group">
                    <!-- Toggle Button -->
                    <button class="toggle-btn" id="sidebarToggle" aria-label="Toggle sidebar">
                        <i class="fa fa-bars"></i>
                    </button>
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="logo">
                </div>

                <div class="user-info">
                    👤 {{ auth()->user()->name ?? 'Guest' }}
                    <a href="{{ route('profile.index') }}">Profil</a>
                    @auth
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>

            <!-- Page Content -->
            <main class="content">
                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
                @stack('scripts')
            </main>
        </div>
    </div>

    <!-- Mini Sidebar Toggle & Auto-Expand Logic -->
    <script>
        (function(){
            const sidebar = document.getElementById('appSidebar');
            const btn = document.getElementById('sidebarToggle');
            const LS_KEY = 'asy_pay_sidebar_mini';

            // Terapkan state tersimpan
            try{
                const saved = localStorage.getItem(LS_KEY);
                if(saved === '1'){ sidebar.classList.add('mini'); }
            }catch(e){}

            // Toggle manual via klik tombol
            btn?.addEventListener('click', function(){
                sidebar.classList.toggle('mini');
                try{
                    localStorage.setItem(LS_KEY, sidebar.classList.contains('mini') ? '1' : '0');
                }catch(e){}
            });

            // Auto expand ketika mouse hover
            sidebar.addEventListener('mouseenter', () => {
                if(sidebar.classList.contains('mini')) sidebar.classList.add('hover-open');
            });
            sidebar.addEventListener('mouseleave', () => {
                sidebar.classList.remove('hover-open');
            });

            // Aksesibilitas: fokus keyboard juga membuka (untuk perangkat sentuh)
            sidebar.addEventListener('focusin', () => {
                if(sidebar.classList.contains('mini')) sidebar.classList.add('hover-open');
            });
            sidebar.addEventListener('focusout', (e) => {
                if(!sidebar.contains(e.relatedTarget)){
                    sidebar.classList.remove('hover-open');
                }
            });
        })();
    </script>
</body>
</html>
