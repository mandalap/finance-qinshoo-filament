<?php

namespace App;

enum TingkatUrgensi: string
{
    case NORMAL = 'normal';
    case MENDESAK = 'mendesak';
    
    public function getLabel(): string
    {
        return match($this) {
            self::NORMAL => 'Normal',
            self::MENDESAK => 'Mendesak',
        };
    }
    
    public function getColor(): string
    {
        return match($this) {
            self::NORMAL => 'info',
            self::MENDESAK => 'danger',
        };
    }
}
