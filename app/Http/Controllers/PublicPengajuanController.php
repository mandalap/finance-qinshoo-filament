<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicPengajuanController extends Controller
{
    public function create()
    {
        return view('public.pengajuan-form');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pengaju' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'kontak' => 'nullable|string|max:255',
            'tujuan_pengajuan' => 'required|string',
            'tanggal_dibutuhkan' => 'required|date|after_or_equal:today',
            'tingkat_urgensi' => 'required|in:normal,mendesak',
            
            // Validasi array barang
            'barang' => 'required|array|min:1',
            'barang.*.nama_barang' => 'required|string|max:255',
            'barang.*.spesifikasi_barang' => 'required|string',
            'barang.*.jumlah' => 'required|integer|min:1',
            'barang.*.satuan' => 'required|string|max:50',
            'barang.*.estimasi_harga' => 'required|numeric|min:0',
        ], [
            'nama_pengaju.required' => 'Nama pengaju wajib diisi',
            'divisi.required' => 'Divisi wajib diisi',
            'jabatan.required' => 'Jabatan wajib diisi',
            'tujuan_pengajuan.required' => 'Tujuan pengajuan wajib diisi',
            'tanggal_dibutuhkan.required' => 'Tanggal dibutuhkan wajib diisi',
            'tanggal_dibutuhkan.after_or_equal' => 'Tanggal dibutuhkan tidak boleh kurang dari hari ini',
            'tingkat_urgensi.required' => 'Tingkat urgensi wajib dipilih',
            
            'barang.required' => 'Minimal harus ada 1 barang',
            'barang.*.nama_barang.required' => 'Nama barang wajib diisi',
            'barang.*.spesifikasi_barang.required' => 'Spesifikasi barang wajib diisi',
            'barang.*.jumlah.required' => 'Jumlah wajib diisi',
            'barang.*.jumlah.min' => 'Jumlah minimal 1',
            'barang.*.satuan.required' => 'Satuan wajib diisi',
            'barang.*.estimasi_harga.required' => 'Estimasi harga wajib diisi',
            'barang.*.estimasi_harga.min' => 'Estimasi harga tidak boleh negatif',
        ]);
        
        // Create pengajuan (header)
        $pengajuan = \App\Models\PengajuanBarang::create([
            'nama_pengaju' => $validated['nama_pengaju'],
            'divisi' => $validated['divisi'],
            'jabatan' => $validated['jabatan'],
            'kontak' => $validated['kontak'],
            'tujuan_pengajuan' => $validated['tujuan_pengajuan'],
            'tanggal_dibutuhkan' => $validated['tanggal_dibutuhkan'],
            'tingkat_urgensi' => $validated['tingkat_urgensi'],
            'status' => 'pending',
        ]);
        
        // Create detail barang (items)
        foreach ($validated['barang'] as $barang) {
            $pengajuan->detailBarang()->create([
                'nama_barang' => $barang['nama_barang'],
                'spesifikasi_barang' => $barang['spesifikasi_barang'],
                'jumlah' => $barang['jumlah'],
                'satuan' => $barang['satuan'],
                'estimasi_harga' => $barang['estimasi_harga'],
            ]);
        }
        
        return redirect()->route('pengajuan.success', $pengajuan->nomor_pengajuan);
    }
    
    public function success($nomorPengajuan)
    {
        $pengajuan = \App\Models\PengajuanBarang::where('nomor_pengajuan', $nomorPengajuan)->firstOrFail();
        return view('public.pengajuan-success', compact('pengajuan'));
    }

    public function print(mixed $uuid)
    {
        // Handle uuid logic if passed as Route model binding or raw string
        $pengajuan = \App\Models\PengajuanBarang::where('uuid', $uuid)->firstOrFail();
        
        return view('public.pengajuan-print', compact('pengajuan'));
    }
}
