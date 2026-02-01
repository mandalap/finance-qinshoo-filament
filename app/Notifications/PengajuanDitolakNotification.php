<?php

namespace App\Notifications;

use App\Models\PengajuanBarang;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanDitolakNotification extends Notification
{
    use Queueable;

    public function __construct(
        public PengajuanBarang $pengajuan,
        public string $alasan
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengajuan Ditolak - ' . $this->pengajuan->nomor_pengajuan)
            ->greeting('Halo ' . $this->pengajuan->nama_pengaju . '!')
            ->line('Mohon maaf, pengajuan barang Anda telah **DITOLAK**.')
            ->line('**Nomor Pengajuan:** ' . $this->pengajuan->nomor_pengajuan)
            ->line('**Ditolak oleh:** ' . $this->pengajuan->approver?->name)
            ->line('**Tanggal:** ' . $this->pengajuan->tanggal_persetujuan?->format('d/m/Y H:i'))
            ->line('**Alasan Penolakan:** ' . $this->alasan)
            ->line('Silakan hubungi bagian terkait untuk informasi lebih lanjut.')
            ->line('Terima kasih!');
    }

    public function toArray($notifiable): array
    {
        return [
            'pengajuan_id' => $this->pengajuan->id,
            'nomor_pengajuan' => $this->pengajuan->nomor_pengajuan,
            'status' => 'rejected',
            'alasan' => $this->alasan,
            'message' => 'Pengajuan ' . $this->pengajuan->nomor_pengajuan . ' ditolak',
        ];
    }
}
