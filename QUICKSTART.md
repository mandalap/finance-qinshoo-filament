# 🚀 QUICK START GUIDE

Panduan cepat untuk menjalankan Sistem Pengajuan Barang Yayasan.

## ⚡ Langkah Cepat

### 1. **Jalankan Server**
```bash
php artisan serve
```

Server akan berjalan di: `http://127.0.0.1:8000`

---

### 2. **Akses Public Form**

**URL**: http://127.0.0.1:8000

Siapa saja bisa mengisi form pengajuan barang tanpa login.

**Contoh Data untuk Testing:**
```
Data Pengaju:
- Nama: Ahmad Hidayat
- Divisi: Pendidikan
- Jabatan: Koordinator Program
- Kontak: 081234567890

Detail Barang:
- Nama Barang: Laptop Dell Inspiron 15
- Spesifikasi: Intel Core i5, RAM 8GB, SSD 256GB
- Jumlah: 2
- Satuan: Unit
- Estimasi Harga: 15000000

Kebutuhan:
- Tujuan: Untuk kegiatan pembelajaran online
- Tanggal Dibutuhkan: [pilih tanggal besok]
- Urgensi: Mendesak
```

Setelah submit, Anda akan mendapat **Nomor Pengajuan** seperti: `PB/2026/01/0001`

---

### 3. **Login ke Dashboard Filament**

**URL**: http://127.0.0.1:8000/admin

**Kredensial Login:**
```
Email: admin@yayasan.com
Password: password
```

---

### 4. **Kelola Pengajuan**

Setelah login:

1. Klik menu **"Operasional Yayasan"** di sidebar
2. Klik **"Pengajuan Barang"**
3. Anda akan melihat daftar semua pengajuan

**Actions yang tersedia:**

| Action | Kapan Muncul | Catatan Wajib? |
|--------|--------------|----------------|
| 👁️ **Lihat** | Semua status | - |
| ✅ **Setujui** | Status Pending | Opsional |
| ❌ **Tolak** | Status Pending | **Wajib** |
| 🚫 **Batalkan** | Pending/Approved | **Wajib** |

**Cara Approve:**
1. Klik tombol **"Setujui"** (hijau)
2. Isi catatan persetujuan (opsional)
3. Klik **"Confirm"**

**Cara Reject:**
1. Klik tombol **"Tolak"** (merah)
2. Isi alasan penolakan (**wajib**)
3. Klik **"Confirm"**

**Cara Cancel:**
1. Klik tombol **"Batalkan"** (abu-abu)
2. Isi alasan pembatalan (**wajib**)
3. Klik **"Confirm"**

---

### 5. **Filter & Search**

**Filter berdasarkan Status:**
- Klik dropdown "Status"
- Pilih: Menunggu Persetujuan / Disetujui / Ditolak / Dibatalkan

**Filter berdasarkan Urgensi:**
- Klik dropdown "Tingkat Urgensi"
- Pilih: Normal / Mendesak

**Search:**
- Ketik di search box untuk cari:
  - Nomor pengajuan
  - Nama pengaju
  - Nama barang

---

## 🎨 Badge Warna

### Status
- 🟡 **Menunggu Persetujuan** (Kuning)
- 🟢 **Disetujui** (Hijau)
- 🔴 **Ditolak** (Merah)
- ⚫ **Dibatalkan** (Abu-abu)

### Urgensi
- 🔵 **Normal** (Biru)
- 🔴 **Mendesak** (Merah)

---

## 📱 Mobile Access

Form pengajuan sudah **responsive** dan bisa diakses dari HP:

1. Pastikan HP dan komputer dalam jaringan yang sama
2. Cek IP komputer: `ipconfig` (Windows) atau `ifconfig` (Mac/Linux)
3. Akses dari HP: `http://[IP-KOMPUTER]:8000`

Contoh: `http://192.168.1.100:8000`

---

## 🔧 Troubleshooting

### Server tidak bisa diakses?
```bash
# Cek apakah server running
php artisan serve

# Atau gunakan port lain jika 8000 sudah dipakai
php artisan serve --port=8080
```

### Lupa password admin?
```bash
# Reset password via tinker
php artisan tinker
>>> $user = User::where('email', 'admin@yayasan.com')->first();
>>> $user->password = bcrypt('password');
>>> $user->save();
```

### Database error?
```bash
# Jalankan ulang migration
php artisan migrate:fresh

# Buat ulang user admin
php artisan make:filament-user
```

---

## 📊 Contoh Workflow

### Skenario 1: Pengajuan Normal
1. **Pengaju** mengisi form → Status: **Pending**
2. **Approver** login dan review
3. **Approver** klik "Setujui" → Status: **Approved** ✅

### Skenario 2: Pengajuan Ditolak
1. **Pengaju** mengisi form → Status: **Pending**
2. **Approver** login dan review
3. **Approver** klik "Tolak" + isi alasan → Status: **Rejected** ❌

### Skenario 3: Pengajuan Dibatalkan
1. **Pengaju** mengisi form → Status: **Pending**
2. **Approver** approve → Status: **Approved**
3. Terjadi perubahan kebutuhan
4. **Approver** klik "Batalkan" + isi alasan → Status: **Cancelled** 🚫

---

## 💡 Tips

1. **Simpan Nomor Pengajuan** - Nomor ini unik dan bisa digunakan untuk tracking
2. **Isi Catatan Persetujuan** - Meskipun opsional saat approve, catatan membantu dokumentasi
3. **Wajib Isi Alasan** - Saat reject/cancel, alasan wajib diisi untuk transparansi
4. **Gunakan Filter** - Untuk melihat pengajuan pending saja, gunakan filter status
5. **Export Data** - (Coming soon) Fitur export laporan akan ditambahkan

---

## 📞 Support

Jika ada pertanyaan atau masalah:
1. Cek dokumentasi lengkap di `README.md`
2. Cek todolist di `TODOLIST.md`
3. Hubungi developer sistem

---

**Happy Managing! 🎉**
