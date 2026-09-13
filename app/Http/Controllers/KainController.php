<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kain;

class KainController extends Controller
{
    public function index(Request $request)
    {
        $katakunci = $request->search;
        $filterStatus = $request->status;

        $kains = Kain::when($katakunci, function ($query, $katakunci) {
                return $query->where(function ($q) use ($katakunci) {
                    $q->where('nama_kain', 'like', '%' . $katakunci . '%')
                      ->orWhere('kode_kain', 'like', '%' . $katakunci . '%')
                      ->orWhere('warna', 'like', '%' . $katakunci . '%')
                      ->orWhere('jenis_kain', 'like', '%' . $katakunci . '%');
                });
            })
            ->when($filterStatus, function ($query, $filterStatus) {
                if ($filterStatus == 'habis') {
                    return $query->where('stok', '<=', 0);
                } elseif ($filterStatus == 'menipis') {
                    return $query->where('stok', '>', 0)->where('stok', '<=', 5);
                } elseif ($filterStatus == 'tersedia') {
                    return $query->where('stok', '>', 5);
                }
            })
            ->orderBy('nama_kain', 'asc')
            ->get();

        // Ringkasan Statistik Inventori
        $total_variasi   = Kain::count();
        $total_meter     = Kain::sum('stok');
        $stok_menipis    = Kain::where('stok', '>', 0)->where('stok', '<=', 5)->count();
        $stok_habis      = Kain::where('stok', '<=', 0)->count();

        // Generate saran kode kain otomatis berikutnya
        $countKain = Kain::count() + 1;
        $saran_kode = 'KN-' . str_pad($countKain, 3, '0', STR_PAD_LEFT);

        return view('kain.index', compact(
            'kains', 'total_variasi', 'total_meter', 'stok_menipis', 'stok_habis', 'saran_kode'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kain' => 'required|unique:kains,kode_kain',
            'nama_kain' => 'required|string|max:255',
            'stok'      => 'required|numeric|min:0',
            'satuan'    => 'required|string',
        ], [
            'kode_kain.unique' => 'Kode kain tersebut sudah digunakan oleh kain lain.',
            'kode_kain.required' => 'Kode kain wajib diisi.',
            'nama_kain.required' => 'Nama kain wajib diisi.',
        ]);

        Kain::create([
            'kode_kain'        => strtoupper($request->kode_kain),
            'nama_kain'        => $request->nama_kain,
            'jenis_kain'       => $request->jenis_kain,
            'warna'            => $request->warna,
            'stok'             => $request->stok,
            'satuan'           => $request->satuan ?: 'Meter',
            'harga_per_satuan' => $request->harga_per_satuan,
            'keterangan'       => $request->keterangan,
        ]);

        return redirect('/kain')->with('success', 'Bahan kain baru berhasil ditambahkan ke inventori!');
    }

    public function update(Request $request, $id)
    {
        $kain = Kain::findOrFail($id);

        $request->validate([
            'kode_kain' => 'required|unique:kains,kode_kain,' . $id . ',id_kain',
            'nama_kain' => 'required|string|max:255',
            'stok'      => 'required|numeric|min:0',
            'satuan'    => 'required|string',
        ], [
            'kode_kain.unique' => 'Kode kain tersebut sudah digunakan oleh kain lain.',
            'kode_kain.required' => 'Kode kain wajib diisi.',
            'nama_kain.required' => 'Nama kain wajib diisi.',
        ]);

        $kain->update([
            'kode_kain'        => strtoupper($request->kode_kain),
            'nama_kain'        => $request->nama_kain,
            'jenis_kain'       => $request->jenis_kain,
            'warna'            => $request->warna,
            'stok'             => $request->stok,
            'satuan'           => $request->satuan ?: 'Meter',
            'harga_per_satuan' => $request->harga_per_satuan,
            'keterangan'       => $request->keterangan,
        ]);

        return redirect('/kain')->with('success', 'Data bahan kain berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kain = Kain::findOrFail($id);
        $nama = $kain->nama_kain;
        $kain->delete();

        return redirect('/kain')->with('success', 'Kain "' . $nama . '" berhasil dihapus dari inventori!');
    }
}

