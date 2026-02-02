<?php

namespace App\Filament\Resources\TransaksiKeuangans\Pages;

use App\Filament\Resources\TransaksiKeuangans\TransaksiKeuanganResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaksiKeuangan extends CreateRecord
{
    protected static string $resource = TransaksiKeuanganResource::class;
    
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $maxAttempts = 3;
        $attempt = 0;
        
        while ($attempt < $maxAttempts) {
            try {
                return parent::handleRecordCreation($data);
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                $attempt++;
                
                // Check if it's the transaction number that's duplicate
                if (str_contains($e->getMessage(), 'nomor_transaksi')) {
                    if ($attempt >= $maxAttempts) {
                        // Last attempt failed, throw error
                        throw $e;
                    }
                    
                    // Regenerate transaction number with unique suffix
                    $year = date('Y');
                    $month = date('m');
                    $microtime = (int)(microtime(true) * 10000);
                    $suffix = base_convert($microtime, 10, 36);
                    $data['nomor_transaksi'] = sprintf('TRX-%s-%s-%s', $year, $month, strtoupper($suffix));
                    
                    // Small delay before retry
                    usleep(50000); // 50ms
                } else {
                    // Different unique constraint, throw immediately
                    throw $e;
                }
            }
        }
        
        // This should never be reached, but just in case
        return parent::handleRecordCreation($data);
    }
    
    protected function afterCreate(): void
    {
        // Clear cache untuk widget stats
        cache()->forget('keuangan_stats_' . md5('_'));
        
        // Dispatch event untuk refresh widgets di dashboard
        $this->dispatch('refresh-widgets');
    }
}
