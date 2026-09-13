@extends('layouts.main')

@section('content')
    
    <div class="row mb-4">
        <!-- Menyapa Penjahit menggunakan nama asli mereka -->
        <h2 class="fw-bold">Halo, {{ Auth::user()->nama_user }}!</h2>
        <p class="text-muted">Berikut adalah daftar antrean pesanan yang ditugaskan khusus kepada Anda.</p>
    </div>

    <!-- Form Pencarian Penjahit -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-4">
            <form action="" method="GET">
                <div class="input-group search-group shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-white text-muted ps-3"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control ps-1" placeholder="Cari pesanan..." value="{{ request('search') }}">
                    @if(request('search'))
                        <a href="{{ url()->current() }}" class="btn btn-white text-muted d-flex align-items-center pe-2" title="Reset Pencarian"><i class="bi bi-x-circle-fill"></i></a>
                    @endif
                    <button class="btn btn-primary fw-semibold px-3" type="submit">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- KOTAK RESPONSIVE TABEL -->
    <div class="table-responsive shadow-sm bg-white rounded p-3">
        
        <table class="table table-bordered table-hover align-middle tabel-butik">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Pelanggan</th>
                    <th style="min-width: 120px">Tgl Pesan</th>
                    <th style="min-width: 120px">Tgl Deadline</th>
                    <th>Detail Baju & Ukuran</th>
                    <th>Penjahit PIC</th>
                    <th>Peringatan</th>
                    <th style="min-width: 150px">Ubah Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $index => $pesanan)
                <tr class="{{ $pesanan->warna_baris }}">
                    
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $pesanan->pelanggan->nama_pelanggan }}</strong></td>
                    
                    <td>{{ date('d-m-Y', strtotime($pesanan->tgl_pesan)) }}</td>
                    <td>{{ date('d-m-Y', strtotime($pesanan->tgl_deadline)) }}</td>
                    
                    <!-- Kolom Harga Dihapus -->
                    
                    <td>
                        <!-- Desain Kartu Ukuran -->
                        <div class="kartu-ukuran">
                            <strong>Bahan:</strong> {{ $pesanan->bahan }} <br>
                            <strong>Model:</strong> {{ $pesanan->model_baju }} <br>
                            <span class="teks-catatan">Catatan:</span> {{ $pesanan->catatan ?? '-' }}
                            <hr>
                            <div class="row row-cols-2 g-1">
                                <div class="col">P: {{ $pesanan->ukuran->P ?? '-' }}</div>
                                <div class="col">Bahu: {{ $pesanan->ukuran->Bahu ?? '-' }}</div>
                                <div class="col">Dada: {{ $pesanan->ukuran->Dada ?? '-' }}</div>
                                <div class="col">Perut: {{ $pesanan->ukuran->Perut ?? '-' }}</div>
                                <div class="col">Pinggul: {{ $pesanan->ukuran->Pinggul ?? '-' }}</div>
                                <div class="col">PT: {{ $pesanan->ukuran->PT ?? '-' }}</div>
                                <div class="col">LT: {{ $pesanan->ukuran->LT ?? '-' }}</div>
                                <div class="col">Ketiak: {{ $pesanan->ukuran->Ketiak ?? '-' }}</div>
                                @if(!empty($pesanan->ukuran->Leher)) <div class="col">Leher: {{ $pesanan->ukuran->Leher }}</div> @endif
                                @if(!empty($pesanan->ukuran->bet_kebaya)) <div class="col">Bet Kebaya: {{ $pesanan->ukuran->bet_kebaya }}</div> @endif
                                @if(!empty($pesanan->ukuran->LP)) <div class="col">LP: {{ $pesanan->ukuran->LP }}</div> @endif
                        </div>
                    </td>

                    <td class="text-center">
                        @if($pesanan->id_penjahit == Auth::id())
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fw-bold">
                                <i class="bi bi-person-check-fill"></i> Tugas Anda
                            </span>
                        @elseif($pesanan->penjahit)
                            <span class="badge bg-light text-secondary border px-2 py-1">
                                <i class="bi bi-person"></i> {{ $pesanan->penjahit->nama_user }}
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">
                                <i class="bi bi-clock"></i> Belum Ada PIC
                            </span>
                        @endif
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

                    <!-- KOLOM AKSI (Hanya bisa ubah status) -->
                    <td>
                        @if($pesanan->status_pesanan == 'Diambil')
                            <!-- Jika sudah diambil, Penjahit hanya melihat lencana, tidak bisa mengubah -->
                            <span class="badge bg-success">Sudah Diserahkan Kasir</span>
                        @else
                            <form action="/pesanan/{{ $pesanan->id_pesanan }}/status" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status_pesanan" onchange="this.form.submit()" class="form-select form-select-sm">
                                    <option value="Belum Diproses" {{ $pesanan->status_pesanan == 'Belum Diproses' ? 'selected' : '' }}>Belum Diproses</option>
                                    <option value="Dalam Proses" {{ $pesanan->status_pesanan == 'Dalam Proses' ? 'selected' : '' }}>Sedang Dijahit</option>
                                    <option value="Selesai" {{ $pesanan->status_pesanan == 'Selesai' ? 'selected' : '' }}>Selesai / Siap!</option>
                                </select>
                            </form>
                        @endif
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-2 text-secondary d-block mb-2"></i>
                        <div class="fw-semibold">Belum ada pesanan yang ditugaskan kepada Anda.</div>
                        <small class="text-muted">Pesanan yang ditugaskan oleh Admin akan otomatis muncul di sini.</small>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

@endsection