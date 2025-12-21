<!DOCTYPE html> 
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ASY-PAY - @yield('title', 'Dashboard')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/dashboard-charts.js') }}"></script> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        :root{
            --brand-green:#4CAF50;
            --brand-green-dark:#388E3C;
            --sidebar-w:240px;
            --sidebar-w-mini:120px;

            /* ===== LIGHT ===== */
            --bg-page:#f5f5f5;
            --bg-content:#ffffff;

            --text-main:#111827;
            --text-muted:#6b7280;

            --surface-1:#ffffff;
            --surface-2:#f3f4f6;

            --border:rgba(0,0,0,0.08);

            --sidebar-bg:linear-gradient(135deg,#a8f0a5,#d2f8d2);
            --sidebar-border:rgba(0,123,51,0.1);
            --menu-text:#223;
            --menu-hover-bg:#b6f4b0;
            --menu-hover-text:#007b33;
            --menu-active-bg:#007b33;
            --menu-active-text:#ffffff;

            --shadow: 2px 0 8px rgba(0,0,0,0.08);

            /* Table */
            --table-head-text:#111827;
            --table-row-hover:rgba(0,0,0,0.03);

            /* Form */
            --input-bg:#ffffff;
            --input-border:rgba(0,0,0,0.14);
            --input-focus:#22C55E;
            --placeholder:#9CA3AF;

            /* Extra for Dark Table (default safe) */
            --table-obsidian:#0B0F19;
            --table-obsidian-2:#0F172A;
            --table-border-dark:rgba(255,255,255,0.10);

            /* Soft Blue (for dark mode background) */
            --soft-blue:#BFD7FF;
            --soft-blue-2:#A9C7FF;
            --soft-blue-border:rgba(17,24,39,0.20);
        }

        /* ===== DARK =====
           REQUEST:
           - background konten biru lembut
           - tulisan hitam
           - tabel obsidian (gelap)
        */
        body.dark-mode{
            /* halaman dan konten biru lembut */
            --bg-page:#0B1020;          /* boleh tetap gelap biar sidebar/navbar nyaman */
            --bg-content:var(--soft-blue);

            /* teks hitam */
            --text-main:#111827;
            --text-muted:#1F2937;

            /* permukaan komponen ikut biru lembut */
            --surface-1:var(--soft-blue);
            --surface-2:var(--soft-blue-2);

            /* border lebih gelap */
            --border:var(--soft-blue-border);

            /* sidebar tetap gelap (obsidian-ish) */
            --sidebar-bg:linear-gradient(135deg,#0A0F1E,#060914);
            --sidebar-border:rgba(255,255,255,0.12);

            --menu-text:#E5E7EB;               /* sidebar text tetap terang biar kebaca */
            --menu-hover-bg:rgba(255,255,255,0.10);
            --menu-hover-text:#FFFFFF;
            --menu-active-bg:#22c55e;
            --menu-active-text:#07120b;

            --shadow: 2px 0 14px rgba(0,0,0,0.55);

            /* Table (obsidian) */
            --table-head-text:#E5E7EB;
            --table-row-hover:rgba(255,255,255,0.06);

            /* Form */
            --input-bg:var(--soft-blue-2);
            --input-border:rgba(17,24,39,0.25);
            --input-focus:#111827;
            --placeholder:rgba(17,24,39,0.55);
        }

        *{margin:0;padding:0;box-sizing:border-box}

        body{
            font-family:'Poppins',sans-serif;
            background:var(--bg-page);
            color:var(--text-main);
            transition: background-color .2s ease, color .2s ease;
        }

        .wrapper{display:flex;min-height:100vh;overflow:hidden}

        /* ===== Sidebar ===== */
        .sidebar{
            width:var(--sidebar-w);
            background:var(--sidebar-bg);
            padding:20px 16px;
            box-shadow:var(--shadow);
            position:relative;
            transition: width .3s cubic-bezier(.4,0,.2,1), padding .3s ease, background .2s ease;
            display:flex;
            flex-direction:column;
            z-index:100;
        }
        .sidebar .brand{
            display:flex;
            align-items:center;
            justify-content:center;
            margin-bottom:24px;
            padding-bottom:16px;
            border-bottom:2px solid var(--sidebar-border);
        }
        .sidebar .brand .brand-logo{
            width:108px;height:108px;object-fit:contain;border-radius:10px;
        }
        .sidebar ul{list-style:none;padding:0;margin:0;flex:1}
        .sidebar li{margin-bottom:6px}

        .sidebar a{
            text-decoration:none;
            color:var(--menu-text);
            display:flex;
            align-items:center;
            gap:12px;
            padding:12px 14px;
            border-radius:10px;
            transition: all .2s ease, color .2s ease, background-color .2s ease;
            position:relative;
            font-weight:600;
            font-size:14px;
        }
        .sidebar a i{width:24px;text-align:center;font-size:18px;flex-shrink:0}
        .menu-text{white-space:nowrap;display:inline-block;vertical-align:middle}

        .sidebar a:hover{
            background:var(--menu-hover-bg);
            color:var(--menu-hover-text);
            transform:translateX(4px);
        }
        .sidebar a.active{
            background:var(--menu-active-bg);
            color:var(--menu-active-text);
        }

        /* Mini Sidebar */
        .sidebar.mini{width:var(--sidebar-w-mini);padding:20px 8px}
        .sidebar.mini .menu-text{opacity:0;width:0;overflow:hidden;visibility:hidden}
        .sidebar.mini a{justify-content:center;transform:translateX(0)!important}

        .sidebar.mini a[data-title]:hover::after{
            content:attr(data-title);
            position:absolute;
            left:calc(100% + 12px);
            top:50%;
            transform:translateY(-50%);
            background:#111827;
            color:#fff;
            font-size:13px;
            padding:8px 12px;
            border-radius:8px;
            white-space:nowrap;
            box-shadow:0 6px 16px rgba(0,0,0,0.35);
            z-index:200;
            pointer-events:none;
        }
        body.dark-mode .sidebar.mini a[data-title]:hover::after{
            background:#000;
            border:1px solid rgba(255,255,255,0.12);
            color:#fff;
        }

        .sidebar.mini:hover,.sidebar.mini.hover-open{
            width:var(--sidebar-w);
            padding:20px 16px;
        }
        .sidebar.mini:hover .menu-text,.sidebar.mini.hover-open .menu-text{
            opacity:1;width:auto;visibility:visible
        }
        .sidebar.mini:hover a,.sidebar.mini.hover-open a{justify-content:flex-start}
        .sidebar.mini:hover a[data-title]::after,.sidebar.mini.hover-open a[data-title]::after{display:none}

        /* ===== Main wrapper & Navbar ===== */
        .main-wrapper{flex:1;display:flex;flex-direction:column;min-width:0}

        .navbar{
            display:flex;
            gap:12px;
            justify-content:space-between;
            align-items:center;
            padding:14px 24px;
            background:linear-gradient(135deg,var(--brand-green),var(--brand-green-dark));
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
            position:sticky;
            top:0;
            z-index:50;
        }
        body.dark-mode .navbar{
            background:linear-gradient(135deg,#0b1220,#050712);
            box-shadow:0 6px 18px rgba(0,0,0,0.45);
        }

        .navbar .left-group{display:flex;align-items:center;gap:14px}
        .toggle-btn{
            display:inline-flex;align-items:center;justify-content:center;
            width:40px;height:40px;border:none;border-radius:10px;
            background:rgba(255,255,255,0.15);
            color:#fff;cursor:pointer;transition:all .2s ease;
        }
        .toggle-btn:hover{background:rgba(255,255,255,0.25);transform:scale(1.05)}
        .toggle-btn:active{transform:scale(0.95)}
        .navbar img.logo{height:96px;border-radius:10px}

        .right-group{display:flex;align-items:center;gap:10px;min-width:0}

        /* Theme Toggle */
        .theme-toggle{
            display:inline-flex;align-items:center;gap:10px;
            padding:8px 14px;border-radius:999px;
            border:1px solid rgba(255,255,255,0.25);
            background:rgba(255,255,255,0.15);
            color:#fff;cursor:pointer;transition:all .2s ease;
            white-space:nowrap;user-select:none;
        }
        .theme-toggle:hover{background:rgba(255,255,255,0.25);transform:translateY(-1px)}
        .theme-toggle i{font-size:16px}
        .theme-toggle .theme-text{font-weight:700;font-size:13px;letter-spacing:.2px}

        /* User Info */
        .user-info{
            display:flex;align-items:center;gap:12px;
            background:rgba(255,255,255,0.15);
            padding:6px 16px 6px 6px;
            border-radius:50px;
            transition:background .2s ease;
            min-width:0;
        }
        .user-info:hover{background:rgba(255,255,255,0.25)}

        .user-avatar{
            width:38px;height:38px;border-radius:50%;
            object-fit:cover;border:2px solid white;background:white;flex-shrink:0;
        }
        .user-name{
            font-weight:700;color:#fff;font-size:14px;
            line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
            max-width:180px;
        }
        .user-role{
            font-size:11px;color:rgba(255,255,255,0.92);
            text-transform:capitalize;white-space:nowrap;
        }
        .logout-btn{
            background:none;border:none;color:white;cursor:pointer;
            font-size:18px;padding:6px;margin-left:8px;border-radius:6px;
            transition:all .2s ease;display:flex;align-items:center;justify-content:center;flex-shrink:0;
        }
        .logout-btn:hover{background:rgba(255,255,255,0.15);transform:scale(1.1)}

        /* Content */
        .content{
            flex:1;
            padding:28px;
            background:var(--bg-content);
            overflow-y:auto;
            transition: background-color .2s ease, color .2s ease;
            color: var(--text-main);
        }

        /* Helper */
        .text-muted { color: var(--text-muted) !important; }

        /* ===== ALERTS ===== */
        .alert{
            padding:14px 18px;border-radius:10px;margin-bottom:20px;
            font-weight:600;display:flex;align-items:center;gap:10px;
            animation:slideDown .3s ease;
        }
        @keyframes slideDown{ from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }
        .alert-success{background:#d4edda;border-left:4px solid #28a745;color:#155724}
        .alert-error{background:#f8d7da;border-left:4px solid #dc3545;color:#721c24}

        /* alerts dark: tetap readable di background biru */
        body.dark-mode .alert-success{
            background:rgba(34,197,94,0.18);
            border-left-color:#22c55e;
            color:#111827;
        }
        body.dark-mode .alert-error{
            background:rgba(239,68,68,0.18);
            border-left-color:#ef4444;
            color:#111827;
        }

        /* ===== TABLE DARK MODE (Obsidian table) ===== */
        body.dark-mode .table{
            --bs-table-bg: var(--table-obsidian);
            --bs-table-color: #E5E7EB;            /* teks di tabel tetap terang supaya kebaca */
            --bs-table-border-color: var(--table-border-dark);
        }

        body.dark-mode .table thead th{
            background: var(--table-obsidian-2);
            color: #F9FAFB;
            border-color: var(--table-border-dark);
        }

        body.dark-mode .table tbody td{
            border-color: var(--table-border-dark);
        }

        body.dark-mode .table tbody tr:hover{
            background: rgba(255,255,255,0.06);
        }

        body.dark-mode .table-striped > tbody > tr:nth-of-type(odd){
            background: rgba(255,255,255,0.03);
        }

        /* kalau tabel dibungkus .table-responsive */
        body.dark-mode .table-responsive{
            border:1px solid var(--table-border-dark);
            border-radius:12px;
            overflow:hidden;
        }

        /* ===== FORM DARK MODE (background biru lembut, tulisan hitam) ===== */
        body.dark-mode label,
        body.dark-mode .form-label{
            color: var(--text-main);
        }

        body.dark-mode .form-control,
        body.dark-mode .form-select,
        body.dark-mode textarea,
        body.dark-mode input{
            background: var(--input-bg) !important;     /* biru lembut */
            color: var(--text-main) !important;         /* hitam */
            border: 1px solid var(--input-border) !important;
        }

        body.dark-mode .form-control::placeholder,
        body.dark-mode input::placeholder,
        body.dark-mode textarea::placeholder{
            color: var(--placeholder) !important;
            opacity: 1;
        }

        body.dark-mode .form-control:focus,
        body.dark-mode .form-select:focus,
        body.dark-mode textarea:focus,
        body.dark-mode input:focus{
            border-color: var(--input-focus) !important; /* hitam */
            box-shadow: 0 0 0 .2rem rgba(17,24,39,0.18) !important;
        }

        body.dark-mode .form-control:disabled,
        body.dark-mode .form-select:disabled{
            opacity: 0.75;
        }

        /* ===== CARD / MODAL ===== */
        body.dark-mode .card,
        body.dark-mode .modal-content{
            background: var(--surface-1);
            border: 1px solid var(--border);
            color: var(--text-main);
        }

        /* ===== Responsive ===== */
        @media (max-width:1024px){
            .sidebar{position:fixed;top:0;left:0;height:100vh;z-index:1000}
            .main-wrapper{margin-left:var(--sidebar-w);transition:margin-left .3s ease}
            .sidebar.mini ~ .main-wrapper{margin-left:var(--sidebar-w-mini)}
        }
        @media (max-width:768px){
            .sidebar{width:var(--sidebar-w-mini);padding:20px 8px}
            .menu-text{opacity:0;width:0;visibility:hidden}
            .navbar{padding:12px 16px}
            .content{padding:20px 16px}
            .user-details{display:none}
            .main-wrapper{margin-left:var(--sidebar-w-mini)}
            .theme-toggle .theme-text{display:none}
            .theme-toggle{padding:8px 10px;gap:0}
        }
        @media (max-width:480px){
            .navbar img.logo{height:72px}
            .toggle-btn{width:36px;height:36px}
            .user-avatar{width:34px;height:34px}
        }
    </style>

    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
        });
    </script>

    @stack('head')
</head>

<body>
<div class="wrapper">
    <aside class="sidebar" id="appSidebar">
        <div class="brand">
            <img src="{{ asset('images/logo.jpg') }}" class="brand-logo" alt="Logo">
        </div>

        <ul>
            @php $role = auth()->user()->role ?? 'guest'; @endphp

            @if($role === 'admin')
                <li>
                    <a href="{{ route('admin.dashboard') }}" data-title="Dashboard" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa fa-tachometer-alt"></i><span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.siswa.index') }}" data-title="Siswa" class="{{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                        <i class="fa fa-users"></i><span class="menu-text">Siswa</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.kategori.index') }}" data-title="Kategori" class="{{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                        <i class="fa fa-tags"></i><span class="menu-text">Kategori</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.pembayaran.index') }}" data-title="Pembayaran" class="{{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                        <i class="fa fa-credit-card"></i><span class="menu-text">Pembayaran</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.laporan.index') }}" data-title="Laporan" class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                        <i class="fa fa-file-alt"></i><span class="menu-text">Laporan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.kasir.index') }}" data-title="Kasir" class="{{ request()->routeIs('admin.kasir.*') ? 'active' : '' }}">
                        <i class="fa fa-cash-register"></i><span class="menu-text">Kasir</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.index') }}" data-title="Profil" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="fa fa-user-circle"></i><span class="menu-text">Profil</span>
                    </a>
                </li>
               

            @elseif($role === 'siswa')
                <li>
                    <a href="{{ route('siswa.dashboard') }}" data-title="Dashboard" class="{{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                        <i class="fa fa-tachometer-alt"></i><span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.pembayaran.index') }}" data-title="Pembayaran" class="{{ request()->routeIs('siswa.pembayaran.*') ? 'active' : '' }}">
                        <i class="fa fa-money-bill-wave"></i><span class="menu-text">Pembayaran</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.riwayat.index') }}" data-title="Riwayat" class="{{ request()->routeIs('siswa.riwayat.*') ? 'active' : '' }}">
                        <i class="fa fa-history"></i><span class="menu-text">Riwayat</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.index') }}" data-title="Profil" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="fa fa-user-circle"></i><span class="menu-text">Profil</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('chat.view') }}" data-title="Asisten AI" class="{{ request()->routeIs('chat.view') ? 'active' : '' }}">
                        <i class="fa-solid fa-robot"></i><span class="menu-text">Asisten AI</span>
                    </a>
                </li>
            @endif
        </ul>
    </aside>

    <div class="main-wrapper">
        <div class="navbar">
            <div class="left-group">
                <button class="toggle-btn" id="sidebarToggle" aria-label="Toggle sidebar">
                    <i class="fa fa-bars"></i>
                </button>
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="logo">
            </div>

            <div class="right-group">
                <button class="theme-toggle" id="themeToggle" type="button" aria-label="Toggle dark mode">
                    <i class="fa-solid fa-moon" id="themeIcon"></i>
                    <span class="theme-text" id="themeText">Dark</span>
                </button>

                <div class="user-info">
                    @auth
                        @php
                            $user = auth()->user();
                            $photoUrl = null;
                            if ($user->foto) {
                                if (filter_var($user->foto, FILTER_VALIDATE_URL)) {
                                    $photoUrl = $user->foto;
                                } elseif (file_exists(storage_path('app/public/' . $user->foto))) {
                                    $photoUrl = asset('storage/' . $user->foto);
                                } elseif (file_exists(public_path('foto_siswa/' . $user->foto))) {
                                    $photoUrl = asset('foto_siswa/' . $user->foto);
                                } elseif (file_exists(public_path('images/users/' . $user->foto))) {
                                    $photoUrl = asset('images/users/' . $user->foto);
                                } elseif (file_exists(public_path($user->foto))) {
                                    $photoUrl = asset($user->foto);
                                }
                            }
                            $finalPhotoUrl = $photoUrl ?: asset('images/default-avatar.png');
                        @endphp

                        <img src="{{ $finalPhotoUrl }}"
                             alt="{{ $user->name }}"
                             class="user-avatar"
                             onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}';">

                        <div class="user-details">
                            <span class="user-name">{{ $user->name }}</span>
                            <span class="user-role">{{ $user->role }}</span>
                        </div>

                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="logout-btn" title="Logout">
                                <i class="fa fa-sign-out-alt"></i>
                            </button>
                        </form>
                    @else
                        <img src="{{ asset('images/default-avatar.png') }}" alt="Guest" class="user-avatar">
                        <div class="user-details">
                            <span class="user-name">Guest</span>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <main class="content">
            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fa fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
(function () {
    // ===== Sidebar Mini Logic =====
    const sidebar = document.getElementById('appSidebar');
    const btn = document.getElementById('sidebarToggle');
    const LS_KEY = 'asy_pay_sidebar_mini';

    try {
        const saved = localStorage.getItem(LS_KEY);
        if (saved === '1') sidebar.classList.add('mini');
    } catch(e) {}

    btn?.addEventListener('click', function() {
        sidebar.classList.toggle('mini');
        try {
            localStorage.setItem(LS_KEY, sidebar.classList.contains('mini') ? '1' : '0');
        } catch(e) {}
    });

    sidebar.addEventListener('mouseenter', () => {
        if (sidebar.classList.contains('mini')) sidebar.classList.add('hover-open');
    });
    sidebar.addEventListener('mouseleave', () => sidebar.classList.remove('hover-open'));

    // ===== Dark Mode Logic =====
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const themeText = document.getElementById('themeText');
    const THEME_KEY = 'asy_pay_theme';

    function applyTheme(theme) {
        const isDark = theme === 'dark';
        document.body.classList.toggle('dark-mode', isDark);

        if (isDark) {
            themeIcon.className = 'fa-solid fa-sun';
            themeText.textContent = 'Light';
        } else {
            themeIcon.className = 'fa-solid fa-moon';
            themeText.textContent = 'Dark';
        }
    }

    try {
        const savedTheme = localStorage.getItem(THEME_KEY);
        if (savedTheme === 'dark' || savedTheme === 'light') {
            applyTheme(savedTheme);
        } else {
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            applyTheme(prefersDark ? 'dark' : 'light');
        }
    } catch (e) {
        applyTheme('light');
    }

    themeToggle?.addEventListener('click', function () {
        const nowDark = document.body.classList.contains('dark-mode');
        const nextTheme = nowDark ? 'light' : 'dark';
        applyTheme(nextTheme);
        try { localStorage.setItem(THEME_KEY, nextTheme); } catch(e) {}
    });

    // Alert auto close
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.animation = 'slideDown 0.3s ease reverse';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
})();
</script>

@stack('scripts')
</body>
</html>
