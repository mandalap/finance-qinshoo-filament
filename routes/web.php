<?php

use App\Http\Controllers\PublicPengajuanController;

Route::get('/', [PublicPengajuanController::class, 'create'])->name('pengajuan.create');
Route::post('/pengajuan', [PublicPengajuanController::class, 'store'])->name('pengajuan.store');

Route::get('/pengajuan/sukses/{nomorPengajuan}', [PublicPengajuanController::class, 'success'])
    ->where('nomorPengajuan', '.*')
    ->name('pengajuan.success');

Route::get('/pengajuan/cetak/{uuid}', [PublicPengajuanController::class, 'print'])
    ->name('pengajuan.print');

use App\Http\Controllers\PublicTransaksiController;
Route::get('/transaksi/cetak/{id}', [PublicTransaksiController::class, 'print'])
    ->name('transaksi.print');