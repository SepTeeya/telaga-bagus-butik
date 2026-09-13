<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;

class PenjahitController extends Controller
{
    public function indexPenjahit(Request $request)
    {
        $katakunci = $request->search;
        $id_penjahit = Auth::id();

        // Hanya mengambil pesanan yang ditugaskan kepada penjahit yang sedang login
        $pesanans = Pesanan::with(['pelanggan', 'ukuran', 'penjahit'])
            ->where('id_penjahit', $id_penjahit)
            ->when($katakunci, function ($query, $katakunci) {
                return $query->where(function ($sub) use ($katakunci) {
                    $sub->whereHas('pelanggan', function ($q) use ($katakunci) {
                        $q->where('nama_pelanggan', 'like', '%' . $katakunci . '%');
                    })
                    ->orWhere('model_baju', 'like', '%' . $katakunci . '%');
                });
            })
            ->orderBy('tgl_deadline', 'asc')
            ->get();

        foreach ($pesanans as $pesanan) {
            $hari_ini = \Carbon\Carbon::now()->startOfDay();
            $deadline = \Carbon\Carbon::parse($pesanan->tgl_deadline)->startOfDay();
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

        return view('pesanan.penjahit_index', compact('pesanans'));
    }
}