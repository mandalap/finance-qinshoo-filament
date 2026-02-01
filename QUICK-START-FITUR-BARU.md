# ⚡ QUICK START - FITUR BARU

## 🎯 Setup Awal (Wajib!)

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Setup Email (Untuk Notifikasi)
Edit file `.env`:
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

### 3. Test Backup
```bash
php artisan backup:run --only-db
```

---

## 📋 CARA MENGGUNAKAN SETIAP FITUR

### 1️⃣ ACTIVITY LOG (Audit Trail)

**Melihat Log Aktivitas:**
```bash
php artisan tinker
```

```php
// Lihat 10 aktivitas terakhir
\Spatie\Activitylog\Models\Activity::latest()->take(10)->get();

// Lihat aktivitas transaksi keuangan
\Spatie\Activitylog\Models\Activity::inLog('transaksi_keuangan')->get();

// Lihat aktivitas pengajuan barang
\Spatie\Activitylog\Models\Activity::inLog('pengajuan_barang')->get();

// Lihat siapa yang mengubah data
$activity = \Spatie\Activitylog\Models\Activity::first();
$activity->causer; // User yang melakukan
$activity->subject; // Data yang diubah
$activity->properties; // Perubahan detail
```

**Auto-Tracking:**
- ✅ Semua perubahan di TransaksiKeuangan otomatis tercatat
- ✅ Semua perubahan di PengajuanBarang otomatis tercatat
- ✅ Semua perubahan di KategoriTransaksi otomatis tercatat

---

### 2️⃣ NOTIFICATION SYSTEM

**Auto-Notification:**
- ✅ Saat ada pengajuan baru → Email ke semua approver
- ✅ Saat pengajuan disetujui → Email ke pengaju (jika ada email)
- ✅ Saat pengajuan ditolak → Email ke pengaju (jika ada email)

**Test Notifikasi Manual:**
```bash
php artisan tinker
```

```php
// Kirim notifikasi pengajuan baru
$user = User::first();
$pengajuan = PengajuanBarang::first();
$user->notify(new \App\Notifications\PengajuanBaruNotification($pengajuan));

// Cek notifikasi user
$user->notifications; // Semua notifikasi
$user->unreadNotifications; // Yang belum dibaca
```

**Setup Role Approver:**
```php
// Buat role approver
$role = \Spatie\Permission\Models\Role::create(['name' => 'approver']);

// Assign ke user
$user = User::find(1);
$user->assignRole('approver');
```

---

### 3️⃣ BACKUP SYSTEM

**Manual Backup:**
```bash
# Backup database + files
php artisan backup:run

# Backup hanya database (lebih cepat)
php artisan backup:run --only-db

# Lihat daftar backup
php artisan backup:list

# Cek status backup
php artisan backup:monitor
```

**Scheduled Backup (Recommended):**

Edit `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    // Backup setiap hari jam 2 pagi
    $schedule->command('backup:run --only-db')->daily()->at('02:00');
    
    // Hapus backup lama (> 7 hari)
    $schedule->command('backup:clean')->daily()->at('03:00');
}
```

Jalankan scheduler:
```bash
# Development
php artisan schedule:work

# Production (tambahkan ke cron)
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

**Restore Backup:**
```bash
# 1. Extract backup
unzip storage/app/backups/keuangan-filament_2026-01-31.zip

# 2. Restore database
mysql -u root -p keuangan-qinshoo < db-dumps/mysql-keuangan-qinshoo.sql
```

---

### 4️⃣ ENHANCED VALIDATION

**Sudah Otomatis Aktif!**

Validasi yang ditambahkan:
- ✅ Nomor transaksi harus unique
- ✅ Tanggal transaksi tidak boleh future date
- ✅ Nominal harus positif
- ✅ Kategori harus sesuai jenis transaksi
- ✅ Hanya pengajuan PENDING yang bisa di-approve/reject
- ✅ Alasan wajib untuk reject/cancel
- ✅ Budget unique per kategori per bulan

**Testing:**
Coba buat transaksi dengan:
- Tanggal besok → Error
- Nominal negatif → Error
- Kategori tidak sesuai jenis → Error

---

### 5️⃣ BUDGET MANAGEMENT

**Buat Budget Baru:**
1. Login ke `/admin`
2. Menu **Keuangan** > **Budgets**
3. Klik **New Budget**
4. Isi form:
   - Kategori: Pilih kategori transaksi
   - Tahun: 2026
   - Bulan: 1-12
   - Nominal Budget: Rp 10,000,000
   - Keterangan: "Budget operasional Januari"
5. Save

**Monitoring Budget:**
- **Realisasi**: Otomatis dihitung dari transaksi
- **Sisa Budget**: Budget - Realisasi
- **Persentase**: (Realisasi / Budget) × 100%
- **Status**:
  - 🟢 Safe (0-74%)
  - 🟡 Caution (75-89%)
  - 🟠 Warning (90-99%)
  - 🔴 Over Budget (≥100%)

**Query Budget:**
```bash
php artisan tinker
```

```php
// Budget bulan ini
$budgets = Budget::currentMonth()->get();

// Budget kategori tertentu
$budget = Budget::where('kategori_id', 1)
    ->where('tahun', 2026)
    ->where('bulan', 1)
    ->first();

// Cek realisasi
$budget->realisasi; // Total pengeluaran
$budget->sisa_budget; // Sisa budget
$budget->persentase_realisasi; // Persentase (%)
$budget->status; // safe/caution/warning/over_budget
```

---

## 🧪 TESTING CHECKLIST

### Activity Log
- [ ] Buat transaksi baru → Cek activity log
- [ ] Update transaksi → Cek activity log
- [ ] Approve pengajuan → Cek activity log

### Notification
- [ ] Submit pengajuan baru → Cek email approver
- [ ] Approve pengajuan → Cek email pengaju (jika ada)
- [ ] Reject pengajuan → Cek email pengaju (jika ada)

### Backup
- [ ] Run `php artisan backup:run --only-db`
- [ ] Cek file di `storage/app/backups/`
- [ ] Extract dan verify isi backup

### Validation
- [ ] Coba buat transaksi dengan tanggal future → Error
- [ ] Coba approve pengajuan yang sudah approved → Error
- [ ] Coba buat budget duplicate → Error

### Budget
- [ ] Buat budget untuk kategori
- [ ] Buat transaksi di kategori tersebut
- [ ] Cek realisasi budget otomatis update
- [ ] Cek status berubah sesuai persentase

---

## 🆘 TROUBLESHOOTING

### Email Tidak Terkirim
```bash
# Cek konfigurasi
php artisan config:clear
php artisan cache:clear

# Test email
php artisan tinker
Mail::raw('Test email', function($msg) {
    $msg->to('test@example.com')->subject('Test');
});
```

### Backup Gagal
```bash
# Cek permission
chmod -R 775 storage/app/backups

# Cek MySQL path
which mysqldump

# Update config/backup.php jika perlu
```

### Activity Log Tidak Muncul
```bash
# Clear cache
php artisan config:clear
php artisan cache:clear

# Cek table
php artisan tinker
\Spatie\Activitylog\Models\Activity::count();
```

---

## 📚 DOKUMENTASI LENGKAP

Lihat file `FITUR-BARU.md` untuk dokumentasi detail semua fitur.

---

**Happy Coding! 🚀**
