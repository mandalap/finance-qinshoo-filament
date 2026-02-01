<?php

namespace App\Observers;

use App\Models\PengajuanBarang;
use App\Models\User;
use App\Notifications\PengajuanBaruNotification;
use App\Notifications\PengajuanDisetujuiNotification;
use App\Notifications\PengajuanDitolakNotification;
use Illuminate\Support\Facades\Notification;

class PengajuanBarangObserver
{
    /**
     * Handle the PengajuanBarang "created" event.
     */
    public function created(PengajuanBarang $pengajuanBarang): void
    {
        // Kirim notifikasi ke semua approver saat ada pengajuan baru
        $approvers = User::role(['approver', 'super-admin'])->get();
        
        if ($approvers->count() > 0) {
            Notification::send($approvers, new PengajuanBaruNotification($pengajuanBarang));
        }
    }

    /**
     * Handle the PengajuanBarang "updated" event.
     */
    public function updated(PengajuanBarang $pengajuanBarang): void
    {
        // Cek jika status berubah
        if ($pengajuanBarang->isDirty('status')) {
            $status = $pengajuanBarang->status->value;
            
            // Kirim notifikasi berdasarkan status
            if ($status === 'approved') {
                // Kirim email ke pengaju bahwa pengajuan disetujui
                // Karena pengaju tidak punya akun, kita skip email notification
                // Atau bisa kirim ke email jika ada field email di pengajuan
                
            } elseif ($status === 'rejected') {
                // Kirim email ke pengaju bahwa pengajuan ditolak
                // Skip karena tidak ada email pengaju
            }
        }
    }

    /**
     * Handle the PengajuanBarang "deleted" event.
     */
    public function deleted(PengajuanBarang $pengajuanBarang): void
    {
        //
    }

    /**
     * Handle the PengajuanBarang "restored" event.
     */
    public function restored(PengajuanBarang $pengajuanBarang): void
    {
        //
    }

    /**
     * Handle the PengajuanBarang "force deleted" event.
     */
    public function forceDeleted(PengajuanBarang $pengajuanBarang): void
    {
        //
    }
}
