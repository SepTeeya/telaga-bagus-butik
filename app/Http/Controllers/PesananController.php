<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Pelanggan;
use App\Models\Ukuran;
use App\Models\User;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->isPenjahit()) {
            return redirect('/penjahit/dashboard');
        }

        $katakunci = $request->search;
        // Mengambil semua data pesanan sekaligus merelasikannya dengan tabel pelanggan, penjahit
        $pesanans = Pesanan::with(['pelanggan', 'ukuran', 'penjahit'])
            ->when($katakunci, function ($query, $katakunci) {
                return $query->whereHas('pelanggan', function ($q) use ($katakunci) {
                    $q->where('nama_pelanggan', 'like', '%' . $katakunci . '%');
                })
                ->orWhere('model_baju', 'like', '%' . $katakunci . '%');
            })
            ->orderBy('tgl_deadline', 'asc')
            ->get();

        foreach ($pesanans as $pesanan) {
            $hari_ini = \Carbon\Carbon::now()->startOfDay();
            $deadline = \Carbon\Carbon::parse($pesanan->tgl_deadline)->startOfDay();
            // Hitung selisih hari
            $selisih = (int) $hari_ini->diffInDays($deadline, false);

            if ($pesanan->status_pesanan == 'Selesai' || $pesanan->status_pesanan == 'Diambil') {
                $pesanan->warna_baris = 'status-row-success';
                $pesanan->warning_level = 'success';
                $pesanan->pesan_warning = 'Tuntas';
            } else {
                if ($selisih < 0) {
                    $pesanan->warna_baris = 'status-row-danger';
                    $pesanan->warning_level = 'danger';
                    $pesanan->pesan_warning = 'Terlambat (' . abs($selisih) . ' Hari)';
                } elseif ($selisih >= 0 && $selisih <= 3) {
                    $pesanan->warna_baris = 'status-row-warning';
                    $pesanan->warning_level = 'warning';
                    $pesanan->pesan_warning = ($selisih == 0) ? 'Hari Ini Deadline!' : 'H-' . $selisih . ' Deadline';
                } else {
                    $pesanan->warna_baris = 'status-row-normal';
                    $pesanan->warning_level = 'normal';
                    $pesanan->pesan_warning = 'Aman (H-' . $selisih . ')';
                }
            }
        }

        $penjahits = User::where('role', 'penjahit')->get();
        return view('pesanan.index', compact('pesanans', 'penjahits'));
    }

    // Fungsi untuk penugasan penjahit oleh Admin
    public function tugaskan(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update([
            'id_penjahit' => $request->id_penjahit ?: null
        ]);

        $namaPenjahit = $pesanan->penjahit ? $pesanan->penjahit->nama_user : 'Belum Ditentukan';
        return redirect()->back()->with('success', 'Penanggung jawab jahitan berhasil diatur: ' . $namaPenjahit);
    }

    // 1. Fungsi menampilkan form
    public function create()
    {
        if (Auth::user()->isPenjahit()) {
            return redirect('/penjahit/dashboard');
        }

        $pelanggans = Pelanggan::all(); 
        $penjahits = User::where('role', 'penjahit')->get();
        return view('pesanan.create', compact('pelanggans', 'penjahits'));
    }  
    
    public function store(Request $request)
    {
        // 1. LOGIKA PERCABANGAN PELANGGAN
        if ($request->status_pelanggan == 'baru') {
            $pelanggan = Pelanggan::firstOrcreate(
                [
                    'nama_pelanggan' => $request->nama_pelanggan,
                    'no_hp'          => $request->no_hp,
                ],
                [
                    'alamat'         => $request->alamat
                ]
            );
            $id_pelanggan = $pelanggan->id_pelanggan;
        } else {
            $id_pelanggan = $request->id_pelanggan;
        }

        // 2. MENYIMPAN DATA PESANAN & KEUANGAN
        $pesanan = Pesanan::create([
            'id_pelanggan'    => $id_pelanggan,
            'id_user'         => Auth::id(), // Admin yang menginput
            'id_penjahit'     => $request->id_penjahit ?: null, // Penjahit penanggung jawab
            'tgl_pesan'       => $request->tgl_pesan ?: now(),
            'tgl_deadline'    => $request->tgl_deadline,
            'status_pesanan'  => 'Belum Diproses',
            'dp'              => $request->dp,
            'sisa_pembayaran' => $request->sisa_pembayaran,
            'bahan'           => $request->bahan,
            'model_baju'      => $request->model_baju,
        ]);

        // 3. MENYIMPAN DATA UKURAN
        Ukuran::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'P'          => $request->P,
            'Bahu'       => $request->Bahu,
            'Dada'       => $request->Dada,
            'Perut'      => $request->Perut,
            'Pinggul'    => $request->Pinggul,
            'PT'         => $request->PT,
            'LT'         => $request->LT,
            'Ketiak'     => $request->Ketiak,
            'Leher'      => $request->Leher,
            'bet_kebaya' => $request->bet_kebaya,
            'LP'         => $request->LP,
            'catatan'    => $request->catatan
        ]);

        return redirect('/pesanan')->with('success', 'Pesanan dan Detail Ukuran berhasil ditambahkan!');
    }
    
    // Fungsi untuk memperbarui status pesanan
    public function updateStatus(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $dataUpdate = [
            'status_pesanan' => $request->status_pesanan
        ];

        // Jika diproses/diubah oleh user penjahit, catat id_penjahit
        if (Auth::user()->role == 'penjahit') {
            $dataUpdate['id_penjahit'] = Auth::id();
        } elseif ($request->filled('id_penjahit')) {
            $dataUpdate['id_penjahit'] = $request->id_penjahit;
        }

        $pesanan->update($dataUpdate);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    // Fungsi untuk menghapus data pesanan beserta ukurannya
    public function destroy($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        
        // Hapus data ukuran yang menempel lebih dulu (jika ada)
        if ($pesanan->ukuran) {
            $pesanan->ukuran->delete();
        }
        
        // Hapus data pesanan
        $pesanan->delete();

        return redirect()->back()->with('success', 'Data pesanan berhasil dihapus!');
    }

    // Fungsi untuk menampilkan halaman daftar kwitansi pesanan
    public function kwitansiIndex(Request $request)
    {
        $katakunci = $request->search;
        $pesanans = Pesanan::with(['pelanggan', 'ukuran'])
            ->when($katakunci, function ($query, $katakunci) {
                return $query->whereHas('pelanggan', function ($q) use ($katakunci) {
                    $q->where('nama_pelanggan', 'like', '%' . $katakunci . '%');
                })
                ->orWhere('model_baju', 'like', '%' . $katakunci . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kwitansi.index', compact('pesanans'));
    }

    // Fungsi untuk mencetak kwitansi DP
    public function cetakDp($id)
    {
        $pesanan = Pesanan::with(['pelanggan', 'ukuran', 'user'])->findOrFail($id);
        return view('kwitansi.cetak_dp', compact('pesanan'));
    }

    // Fungsi untuk mencetak kwitansi Pelunasan
    public function cetakPelunasan($id)
    {
        $pesanan = Pesanan::with(['pelanggan', 'ukuran', 'user'])->findOrFail($id);
        return view('kwitansi.cetak_pelunasan', compact('pesanan'));
    }

}
