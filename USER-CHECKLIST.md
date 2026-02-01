# ✅ USER ACTION CHECKLIST - FITUR BARU

## 🎯 Checklist untuk Mengaktifkan Semua Fitur Baru

Ikuti checklist ini untuk memastikan semua fitur baru berfungsi dengan baik.

---

## ✅ STEP 1: VERIFIKASI INSTALASI

### 1.1 Cek Migration Status
```bash
php artisan migrate:status
```

**Expected Result:**
- ✅ `2026_01_31_011748_create_activity_log_table` - Ran
- ✅ `2026_01_31_012156_create_notifications_table` - Ran
- ✅ `2026_01_31_013000_create_budgets_table` - Ran

**Status**: ✅ DONE (Sudah dijalankan)

### 1.2 Cek Packages
```bash
composer show | grep spatie
```

**Expected Result:**
- ✅ spatie/laravel-activitylog
- ✅ spatie/laravel-backup
- ✅ spatie/db-dumper

**Status**: ✅ DONE (Sudah terinstall)

---

## ⏳ STEP 2: SETUP EMAIL (UNTUK NOTIFIKASI)

### 2.1 Update File .env

Buka file `.env` dan update bagian MAIL:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Keuangan Yayasan"
```

**Untuk Gmail:**
1. Buka https://myaccount.google.com/apppasswords
2. Generate App Password
3. Copy password ke MAIL_PASSWORD

**Status**: ⏳ PENDING (User action required)

### 2.2 Test Email
```bash
php artisan tinker
```

```php
Mail::raw('Test email dari sistem keuangan', function($msg) {
    $msg->to('your-email@gmail.com')->subject('Test Email');
});
```

**Expected Result:**
- ✅ Email terkirim ke inbox
- ✅ Tidak ada error

**Status**: ⏳ PENDING (User action required)

---

## ⏳ STEP 3: SETUP BACKUP SYSTEM

### 3.1 Test Manual Backup
```bash
php artisan backup:run --only-db
```

**Expected Result:**
- ✅ Backup berhasil dibuat
- ✅ File ZIP ada di `storage/app/backups/`
- ✅ Tidak ada error

**Status**: ⏳ PENDING (User action required)

### 3.2 Verify Backup File
```bash
# Windows
dir storage\app\backups

# Atau buka folder di File Explorer
```

**Expected Result:**
- ✅ Ada file ZIP dengan nama `keuangan-filament_YYYY-MM-DD_HH-MM-SS.zip`
- ✅ File size > 0 bytes

**Status**: ⏳ PENDING (User action required)

### 3.3 Setup Scheduled Backup (Optional)

**Option A: Task Scheduler (Windows)**
1. Buka Task Scheduler
2. Create Basic Task
3. Trigger: Daily at 2:00 AM
4. Action: Start a program
5. Program: `php`
6. Arguments: `artisan backup:run --only-db`
7. Start in: `E:\development\Keuangan-Filament`

**Option B: Manual (Development)**
```bash
# Jalankan setiap kali butuh backup
php artisan backup:run --only-db
```

**Status**: ⏳ PENDING (User action required)

---

## ⏳ STEP 4: SETUP ROLES & PERMISSIONS

### 4.1 Create Approver Role
```bash
php artisan tinker
```

```php
// Create role approver
$role = \Spatie\Permission\Models\Role::create(['name' => 'approver']);

// Create role super-admin (jika belum ada)
$superAdmin = \Spatie\Permission\Models\Role::create(['name' => 'super-admin']);
```

**Status**: ⏳ PENDING (User action required)

### 4.2 Assign Role ke User
```php
// Assign role approver ke user
$user = User::where('email', 'admin@yayasan.com')->first();
$user->assignRole('approver');

// Atau assign super-admin
$user->assignRole('super-admin');
```

**Status**: ⏳ PENDING (User action required)

---

## ⏳ STEP 5: TESTING FITUR

### 5.1 Test Activity Log

**Action:**
1. Login ke `/admin`
2. Buat transaksi baru
3. Edit transaksi
4. Jalankan query:

```bash
php artisan tinker
```

```php
// Lihat activity log
\Spatie\Activitylog\Models\Activity::latest()->take(5)->get();

// Lihat detail
$activity = \Spatie\Activitylog\Models\Activity::first();
echo "User: " . $activity->causer->name;
echo "Action: " . $activity->description;
echo "Model: " . $activity->subject_type;
```

**Expected Result:**
- ✅ Ada log untuk create transaksi
- ✅ Ada log untuk update transaksi
- ✅ Causer adalah user yang login
- ✅ Properties berisi before & after values

**Status**: ⏳ PENDING (User action required)

---

### 5.2 Test Notification System

**Action:**
1. Buka public form: `http://localhost:8000`
2. Submit pengajuan barang baru
3. Cek email approver

**Expected Result:**
- ✅ Email notifikasi terkirim ke approver
- ✅ Subject: "Pengajuan Barang Baru - PB/YYYY/MM/XXXX"
- ✅ Isi email lengkap dengan detail pengajuan
- ✅ Ada link ke detail pengajuan

**Status**: ⏳ PENDING (User action required)

**Action 2:**
1. Login sebagai approver
2. Approve/Reject pengajuan
3. Cek email pengaju (jika ada)

