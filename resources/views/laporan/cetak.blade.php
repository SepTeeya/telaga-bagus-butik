<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - Telaga Bagus Butik</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #111;
            background-color: #fff;
        }
        .header-kop {
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
            margin-bottom: 24px;
        }
        .tabel-laporan th, .tabel-laporan td {
            font-size: 13px;
            padding: 6px 8px;
            border: 1px solid #444;
        }
        .tabel-laporan th {
            background-color: #f2f2f2 !important;
            text-align: center;
            font-weight: bold;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
            @page {
                size: A4;
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body class="p-4">

    <!-- Tombol Aksi di Layar -->
    <div class="no-print mb-4 d-flex justify-content-between align-items-center bg-light p-3 rounded border">
        <div>
            <h6 class="fw-bold m-0 text-dark">Pratinjau Cetak Laporan</h6>
            <span class="text-muted small">Silakan klik tombol cetak untuk mencetak atau menyimpan sebagai PDF.</span>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-primary btn-sm px-4 fw-bold">
                <i class="bi bi-printer me-1"></i> Cetak Dokumen
            </button>
            <button onclick="window.close()" class="btn btn-outline-secondary btn-sm px-3">
                Tutup
            </button>
        </div>
    </div>

    <!-- KOP RESMI LAPORAN -->
    <div class="header-kop text-center">
        <h2 class="fw-bold m-0 tracking-wide text-uppercase" style="letter-spacing: 2px;">TELAGA BAGUS BUTIK</h2>
        <p class="m-0 small">Spesialis Jahit Busana Adat Pria & Wanita</p>
        <p class="m-0 small text-muted">Jl. Seroja No. 48,Tonja, Kec. Denpasar Utara, Kota Denpasar, Telp / WA: 0823-4288-1519</p>
    </div>

    <!-- JUDUL LAPORAN -->
    <div class="text-center mb-4">
        <h4 class="fw-bold text-uppercase m-0">
            @if($type == 'keuangan')
                LAPORAN KEUANGAN & PEMASUKAN BUTIK
            @elseif($type == 'pesanan')
                LAPORAN JUMLAH PESANAN MASUK
            @elseif($type == 'penjahit')
                LAPORAN KINERJA & PRODUKTIVITAS PENJAHIT
            @else
                LAPORAN REKAPITULASI BUTIK
            @endif
        </h4>
        <div class="small fw-semibold mt-1">Periode: {{ $label_bulan }} {{ $tahun }}</div>
    </div>

    <!-- 1. LAPORAN KEUANGAN -->
    @if($type == 'keuangan')
        <!-- Ringkasan Keuangan -->
        <div class="row mb-4">
            <div class="col-6">
                <table class="table table-sm table-borderless small mb-0">
                    <tr>
                        <td style="width: 170px;">Total Omzet Pesanan</td>
                        <td>: <strong>Rp {{ number_format($total_omzet, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td>Total Uang Muka (DP)</td>
                        <td>: <strong>Rp {{ number_format($total_dp, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>
            <div class="col-6">
                <table class="table table-sm table-borderless small mb-0">
                    <tr>
                        <td style="width: 170px;">Sisa Piutang / Pending</td>
                        <td>: <strong>Rp {{ number_format($total_sisa_piutang, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td>Total Pendapatan Lunas</td>
                        <td>: <strong>Rp {{ number_format($total_lunas, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <table class="table table-bordered tabel-laporan w-100">
            <thead>
                <tr>
                    <th style="width: 35px;">No</th>
                    <th>Tgl Pesan</th>
                    <th>Nama Pelanggan</th>
                    <th>Model Busana</th>
                    <th class="text-end">DP (Rp)</th>
                    <th class="text-end">Sisa (Rp)</th>
                    <th class="text-end">Total Biaya (Rp)</th>
                    <th>Status Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $index => $p)
                    @php
                        $biaya = ($p->dp ?? 0) + ($p->sisa_pembayaran ?? 0);
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($p->tgl_pesan)->format('d/m/Y') }}</td>
                        <td>{{ $p->pelanggan->nama_pelanggan ?? '-' }}</td>
                        <td>{{ $p->model_baju }}</td>
                        <td class="text-end">{{ number_format($p->dp ?? 0, 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($p->sisa_pembayaran ?? 0, 0, ',', '.') }}</td>
                        <td class="text-end fw-bold">{{ number_format($biaya, 0, ',', '.') }}</td>
                        <td class="text-center">
                            {{ in_array($p->status_pesanan, ['Selesai', 'Diambil']) ? 'Lunas' : 'Belum Lunas' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Tidak ada data transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="fw-bold bg-light">
                    <td colspan="4" class="text-center">TOTAL KESELURUHAN</td>
                    <td class="text-end">{{ number_format($total_dp, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($total_sisa_piutang, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($total_omzet, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    @endif

    <!-- 2. LAPORAN PESANAN MASUK -->
    @if($type == 'pesanan')
        <div class="mb-3 small">
            <strong>Total Pesanan Masuk:</strong> {{ $pesanans->count() }} Pesanan
        </div>

        <table class="table table-bordered tabel-laporan w-100">
            <thead>
                <tr>
                    <th style="width: 35px;">No</th>
                    <th>Pelanggan</th>
                    <th>Model Busana & Bahan</th>
                    <th>Tgl Pesan</th>
                    <th>Target Selesai</th>
                    <th>Penjahit PIC</th>
                    <th>Status Pengerjaan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $index => $p)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td><strong>{{ $p->pelanggan->nama_pelanggan ?? '-' }}</strong></td>
                        <td>{{ $p->model_baju }} ({{ $p->bahan }})</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($p->tgl_pesan)->format('d/m/Y') }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($p->tgl_deadline)->format('d/m/Y') }}</td>
                        <td>{{ $p->penjahit->nama_user ?? 'Belum Ditentukan' }}</td>
                        <td class="text-center">{{ $p->status_pesanan }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Tidak ada data pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <!-- 3. LAPORAN KINERJA PENJAHIT -->
    @if($type == 'penjahit')
        <table class="table table-bordered tabel-laporan w-100">
            <thead>
                <tr>
                    <th style="width: 35px;">No</th>
                    <th>Nama Penjahit</th>
                    <th class="text-center">Total Ditangani</th>
                    <th class="text-center">Dalam Pengerjaan</th>
                    <th class="text-center">Selesai Tepat Waktu</th>
                    <th class="text-center">Terlambat</th>
                    <th class="text-center">Ketepatan Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kinerja_penjahit as $index => $k)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td><strong>{{ $k->nama_penjahit }}</strong></td>
                        <td class="text-center">{{ $k->total_ditangani }}</td>
                        <td class="text-center">{{ $k->dalam_proses }}</td>
                        <td class="text-center">{{ $k->tepat_waktu }}</td>
                        <td class="text-center">{{ $k->terlambat }}</td>
                        <td class="text-center fw-bold">{{ $k->persentase_tepat_waktu }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Tidak ada data penjahit.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <!-- TANDA TANGAN -->
    <div class="row mt-5 pt-3">
        <div class="col-7"></div>
        <div class="col-5 text-center small">
            <div>Denpasar, {{ date('d') }} {{ $label_bulan }} {{ date('Y') }}</div>
            <div class="mb-5 pb-4">Pimpinan / Pengelola Telaga Bagus Butik</div>
            <div class="fw-bold text-decoration-underline">( {{ Auth::user()->nama_user ?? 'Administrator' }} )</div>
        </div>
    </div>

</body>
</html>

