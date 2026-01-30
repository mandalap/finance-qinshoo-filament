# 📋 Sistem Pengajuan Barang Yayasan

Sistem pencatatan dan persetujuan pengajuan barang untuk Yayasan menggunakan Laravel 12 + Filament 5.

## 🔗 Repository GitHub

**GitHub:** https://github.com/mandalap/finance-qinshoo-filament.git

Untuk workflow Git dan cara push update, lihat [GIT-WORKFLOW.md](GIT-WORKFLOW.md)

## 🎯 Fitur Utama

### 1. **Public Form (Tanpa Login)**
- Form pengajuan barang yang dapat diakses siapa saja
- Design modern dan mobile-friendly
- Auto-generate nomor pengajuan (Format: PB/YYYY/MM/XXXX)
- Status otomatis: **PENDING**
- Halaman konfirmasi setelah submit

### 2. **Dashboard Filament (Approver)**
- Login required untuk approver/pengurus
- Melihat semua pengajuan dengan filter
- Approval actions: **Setujui**, **Tolak**, **Batalkan**
- View detail lengkap pengajuan
- Tidak bisa create/edit/delete (pengajuan hanya dari public form)

### 3. **Status Pengajuan**
- 🟡 **Pending** - Menunggu Persetujuan
- 🟢 **Approved** - Disetujui
- 🔴 **Rejected** - Ditolak
- ⚫ **Cancelled** - Dibatalkan

### 4. **Tingkat Urgensi**
- 🔵 **Normal**
- 🔴 **Mendesak**

## 🚀 Cara Menggunakan

### Akses Public Form
1. Buka browser dan akses: `http://localhost:8000`
2. Isi form pengajuan barang
3. Submit dan simpan nomor pengajuan

### Login Dashboard Approver
1. Akses: `http://localhost:8000/admin`
2. Login dengan kredensial:
   - **Email**: admin@yayasan.com
   - **Password**: password
3. Klik menu **Operasional Yayasan** > **Pengajuan Barang**
4. Filter berdasarkan status atau urgensi
5. Klik tombol **Setujui**, **Tolak**, atau **Batalkan** pada setiap pengajuan

## 📊 Struktur Data Pengajuan

### Data Pengaju
- Nama Lengkap
- Divisi / Bidang
- Jabatan
- Kontak (opsional)

### Detail Barang
- Nama Barang
- Spesifikasi Barang
- Jumlah
- Satuan
- Estimasi Harga

### Kebutuhan
- Tujuan Pengajuan
- Tanggal Dibutuhkan
- Tingkat Urgensi

### Approval
- Status
- Catatan Persetujuan
- Disetujui Oleh
- Tanggal Persetujuan

## 🛠️ Teknologi

- **Laravel 12** - Backend Framework
- **Filament 5** - Admin Panel
- **SQLite** - Database
- **Blade** - Template Engine
- **Vanilla CSS** - Styling

## 📁 Struktur File Penting

```
app/
├── Models/
│   └── PengajuanBarang.php          # Model dengan auto-generate nomor
├── Enums/
│   ├── StatusPengajuan.php          # Enum status
│   └── TingkatUrgensi.php           # Enum urgensi
├── Http/Controllers/
│   └── PublicPengajuanController.php # Controller public form
└── Filament/
    └── Resources/
        └── PengajuanBarangs/
            ├── PengajuanBarangResource.php
            ├── Tables/
            │   └── PengajuanBarangsTable.php    # Konfigurasi table & actions
            └── Schemas/
                └── PengajuanBarangInfolist.php  # View detail

resources/views/public/
├── pengajuan-form.blade.php         # Public form
└── pengajuan-success.blade.php      # Halaman sukses

database/migrations/
└── 2026_01_28_042927_create_pengajuan_barang_table.php
```

## 🔐 Keamanan & Aturan

1. ✅ Pengajuan **HANYA** bisa dibuat dari public form
2. ✅ Approver **TIDAK BISA** create/edit/delete pengajuan
3. ✅ Reject dan Cancel **WAJIB** isi alasan
4. ✅ Approve bisa dengan catatan opsional
5. ✅ Bulk delete **DISABLED** untuk keamanan data

## 🎨 Desain

### Public Form
- Gradient background (purple)
- Card design dengan shadow
- Responsive mobile-friendly
- Validasi real-time
- Pesan error yang jelas

### Dashboard Filament
- Badge berwarna untuk status & urgensi
- Filter & search
- Action buttons dengan icon
- Section terorganisir di view page

## 📝 Catatan Pengembangan

### Auto-Generate Nomor Pengajuan
Format: `PB/YYYY/MM/XXXX`
- PB = Prefix "Pengajuan Barang"
- YYYY = Tahun
- MM = Bulan
- XXXX = Sequence number (reset setiap bulan)

### Approval Flow
1. Pengajuan masuk dengan status **PENDING**
2. Approver review dan pilih action
3. Sistem update status, approver, tanggal, dan catatan
4. Hanya pengajuan **APPROVED** yang dianggap sah

## 🔄 Pengembangan Selanjutnya (Opsional)

- [ ] Dashboard widget jumlah pengajuan pending
- [ ] Notifikasi email/WhatsApp saat ada pengajuan baru
- [ ] Export laporan Excel/PDF
- [ ] Approval bertingkat (multi-level)
- [ ] History log perubahan status
- [ ] Upload attachment/dokumen pendukung

## 👨‍💻 Developer

Sistem ini dibuat dengan prinsip:
- **Amanah** - Semua pengajuan tercatat dan transparan
- **Kontrol** - Approval wajib dari pengurus
- **Mudah** - Interface sederhana dan jelas

---

**Dibuat dengan ❤️ untuk Yayasan**
