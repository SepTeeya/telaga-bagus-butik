@extends('layouts.main')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold m-0 d-flex align-items-center gap-2 text-dark">
                <i class="bi bi-receipt-cutoff text-primary"></i> Menu Cetak Kwitansi
            </h3>
            <p class="text-muted small m-0 mt-1">Pilih pesanan untuk mencetak Kwitansi Uang Muka (DP) atau Kwitansi Pelunasan.</p>
        </div>

        <!-- Form Pencarian -->
        <form action="/kwitansi" method="GET" class="m-0" style="flex-grow: 1; max-width: 400px;">
            <div class="input-group search-group shadow-sm rounded-3 overflow-hidden">
                <span class="input-group-text bg-white text-muted ps-3"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control ps-1" placeholder="Cari nama pelanggan / model..." value="{{ request('search') }}">
                @if(request('search'))
                    <a href="/kwitansi" class="btn btn-white text-muted d-flex align-items-center pe-2" title="Reset Pencarian"><i class="bi bi-x-circle-fill"></i></a>
                @endif
                <button class="btn btn-primary fw-semibold px-3 d-flex align-items-center gap-1" type="submit">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary text-uppercase small font-monospace border-bottom">
                        <tr>
                            <th class="ps-3 py-3" style="width: 50px;">No</th>
                            <th class="py-3">Pelanggan</th>
                            <th class="py-3">No. HP</th>
                            <th class="py-3">Tgl Pesan</th>
                            <th class="py-3">Jenis / Model Baju</th>
                            <th class="py-3 text-end">DP (Uang Muka)</th>
                            <th class="py-3 text-end">Sisa Pembayaran</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="pe-3 py-3 text-center" style="min-width: 220px;">Cetak Kwitansi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($pesanans as $index => $pesanan)
                            <tr>
                                <td class="ps-3 text-muted small fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $pesanan->pelanggan->nama_pelanggan ?? '-' }}</div>
                                </td>
                                <td class="text-muted small">{{ $pesanan->pelanggan->no_hp ?? '-' }}</td>
                                <td class="text-muted small">{{ \Carbon\Carbon::parse($pesanan->tgl_pesan)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="fw-medium text-dark">{{ $pesanan->model_baju }}</span>
                                    <span class="text-muted small d-block">Bahan: {{ $pesanan->bahan }}</span>
                                </td>
                                <td class="text-end fw-semibold text-primary">Rp {{ number_format($pesanan->dp ?? 0, 0, ',', '.') }}</td>
                                <td class="text-end fw-semibold text-danger">Rp {{ number_format($pesanan->sisa_pembayaran ?? 0, 0, ',', '.') }}</td>
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
                                <td class="pe-3">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="/pesanan/{{ $pesanan->id_pesanan }}/kwitansi/dp" target="_blank" class="btn btn-sm btn-outline-primary fw-medium px-2 py-1 d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-printer"></i> Cetak DP
                                        </a>
                                        <a href="/pesanan/{{ $pesanan->id_pesanan }}/kwitansi/pelunasan" target="_blank" class="btn btn-sm btn-outline-success fw-medium px-2 py-1 d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-check2-circle"></i> Cetak Pelunasan
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox text-secondary fs-3 d-block mb-2"></i>
                                    Data pesanan tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
