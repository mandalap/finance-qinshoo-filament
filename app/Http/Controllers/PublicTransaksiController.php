<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicTransaksiController extends Controller
{
    public function print($id)
    {
        $transaksi = \App\Models\TransaksiKeuangan::with(['kategori', 'creator'])->findOrFail($id);

        return view('public.transaksi-print', compact('transaksi'));
    }
}
