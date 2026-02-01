# 🚨 SOLUSI LENGKAP: 403 Forbidden di Hosting

## 📋 Situasi Anda
- ✅ Halaman utama tampil normal
- ✅ Halaman login tampil normal  
- ❌ Setelah login → **403 Forbidden**
- ❌ File `storage/logs/laravel.log` **KOSONG**

**Kesimpulan:** Error terjadi di level **web server/permission**, bukan di Laravel.

---

## ⚡ SOLUSI TERCEPAT (Ikuti Urutan Ini!)

### 🎯 STEP 1: Upload & Jalankan Script Otomatis

1. **Upload file `fix-403-complete.sh` ke hosting** (via FTP/File Manager)
2. **Login ke hosting via SSH atau Terminal cPanel**
3. **Jalankan script:**

```bash
cd /path/to/your/application
bash fix-403-complete.sh
```

Script ini akan otomatis:
- ✅ Set permission 775 untuk storage & bootstrap/cache
- ✅ Set ownership yang benar
- ✅ Buat semua folder yang dibutuhkan
- ✅ Clear semua cache
- ✅ Optimize aplikasi
- ✅ Buat storage link

---

### 🎯 STEP 2: Test Permission

1. **Upload file `public/test-permission.php` ke hosting**
2. **Akses di browser:**
   ```
   https://yourdomain.com/test-permission.php
   ```

3. **Lihat hasilnya:**
   - ✅ Semua hijau = Permission OK, lanjut ke Step 3
   - ❌ Ada merah = Catat error-nya, perbaiki dulu

**⚠️ PENTING:** Hapus file `test-permission.php` setelah testing!

---

### 🎯 STEP 3: Periksa & Update .env

1. **Buka file `.env` di hosting**
2. **Pastikan konfigurasi ini benar:**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_DOMAIN=yourdomain.com
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

SANCTUM_STATEFUL_DOMAINS=yourdomain.com
```

**⚠️ GANTI `yourdomain.com` dengan domain Anda yang sebenarnya!**

3. **Jika pakai HTTP (bukan HTTPS), ubah:**
```env
SESSION_SECURE_COOKIE=false
```

4. **Setelah edit .env, clear cache:**
```bash
php artisan config:clear
php artisan config:cache
```

---

### 🎯 STEP 4: Cek ModSecurity

ModSecurity sering memblokir POST request dari form login.

**Cara 1: Via .htaccess**

Edit file `public/.htaccess`, tambahkan di **paling atas**:

```apache
<IfModule mod_security.c>
    SecRuleEngine Off
</IfModule>
```

**Cara 2: Via cPanel**
1. Buka **Security** → **ModSecurity**
2. Disable untuk domain Anda

---

### 🎯 STEP 5: Cek Document Root

Pastikan web server mengarah ke folder `public`.

**Via cPanel:**
1. **Domains** → **Domains** → **Manage**
2. **Document Root** harus: `/home/username/public_html/your-app/public`

**Jika tidak bisa ubah Document Root:**

Buat file `.htaccess` di **root aplikasi** (bukan di public):

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

### 🎯 STEP 6: Cek Error Log Web Server

Karena Laravel log kosong, cek error log web server:

```bash
# cPanel/Shared Hosting
tail -50 ~/logs/error_log

# Atau
tail -50 /usr/local/apache/logs/error_log
```

**Via cPanel:**
- **Metrics** → **Errors**

---

## 🔧 JIKA SCRIPT GAGAL - Manual Commands

Jalankan satu per satu:

```bash
# 1. Masuk ke folder aplikasi
cd /home/username/public_html/your-app

# 2. Set permission
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 3. Buat folder yang dibutuhkan
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs

# 4. Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 5. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 6. Storage link
php artisan storage:link

# 7. Cek permission
ls -la storage/
ls -la bootstrap/cache/
```

---

## 🆘 TROUBLESHOOTING

### ❌ Error: "Permission denied"

**Solusi:**
```bash
# Coba dengan permission 777 (sementara)
chmod -R 777 storage
chmod -R 777 bootstrap/cache

# Jika berhasil, kembalikan ke 775
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### ❌ Error: "Session not working"

