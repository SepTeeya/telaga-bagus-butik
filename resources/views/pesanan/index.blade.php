@extends('layouts.main')

@section('content')  

    @php
        $jam = date('H');
        if ($jam < 11) {
            $sapaan = 'Selamat Pagi';
        } elseif ($jam < 15) {
            $sapaan = 'Selamat Siang';
        } elseif ($jam < 18) {
            $sapaan = 'Selamat Sore';
        } else {
            $sapaan = 'Selamat Malam';
        }
    @endphp

    <!-- GREETING BANNER ADMIN & OWNER -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 text-white overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="card-body p-4 position-relative">
            <div class="row align-items-center">
                <div class="col-md-7 mb-3 mb-md-0">
                    <div class="d-inline-flex align-items-center gap-2 bg-white bg-opacity-10 text-white rounded-pill px-3 py-1 fs-7 mb-2 border border-white border-opacity-10">
                        @if(Auth::user()->isOwner())
                            <i class="bi bi-patch-check-fill text-warning"></i> Panel Pemilik Butik (Owner)
                        @else
                            <i class="bi bi-person-workspace text-info"></i> Panel Administrator (Operasional)
                        @endif
                    </div>
                    <h2 class="fw-bold mb-1 text-white">
                        {{ $sapaan }}, {{ Auth::user()->nama_user }}!
                    </h2>
                    <p class="text-white opacity-75 mb-0 small">
                        @if(Auth::user()->isOwner())
                            Pantau performa bisnis, omzet keuangan butik, dan koordinasi pesanan pelanggan Telaga Bagus Butik.
                        @else
                            Kelola alur pesanan pelanggan, penugasan penjahit, dan inventori kain Telaga Bagus Butik.
                        @endif
                    </p>
                </div>
                <div class="col-md-5 text-md-end d-flex flex-wrap justify-content-md-end gap-2">
                    @if(Auth::user()->isOwner())
                        <a href="/laporan" class="btn btn-outline-light fw-semibold shadow-sm px-3 py-2 d-inline-flex align-items-center gap-2">
                            <i class="bi bi-bar-chart-line-fill text-warning"></i> Laporan Keuangan
                        </a>
                    @endif
                    <a href="/pesanan/tambah" class="btn btn-primary fw-semibold shadow-sm px-3 py-2 d-inline-flex align-items-center gap-2">
                        <i class="bi bi-plus-circle-fill fs-6"></i> Pesanan Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- NOTIFIKASI SUKSES & ERROR -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="bi bi-shield-lock-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- KARTU RINGKASAN STATISTIK -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2 bg-primary-subtle text-primary">
                        <i class="bi bi-journal-text fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Total Pesanan</div>
                        <div class="fs-5 fw-bold text-dark">{{ $pesanans->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2 bg-warning-subtle text-warning-emphasis">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Mendekati Deadline</div>
                        <div class="fs-5 fw-bold text-dark">{{ $pesanans->where('warning_level', 'warning')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2 bg-danger-subtle text-danger">
                        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Terlambat</div>
                        <div class="fs-5 fw-bold text-dark">{{ $pesanans->where('warning_level', 'danger')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2 bg-success-subtle text-success">
                        <i class="bi bi-check-circle-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Pesanan Tuntas</div>
                        <div class="fs-5 fw-bold text-dark">{{ $pesanans->whereIn('status_pesanan', ['Selesai', 'Diambil'])->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- HEADER TABLE & FORM PENCARIAN -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-3">
        <h5 class="fw-bold m-0 text-dark d-flex align-items-center gap-2">
            <i class="bi bi-list-task text-primary"></i> Daftar Pesanan
        </h5>

        <!-- Form Pencarian -->
        <form action="/pesanan" method="GET" class="m-0" style="flex-grow: 1; max-width: 400px;">
            <div class="input-group search-group shadow-sm rounded-3 overflow-hidden">
                <span class="input-group-text bg-white text-muted ps-3"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control ps-1" placeholder="Cari pelanggan / model..." value="{{ request('search') }}">
                @if(request('search'))
                    <a href="/pesanan" class="btn btn-white text-muted d-flex align-items-center pe-2" title="Reset Pencarian"><i class="bi bi-x-circle-fill"></i></a>
                @endif
                <button class="btn btn-primary fw-semibold px-3 d-flex align-items-center gap-1" type="submit">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <!-- KOTAK RESPONSIVE TABEL -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 tabel-butik">
                    <thead class="bg-light text-secondary text-uppercase small font-monospace border-bottom">
                        <tr class="text-center align-middle">
                            <th style="width: 50px;">No</th>
                            <th>Nama Pelanggan</th>
                            <th>Tgl Pesan</th>
                            <th>Tgl Deadline</th>
                            <th>Detail Baju & Ukuran</th>
                            <th>Status</th>
                            <th style="min-width: 175px;">Penjahit PIC</th>
                            <th>Info Pembayaran</th>
                            <th style="min-width: 140px;">Peringatan</th>
                            <th style="min-width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanans as $index => $pesanan)
                        <tr class="{{ $pesanan->warna_baris }}">
                            <td class="text-center text-muted small fw-semibold">{{ $index + 1 }}</td>
                            <td><strong class="text-dark">{{ $pesanan->pelanggan->nama_pelanggan }}</strong></td>
                            <td class="text-center text-muted small">{{ \Carbon\Carbon::parse($pesanan->tgl_pesan)->format('d/m/Y') }}</td>
                            <td class="text-center text-muted small">{{ \Carbon\Carbon::parse($pesanan->tgl_deadline)->format('d/m/Y') }}</td>
                            <td>
                                <!-- Desain Kartu -->
                                <div class="kartu-ukuran">
                                    <strong>Bahan:</strong> {{ $pesanan->bahan }} <br>
                                    <strong>Jenis Pesanan:</strong> {{ $pesanan->model_baju }} <br>

                                    <hr class="my-2 text-muted">
                                    <div class="row row-cols-2 g-1 small">
                                        @if(!empty($pesanan->ukuran->P)) <div class="col">P: {{ $pesanan->ukuran->P }}</div> @endif
                                        @if(!empty($pesanan->ukuran->Bahu))<div class="col">Bahu: {{ $pesanan->ukuran->Bahu  }}</div> @endif
                                        @if(!empty($pesanan->ukuran->Dada))<div class="col">Dada: {{ $pesanan->ukuran->Dada  }}</div> @endif
                                        @if(!empty($pesanan->ukuran->Perut))<div class="col">Perut: {{ $pesanan->ukuran->Perut  }}</div> @endif
                                        @if(!empty($pesanan->ukuran->Pinggul))<div class="col">Pinggul: {{ $pesanan->ukuran->Pinggul  }}</div> @endif
                                        @if(!empty($pesanan->ukuran->PT))<div class="col">PT: {{ $pesanan->ukuran->PT  }}</div> @endif
                                        @if(!empty($pesanan->ukuran->LT))<div class="col">LT: {{ $pesanan->ukuran->LT  }}</div> @endif
                                        @if(!empty($pesanan->ukuran->Ketiak))<div class="col">Ketiak: {{ $pesanan->ukuran->Ketiak  }}</div> @endif

                                        @if(!empty($pesanan->ukuran->Leher)) <div class="col">Leher: {{ $pesanan->ukuran->Leher }}</div> @endif
                                        @if(!empty($pesanan->ukuran->bet_kebaya)) <div class="col">Bet Kebaya: {{ $pesanan->ukuran->bet_kebaya }}</div> @endif
                                        @if(!empty($pesanan->ukuran->LP)) <div class="col">LP: {{ $pesanan->ukuran->LP }}</div> @endif
                                        @if(!empty($pesanan->ukuran->catatan))<strong class="teks-catatan">Catatan:</strong> {{ $pesanan->ukuran->catatan }} @endif
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                @if($pesanan->status_pesanan == 'Belum Diproses')
                                    <span class="badge-status badge-status-normal"><i class="bi bi-clock"></i> Belum Diproses</span>
                                @elseif($pesanan->status_pesanan == 'Dalam Proses')
                                    <span class="badge-status bg-primary-subtle text-primary border border-primary-subtle"><i class="bi bi-gear-wide-connected"></i> Dalam Proses</span>
                                @elseif($pesanan->status_pesanan == 'Selesai')
                                    <span class="badge-status badge-status-success"><i class="bi bi-check-circle-fill"></i> Selesai</span>
                                @elseif($pesanan->status_pesanan == 'Diambil')
                                    <span class="badge-status bg-dark-subtle text-dark border border-dark-subtle"><i class="bi bi-bag-check-fill"></i> Diambil</span>
                                @else
                                    <span class="badge-status badge-status-normal">{{ $pesanan->status_pesanan }}</span>
                                @endif
                            </td>

                            <!-- Penugasan Penjahit PIC -->
                            <td>
                                <form action="/pesanan/{{ $pesanan->id_pesanan }}/tugaskan" method="POST" class="m-0">
                                    @csrf
                                    @method('PATCH')
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-secondary-subtle px-2">
                                            <i class="bi bi-scissors {{ $pesanan->id_penjahit ? 'text-primary' : 'text-muted' }}"></i>
                                        </span>
                                        <select name="id_penjahit" onchange="this.form.submit()" class="form-select form-select-sm border-secondary-subtle" title="Tugaskan penjahit">
                                            <option value="" class="text-muted">-- Belum Ada --</option>
                                            @foreach($penjahits as $penjahit)
                                                <option value="{{ $penjahit->id_user }}" {{ $pesanan->id_penjahit == $penjahit->id_user ? 'selected' : '' }}>
                                                    {{ $penjahit->nama_user }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </td>

                            <td class="small">
                                <div class="text-primary fw-semibold">DP: Rp {{ number_format($pesanan->dp ?? 0, 0, ',', '.') }}</div>
                                <div class="text-danger fw-semibold">Sisa: Rp {{ number_format($pesanan->sisa_pembayaran ?? 0, 0, ',', '.') }}</div>
                            </td>
                            <td class="text-center">
                                @if($pesanan->warning_level == 'danger')
                                    <span class="badge-status badge-status-danger" title="Pesanan melebihi deadline">
                                        <i class="bi bi-exclamation-triangle-fill"></i> {{ $pesanan->pesan_warning }}
                                    </span>
                                @elseif($pesanan->warning_level == 'warning')
                                    <span class="badge-status badge-status-warning" title="Mendekati deadline">
                                        <i class="bi bi-clock-history"></i> {{ $pesanan->pesan_warning }}
                                    </span>
                                @elseif($pesanan->warning_level == 'success')
                                    <span class="badge-status badge-status-success">
                                        <i class="bi bi-check-all"></i> {{ $pesanan->pesan_warning }}
                                    </span>
                                @else
                                    <span class="badge-status badge-status-normal">
                                        <i class="bi bi-shield-check"></i> {{ $pesanan->pesan_warning }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <form action="/pesanan/{{ $pesanan->id_pesanan }}/status" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status_pesanan" onchange="this.form.submit()" class="form-select form-select-sm border-secondary-subtle">
                                            <option value="Belum Diproses" {{ $pesanan->status_pesanan == 'Belum Diproses' ? 'selected' : '' }}>Belum Diproses</option>
                                            <option value="Dalam Proses" {{ $pesanan->status_pesanan == 'Dalam Proses' ? 'selected' : '' }}>Dalam Proses</option>
                                            <option value="Selesai" {{ $pesanan->status_pesanan == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="Diambil" {{ $pesanan->status_pesanan == 'Diambil' ? 'selected' : '' }}>Diambil</option>
                                        </select>
                                    </form>

                                    <div class="dropdown w-100">
                                        <button class="btn btn-outline-primary btn-sm w-100 dropdown-toggle d-flex align-items-center justify-content-center gap-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-printer"></i> Kwitansi
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                                            <li><a class="dropdown-item d-flex align-items-center gap-2 small" href="/pesanan/{{ $pesanan->id_pesanan }}/kwitansi/dp" target="_blank"><i class="bi bi-receipt text-primary"></i> Cetak Kwitansi DP</a></li>
                                            <li><a class="dropdown-item d-flex align-items-center gap-2 small" href="/pesanan/{{ $pesanan->id_pesanan }}/kwitansi/pelunasan" target="_blank"><i class="bi bi-check2-circle text-success"></i> Cetak Kwitansi Pelunasan</a></li>
                                        </ul>
                                    </div>

                                    <form action="/pesanan/{{ $pesanan->id_pesanan }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus pesanan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-1">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection