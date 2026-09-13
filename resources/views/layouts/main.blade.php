<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Telaga Bagus Butik</title>
    
    <!-- Link Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Link CSS Custom Milik Kita -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Dynamic CSS Stack for Page-Specific Styles -->
    @stack('css')
</head>
<body>

    <!-- HEADER ATAS -->
    <nav class="navbar navbar-dark navbar-custom shadow-sm sticky-top">
        <div class="container-fluid">
            <!-- Tombol Garis Tiga (Hamburger Menu) -->
            <button class="navbar-toggler border-0 shadow-none px-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Judul Aplikasi -->
            <span class="navbar-brand mb-0 h1 ms-2 me-auto fw-bold d-flex align-items-center gap-2">
                <i class="bi bi-scissors text-primary fs-5"></i> TELAGA BAGUS BUTIK
            </span>

            <!-- Profil Ringkas di Navbar -->
            <div class="d-flex align-items-center gap-2">
                @if(Auth::check())
                    <span class="text-white-50 small d-none d-sm-inline">{{ Auth::user()->nama_user }}</span>
                    @if(Auth::user()->isOwner())
                        <span class="badge bg-warning text-dark fw-semibold d-inline-flex align-items-center gap-1 shadow-sm">
                            <i class="bi bi-star-fill fs-8"></i> Owner
                        </span>
                    @elseif(Auth::user()->isAdmin())
                        <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 fw-semibold">
                            Admin
                        </span>
                    @else
                        <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-25 fw-semibold">
                            Penjahit
                        </span>
                    @endif
                @endif
            </div>
        </div>
    </nav>

    <!-- SIDEBAR TERSEMBUNYI (Offcanvas) -->
    <div class="offcanvas offcanvas-start border-0 shadow-lg" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel" style="width: 280px;">
        <div class="offcanvas-header text-white">
            <h5 class="offcanvas-title fw-bold fs-6 tracking-wide d-flex align-items-center gap-2" id="sidebarMenuLabel">
                <i class="bi bi-grid-fill text-primary"></i> MENU UTAMA
            </h5>
            <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        
        <div class="offcanvas-body d-flex flex-column p-3">
            <!-- Profil Singkat User -->
            <div class="p-3 bg-dark bg-opacity-50 rounded-3 mb-3 border border-secondary border-opacity-25">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle {{ Auth::user()->isOwner() ? 'bg-warning text-dark' : (Auth::user()->isAdmin() ? 'bg-primary text-white' : 'bg-info text-dark') }} d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px;">
                        {{ strtoupper(substr(Auth::user()->nama_user, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <div class="text-white fw-bold small text-truncate">{{ Auth::user()->nama_user }}</div>
                        <div class="fs-8 text-muted d-flex align-items-center gap-1">
                            @if(Auth::user()->isOwner())
                                <span class="badge bg-warning text-dark px-1 py-0">Owner</span>
                            @elseif(Auth::user()->isAdmin())
                                <span class="badge bg-primary bg-opacity-25 text-info px-1 py-0 border border-info border-opacity-25">Admin Operasional</span>
                            @else
                                <span class="badge bg-secondary px-1 py-0">Penjahit</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Link Menu -->
            <div class="mb-auto">
                @if(Auth::user()->isAdmin())
                    <a href="/pesanan" class="sidebar-link {{ request()->is('pesanan') ? 'active' : '' }}">
                        <i class="bi bi-journal-bookmark-fill"></i> Daftar Pesanan
                    </a>
                    <a href="/pesanan/tambah" class="sidebar-link {{ request()->is('pesanan/tambah') ? 'active' : '' }}">
                        <i class="bi bi-plus-circle-fill"></i> Tambah Pesanan Baru
                    </a>
                    <a href="/kwitansi" class="sidebar-link {{ request()->is('kwitansi*') ? 'active' : '' }}">
                        <i class="bi bi-receipt-cutoff"></i> Cetak Kwitansi
                    </a>
                    <a href="/kain" class="sidebar-link {{ request()->is('kain*') ? 'active' : '' }}">
                        <i class="bi bi-layers-half"></i> Inventori Kain
                    </a>
                    
                    {{-- Menu Khusus Pemilik Butik (Owner) --}}
                    @if(Auth::user()->isOwner())
                        <div class="text-uppercase text-secondary fs-8 fw-bold mt-3 mb-1 px-2 d-flex align-items-center justify-content-between">
                            <span>Khusus Pemilik</span>
                            <i class="bi bi-shield-lock-fill text-warning fs-8"></i>
                        </div>
                        <a href="/laporan" class="sidebar-link {{ request()->is('laporan*') ? 'active' : '' }}">
                            <i class="bi bi-bar-chart-line-fill text-warning"></i> Laporan & Keuangan
                        </a>
                    @endif
               
                @elseif(Auth::user()->role == 'penjahit')
                    <a href="/penjahit/dashboard" class="sidebar-link {{ request()->is('penjahit/dashboard') ? 'active' : '' }}">
                        <i class="bi bi-scissors"></i> Antrean Jahitan
                    </a>
                @endif
            </div>
            
            <!-- Tombol Logout di paling bawah sidebar -->
            <hr class="text-secondary opacity-25">
            <form action="/logout" method="POST" class="mt-1">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 fw-semibold d-flex align-items-center justify-content-center gap-2 py-2">
                    <i class="bi bi-box-arrow-right fs-6"></i> Keluar (Logout)
                </button>
            </form>
        </div>
    </div>

    <!-- ZONA KONTEN UTAMA -->
    <div class="container mt-4 mb-5">
        @yield('content')
    </div>

    <!-- Link Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>