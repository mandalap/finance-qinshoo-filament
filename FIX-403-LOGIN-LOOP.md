# 📝 Panduan Fix Error 403 Forbidden & Login Loop

## 🔍 Masalah yang Sering Terjadi

1. **Error 403 Forbidden** setelah login
2. **Login Loop** - Redirect kembali ke halaman login setelah submit
3. **Session tidak tersimpan**

---

## ✅ Solusi Lengkap

### 1. **Fix User Model - Implements FilamentUser Interface**

**Masalah:**
- User model tidak implements `FilamentUser`
- Method `canAccessPanel()` tidak ada atau tidak lengkap

**Solusi:**

Edit file: `app/Models/User.php`

```php
<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;  // ← TAMBAHKAN
use Filament\Panel;                           // ← TAMBAHKAN
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser  // ← TAMBAHKAN implements
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ← TAMBAHKAN METHOD INI
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
```

**Yang Ditambahkan:**
1. `use Filament\Models\Contracts\FilamentUser;`
2. `use Filament\Panel;`
3. `implements FilamentUser` di class declaration
4. Method `canAccessPanel(Panel $panel): bool`

---

### 2. **Fix Konfigurasi Session di .env**

**Masalah:**
- Session domain tidak benar
- Session tidak tersimpan karena cookie domain mismatch

**Solusi:**

Edit file: `.env`

```env
# Session Configuration
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=.wablitz.com          # ← PENTING: Pakai titik (.) di depan
SESSION_SECURE_COOKIE=true           # ← TAMBAHKAN
SESSION_SAME_SITE=lax                # ← TAMBAHKAN

# Sanctum Configuration
SANCTUM_STATEFUL_DOMAINS=wablitz.com,www.wablitz.com
```

**Poin Penting:**
- `SESSION_DOMAIN=.wablitz.com` - **HARUS pakai titik (.) di depan domain**
- `SESSION_SECURE_COOKIE=true` - Untuk HTTPS
- `SESSION_SAME_SITE=lax` - Untuk keamanan cookie

---

### 3. **Fix Permission Storage & Bootstrap Cache**

**Masalah:**
- Laravel tidak bisa menulis session file
- Permission folder storage salah

**Solusi:**

```bash
# Set permission yang benar
chmod -R 775 ~/laravel/storage
chmod -R 775 ~/laravel/bootstrap/cache

# Set ownership
chown -R wablitzc:wablitzc ~/laravel/storage
chown -R wablitzc:wablitzc ~/laravel/bootstrap/cache
```

**Verifikasi:**
```bash
ls -la ~/laravel/storage/framework/sessions/
# Harus: drwxrwxr-x
```

---

### 4. **Clear Cache Setelah Perubahan**

**Wajib dilakukan setiap kali edit .env atau config:**

```bash
cd ~/laravel

# Clear semua cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Hapus session lama
rm -rf storage/framework/sessions/*
rm -rf storage/framework/cache/data/*

# Optimize ulang
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

### 5. **Clear Browser Cookies & Cache**

**Penting dilakukan setelah fix:**

1. Buka browser
2. Tekan `Ctrl + Shift + Delete`
3. Pilih:
   - ✅ Cookies and other site data
   - ✅ Cached images and files
4. Time range: **All time**
5. Klik **Clear data**
6. **Tutup semua tab browser**
7. **Buka browser baru atau Incognito mode**

---

## 🔧 Troubleshooting Checklist

Jika masalah serupa terjadi lagi, cek satu per satu:

### ✅ Checklist 1: User Model
```bash
# Cek apakah implements FilamentUser
grep "implements FilamentUser" ~/laravel/app/Models/User.php

# Cek apakah ada method canAccessPanel
grep "canAccessPanel" ~/laravel/app/Models/User.php
```

**Harus muncul kedua-duanya!**

---

### ✅ Checklist 2: Session Configuration
```bash
# Cek konfigurasi session di .env
cat ~/laravel/.env | grep SESSION_
```

**Harus ada:**
- `SESSION_DOMAIN=.wablitz.com` (dengan titik di depan)
- `SESSION_SECURE_COOKIE=true`

---

### ✅ Checklist 3: Permission
```bash
# Cek permission storage
ls -la ~/laravel/storage/framework/sessions/

# Harus: drwxrwxr-x (775)
```

**Jika salah, fix dengan:**
```bash
chmod -R 775 ~/laravel/storage
chmod -R 775 ~/laravel/bootstrap/cache
```

---

### ✅ Checklist 4: Session File Dibuat
```bash
# Cek apakah session file dibuat setelah login
ls -la ~/laravel/storage/framework/sessions/
```

**Harus ada file baru** setelah login.

**Jika tidak ada:**
- Permission salah
- Session driver salah
- Cookie domain mismatch

---

### ✅ Checklist 5: Cache Sudah Clear
```bash
# Verifikasi cache sudah clear
php artisan config:show session

