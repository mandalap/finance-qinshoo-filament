# 🚀 LANGKAH GIT PULL & FIX 403 DI HOSTING

## ✅ FILES SUDAH DI-PUSH KE GITHUB

Commit terbaru: **"Add: Solusi lengkap 403 Forbidden - Scripts, panduan, dan test tools"**

File yang ditambahkan:
- ✅ `.env.hosting` - Template .env production
- ✅ `FIX-403-FORBIDDEN.md` - Panduan 8 solusi
- ✅ `FIX-403-NO-LOG.md` - Panduan khusus log kosong
- ✅ `HOSTING-403-SOLUTION.md` - Panduan lengkap step-by-step
- ✅ `QUICK-FIX-403.md` - Quick reference
- ✅ `README-403-FIX.md` - Ringkasan eksekutif
- ✅ `fix-403-complete.sh` - Script otomatis
- ✅ `fix-hosting.ps1` - Script Windows
- ✅ `fix-hosting.sh` - Script Linux
- ✅ `public/test-permission.php` - Test permission
- ✅ `.agent/workflows/deploy-to-hosting.md` - Updated workflow

---

## 📋 LANGKAH DI HOSTING (IKUTI URUTAN INI!)

### 1️⃣ LOGIN SSH KE HOSTING

```bash
ssh username@your-hosting-domain.com
```

### 2️⃣ MASUK KE FOLDER APLIKASI

```bash
cd /home/username/public_html/your-app
# atau sesuai path Anda
```

### 3️⃣ BACKUP DULU (PENTING!)

```bash
# Backup database
php artisan backup:run --only-db

# Atau manual
mysqldump -u dbuser -p dbname > ../backup-$(date +%Y%m%d-%H%M%S).sql
```

### 4️⃣ SET MAINTENANCE MODE

```bash
php artisan down
```

### 5️⃣ GIT PULL DARI GITHUB

```bash
# Cek status
git status

# Stash perubahan lokal (jika ada)
git stash

# Pull dari GitHub
git pull origin main

# Jika ada conflict, reset hard:
# git fetch origin
# git reset --hard origin/main
```

### 6️⃣ JALANKAN SCRIPT FIX 403

```bash
# Pastikan script executable
chmod +x fix-403-complete.sh

# Jalankan script
bash fix-403-complete.sh
```

**Script akan otomatis:**
- Set permission 775 untuk storage & bootstrap/cache
- Buat folder yang dibutuhkan
- Clear semua cache
- Optimize aplikasi
- Buat storage link

### 7️⃣ PERIKSA .ENV

```bash
# Edit .env
nano .env
```

**Pastikan konfigurasi ini benar:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

SESSION_DRIVER=file
SESSION_DOMAIN=yourdomain.com
SESSION_SECURE_COOKIE=true

SANCTUM_STATEFUL_DOMAINS=yourdomain.com
```

**⚠️ GANTI `yourdomain.com` dengan domain Anda!**

Setelah edit, clear cache:
```bash
php artisan config:clear
php artisan config:cache
```

### 8️⃣ TEST PERMISSION

```bash
# Akses di browser:
# https://yourdomain.com/test-permission.php

# Pastikan semua ✅ hijau
# Jika ada ❌ merah, catat errornya
```

### 9️⃣ BRING BACK ONLINE

```bash
php artisan up
```

### 🔟 TEST LOGIN

1. **Clear browser cache** (Ctrl+Shift+Del)
2. **Coba login di incognito mode**
3. **Jika berhasil, hapus test-permission.php:**

```bash
rm public/test-permission.php
```

---

## 🔧 JIKA MASIH 403 FORBIDDEN

### Cek ModSecurity

```bash
# Edit public/.htaccess
nano public/.htaccess
```

Tambahkan di **paling atas**:
```apache
<IfModule mod_security.c>
    SecRuleEngine Off
</IfModule>
```

### Cek Error Log Web Server

```bash
# cPanel/Shared Hosting
tail -50 ~/logs/error_log

# Atau
tail -50 /usr/local/apache/logs/error_log
```

### Coba Permission 777 (Sementara)

```bash
chmod -R 777 storage
chmod -R 777 bootstrap/cache

# Test login
# Jika berhasil, kembalikan ke 775:
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

---

## 📊 COMMAND LENGKAP (COPY-PASTE)

Jika ingin manual tanpa script:

```bash
# 1. Set permission
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 2. Buat folder
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs

# 3. Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 4. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 5. Storage link
php artisan storage:link

# 6. Cek permission
ls -la storage/
ls -la bootstrap/cache/
```

---

## ✅ CHECKLIST

- [ ] Login SSH
- [ ] Masuk ke folder aplikasi
- [ ] Backup database
- [ ] Set maintenance mode
- [ ] Git pull origin main
- [ ] Jalankan fix-403-complete.sh
- [ ] Periksa .env (APP_URL, SESSION_DOMAIN)
- [ ] Test test-permission.php
- [ ] Bring back online
- [ ] Clear browser cache
- [ ] Test login incognito
- [ ] Hapus test-permission.php

---

## 📚 BACA PANDUAN LENGKAP

Setelah git pull, buka file ini di hosting:

1. **`README-403-FIX.md`** - Ringkasan cepat
2. **`HOSTING-403-SOLUTION.md`** - Panduan lengkap step-by-step

---

## 🆘 JIKA MASIH ERROR

Kirimkan output command ini:

```bash
# System info
ls -la storage/
ls -la bootstrap/cache/
php -v
pwd
whoami

# Error log
tail -50 ~/logs/error_log

# Git info
git log --oneline -3
git status
```

---

## 🎯 QUICK COMMAND (ALL-IN-ONE)

```bash
# Jalankan semua sekaligus:
php artisan down && \
git pull origin main && \
bash fix-403-complete.sh && \
php artisan config:clear && \
php artisan config:cache && \
php artisan up && \
echo "✅ DONE! Test login sekarang."
```

---

**Siap untuk git pull!** 🚀

**Next:** Login SSH → Git Pull → Jalankan Script → Test Login
