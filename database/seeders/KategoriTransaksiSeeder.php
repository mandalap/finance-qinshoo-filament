<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriTransaksi;

class KategoriTransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            // Pemasukan
            [
                'nama' => 'Wakaf',
                'jenis' => 'pemasukan',
                'deskripsi' => 'Penerimaan dari wakaf uang atau barang',
                'is_active' => true,
            ],
            [
                'nama' => 'Zakat',
                'jenis' => 'pemasukan',
                'deskripsi' => 'Penerimaan zakat mal, fitrah, profesi',
                'is_active' => true,
            ],
            [
                'nama' => 'Infaq/Sedekah',
                'jenis' => 'pemasukan',
                'deskripsi' => 'Infaq dan sedekah dari donatur',
                'is_active' => true,
            ],
            [
                'nama' => 'Donasi Umum',
                'jenis' => 'pemasukan',
                'deskripsi' => 'Donasi untuk kegiatan yayasan',
                'is_active' => true,
            ],
            [
                'nama' => 'Hibah',
                'jenis' => 'pemasukan',
                'deskripsi' => 'Hibah dari pemerintah atau lembaga',
                'is_active' => true,
            ],
            [
                'nama' => 'Pemasukan Lain-lain',
                'jenis' => 'pemasukan',
                'deskripsi' => 'Pemasukan lainnya yang tidak terkategori',
                'is_active' => true,
            ],
            
            // Pengeluaran
            [
                'nama' => 'Pembangunan',
                'jenis' => 'pengeluaran',
                'deskripsi' => 'Biaya pembangunan gedung, renovasi, dll',
                'is_active' => true,
            ],
            [
                'nama' => 'Operasional',
                'jenis' => 'pengeluaran',
                'deskripsi' => 'Biaya operasional harian yayasan',
                'is_active' => true,
            ],
            [
                'nama' => 'Gaji/Honor',
                'jenis' => 'pengeluaran',
                'deskripsi' => 'Gaji karyawan dan honor pengurus',
                'is_active' => true,
            ],
            [
                'nama' => 'Pembelian Barang',
                'jenis' => 'pengeluaran',
                'deskripsi' => 'Pembelian barang inventaris dan kebutuhan',
                'is_active' => true,
            ],
            [
                'nama' => 'Utilitas',
                'jenis' => 'pengeluaran',
                'deskripsi' => 'Listrik, air, internet, telepon',
                'is_active' => true,
            ],
            [
                'nama' => 'Program Sosial',
                'jenis' => 'pengeluaran',
                'deskripsi' => 'Biaya program sosial dan kegiatan',
                'is_active' => true,
            ],
            [
                'nama' => 'Pengeluaran Lain-lain',
                'jenis' => 'pengeluaran',
                'deskripsi' => 'Pengeluaran lainnya yang tidak terkategori',
                'is_active' => true,
            ],
        ];
        
        foreach ($kategoris as $kategori) {
            KategoriTransaksi::create($kategori);
        }
    }
}
