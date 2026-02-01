# 🚨 FIX 403 FORBIDDEN - Log Kosong

## Situasi
- Error 403 Forbidden setelah login
- File `storage/logs/laravel.log` **KOSONG**
- Berarti error terjadi di level **web server**, bukan Laravel

---

## ⚡ SOLUSI PRIORITAS TINGGI

### 1️⃣ CEK PERMISSION FOLDER (PALING PENTING!)

```bash
# Cek permission saat ini
ls -la storage/
ls -la storage/framework/
ls -la storage/framework/sessions/
ls -la bootstrap/cache/

# Set permission yang benar
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Jika masih gagal, coba 777 (sementara untuk testing)
chmod -R 777 storage
chmod -R 777 bootstrap/cache
```

**CATATAN:** 777 hanya untuk testing! Setelah berhasil, kembalikan ke 775.

---

### 2️⃣ CEK OWNERSHIP FOLDER

```bash
# Cek siapa owner folder
ls -la

# Set ownership (ganti 'username' dengan user hosting Anda)
chown -R username:username storage
chown -R username:username bootstrap/cache

# Atau gunakan user web server (biasanya www-data atau apache)
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

**Cara cek user web server:**
```bash
ps aux | grep -E 'apache|httpd|nginx'
```

---

### 3️⃣ CEK ERROR LOG WEB SERVER

Karena Laravel log kosong, cek error log web server:

#### **Apache:**
```bash
# cPanel/Shared Hosting
tail -50 /usr/local/apache/logs/error_log

# Atau di folder home
tail -50 ~/logs/error_log
tail -50 ~/public_html/error_log
```

#### **Nginx:**
```bash
tail -50 /var/log/nginx/error.log
```

#### **Via cPanel:**
1. Buka **Metrics** → **Errors**
2. Atau **File Manager** → folder `logs`

---

### 4️⃣ AKTIFKAN ERROR DISPLAY (SEMENTARA)

Edit file `public/index.php`, tambahkan di bagian paling atas:

```php
<?php

// TAMBAHKAN INI DI BARIS PALING ATAS (setelah <?php)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ... kode lainnya tetap sama
```

**⚠️ INGAT:** Hapus kode ini setelah selesai debugging!

---

### 5️⃣ CEK .htaccess DI PUBLIC

Pastikan file `public/.htaccess` ada dan benar:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Handle X-XSRF-Token Header
    RewriteCond %{HTTP:x-xsrf-token} .
    RewriteRule .* - [E=HTTP_X_XSRF_TOKEN:%{HTTP:X-XSRF-Token}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

### 6️⃣ CEK DOCUMENT ROOT

Pastikan web server mengarah ke folder `public`:

#### **Via cPanel:**
1. Buka **Domains** → **Domains**
2. Klik **Manage** pada domain Anda
3. **Document Root** harus: `/home/username/public_html/your-app/public`

#### **Via .htaccess di root** (jika tidak bisa ubah document root):

Buat file `.htaccess` di **root aplikasi** (bukan di public):

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

### 7️⃣ CEK MODSECURITY (Sering Jadi Masalah!)

ModSecurity bisa memblokir POST request dari login form.

#### **Disable ModSecurity untuk domain:**

Tambahkan di `public/.htaccess` (paling atas):

```apache
<IfModule mod_security.c>
    SecFilterEngine Off
    SecFilterScanPOST Off
</IfModule>
```

Atau tambahkan ini:

```apache
<IfModule mod_security.c>
    SecRuleEngine Off
</IfModule>
```

#### **Via cPanel:**
1. Buka **Security** → **ModSecurity**
2. Disable untuk domain Anda

---

### 8️⃣ CEK SESSION FOLDER

```bash
# Pastikan folder session ada dan writable
ls -la storage/framework/sessions/

# Jika tidak ada, buat manual
mkdir -p storage/framework/sessions
chmod 775 storage/framework/sessions
```

---

### 9️⃣ TEST PERMISSION DENGAN FILE PHP

Buat file `test-permission.php` di folder `public`:

```php
<?php
// test-permission.php

echo "<h1>Permission Test</h1>";

// Test write ke storage
$testFile = __DIR__ . '/../storage/logs/test.txt';
$result = file_put_contents($testFile, 'Test write: ' . date('Y-m-d H:i:s'));