**Expected Result:**
- ✅ Email terkirim sesuai action (approve/reject)
- ✅ Isi email sesuai template

**Status**: ⏳ PENDING (User action required)

---

### 5.3 Test Budget Management

**Action:**
1. Login ke `/admin`
2. Menu **Keuangan** > **Budgets**
3. Klik **New Budget**
4. Isi form:
   - Kategori: Pilih kategori (misal: Operasional)
   - Tahun: 2026
   - Bulan: 2 (Februari)
   - Nominal Budget: 10000000 (10 juta)
   - Keterangan: "Budget operasional Februari 2026"
5. Save

**Expected Result:**
- ✅ Budget berhasil dibuat
- ✅ Realisasi = 0 (belum ada transaksi)
- ✅ Sisa Budget = 10,000,000
- ✅ Status = Safe (hijau)

**Status**: ⏳ PENDING (User action required)

**Action 2:**
1. Buat transaksi dengan kategori yang sama
2. Nominal: 8,000,000 (8 juta)
3. Tanggal: Februari 2026
4. Save
5. Kembali ke list Budget

**Expected Result:**
- ✅ Realisasi = 8,000,000
- ✅ Sisa Budget = 2,000,000
- ✅ Persentase = 80%
- ✅ Status = Caution (kuning)

**Status**: ⏳ PENDING (User action required)

---

### 5.4 Test Enhanced Validation

**Test 1: Future Date Prevention**
1. Buat transaksi baru
2. Tanggal: Besok
3. Try to save

**Expected Result:**
- ❌ Error: "Tanggal transaksi tidak boleh di masa depan"

**Test 2: Negative Nominal**
1. Buat transaksi baru
2. Nominal: -1000
3. Try to save

**Expected Result:**
- ❌ Error: "Nominal harus positif"

**Test 3: Duplicate Budget**
1. Buat budget baru
2. Kategori, Tahun, Bulan sama dengan budget yang sudah ada
3. Try to save

**Expected Result:**
- ❌ Error: "Budget untuk kategori ini di bulan ini sudah ada"

**Status**: ⏳ PENDING (User action required)

---

## 📊 PROGRESS TRACKING

| Step | Task | Status | Notes |
|------|------|--------|-------|
| 1.1 | Verifikasi Migration | ✅ DONE | Semua migration sudah run |
| 1.2 | Verifikasi Packages | ✅ DONE | Semua package terinstall |
| 2.1 | Setup Email Config | ⏳ PENDING | Update .env |
| 2.2 | Test Email | ⏳ PENDING | Kirim test email |
| 3.1 | Test Manual Backup | ⏳ PENDING | Run backup command |
| 3.2 | Verify Backup File | ⏳ PENDING | Cek file ZIP |
| 3.3 | Setup Scheduled Backup | ⏳ PENDING | Optional |
| 4.1 | Create Roles | ⏳ PENDING | Create approver role |
| 4.2 | Assign Roles | ⏳ PENDING | Assign ke user |
| 5.1 | Test Activity Log | ⏳ PENDING | Buat & edit transaksi |
| 5.2 | Test Notification | ⏳ PENDING | Submit pengajuan |
| 5.3 | Test Budget | ⏳ PENDING | Buat budget & transaksi |
| 5.4 | Test Validation | ⏳ PENDING | Test error cases |

---

## 🎯 COMPLETION CRITERIA

Fitur dianggap **FULLY OPERATIONAL** jika:

- [x] ✅ Semua migration berhasil
- [x] ✅ Semua package terinstall
- [ ] ⏳ Email configuration selesai
- [ ] ⏳ Test email berhasil
- [ ] ⏳ Backup berhasil dibuat
- [ ] ⏳ Roles & permissions setup
- [ ] ⏳ Activity log tracking works
- [ ] ⏳ Notification terkirim
- [ ] ⏳ Budget calculation correct
- [ ] ⏳ Validation rules active

**Current Progress: 2/10 (20%)**

---

## 🆘 TROUBLESHOOTING

### Email Tidak Terkirim
```bash
# Clear config cache
php artisan config:clear

# Test dengan log driver dulu
# Update .env: MAIL_MAILER=log
# Cek storage/logs/laravel.log
```

### Backup Gagal
```bash
# Cek MySQL path
where mysqldump

# Jika tidak ada, install MySQL client
# Atau gunakan Laragon MySQL
```

### Activity Log Kosong
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear

# Cek apakah model sudah pakai trait LogsActivity
```

### Budget Tidak Update
```bash
# Clear cache
php artisan cache:clear

# Pastikan kategori_id sama
# Pastikan tahun & bulan sama
```

---

## 📞 SUPPORT

Jika ada masalah:
1. Cek `storage/logs/laravel.log`
2. Lihat dokumentasi di `FITUR-BARU.md`
3. Lihat quick start di `QUICK-START-FITUR-BARU.md`
4. Contact developer

---

## ✅ SIGN OFF

Setelah semua checklist selesai, tandai di sini:

- [ ] Semua fitur sudah ditest
- [ ] Email notification berfungsi
- [ ] Backup system berfungsi
- [ ] Activity log tracking works
- [ ] Budget management works
- [ ] Validation rules active
- [ ] Dokumentasi sudah dibaca
- [ ] System ready for production

**Tested By**: _________________  
**Date**: _________________  
**Signature**: _________________

---

**Good Luck! 🚀**
