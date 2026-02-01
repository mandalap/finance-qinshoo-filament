# 📝 CHANGELOG

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [2.0.0] - 2026-01-31

### 🎉 Major Release - 5 Fitur Krusial

Rilis besar dengan penambahan 5 fitur enterprise-grade untuk meningkatkan keamanan, transparansi, dan manajemen keuangan.

### Added

#### 1. Activity Log (Audit Trail) ⭐⭐⭐⭐⭐
- ✅ Automatic tracking untuk semua perubahan data
- ✅ Log untuk TransaksiKeuangan (create, update, delete)
- ✅ Log untuk PengajuanBarang (create, update, status changes)
- ✅ Log untuk KategoriTransaksi (create, update, delete)
- ✅ Causer tracking (siapa yang melakukan perubahan)
- ✅ Properties tracking (before & after values)
- ✅ Package: spatie/laravel-activitylog v4.10.2

#### 2. Notification System ⭐⭐⭐⭐⭐
- ✅ Email notification untuk pengajuan baru → Approver
- ✅ Email notification untuk pengajuan disetujui → Pengaju
- ✅ Email notification untuk pengajuan ditolak → Pengaju
- ✅ Database notification (in-app notifications)
- ✅ Auto-trigger via Observer pattern
- ✅ Customizable notification templates

#### 3. Backup System ⭐⭐⭐⭐⭐
- ✅ Automated database backup
- ✅ Manual backup command: `php artisan backup:run`
- ✅ Scheduled backup support (daily/weekly)
- ✅ Backup monitoring & health checks
- ✅ Auto cleanup old backups
- ✅ Package: spatie/laravel-backup v9.3.7

#### 4. Enhanced Validation ⭐⭐⭐⭐
- ✅ Unique nomor transaksi validation
- ✅ Future date prevention untuk tanggal transaksi
- ✅ Positive nominal validation
- ✅ Kategori-jenis matching validation
- ✅ Status-based approval validation (only pending can be approved)
- ✅ Required reason for reject/cancel
- ✅ Budget uniqueness per kategori per bulan

#### 5. Budget Management ⭐⭐⭐⭐
- ✅ Budget setting per kategori per bulan
- ✅ Auto-calculate realisasi dari transaksi
- ✅ Auto-calculate sisa budget
- ✅ Persentase realisasi tracking
- ✅ Status alert system (Safe/Caution/Warning/Over Budget)
- ✅ Filament Resource untuk CRUD budget
- ✅ Budget monitoring dashboard

### Changed
- 📝 Updated README.md dengan informasi fitur baru
- 📝 Updated models dengan LogsActivity trait
- 📝 Updated AppServiceProvider dengan Observer registration

### Documentation
- 📖 Added `FITUR-BARU.md` - Dokumentasi lengkap semua fitur
- 📖 Added `QUICK-START-FITUR-BARU.md` - Quick start guide
- 📖 Added `IMPLEMENTATION-SUMMARY.md` - Implementation summary
- 📖 Added `CHANGELOG.md` - This file

### Database
- 🗄️ Added `activity_log` table
- 🗄️ Added `notifications` table
- 🗄️ Added `budgets` table

### Dependencies
- ➕ spatie/laravel-activitylog: ^4.10
- ➕ spatie/laravel-backup: ^9.3
- ➕ spatie/db-dumper: ^3.8
- ➕ spatie/temporary-directory: ^2.3
- ➕ spatie/laravel-signal-aware-command: ^2.1

---

## [1.0.0] - 2026-01-28

### Initial Release

#### Features
- ✅ Public form untuk pengajuan barang
- ✅ Auto-generate nomor pengajuan (PB/YYYY/MM/XXXX)
- ✅ Filament admin dashboard
- ✅ Approval workflow (Approve/Reject/Cancel)
- ✅ Status tracking (Pending/Approved/Rejected/Cancelled)
- ✅ Tingkat urgensi (Normal/Mendesak)
- ✅ Detail barang dengan relasi terpisah
- ✅ Transaksi keuangan (Pemasukan/Pengeluaran)
- ✅ Auto-generate nomor transaksi (TRX/YYYY/MM/XXXX)
- ✅ Kategori transaksi
- ✅ Upload bukti transaksi
- ✅ Print/cetak pengajuan & transaksi
- ✅ Dashboard widgets (Stats, Charts)
- ✅ Export Excel (filament-excel)
- ✅ Soft deletes
- ✅ Role-based access (Spatie Permission)

#### Technology Stack
- Laravel 12
- Filament 5.1
- MySQL Database
- Blade Templates
- Vanilla CSS

---

## [Unreleased]

### Planned Features
- [ ] Activity Log Filament Resource (UI viewer)
- [ ] Budget Dashboard Widget
- [ ] Email alerts untuk budget warnings
- [ ] Backup to cloud storage (Google Drive/Dropbox)
- [ ] Advanced PDF reporting
- [ ] WhatsApp notification integration
- [ ] Mobile app (Flutter/React Native)
- [ ] API for third-party integration
- [ ] Multi-level approval workflow
- [ ] Budget forecasting & analytics

---

## Version History

| Version | Date | Description |
|---------|------|-------------|
| 2.0.0 | 2026-01-31 | Major release - 5 fitur krusial |
| 1.0.0 | 2026-01-28 | Initial release |

---

## Migration Guide

### From v1.0.0 to v2.0.0

#### 1. Update Dependencies
```bash
composer update
```

#### 2. Run Migrations
```bash
php artisan migrate
```

#### 3. Publish Configurations (Optional)
```bash
# Activity Log config (already created)
# Backup config (already published)
```

#### 4. Setup Email (For Notifications)
Update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

#### 5. Test Backup
```bash
php artisan backup:run --only-db
```

#### 6. Setup Scheduled Backup (Recommended)
Add to `app/Console/Kernel.php`:
```php
$schedule->command('backup:run --only-db')->daily()->at('02:00');
$schedule->command('backup:clean')->daily()->at('03:00');
```

#### 7. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## Breaking Changes

### v2.0.0
- None (all changes are backward compatible)

---

## Security Updates

### v2.0.0
- ✅ Enhanced validation rules
- ✅ Activity logging for audit trail
- ✅ Secure backup system

---

## Performance Improvements

### v2.0.0
- ✅ Optimized activity log (only dirty attributes)
- ✅ Efficient budget calculation with accessors
- ✅ Database indexes on activity_log table

---

## Bug Fixes

### v2.0.0
- No bugs fixed (new features only)

---

## Contributors

- **AI Assistant (Antigravity)** - Initial implementation
- **Yayasan Team** - Requirements & Testing

---

## License

This project is proprietary software for Yayasan internal use only.

---

**For detailed documentation, see:**
- `FITUR-BARU.md` - Complete feature documentation
- `QUICK-START-FITUR-BARU.md` - Quick start guide
- `IMPLEMENTATION-SUMMARY.md` - Technical implementation details
- `README.md` - General project information
