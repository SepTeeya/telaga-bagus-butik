@extends('layouts.main')

@section('content')

    <!-- HEADER DAN TOMBOL AKSI -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                <i class="bi bi-layers-half text-primary"></i> Inventori Kain Butik
            </h3>
            <p class="text-muted small m-0 mt-1">Kelola stok persediaan bahan kain, variasi warna, dan pemantauan ketersediaan meteran.</p>
        </div>

        <button type="button" class="btn btn-primary fw-semibold shadow-sm px-3 py-2 d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahKain">
            <i class="bi bi-plus-circle-fill fs-6"></i> Kain Baru
        </button>
    </div>

    <!-- NOTIFIKASI SUKSES ATAU VALIDASI -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-octagon-fill me-2"></i> <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1 ps-3 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- KARTU RINGKASAN STATISTIK STOK -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2 bg-primary-subtle text-primary">
                        <i class="bi bi-collection-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Total Jenis Kain</div>
                        <div class="fs-5 fw-bold text-dark">{{ $total_variasi }} <span class="fs-6 font-normal text-muted">Variasi</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2 bg-info-subtle text-info">
                        <i class="bi bi-rulers fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Total Stok Bahan</div>
                        <div class="fs-5 fw-bold text-dark">{{ number_format($total_meter, 1, ',', '.') }} <span class="fs-6 font-normal text-muted">Meter</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2 bg-warning-subtle text-warning-emphasis">
                        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Stok Menipis (≤ 5m)</div>
                        <div class="fs-5 fw-bold {{ $stok_menipis > 0 ? 'text-warning' : 'text-dark' }}">{{ $stok_menipis }} <span class="fs-6 font-normal text-muted">Bahan</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2 bg-danger-subtle text-danger">
                        <i class="bi bi-x-circle-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Stok Habis (0m)</div>
                        <div class="fs-5 fw-bold {{ $stok_habis > 0 ? 'text-danger' : 'text-dark' }}">{{ $stok_habis }} <span class="fs-6 font-normal text-muted">Bahan</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PENCARIAN & FILTER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-3">
        <!-- Filter Status Ketersediaan -->
        <div class="d-flex gap-2">
            <a href="/kain" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-light border text-muted' }} rounded-pill px-3">
                Semua Bahan ({{ $total_variasi }})
            </a>
            <a href="/kain?status=menipis" class="btn btn-sm {{ request('status') == 'menipis' ? 'btn-warning text-dark fw-bold' : 'btn-light border text-muted' }} rounded-pill px-3">
                Menipis ({{ $stok_menipis }})
            </a>
            <a href="/kain?status=habis" class="btn btn-sm {{ request('status') == 'habis' ? 'btn-danger fw-bold' : 'btn-light border text-muted' }} rounded-pill px-3">
                Habis ({{ $stok_habis }})
            </a>
            <a href="/kain?status=tersedia" class="btn btn-sm {{ request('status') == 'tersedia' ? 'btn-success fw-bold' : 'btn-light border text-muted' }} rounded-pill px-3">
                Tersedia
            </a>
        </div>

        <!-- Form Pencarian -->
        <form action="/kain" method="GET" class="m-0" style="flex-grow: 1; max-width: 380px;">
            <div class="input-group search-group shadow-sm rounded-3 overflow-hidden">
                <span class="input-group-text bg-white text-muted ps-3"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control ps-1" placeholder="Cari nama kain, kode, atau warna..." value="{{ request('search') }}">
                @if(request('search'))
                    <a href="/kain" class="btn btn-white text-muted d-flex align-items-center pe-2" title="Reset"><i class="bi bi-x-circle-fill"></i></a>
                @endif
                <button class="btn btn-primary fw-semibold px-3" type="submit">Cari</button>
            </div>
        </form>
    </div>

    <!-- TABEL INVENTORI KAIN -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 tabel-butik">
                    <thead class="bg-light text-secondary text-uppercase small font-monospace border-bottom">
                        <tr class="text-center">
                            <th style="width: 50px;">No</th>
                            <th>Kode</th>
                            <th>Nama Kain & Jenis</th>
                            <th>Warna</th>
                            <th>Stok Tersedia</th>
                            <th>Status Ketersediaan</th>
                            <th>Harga / Satuan</th>
                            <th style="width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kains as $index => $kain)
                            <tr>
                                <td class="text-center text-muted small fw-semibold">{{ $index + 1 }}</td>
                                <td class="text-center">
                                    <span class="badge bg-dark-subtle text-dark border border-dark-subtle font-monospace px-2 py-1">
                                        {{ $kain->kode_kain }}
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-dark">{{ $kain->nama_kain }}</strong>
                                    @if($kain->jenis_kain)
                                        <span class="badge bg-light text-muted border ms-1">{{ $kain->jenis_kain }}</span>
                                    @endif
                                    @if($kain->keterangan)
                                        <div class="text-muted small mt-1"><i class="bi bi-info-circle me-1"></i>{{ $kain->keterangan }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($kain->warna)
                                        <span class="d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-circle-fill text-primary" style="font-size: 0.65rem;"></i> {{ $kain->warna }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="fs-6 fw-bold {{ $kain->stok <= 0 ? 'text-danger' : ($kain->stok <= 5 ? 'text-warning' : 'text-dark') }}">
                                        {{ number_format($kain->stok, 2, ',', '.') }}
                                    </span>
                                    <span class="text-muted small">{{ $kain->satuan }}</span>
                                </td>
                                <td class="text-center">
                                    @if($kain->stok <= 0)
                                        <span class="badge-status badge-status-danger">
                                            <i class="bi bi-x-circle-fill"></i> Stok Habis
                                        </span>
                                    @elseif($kain->stok <= 5)
                                        <span class="badge-status badge-status-warning" title="Perlu restock segera!">
                                            <i class="bi bi-exclamation-triangle-fill"></i> Stok Menipis
                                        </span>
                                    @else
                                        <span class="badge-status badge-status-success">
                                            <i class="bi bi-check-circle-fill"></i> Tersedia
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end small pe-3">
                                    @if($kain->harga_per_satuan)
                                        <span class="text-dark fw-semibold">Rp {{ number_format($kain->harga_per_satuan, 0, ',', '.') }}</span>
                                        <span class="text-muted d-block fs-7">/{{ $kain->satuan }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <!-- Tombol Edit Modal -->
                                        <button type="button" class="btn btn-sm btn-outline-primary px-2 py-1" data-bs-toggle="modal" data-bs-target="#modalEditKain{{ $kain->id_kain }}" title="Ubah Data / Stok">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form action="/kain/{{ $kain->id_kain }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus bahan kain ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger px-2 py-1" title="Hapus Bahan">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- MODAL EDIT KAIN PER BARIS -->
                                    <div class="modal fade" id="modalEditKain{{ $kain->id_kain }}" tabindex="-1" aria-labelledby="modalEditLabel{{ $kain->id_kain }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg rounded-4">
                                                <div class="modal-header bg-light border-bottom">
                                                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="modalEditLabel{{ $kain->id_kain }}">
                                                        <i class="bi bi-pencil-square text-primary"></i> Edit Bahan Kain
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="/kain/{{ $kain->id_kain }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body p-4">
                                                        <div class="row g-3">
                                                            <div class="col-md-5">
                                                                <label class="form-label">Kode Kain</label>
                                                                <input type="text" name="kode_kain" class="form-control font-monospace" value="{{ $kain->kode_kain }}" required>
                                                            </div>
                                                            <div class="col-md-7">
                                                                <label class="form-label">Nama Kain</label>
                                                                <input type="text" name="nama_kain" class="form-control" value="{{ $kain->nama_kain }}" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Jenis / Tipe Kain</label>
                                                                <input type="text" name="jenis_kain" class="form-control" value="{{ $kain->jenis_kain }}" placeholder="Contoh: Katun, Sutra">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Warna</label>
                                                                <input type="text" name="warna" class="form-control" value="{{ $kain->warna }}" placeholder="Contoh: Navy, Maroon">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Stok Persediaan</label>
                                                                <input type="number" step="0.01" name="stok" class="form-control fw-bold" value="{{ $kain->stok }}" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Satuan</label>
                                                                <select name="satuan" class="form-select">
                                                                    <option value="Meter" {{ $kain->satuan == 'Meter' ? 'selected' : '' }}>Meter</option>
                                                                    <option value="Yard" {{ $kain->satuan == 'Yard' ? 'selected' : '' }}>Yard</option>
                                                                    <option value="Roll" {{ $kain->satuan == 'Roll' ? 'selected' : '' }}>Roll</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label class="form-label">Harga Beli / Modal per Satuan (Rp)</label>
                                                                <input type="number" name="harga_per_satuan" class="form-control" value="{{ $kain->harga_per_satuan }}" placeholder="0">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label class="form-label">Catatan / Keterangan</label>
                                                                <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan supplier, lokasi rak, dll.">{{ $kain->keterangan }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light border-top">
                                                        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold">
                                                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-layers text-secondary fs-2 d-block mb-2"></i>
                                    <div class="fw-semibold">Belum ada data inventori kain.</div>
                                    <small>Klik tombol "+ Tambah Bahan Kain Baru" di atas untuk menambahkan bahan kain pertama Anda.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH KAIN BARU -->
    <div class="modal fade" id="modalTambahKain" tabindex="-1" aria-labelledby="modalTambahKainLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="modalTambahKainLabel">
                        <i class="bi bi-plus-circle-fill text-primary"></i> Tambah Bahan Kain Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/kain" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label">Kode Kain</label>
                                <input type="text" name="kode_kain" class="form-control font-monospace" value="{{ $saran_kode }}" required placeholder="KN-001">
                            </div>
                            <div class="col-md-7">
                                <label class="form-label">Nama Kain</label>
                                <input type="text" name="nama_kain" class="form-control" placeholder="Contoh: Katun Toyobo Fodu" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jenis / Kategori Kain</label>
                                <input type="text" name="jenis_kain" class="form-control" placeholder="Contoh: Katun, Sutra, Brokat">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Warna Kain</label>
                                <input type="text" name="warna" class="form-control" placeholder="Contoh: Midnight Blue, Sage">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jumlah Stok Awal</label>
                                <input type="number" step="0.01" name="stok" class="form-control" placeholder="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Satuan Ukuran</label>
                                <select name="satuan" class="form-select">
                                    <option value="Meter" selected>Meter</option>
                                    <option value="Yard">Yard</option>
                                    <option value="Roll">Roll</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Harga Beli / Satuan (Rp) (Opsional)</label>
                                <input type="number" name="harga_per_satuan" class="form-control" placeholder="Contoh: 45000">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Catatan Tambahan (Opsional)</label>
                                <textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: Beli di Toko Kain Agung, Rak B-2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top">
                        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold">
                            <i class="bi bi-check-circle-fill me-1"></i> Simpan Bahan Kain
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

