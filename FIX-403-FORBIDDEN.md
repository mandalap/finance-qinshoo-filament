# Solusi 403 Forbidden Setelah Login di Hosting

## Masalah
- Halaman utama dan login tampil normal
- Setelah login, muncul error **403 Forbidden**
- Di local berjalan normal

## Penyebab Umum
1. **Permission folder storage dan bootstrap/cache salah**
2. **CSRF token tidak terkirim dengan benar**
3. **File .htaccess tidak lengkap**
4. **Session tidak bisa ditulis**
5. **Symbolic link storage belum dibuat**

---

## SOLUSI 1: Perbaiki Permission Folder (PALING PENTING)

### Via Terminal SSH (Recommended)
```bash
# Masuk ke folder aplikasi
cd /path/to/your/application

# Set permission untuk storage dan cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Set ownership (ganti 'username' dengan user hosting Anda)
chown -R username:username storage
chown -R username:username bootstrap/cache
```

### Via cPanel File Manager
1. Buka **File Manager** di cPanel
2. Navigate ke folder aplikasi Anda
3. Klik kanan pada folder **storage** → **Change Permissions**
   - Set ke **775** atau **755**
   - ✅ Centang "Recurse into subdirectories"
4. Ulangi untuk folder **bootstrap/cache**

---

## SOLUSI 2: Update .htaccess di Public

Pastikan file `public/.htaccess` memiliki konfigurasi lengkap:

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

## SOLUSI 3: Buat Symbolic Link untuk Storage

### Via Terminal SSH
```bash
php artisan storage:link
```

### Via cPanel Terminal atau SSH
```bash
cd /path/to/your/application
php artisan storage:link
```

Jika gagal, buat manual:
```bash
ln -s /path/to/your/application/storage/app/public /path/to/your/application/public/storage
```

---

## SOLUSI 4: Clear Cache di Hosting

```bash
# Clear semua cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## SOLUSI 5: Periksa File .env di Hosting

Pastikan konfigurasi session di `.env` sudah benar:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_DOMAIN=yourdomain.com
SESSION_SECURE_COOKIE=true

SANCTUM_STATEFUL_DOMAINS=yourdomain.com
```

**PENTING**: Ganti `yourdomain.com` dengan domain hosting Anda yang sebenarnya.

---

## SOLUSI 6: Update config/session.php (Jika Perlu)

Jika masih error, tambahkan konfigurasi ini di `config/session.php`:

```php
'same_site' => env('SESSION_SAME_SITE', 'lax'),
'secure' => env('SESSION_SECURE_COOKIE', false),
'http_only' => true,
```

---

## SOLUSI 7: Periksa PHP Version

Pastikan PHP version di hosting sama dengan di local:
- Laravel 10+ membutuhkan **PHP 8.1** atau lebih tinggi
- Filament 3+ membutuhkan **PHP 8.1** atau lebih tinggi

### Cek PHP Version di Hosting
```bash
php -v
```

### Ubah PHP Version di cPanel
1. Buka **MultiPHP Manager**
2. Pilih domain Anda
3. Set ke **PHP 8.1** atau **PHP 8.2**

---

## SOLUSI 8: Periksa Error Log

### Via Terminal
```bash
tail -f storage/logs/laravel.log
```

### Via cPanel
1. Buka **File Manager**
2. Navigate ke `storage/logs/laravel.log`
3. Lihat error terakhir

---

## CHECKLIST LENGKAP

Jalankan perintah ini satu per satu di hosting:

```bash
# 1. Set permission
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 2. Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 3. Buat symbolic link
php artisan storage:link

# 4. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 5. Cek permission
ls -la storage
ls -la bootstrap/cache
```

---

## JIKA MASIH ERROR

### Cek Error Detail
Tambahkan ini di `.env` sementara untuk melihat error detail:
```env
APP_DEBUG=true
```

**INGAT**: Setelah selesai debugging, kembalikan ke `APP_DEBUG=false`

### Hubungi Support Hosting
Jika semua solusi di atas tidak berhasil, kemungkinan ada konfigurasi khusus di hosting yang perlu disesuaikan. Tanyakan ke support hosting tentang:
- Permission yang direkomendasikan untuk Laravel
- Apakah ada security module (seperti ModSecurity) yang memblokir request POST
- Konfigurasi PHP-FPM atau Apache

---

## SOLUSI CEPAT (Quick Fix)

Jika Anda butuh solusi cepat, jalankan script ini di terminal hosting:

```bash
#!/bin/bash
cd /path/to/your/application
chmod -R 775 storage bootstrap/cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "Done! Silakan coba login lagi."
```

---

## Catatan Penting

1. **Jangan set permission 777** - Ini berbahaya untuk security
2. **Gunakan 775 atau 755** - Ini sudah cukup untuk Laravel
3. **Pastikan .env sudah benar** - Terutama APP_URL dan SESSION_DOMAIN
4. **Clear browser cache** - Kadang browser menyimpan CSRF token lama

---

## Kontak
Jika masih ada masalah, kirimkan:
1. Error message lengkap dari `storage/logs/laravel.log`
2. Screenshot error 403
3. Informasi hosting (shared hosting, VPS, dll)