**Solusi:**
```bash
# Cek folder session
ls -la storage/framework/sessions/

# Pastikan writable
chmod 775 storage/framework/sessions/

# Atau ubah session driver di .env
SESSION_DRIVER=database
```

Lalu jalankan:
```bash
php artisan session:table
php artisan migrate
```

### ❌ Error: "CSRF token mismatch"

**Solusi:**

1. **Clear browser cache** (Ctrl+Shift+Del)
2. **Coba incognito mode**
3. **Periksa .env:**
```env
SESSION_DOMAIN=yourdomain.com
SESSION_SECURE_COOKIE=true  # false jika HTTP
```

4. **Clear cache:**
```bash
php artisan config:clear
php artisan cache:clear
```

### ❌ Error: "Class not found"

**Solusi:**
```bash
composer dump-autoload
php artisan optimize:clear
php artisan optimize
```

---

## 📊 CHECKLIST LENGKAP

Centang setiap langkah yang sudah dilakukan:

- [ ] Upload & jalankan `fix-403-complete.sh`
- [ ] Test dengan `test-permission.php` (semua hijau)
- [ ] File `.env` sudah benar (APP_URL, SESSION_DOMAIN)
- [ ] Permission storage = 775
- [ ] Permission bootstrap/cache = 775
- [ ] Folder session ada dan writable
- [ ] ModSecurity sudah di-disable
- [ ] Document root mengarah ke folder public
- [ ] PHP version >= 8.1
- [ ] Clear browser cache
- [ ] Test login di incognito mode

---

## 📁 FILE YANG SUDAH DIBUAT

1. **`fix-403-complete.sh`** - Script otomatis lengkap
2. **`public/test-permission.php`** - Test permission & environment
3. **`.env.hosting`** - Template .env untuk production
4. **`FIX-403-FORBIDDEN.md`** - Panduan lengkap 8 solusi
5. **`FIX-403-NO-LOG.md`** - Panduan khusus jika log kosong
6. **`QUICK-FIX-403.md`** - Quick reference

---

## 💡 TIPS PENTING

1. **Jangan gunakan permission 777** kecuali untuk testing
2. **Selalu clear browser cache** setelah perubahan
3. **Gunakan incognito mode** untuk testing
4. **Backup .env** sebelum edit
5. **Hapus test-permission.php** setelah testing
6. **Set APP_DEBUG=false** di production

---

## 🎯 KEMUNGKINAN PENYEBAB (Urutan Prioritas)

1. ⭐⭐⭐ **Permission folder storage salah** (775 atau 777)
2. ⭐⭐⭐ **Ownership folder salah**
3. ⭐⭐ **ModSecurity memblokir POST request**
4. ⭐⭐ **SESSION_DOMAIN tidak sesuai**
5. ⭐ **Document root tidak ke folder public**
6. ⭐ **Session folder tidak writable**
7. ⭐ **Cache lama masih tersimpan**

---

## 📞 JIKA MASIH GAGAL

Kirimkan informasi ini:

### 1. Output command:
```bash
ls -la storage/
ls -la bootstrap/cache/
pwd
whoami
php -v
```

### 2. Output test-permission.php
Screenshot atau copy hasil test

### 3. Error log web server:
```bash
tail -50 ~/logs/error_log
```

### 4. Informasi hosting:
- Nama provider hosting
- Jenis hosting (shared/VPS)
- Control panel (cPanel/Plesk/dll)
- Apakah pakai HTTPS?

---

## 🚀 SETELAH BERHASIL

1. **Hapus file test-permission.php**
2. **Set APP_DEBUG=false** di .env
3. **Clear cache sekali lagi:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

4. **Test semua fitur aplikasi**
5. **Backup database & files**

---

## 📚 REFERENSI

- Laravel Deployment: https://laravel.com/docs/deployment
- Filament Deployment: https://filamentphp.com/docs/deployment
- File ini: `HOSTING-403-SOLUTION.md`

---

**Dibuat:** 2026-02-01  
**Untuk:** Keuangan Filament Application  
**Status:** Ready to deploy 🚀
