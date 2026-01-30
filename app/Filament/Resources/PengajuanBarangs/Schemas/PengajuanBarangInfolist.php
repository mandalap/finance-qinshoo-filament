<?php

namespace App\Filament\Resources\PengajuanBarangs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Schemas\Schema;

class PengajuanBarangInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengajuan')
                    ->schema([
                        TextEntry::make('nomor_pengajuan')
                            ->label('Nomor Pengajuan')
                            ->weight('bold')
                            ->size('lg'),
                        TextEntry::make('tanggal_pengajuan')
                            ->label('Tanggal Pengajuan')
                            ->date('d F Y'),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state->getLabel())
                            ->color(fn ($state) => $state->getColor()),
                    ])
                    ->columns(3),
                    
                Section::make('Data Pengaju')
                    ->schema([
                        TextEntry::make('nama_pengaju')
                            ->label('Nama Lengkap'),
                        TextEntry::make('divisi')
                            ->label('Divisi / Bidang'),
                        TextEntry::make('jabatan')
                            ->label('Jabatan'),
                        TextEntry::make('kontak')
                            ->label('Kontak')
                            ->placeholder('-'),
                    ])
                    ->columns(2),
                    
                Section::make('Detail Barang')
                    ->schema([
                        TextEntry::make('detailBarang')
                            ->label('')
                            ->formatStateUsing(function ($record) {
                                $html = '<div style="width: 100%;">';
                                foreach ($record->detailBarang as $index => $barang) {
                                    $html .= '<div style="background: #f8f9fa; border: 1.5px solid #e9ecef; border-radius: 8px; padding: 14px; margin-bottom: 10px;">';
                                    $html .= '<div style="font-weight: 700; color: #667eea; margin-bottom: 8px; font-size: 0.95rem;">' . ($index + 1) . '. ' . e($barang->nama_barang) . '</div>';
                                    $html .= '<div style="margin-bottom: 6px; font-size: 0.9rem;"><strong>Spesifikasi:</strong> ' . e($barang->spesifikasi_barang) . '</div>';
                                    $html .= '<div style="margin-bottom: 6px; font-size: 0.9rem;"><strong>Jumlah:</strong> ' . e($barang->jumlah) . ' ' . e($barang->satuan) . '</div>';
                                    $html .= '<div style="font-size: 0.9rem;"><strong>Estimasi Harga:</strong> Rp ' . number_format($barang->estimasi_harga, 0, ',', '.') . '</div>';
                                    $html .= '</div>';
                                }
                                
                                // Total
                                $total = $record->detailBarang->sum('estimasi_harga');
                                $html .= '<div style="background: #e3f2fd; border-left: 3px solid #2196f3; padding: 12px; border-radius: 5px; margin-top: 8px;">';
                                $html .= '<div style="display: flex; justify-content: space-between; align-items: center;">';
                                $html .= '<strong style="color: #1976d2; font-size: 1rem;">Total Estimasi:</strong>';
                                $html .= '<strong style="color: #1976d2; font-size: 1.05rem;">Rp ' . number_format($total, 0, ',', '.') . '</strong>';
                                $html .= '</div>';
                                $html .= '</div>';
                                
                                $html .= '</div>';
                                return new \Illuminate\Support\HtmlString($html);
                            })
                            ->columnSpanFull(),
                    ]),
                    
                Section::make('Kebutuhan')
                    ->schema([
                        TextEntry::make('tujuan_pengajuan')
                            ->label('Tujuan Pengajuan')
                            ->columnSpanFull(),
                        TextEntry::make('tanggal_dibutuhkan')
                            ->label('Tanggal Dibutuhkan')
                            ->date('d F Y'),
                        TextEntry::make('tingkat_urgensi')
                            ->label('Tingkat Urgensi')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state->getLabel())
                            ->color(fn ($state) => $state->getColor()),
                    ])
                    ->columns(2),
                    
                Section::make('Informasi Persetujuan')
                    ->schema([
                        TextEntry::make('approver.name')
                            ->label('Disetujui Oleh')
                            ->placeholder('-'),
                        TextEntry::make('tanggal_persetujuan')
                            ->label('Tanggal Persetujuan')
                            ->dateTime('d F Y, H:i')
                            ->placeholder('-'),
                        TextEntry::make('catatan_persetujuan')
                            ->label('Catatan Persetujuan')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => $record->status->value !== 'pending'),
                    
                Section::make('Bukti Transaksi')
                    ->schema([
                        TextEntry::make('bukti_transaksi')
                            ->label('Bukti Transaksi')
                            ->formatStateUsing(function ($state, $record) {
                                if (!$state) {
                                    return 'Belum ada bukti transaksi';
                                }
                                
                                $url = asset('storage/' . $state);
                                $filename = basename($state);
                                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                                
                                // Jika gambar, tampilkan sebagai image
                                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                    return new \Illuminate\Support\HtmlString(
                                        '<div style="margin-top: 10px;">
                                            <a href="' . $url . '" target="_blank" style="display: inline-block;">
                                                <img src="' . $url . '" alt="Bukti Transaksi" style="max-width: 500px; max-height: 500px; border: 1px solid #ddd; border-radius: 8px; padding: 5px;">
                                            </a>
                                            <div style="margin-top: 8px;">
                                                <a href="' . $url . '" target="_blank" style="color: #3b82f6; text-decoration: none;">
                                                    📎 ' . $filename . ' (Klik untuk melihat)
                                                </a>
                                            </div>
                                        </div>'
                                    );
                                }
                                
                                // Jika PDF atau file lain, tampilkan sebagai link
                                return new \Illuminate\Support\HtmlString(
                                    '<div style="margin-top: 10px;">
                                        <a href="' . $url . '" target="_blank" style="color: #3b82f6; text-decoration: none; font-weight: 500;">
                                            📎 ' . $filename . ' (Klik untuk melihat)
                                        </a>
                                    </div>'
                                );
                            })
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => $record->status->value === 'approved'),
            ]);
    }
}
