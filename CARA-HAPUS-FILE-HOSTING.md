# 🗑️ Cara Menghapus File/Folder di Hosting

## ❓ Kenapa Tidak Bisa Dihapus?

### Penyebab Umum:
1. ✅ **Folder tidak kosong** (paling sering!)
2. ✅ **Permission tidak cukup** (ownership salah)
3. ✅ **File sedang digunakan** (process masih running)
4. ✅ **Hidden files** (file .git, .env, dll)

**BUKAN karena Git!** Git tidak mencegah penghapusan file.

---

## 🔧 SOLUSI 1: Via cPanel File Manager (GUI)

### Langkah 1: Tampilkan Hidden Files
1. Klik **Settings** (pojok kanan atas)
2. ✅ Centang **"Show Hidden Files (dotfiles)"**
3. Klik **Save**

### Langkah 2: Hapus Isi Folder Dulu
1. **Masuk ke dalam folder** `finance-qinshoo-filament`
2. **Select All** (Ctrl+A atau centang semua)
3. Klik **Delete**
4. Confirm delete

### Langkah 3: Hapus Folder Kosong
1. **Naik satu level** (ke parent folder)
2. **Klik kanan** pada folder `finance-qinshoo-filament`
3. Pilih **Delete**

---

## 🔧 SOLUSI 2: Via SSH/Terminal (RECOMMENDED!)

### Metode A: Hapus Folder & Isinya Sekaligus

```bash
# Login SSH
ssh username@your-hosting.com

# Masuk ke parent folder
cd /home/username/public_html

# Hapus folder dan semua isinya (HATI-HATI!)
rm -rf finance-qinshoo-filament

# Atau dengan konfirmasi:
rm -ri finance-qinshoo-filament
```

**⚠️ PERINGATAN:** `rm -rf` akan menghapus SEMUA tanpa konfirmasi!

### Metode B: Backup Dulu, Baru Hapus

```bash
# Backup dulu
tar -czf backup-finance-$(date +%Y%m%d-%H%M%S).tar.gz finance-qinshoo-filament

# Pindahkan backup ke folder aman
mv backup-finance-*.tar.gz ../backups/

# Baru hapus
rm -rf finance-qinshoo-filament
```

---

## 🔧 SOLUSI 3: Jika Permission Denied

### Cek Ownership & Permission

```bash
# Cek siapa owner folder
ls -la finance-qinshoo-filament

# Output contoh:
# drwxr-xr-x 10 root root 4096 Feb 1 18:00 finance-qinshoo-filament
#                ^^^^ ^^^^
#                owner group
```

### Fix Permission (Jika Perlu)

```bash
# Ubah ownership ke user Anda
sudo chown -R username:username finance-qinshoo-filament

# Atau tanpa sudo (jika punya akses)
chown -R username:username finance-qinshoo-filament

# Baru hapus
rm -rf finance-qinshoo-filament
```

---

## 🔧 SOLUSI 4: Hapus File Git Dulu

Jika masalahnya karena folder `.git`:

```bash
# Hapus folder .git dulu
rm -rf finance-qinshoo-filament/.git

# Baru hapus folder utama
rm -rf finance-qinshoo-filament
```

---

## 🔧 SOLUSI 5: Via cPanel Terminal

Jika tidak punya akses SSH:

1. Buka **cPanel** → **Terminal**
2. Jalankan command:

```bash
cd public_html
rm -rf finance-qinshoo-filament
```

---

## 📋 SKENARIO: Ingin Hapus & Git Pull Ulang

### Opsi A: Hapus Total, Clone Ulang

```bash
# 1. Backup database dulu!
php artisan backup:run --only-db

# 2. Backup .env
cp finance-qinshoo-filament/.env ~/env-backup.txt

# 3. Hapus folder
rm -rf finance-qinshoo-filament

# 4. Clone ulang
git clone https://github.com/mandalap/finance-qinshoo-filament.git

# 5. Restore .env
cp ~/env-backup.txt finance-qinshoo-filament/.env

# 6. Install dependencies
cd finance-qinshoo-filament
composer install --no-dev --optimize-autoloader

# 7. Set permission
chmod -R 775 storage bootstrap/cache

# 8. Clear & optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Opsi B: Reset Git, Jangan Hapus

```bash
# Masuk ke folder
cd finance-qinshoo-filament

# Hard reset ke commit terakhir
git fetch origin
git reset --hard origin/main

# Pull latest
git pull origin main

# Clear cache
php artisan cache:clear
php artisan config:cache
```

---

## 🚨 JIKA MASIH TIDAK BISA DIHAPUS

### Cek Process yang Menggunakan File

```bash
# Cek process
lsof +D finance-qinshoo-filament

# Atau
fuser -v finance-qinshoo-filament
```

### Kill Process (Jika Ada)

```bash
# Kill process by PID
kill -9 [PID]

# Atau stop web server sementara
sudo systemctl stop apache2
# atau
sudo systemctl stop nginx

# Hapus folder
rm -rf finance-qinshoo-filament

# Start web server lagi
sudo systemctl start apache2
```

---

## 💡 TIPS AMAN

### 1. Selalu Backup Dulu!

```bash
# Backup database
php artisan backup:run --only-db

# Backup folder
tar -czf backup-$(date +%Y%m%d-%H%M%S).tar.gz finance-qinshoo-filament

# Backup .env
cp .env ~/env-backup-$(date +%Y%m%d-%H%M%S).txt
```

### 2. Jangan Hapus Sembarangan

File penting yang JANGAN dihapus:
- ❌ `.env` (konfigurasi database)
- ❌ `storage/` (file upload, log, session)
- ❌ Database

### 3. Gunakan Git Reset Daripada Hapus

Lebih aman:
```bash
git fetch origin
git reset --hard origin/main
git clean -fd
```

---

## 🎯 COMMAND LENGKAP (COPY-PASTE)

### Hapus & Clone Ulang (Aman):

```bash
# 1. Backup
cd /home/username/public_html
cp finance-qinshoo-filament/.env ~/env-backup.txt
tar -czf ~/backup-finance-$(date +%Y%m%d-%H%M%S).tar.gz finance-qinshoo-filament

# 2. Hapus
rm -rf finance-qinshoo-filament

# 3. Clone ulang
git clone https://github.com/mandalap/finance-qinshoo-filament.git

# 4. Setup
cd finance-qinshoo-filament
cp ~/env-backup.txt .env
composer install --no-dev --optimize-autoloader
chmod -R 775 storage bootstrap/cache
php artisan config:cache
php artisan route:cache
php artisan storage:link

# 5. Test
php artisan --version
```

---

## ✅ CHECKLIST

- [ ] Backup database
- [ ] Backup .env
- [ ] Backup folder (tar.gz)
- [ ] Tampilkan hidden files (jika via cPanel)
- [ ] Hapus isi folder dulu
- [ ] Hapus folder kosong
- [ ] Clone/pull ulang
- [ ] Restore .env
- [ ] Install dependencies
- [ ] Set permission
- [ ] Test aplikasi

---

## 🆘 MASIH TIDAK BISA?

Hubungi support hosting dengan info:

```bash
# Kirim output command ini:
ls -la finance-qinshoo-filament
pwd
whoami
lsof +D finance-qinshoo-filament
```

---

**Selamat mencoba!** 🗑️

**Ingat:** Selalu backup dulu sebelum hapus!
