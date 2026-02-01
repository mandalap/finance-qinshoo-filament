<?php

namespace App\Notifications;

use App\Models\PengajuanBarang;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanDisetujuiNotification extends Notification
{
    use Queueable;

    public function __construct(
        public PengajuanBarang $pengajuan,
        public string $catatan = ''
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Pengajuan Disetujui - ' . $this->pengajuan->nomor_pengajuan)
            ->greeting('Halo ' . $this->pengajuan->nama_pengaju . '!')
            ->line('Pengajuan barang Anda telah **DISETUJUI**.')
            ->line('**Nomor Pengajuan:** ' . $this->pengajuan->nomor_pengajuan)
            ->line('**Disetujui oleh:** ' . $this->pengajuan->approver?->name)
            ->line('**Tanggal Persetujuan:** ' . $this->pengajuan->tanggal_persetujuan?->format('d/m/Y H:i'));

        if ($this->catatan) {
            $message->line('**Catatan:** ' . $this->catatan);
        }

        return $message
            ->action('Lihat Detail', url('/pengajuan/cetak/' . $this->pengajuan->uuid))
            ->line('Terima kasih!');
    }

    public function toArray($notifiable): array
    {
        return [
            'pengajuan_id' => $this->pengajuan->id,
            'nomor_pengajuan' => $this->pengajuan->nomor_pengajuan,
            'status' => 'approved',
            'catatan' => $this->catatan,
            'message' => 'Pengajuan ' . $this->pengajuan->nomor_pengajuan . ' telah disetujui',
        ];
    }
}
