<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi DP #{{ $pesanan->id_pesanan }} - Telaga Bagus Butik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
        }
        .kwitansi-card {
            max-width: 780px;
            margin: 40px auto;
            background: #ffffff;
            padding: 36px 40px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
        }
        .kop-title {
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #0f172a;
        }
        .stamp-dp {
            border: 2px solid #2563eb;
            color: #2563eb;
            padding: 6px 16px;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 6px;
            background-color: #eff6ff;
            transform: rotate(-3deg);
        }
        .label-muted {
            color: #64748b;
            font-size: 0.875rem;
        }
        .val-text {
            color: #0f172a;
            font-weight: 600;
            font-size: 0.9rem;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #ffffff;
            }
            .kwitansi-card {
                margin: 0;
                border: 1px solid #000;
                box-shadow: none;
                width: 100%;
                max-width: 100%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Tombol Aksi di Luar Print -->
    <div class="container text-center my-4 no-print">
        <button onclick="window.print()" class="btn btn-primary fw-semibold px-4 shadow-sm me-2 d-inline-flex align-items-center gap-2">
            <i class="bi bi-printer"></i> Cetak Kwitansi
        </button>
        <button onclick="window.close()" class="btn btn-outline-secondary fw-semibold px-3 d-inline-flex align-items-center gap-1">
            <i class="bi bi-x-lg"></i> Tutup
        </button>
    </div>

    <div class="kwitansi-card">
        <!-- Header Kop Butik -->
        <div class="row align-items-center pb-3 mb-4 border-bottom">
            <div class="col-7">
                <h3 class="kop-title mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-scissors text-primary"></i> TELAGA BAGUS BUTIK
                </h3>
                <span class="text-muted small">Jasa Jahit & Busana Adat Professional</span>
            </div>
            <div class="col-5 text-end">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 fw-bold fs-6">KWITANSI DP</span>
                <div class="text-muted small mt-1 font-monospace">No: KW-DP/{{ date('Ymd', strtotime($pesanan->tgl_pesan)) }}/{{ $pesanan->id_pesanan }}</div>
            </div>
        </div>

        <!-- Info Pelanggan & Tanggal -->
        <div class="row mb-4 bg-light rounded-3 p-3 mx-0 border border-slate-100">
            <div class="col-6">
                <div class="mb-2">
                    <span class="label-muted">Diterima Dari:</span>
                    <div class="val-text fs-6">{{ $pesanan->pelanggan->nama_pelanggan ?? '-' }}</div>
                </div>
                <div>
                    <span class="label-muted">No. HP / WA:</span>
                    <div class="val-text">{{ $pesanan->pelanggan->no_hp ?? '-' }}</div>
                </div>
            </div>
            <div class="col-6 text-end">
                <div class="mb-2">
                    <span class="label-muted">Tgl. Pesan:</span>
                    <div class="val-text">{{ \Carbon\Carbon::parse($pesanan->tgl_pesan)->translatedFormat('d F Y') }}</div>
                </div>
                <div>
                    <span class="label-muted">Estimasi Selesai (Deadline):</span>
                    <div class="val-text text-danger">{{ \Carbon\Carbon::parse($pesanan->tgl_deadline)->translatedFormat('d F Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Detail Rincian Pesanan -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle mb-0">
                <thead class="bg-light text-secondary small text-uppercase font-monospace">
                    <tr>
                        <th class="py-2">Jenis Pesanan / Model</th>
                        <th class="py-2">Bahan / Kain</th>
                        <th class="py-2 text-end" style="width: 200px;">Jumlah Uang Muka (DP)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold text-dark">{{ $pesanan->model_baju }}</td>
                        <td>{{ $pesanan->bahan }}</td>
                        <td class="text-end fs-5 fw-bold text-primary">
                            Rp {{ number_format($pesanan->dp ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Stempel & Ringkasan Keuangan -->
        <div class="row align-items-center my-4">
            <div class="col-6">
                <div class="stamp-dp">
                    <i class="bi bi-patch-check-fill"></i> DP TELAH DITERIMA
                </div>
            </div>
            <div class="col-6">
                <div class="border rounded-3 p-3 bg-white">
                    <div class="d-flex justify-content-between mb-1 small text-muted">
                        <span>Total Estimasi Harga:</span>
                        <span class="fw-semibold text-dark">Rp {{ number_format(($pesanan->dp ?? 0) + ($pesanan->sisa_pembayaran ?? 0), 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="fw-bold text-primary">DP Dibayar:</span>
                        <span class="fw-bold text-primary">Rp {{ number_format($pesanan->dp ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between pt-2 border-top fw-bold">
                        <span class="text-danger">Sisa Pembayaran:</span>
                        <span class="text-danger">Rp {{ number_format($pesanan->sisa_pembayaran ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tanda Tangan -->
        <div class="row text-center mt-5 pt-3">
            <div class="col-6">
                <p class="text-muted small mb-5">Pelanggan,</p>
                <p class="fw-semibold text-dark border-top d-inline-block px-4 pt-1 mb-0">{{ $pesanan->pelanggan->nama_pelanggan ?? 'Pelanggan' }}</p>
            </div>
            <div class="col-6">
                <p class="text-muted small mb-5">Kasir / Penerima,</p>
                <p class="fw-semibold text-dark border-top d-inline-block px-4 pt-1 mb-0">{{ Auth::user()->name ?? 'Admin' }}</p>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top text-center text-muted small">
            <i class="bi bi-info-circle me-1"></i> Kwitansi ini adalah bukti sah pembayaran Uang Muka (DP). Harap disimpan untuk pelunasan saat pengambilan barang.
        </div>
    </div>

</body>
</html>
