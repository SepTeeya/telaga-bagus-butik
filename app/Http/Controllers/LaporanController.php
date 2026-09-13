<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->isOwner()) {
            return redirect('/pesanan')->with('error', 'Akses Ditolak: Halaman Laporan Keuangan & Analisis hanya dapat diakses oleh Pemilik Butik (Owner).');
        }

        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $tab   = $request->input('tab', 'keuangan');

        // Query Pesanan berdasarkan bulan & tahun pesanan
        $pesanans = Pesanan::with(['pelanggan', 'user', 'penjahit', 'ukuran'])
            ->whereMonth('tgl_pesan', $bulan)
            ->whereYear('tgl_pesan', $tahun)
            ->orderBy('tgl_pesan', 'desc')
            ->get();

        // 1. STATISTIK KEUANGAN
        $total_omzet        = $pesanans->sum(function($p) { return ($p->dp ?? 0) + ($p->sisa_pembayaran ?? 0); });
        $total_dp           = $pesanans->sum('dp');
        $total_sisa_piutang = $pesanans->sum('sisa_pembayaran');
        $total_lunas        = $pesanans->whereIn('status_pesanan', ['Selesai', 'Diambil'])
                                      ->sum(function($p) { return ($p->dp ?? 0) + ($p->sisa_pembayaran ?? 0); });

        // 2. STATISTIK PESANAN MASUK
        $pesanan_belum_diproses = $pesanans->where('status_pesanan', 'Belum Diproses')->count();
        $pesanan_dalam_proses   = $pesanans->where('status_pesanan', 'Dalam Proses')->count();
        $pesanan_selesai        = $pesanans->where('status_pesanan', 'Selesai')->count();
        $pesanan_diambil        = $pesanans->where('status_pesanan', 'Diambil')->count();

        // 3. STATISTIK KINERJA PENJAHIT
        $penjahits = User::where('role', 'penjahit')->get();
        $kinerja_penjahit = [];

        foreach ($penjahits as $penjahit) {
            // Pesanan yang ditangani penjahit ini di bulan & tahun terpilih
            $jahitan_penjahit = Pesanan::where('id_penjahit', $penjahit->id_user)
                ->whereMonth('tgl_pesan', $bulan)
                ->whereYear('tgl_pesan', $tahun)
                ->get();

            $total_ditangani = $jahitan_penjahit->count();
            $selesai         = $jahitan_penjahit->whereIn('status_pesanan', ['Selesai', 'Diambil']);
            $dalam_proses    = $jahitan_penjahit->where('status_pesanan', 'Dalam Proses')->count();
            $belum_diproses  = $jahitan_penjahit->where('status_pesanan', 'Belum Diproses')->count();

            $tepat_waktu = 0;
            $terlambat   = 0;

            foreach ($selesai as $p) {
                $deadline  = Carbon::parse($p->tgl_deadline)->endOfDay();
                $completed = Carbon::parse($p->updated_at);
                
                if ($completed->lte($deadline)) {
                    $tepat_waktu++;
                } else {
                    $terlambat++;
                }
            }

            $persentase_tepat_waktu = $total_ditangani > 0 ? round(($tepat_waktu / max(1, $selesai->count())) * 100) : 0;

            $kinerja_penjahit[] = (object) [
                'nama_penjahit'          => $penjahit->nama_user,
                'total_ditangani'        => $total_ditangani,
                'dalam_proses'           => $dalam_proses,
                'belum_diproses'         => $belum_diproses,
                'selesai'                => $selesai->count(),
                'tepat_waktu'            => $tepat_waktu,
                'terlambat'              => $terlambat,
                'persentase_tepat_waktu' => $persentase_tepat_waktu,
            ];
        }

        return view('laporan.index', compact(
            'pesanans', 'bulan', 'tahun', 'tab',
            'total_omzet', 'total_dp', 'total_sisa_piutang', 'total_lunas',
            'pesanan_belum_diproses', 'pesanan_dalam_proses', 'pesanan_selesai', 'pesanan_diambil',
            'kinerja_penjahit'
        ));
    }

    public function cetak(Request $request)
    {
        if (!Auth::user()->isOwner()) {
            return redirect('/pesanan')->with('error', 'Akses Ditolak: Pencetakan Laporan Keuangan hanya dapat dilakukan oleh Pemilik Butik (Owner).');
        }

        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $type  = $request->input('type', 'keuangan'); // keuangan | pesanan | penjahit

        $pesanans = Pesanan::with(['pelanggan', 'user', 'penjahit', 'ukuran'])
            ->whereMonth('tgl_pesan', $bulan)
            ->whereYear('tgl_pesan', $tahun)
            ->orderBy('tgl_pesan', 'desc')
            ->get();

        $total_omzet        = $pesanans->sum(function($p) { return ($p->dp ?? 0) + ($p->sisa_pembayaran ?? 0); });
        $total_dp           = $pesanans->sum('dp');
        $total_sisa_piutang = $pesanans->sum('sisa_pembayaran');
        $total_lunas        = $pesanans->whereIn('status_pesanan', ['Selesai', 'Diambil'])
                                      ->sum(function($p) { return ($p->dp ?? 0) + ($p->sisa_pembayaran ?? 0); });

        $penjahits = User::where('role', 'penjahit')->get();
        $kinerja_penjahit = [];

        foreach ($penjahits as $penjahit) {
            $jahitan_penjahit = Pesanan::where('id_penjahit', $penjahit->id_user)
                ->whereMonth('tgl_pesan', $bulan)
                ->whereYear('tgl_pesan', $tahun)
                ->get();

            $total_ditangani = $jahitan_penjahit->count();
            $selesai         = $jahitan_penjahit->whereIn('status_pesanan', ['Selesai', 'Diambil']);
            $dalam_proses    = $jahitan_penjahit->where('status_pesanan', 'Dalam Proses')->count();

            $tepat_waktu = 0;
            $terlambat   = 0;

            foreach ($selesai as $p) {
                $deadline  = Carbon::parse($p->tgl_deadline)->endOfDay();
                $completed = Carbon::parse($p->updated_at);
                
                if ($completed->lte($deadline)) {
                    $tepat_waktu++;
                } else {
                    $terlambat++;
                }
            }

            $persentase_tepat_waktu = $total_ditangani > 0 ? round(($tepat_waktu / max(1, $selesai->count())) * 100) : 0;

            $kinerja_penjahit[] = (object) [
                'nama_penjahit'          => $penjahit->nama_user,
                'total_ditangani'        => $total_ditangani,
                'dalam_proses'           => $dalam_proses,
                'selesai'                => $selesai->count(),
                'tepat_waktu'            => $tepat_waktu,
                'terlambat'              => $terlambat,
                'persentase_tepat_waktu' => $persentase_tepat_waktu,
            ];
        }

        $nama_bulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        $label_bulan = $nama_bulan[sprintf('%02d', $bulan)] ?? $bulan;

        return view('laporan.cetak', compact(
            'pesanans', 'bulan', 'tahun', 'type', 'label_bulan',
            'total_omzet', 'total_dp', 'total_sisa_piutang', 'total_lunas',
            'kinerja_penjahit'
        ));
    }
}
