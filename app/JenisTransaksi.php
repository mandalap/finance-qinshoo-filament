<?php

namespace App;

enum JenisTransaksi: string
{
    case PEMASUKAN = 'pemasukan';
    case PENGELUARAN = 'pengeluaran';
    
    public function getLabel(): string
    {
        return match($this) {
            self::PEMASUKAN => 'Pemasukan',
            self::PENGELUARAN => 'Pengeluaran',
        };
    }
    
    public function getColor(): string
    {
        return match($this) {
            self::PEMASUKAN => 'success',
            self::PENGELUARAN => 'danger',
        };
    }
    
    public function getIcon(): string
    {
        return match($this) {
            self::PEMASUKAN => 'heroicon-o-arrow-trending-up',
            self::PENGELUARAN => 'heroicon-o-arrow-trending-down',
        };
    }
}