if ($result !== false) {
    echo "<p style='color: green;'>✓ Storage writable!</p>";
    echo "<p>File created: $testFile</p>";
} else {
    echo "<p style='color: red;'>✗ Storage NOT writable!</p>";
    echo "<p>Error: " . error_get_last()['message'] . "</p>";
}

// Test session
session_start();
$_SESSION['test'] = 'OK';
if (isset($_SESSION['test'])) {
    echo "<p style='color: green;'>✓ Session working!</p>";
} else {
    echo "<p style='color: red;'>✗ Session NOT working!</p>";
}

// Show PHP info
echo "<hr>";
echo "<h2>PHP Info</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Session Save Path: " . session_save_path() . "</p>";
echo "<p>Session Save Handler: " . ini_get('session.save_handler') . "</p>";
```

Akses: `https://yourdomain.com/test-permission.php`

**⚠️ HAPUS file ini setelah testing!**

---

## 🔟 CHECKLIST LENGKAP

Jalankan semua command ini:

```bash
# 1. Set permission
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 2. Set ownership (ganti username)
chown -R username:username storage
chown -R username:username bootstrap/cache

# 3. Pastikan folder ada
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs

# 4. Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 5. Recreate cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Storage link
php artisan storage:link

# 7. Optimize
php artisan optimize

# 8. Cek permission lagi
ls -la storage/
ls -la bootstrap/cache/
```

---

## 📋 SCRIPT OTOMATIS LENGKAP

Buat file `fix-403-complete.sh`:

```bash
#!/bin/bash

echo "=== FIX 403 FORBIDDEN - COMPLETE ==="
echo ""

# Get current user
CURRENT_USER=$(whoami)
echo "Current user: $CURRENT_USER"
echo ""

# Set permission
echo "[1/10] Setting permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
echo "✓ Done"

# Set ownership
echo "[2/10] Setting ownership..."
chown -R $CURRENT_USER:$CURRENT_USER storage
chown -R $CURRENT_USER:$CURRENT_USER bootstrap/cache
echo "✓ Done"

# Create directories
echo "[3/10] Creating directories..."
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
echo "✓ Done"

# Clear cache
echo "[4/10] Clearing cache..."
php artisan cache:clear
echo "✓ Done"

echo "[5/10] Clearing config..."
php artisan config:clear
echo "✓ Done"

echo "[6/10] Clearing routes..."
php artisan route:clear
echo "✓ Done"

echo "[7/10] Clearing views..."
php artisan view:clear
echo "✓ Done"

# Recreate cache
echo "[8/10] Caching config..."
php artisan config:cache
echo "✓ Done"

echo "[9/10] Caching routes..."
php artisan route:cache
echo "✓ Done"

# Storage link
echo "[10/10] Creating storage link..."
php artisan storage:link
echo "✓ Done"

echo ""
echo "=== CHECKING PERMISSIONS ==="
ls -la storage/ | head -10
echo ""
ls -la bootstrap/cache/

echo ""
echo "=== ALL DONE! ==="
echo "Silakan coba login lagi."
echo ""
echo "Jika masih error, jalankan:"
echo "  tail -50 ~/logs/error_log"
echo ""
```

Jalankan:
```bash
bash fix-403-complete.sh
```

---

## 🆘 JIKA MASIH GAGAL

### Kirimkan informasi ini:

1. **Output command ini:**
```bash
ls -la storage/
ls -la bootstrap/cache/
pwd
whoami
php -v
```

2. **Error log web server:**
```bash
tail -50 ~/logs/error_log
# atau
tail -50 /usr/local/apache/logs/error_log
```

3. **Output test-permission.php**

4. **Informasi hosting:**
   - Nama provider hosting
   - Jenis hosting (shared/VPS)
   - Control panel (cPanel/Plesk/dll)

---

## 💡 KEMUNGKINAN PENYEBAB

Jika log kosong, kemungkinan besar:

1. ✅ **Permission storage salah** (775 atau 777)
2. ✅ **Ownership folder salah** (harus sama dengan web server user)
3. ✅ **ModSecurity memblokir POST request**
4. ✅ **Document root tidak mengarah ke folder public**
5. ✅ **Session folder tidak writable**
6. ✅ **PHP version terlalu rendah** (butuh >= 8.1)

---

## 🎯 QUICK TEST

Coba akses URL ini di browser:
```
https://yourdomain.com/test-permission.php
```

Jika muncul error atau 403, berarti masalah di **web server/permission**.
Jika berhasil dan tampil halaman test, berarti masalah di **Laravel/session**.
