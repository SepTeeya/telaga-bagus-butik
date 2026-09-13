@extends('layouts.main')

@section('content')

    @php
        $nama_bulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        $label_bulan_ini = $nama_bulan[sprintf('%02d', $bulan)] ?? $bulan;
    @endphp

    <!-- HEADER DAN FILTER PERIODE -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                <i class="bi bi-bar-chart-line-fill text-primary"></i> Laporan & Analisis Butik
            </h3>
            <p class="text-muted small m-0 mt-1">Periode: <strong>{{ $label_bulan_ini }} {{ $tahun }}</strong></p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- Form Filter Bulan & Tahun -->
            <form action="/laporan" method="GET" class="d-flex align-items-center gap-2 m-0">
                <input type="hidden" name="tab" value="{{ $tab }}">
                
                <select name="bulan" class="form-select form-select-sm shadow-sm" onchange="this.form.submit()">
                    @foreach($nama_bulan as $k => $v)
                        <option value="{{ $k }}" {{ sprintf('%02d', $bulan) == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>

                <select name="tahun" class="form-select form-select-sm shadow-sm" onchange="this.form.submit()">
                    @for($t = date('Y'); $t >= date('Y') - 3; $t--)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endfor
                </select>
            </form>

            <!-- Tombol Cetak -->
            <a href="/laporan/cetak?bulan={{ $bulan }}&tahun={{ $tahun }}&type={{ $tab }}" target="_blank" class="btn btn-outline-primary btn-sm px-3 d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-printer"></i> Cetak Laporan
            </a>
        </div>
    </div>

    <!-- TAB NAVIGASI LAPORAN -->
    <ul class="nav nav-pills mb-4 border-bottom pb-2 gap-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'keuangan' ? 'active bg-primary fw-bold' : 'text-secondary bg-light' }} rounded-pill px-3 py-2" href="/laporan?tab=keuangan&bulan={{ $bulan }}&tahun={{ $tahun }}">
                <i class="bi bi-cash-stack me-1"></i> Laporan Keuangan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'pesanan' ? 'active bg-primary fw-bold' : 'text-secondary bg-light' }} rounded-pill px-3 py-2" href="/laporan?tab=pesanan&bulan={{ $bulan }}&tahun={{ $tahun }}">
                <i class="bi bi-journal-check me-1"></i> Pesanan Masuk
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'penjahit' ? 'active bg-primary fw-bold' : 'text-secondary bg-light' }} rounded-pill px-3 py-2" href="/laporan?tab=penjahit&bulan={{ $bulan }}&tahun={{ $tahun }}">
                <i class="bi bi-person-badge me-1"></i> Kinerja Penjahit
            </a>
        </li>
    </ul>

    <!-- CONTENT TAB 1: LAPORAN KEUANGAN -->
    @if($tab == 'keuangan')
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="text-muted small fw-medium">Total Omzet (Nilai Pesanan)</div>
                    <div class="fs-4 fw-bold text-dark mt-1">Rp {{ number_format($total_omzet, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="text-muted small fw-medium">Total DP Masuk</div>
                    <div class="fs-4 fw-bold text-primary mt-1">Rp {{ number_format($total_dp, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="text-muted small fw-medium">Sisa Piutang / Pelunasan</div>
                    <div class="fs-4 fw-bold text-danger mt-1">Rp {{ number_format($total_sisa_piutang, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="text-muted small fw-medium">Pemasukan Lunas</div>
                    <div class="fs-4 fw-bold text-success mt-1">Rp {{ number_format($total_lunas, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold m-0 text-dark">Rincian Keuangan Pesanan ({{ $label_bulan_ini }} {{ $tahun }})</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 tabel-butik">
                        <thead class="bg-light text-secondary text-uppercase small border-bottom">
                            <tr>
                                <th class="ps-3" style="width: 50px;">No</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Model Baju</th>
                                <th class="text-end">DP</th>
                                <th class="text-end">Sisa</th>
                                <th class="text-end">Total Biaya</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanans as $index => $pesanan)
                                @php
                                    $total_biaya = ($pesanan->dp ?? 0) + ($pesanan->sisa_pembayaran ?? 0);
                                @endphp
                                <tr>
                                    <td class="ps-3 text-muted small">{{ $index + 1 }}</td>
                                    <td class="small">{{ \Carbon\Carbon::parse($pesanan->tgl_pesan)->format('d/m/Y') }}</td>
                                    <td><strong class="text-dark">{{ $pesanan->pelanggan->nama_pelanggan ?? '-' }}</strong></td>
                                    <td class="small">{{ $pesanan->model_baju }}</td>
                                    <td class="text-end text-primary fw-semibold small">Rp {{ number_format($pesanan->dp ?? 0, 0, ',', '.') }}</td>
                                    <td class="text-end text-danger fw-semibold small">Rp {{ number_format($pesanan->sisa_pembayaran ?? 0, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold text-dark small">Rp {{ number_format($total_biaya, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if($pesanan->status_pesanan == 'Diambil' || $pesanan->status_pesanan == 'Selesai')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Lunas / Selesai</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">Belum Lunas</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Belum ada transaksi di bulan {{ $label_bulan_ini }} {{ $tahun }}.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- CONTENT TAB 2: LAPORAN PESANAN MASUK -->
    @if($tab == 'pesanan')
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="text-muted small fw-medium">Total Pesanan Masuk</div>
                    <div class="fs-4 fw-bold text-dark mt-1">{{ $pesanans->count() }} <span class="fs-6 text-muted font-normal">Pesanan</span></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="text-muted small fw-medium">Belum Diproses</div>
                    <div class="fs-4 fw-bold text-secondary mt-1">{{ $pesanan_belum_diproses }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="text-muted small fw-medium">Dalam Proses Jahit</div>
                    <div class="fs-4 fw-bold text-primary mt-1">{{ $pesanan_dalam_proses }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                    <div class="text-muted small fw-medium">Selesai / Diambil</div>
                    <div class="fs-4 fw-bold text-success mt-1">{{ $pesanan_selesai + $pesanan_diambil }}</div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold m-0 text-dark">Daftar Pesanan Masuk Periode {{ $label_bulan_ini }} {{ $tahun }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 tabel-butik">
                        <thead class="bg-light text-secondary text-uppercase small border-bottom">
                            <tr>
                                <th class="ps-3" style="width: 50px;">No</th>
                                <th>Pelanggan</th>
                                <th>Model Baju & Bahan</th>
                                <th>Tgl Pesan</th>
                                <th>Tgl Deadline</th>
                                <th>Penjahit PIC</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanans as $index => $pesanan)
                                <tr>
                                    <td class="ps-3 text-muted small">{{ $index + 1 }}</td>
                                    <td><strong class="text-dark">{{ $pesanan->pelanggan->nama_pelanggan ?? '-' }}</strong></td>
                                    <td class="small">
                                        <span class="fw-semibold">{{ $pesanan->model_baju }}</span><br>
                                        <span class="text-muted">Bahan: {{ $pesanan->bahan }}</span>
                                    </td>
                                    <td class="small">{{ \Carbon\Carbon::parse($pesanan->tgl_pesan)->format('d/m/Y') }}</td>
                                    <td class="small">{{ \Carbon\Carbon::parse($pesanan->tgl_deadline)->format('d/m/Y') }}</td>
                                    <td class="small">
                                        @if($pesanan->penjahit)
                                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle"><i class="bi bi-person me-1"></i>{{ $pesanan->penjahit->nama_user }}</span>
                                        @else
                                            <span class="text-muted fs-7">Belum Ditentukan</span>
                                        @endif
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
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Belum ada data pesanan masuk di bulan {{ $label_bulan_ini }} {{ $tahun }}.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- CONTENT TAB 3: LAPORAN KINERJA PENJAHIT -->
    @if($tab == 'penjahit')
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold m-0 text-dark">Analisis Kinerja Penjahit ({{ $label_bulan_ini }} {{ $tahun }})</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 tabel-butik">
                        <thead class="bg-light text-secondary text-uppercase small border-bottom">
                            <tr>
                                <th class="ps-3" style="width: 50px;">No</th>
                                <th>Nama Penjahit</th>
                                <th class="text-center">Total Jahitan</th>
                                <th class="text-center">Dalam Proses</th>
                                <th class="text-center">Selesai Tepat Waktu</th>
                                <th class="text-center">Terlambat</th>
                                <th class="text-center" style="min-width: 180px;">Performa Ketepatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kinerja_penjahit as $index => $k)
                                <tr>
                                    <td class="ps-3 text-muted small">{{ $index + 1 }}</td>
                                    <td>
                                        <strong class="text-dark d-flex align-items-center gap-2">
                                            <i class="bi bi-person-badge text-primary"></i> {{ $k->nama_penjahit }}
                                        </strong>
                                    </td>
                                    <td class="text-center fw-bold fs-6">{{ $k->total_ditangani }}</td>
                                    <td class="text-center"><span class="badge bg-primary-subtle text-primary px-2 py-1">{{ $k->dalam_proses }}</span></td>
                                    <td class="text-center"><span class="badge bg-success-subtle text-success px-2 py-1 fw-bold">{{ $k->tepat_waktu }}</span></td>
                                    <td class="text-center"><span class="badge bg-danger-subtle text-danger px-2 py-1">{{ $k->terlambat }}</span></td>
                                    <td class="text-center pe-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                <div class="progress-bar {{ $k->persentase_tepat_waktu >= 80 ? 'bg-success' : ($k->persentase_tepat_waktu >= 50 ? 'bg-warning' : 'bg-danger') }}" role="progressbar" style="width: {{ $k->persentase_tepat_waktu }}%;" aria-valuenow="{{ $k->persentase_tepat_waktu }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="fw-bold small text-dark" style="min-width: 40px;">{{ $k->persentase_tepat_waktu }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Belum ada penjahit terdaftar dalam sistem.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

@endsection
