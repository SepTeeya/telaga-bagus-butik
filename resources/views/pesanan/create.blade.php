@extends('layouts.main')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                <i class="bi bi-plus-circle-fill text-primary"></i> Tambah Pesanan Baru
            </h3>
            <p class="text-muted small m-0 mt-1">Isi formulir di bawah ini untuk mencatat pesanan pakaian baru dan detail ukurannya.</p>
        </div>
        <a href="/pesanan" class="btn btn-outline-secondary btn-sm px-3 d-inline-flex align-items-center gap-1 shadow-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <!-- Pesan Sukses jika ada -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="/pesanan/simpan" method="POST">
        @csrf

        <!-- 1. DATA PELANGGAN -->
        <div class="form-section-card">
            <div class="form-section-header">
                <h5 class="form-section-title">
                    <i class="bi bi-person-lines-fill text-primary fs-5"></i> 1. Data Pelanggan
                </h5>
            </div>
            <div class="form-section-body">
                <div class="mb-4">
                    <label class="form-label d-block text-muted small text-uppercase fw-bold mb-2">Pilih Metode Pelanggan</label>
                    <div class="btn-group" role="group" aria-label="Status Pelanggan">
                        <input type="radio" class="btn-check" name="status_pelanggan" id="pelanggan_lama" value="lama" checked onchange="aturFormPelanggan()">
                        <label class="btn btn-outline-primary px-3 py-2" for="pelanggan_lama">
                            <i class="bi bi-people-fill me-1"></i> Pilih dari Pelanggan Terdaftar
                        </label>

                        <input type="radio" class="btn-check" name="status_pelanggan" id="pelanggan_baru" value="baru" onchange="aturFormPelanggan()">
                        <label class="btn btn-outline-primary px-3 py-2" for="pelanggan_baru">
                            <i class="bi bi-person-plus-fill me-1"></i> Input Pelanggan Baru
                        </label>
                    </div>
                </div>

                <div id="form_pilih_lama" class="mb-2" style="max-width: 480px;">
                    <label class="form-label">Nama Pelanggan:</label>
                    <select name="id_pelanggan" class="form-select">
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($pelanggans as $pelanggan)
                            <option value="{{ $pelanggan->id_pelanggan }}">{{ $pelanggan->nama_pelanggan }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="form_input_baru" class="row g-3" style="display: none;">
                    <div class="col-md-4">
                        <label class="form-label">Nama Pelanggan Baru</label>
                        <input type="text" name="nama_pelanggan" class="form-control" placeholder="Ketik nama lengkap...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 08123456789">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" class="form-control" placeholder="Ketik alamat...">
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. DETAIL PESANAN & PEMBAYARAN -->
        <div class="form-section-card">
            <div class="form-section-header">
                <h5 class="form-section-title">
                    <i class="bi bi-journal-text text-primary fs-5"></i> 2. Detail Pesanan & Pembayaran
                </h5>
            </div>
            <div class="form-section-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Bahan Pakaian</label>
                        <input type="text" name="bahan" class="form-control" placeholder="Contoh: Katun Toyobo, Sutra, dll." required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis / Model Baju</label>
                        <input type="text" name="model_baju" class="form-control" placeholder="Contoh: Kemeja Batik, Kebaya Modern, dll." required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Pesan</label>
                        <input type="date" name="tgl_pesan" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Deadline (Target Selesai)</label>
                        <input type="date" name="tgl_deadline" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Total Biaya (Rp)</label>
                        <input type="number" id="total_biaya" name="total_biaya" class="form-control" placeholder="0" onkeyup="hitungSisa()">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Uang Muka / DP (Rp)</label>
                        <input type="number" id="dp" name="dp" class="form-control text-primary fw-semibold" placeholder="0" onkeyup="hitungSisa()">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sisa Pembayaran (Rp)</label>
                        <input type="number" id="sisa_pembayaran" name="sisa_pembayaran" class="form-control bg-light text-danger fw-bold" readonly placeholder="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tugaskan Penjahit PIC (Opsional)</label>
                        <select name="id_penjahit" class="form-select">
                            <option value="">-- Belum Ditentukan (Bisa Diproses Siapa Saja) --</option>
                            @foreach($penjahits as $penjahit)
                                <option value="{{ $penjahit->id_user }}">{{ $penjahit->nama_user }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. DETAIL UKURAN PAKAIAN -->
        <div class="form-section-card">
            <div class="form-section-header">
                <h5 class="form-section-title">
                    <i class="bi bi-ruler text-primary fs-5"></i> 3. Detail Ukuran Pakaian (cm)
                </h5>
            </div>
            <div class="form-section-body">
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <label class="form-label">Panjang (P)</label>
                        <input type="number" name="P" class="form-control" placeholder="cm">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Bahu</label>
                        <input type="number" name="BahuP" class="form-control" placeholder="cm">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Dada</label>
                        <input type="number" name="Dada" class="form-control" placeholder="cm">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Perut</label>
                        <input type="number" name="Perut" class="form-control" placeholder="cm">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Pinggul</label>
                        <input type="number" name="Pinggul" class="form-control" placeholder="cm">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Panjang Tangan (PT)</label>
                        <input type="number" name="PT" class="form-control" placeholder="cm">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Lingkar Tangan (LT)</label>
                        <input type="number" name="LT" class="form-control" placeholder="cm">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Ketiak</label>
                        <input type="number" name="Ketiak" class="form-control" placeholder="cm">
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label">Leher</label>
                        <input type="text" name="Leher" class="form-control" placeholder="cm">
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label text-primary">Bet Kebaya</label>
                        <input type="text" name="bet_kebaya" class="form-control" placeholder="cm">
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label text-primary">Lingkar Pinggang (LP)</label>
                        <input type="number" name="LP" class="form-control" placeholder="cm">
                    </div>
                </div>

                <div>
                    <label class="form-label">Catatan Khusus / Permintaan Tambahan</label>
                    <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Kerah kemeja sanghai, tambahkan furing..."></textarea>
                </div>
            </div>
        </div>

        <!-- TOMBOL SIMPAN / BATAL -->
        <div class="d-flex justify-content-end align-items-center gap-2 mb-5">
            <a href="/pesanan" class="btn btn-light border fw-semibold px-4 py-2">
                <i class="bi bi-x-circle me-1"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary fw-bold px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill"></i> Simpan Pesanan
            </button>
        </div>
    </form>

    <script>
        // Logika 1: Menampilkan form yang tepat berdasarkan pilihan pelanggan
        function aturFormPelanggan() {
            let status = document.querySelector('input[name="status_pelanggan"]:checked').value;
            if(status === 'lama') {
                document.getElementById('form_pilih_lama').style.display = 'block';
                document.getElementById('form_input_baru').style.display = 'none';
            } else {
                document.getElementById('form_pilih_lama').style.display = 'none';
                document.getElementById('form_input_baru').style.display = 'flex';
            }
        }

        // Logika 2: Menghitung Sisa Pembayaran Otomatis
        function hitungSisa() {
            let total = document.getElementById('total_biaya').value;
            let dp = document.getElementById('dp').value;
            
            total = total ? parseInt(total) : 0;
            dp = dp ? parseInt(dp) : 0;
            
            let sisa = total - dp;
            document.getElementById('sisa_pembayaran').value = sisa;
        }       
    </script>

@endsection