# Harus sesuai dengan .env
```

---

## 🚨 Error 403 vs Login Loop - Perbedaan

### Error 403 Forbidden
**Penyebab:**
- ModSecurity memblokir POST request
- Permission file/folder salah
- .htaccess bermasalah

**Solusi:**
- Hubungi support hosting untuk disable ModSecurity
- Fix permission: `chmod -R 775 storage`
- Cek .htaccess di `public_html/`

---

### Login Loop (Redirect ke Login Terus)
**Penyebab:**
- User model tidak implements `FilamentUser`
- Session tidak tersimpan (domain/cookie mismatch)
- Method `canAccessPanel()` return false

**Solusi:**
- Fix User model (implements FilamentUser)
- Fix SESSION_DOMAIN di .env (pakai titik di depan)
- Clear browser cookies

---

## 📋 Command Cepat untuk Fix

**Copy-paste command ini jika masalah muncul lagi:**

```bash
# 1. Fix permission
chmod -R 775 ~/laravel/storage ~/laravel/bootstrap/cache

# 2. Clear cache & session
cd ~/laravel
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
rm -rf storage/framework/sessions/*
rm -rf storage/framework/cache/data/*

# 3. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 4. Verifikasi session
ls -la storage/framework/sessions/
```

Lalu **clear browser cookies** dan test login lagi.

---

## 💡 Tips Pencegahan

### 1. Setiap Deploy/Update Code:
```bash
cd ~/laravel
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan optimize:clear
php artisan optimize
```

### 2. Jangan Edit File di Hosting Langsung
- Edit di lokal
- Push ke GitHub
- Pull di hosting

### 3. Backup .env
```bash
cp ~/laravel/.env ~/laravel/.env.backup
```

### 4. Monitor Log
```bash
tail -f ~/laravel/storage/logs/laravel.log
```

---

## 📞 Kapan Harus Hubungi Support Hosting

Hubungi support jika:
- ✅ Error 403 **setelah** fix User model & session
- ✅ ModSecurity memblokir POST request
- ✅ Permission tidak bisa diubah
- ✅ Session file tidak bisa dibuat meski permission sudah 775

**Template pesan:**
```
Halo Support,

Aplikasi Laravel Filament saya mengalami error 403 setelah login.
Saya sudah coba fix permission dan session configuration.
Sepertinya ModSecurity memblokir POST request ke /admin/login.

Mohon bantuan untuk disable ModSecurity untuk domain wablitz.com.

Terima kasih!
```

---

## 🔍 Debug Session Step-by-Step

### 1. Cek Apakah Session File Dibuat
```bash
# Sebelum login
ls -la ~/laravel/storage/framework/sessions/

# Login di browser

# Setelah login
ls -la ~/laravel/storage/framework/sessions/
```

**Harus ada file baru!**

---

### 2. Cek Isi Session File
```bash
# Ganti dengan nama file session yang baru dibuat
cat ~/laravel/storage/framework/sessions/NAMA_FILE_SESSION
```

**Harus ada data authentication!**

---

### 3. Test Authentication Manual
```bash
cd ~/laravel
php artisan tinker
```

```php
// Test user
$user = \App\Models\User::where('email', 'admin@yayasan.com')->first();
$user->email;
$user->name;

// Test password
\Hash::check('password_anda', $user->password);

// Test canAccessPanel
method_exists($user, 'canAccessPanel');

exit
```

---

### 4. Monitor Log Real-time
```bash
# Terminal 1 - Monitor Laravel log
tail -f ~/laravel/storage/logs/laravel.log

# Terminal 2 - Monitor session files
watch -n 1 'ls -lah ~/laravel/storage/framework/sessions/ | tail -5'
```

Lalu **login di browser** dan lihat output di kedua terminal.

---

## 🎯 Kesimpulan

**3 Hal Utama yang Harus Benar:**

1. ✅ **User Model** - Implements `FilamentUser` + method `canAccessPanel()`
2. ✅ **Session Config** - `SESSION_DOMAIN=.domain.com` (dengan titik)
3. ✅ **Permission** - Storage & cache 775

**Jika ketiga hal ini benar, login pasti berhasil!**

---

## 📚 Referensi

- [Filament Documentation - Users](https://filamentphp.com/docs/3.x/panels/users)
- [Laravel Session Documentation](https://laravel.com/docs/11.x/session)
- [Laravel Authentication](https://laravel.com/docs/11.x/authentication)

---

**Dibuat:** 2026-02-01  
**Terakhir Update:** 2026-02-01  
**Status:** ✅ Tested & Working
