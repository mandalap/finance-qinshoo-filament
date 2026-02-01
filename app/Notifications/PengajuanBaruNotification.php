<?php

namespace App\Notifications;

use App\Models\PengajuanBarang;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanBaruNotification extends Notification
{
    use Queueable;

    public function __construct(
        public PengajuanBarang $pengajuan
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengajuan Barang Baru - ' . $this->pengajuan->nomor_pengajuan)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Ada pengajuan barang baru yang perlu Anda review.')
            ->line('**Nomor Pengajuan:** ' . $this->pengajuan->nomor_pengajuan)
            ->line('**Nama Pengaju:** ' . $this->pengajuan->nama_pengaju)
            ->line('**Divisi:** ' . $this->pengajuan->divisi)
            ->line('**Tingkat Urgensi:** ' . $this->pengajuan->tingkat_urgensi->getLabel())
            ->line('**Tujuan:** ' . $this->pengajuan->tujuan_pengajuan)
            ->action('Lihat Detail Pengajuan', url('/admin/pengajuan-barangs/' . $this->pengajuan->uuid))
            ->line('Terima kasih!');
    }

    public function toArray($notifiable): array
    {
        return [
            'pengajuan_id' => $this->pengajuan->id,
            'nomor_pengajuan' => $this->pengajuan->nomor_pengajuan,
            'nama_pengaju' => $this->pengajuan->nama_pengaju,
            'divisi' => $this->pengajuan->divisi,
            'tingkat_urgensi' => $this->pengajuan->tingkat_urgensi->value,
            'message' => 'Pengajuan barang baru dari ' . $this->pengajuan->nama_pengaju,
        ];
    }
}
