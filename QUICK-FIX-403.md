# 🚨 QUICK FIX: 403 Forbidden Setelah Login

## ⚡ SOLUSI TERCEPAT (5 Menit)

### Via SSH/Terminal Hosting:

```bash
# 1. Masuk ke folder aplikasi
cd /home/username/public_html/your-app

# 2. Set permission
chmod -R 775 storage bootstrap/cache

# 3. Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 4. Optimize
php artisan config:cache
php artisan route:cache
php artisan optimize

# 5. Storage link
php artisan storage:link
```

### Atau gunakan script otomatis:
```bash
bash fix-hosting.sh
```

---

## 🔧 CHECKLIST CEPAT

- [ ] **Permission folder storage = 775**
- [ ] **Permission folder bootstrap/cache = 775**
- [ ] **Clear semua cache**
- [ ] **File .env sudah benar** (APP_URL, SESSION_DOMAIN)
- [ ] **PHP version >= 8.1**
- [ ] **Storage link sudah dibuat**
- [ ] **Clear browser cache**

---

## 📋 KONFIGURASI .ENV PENTING

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

---

## 🔍 CEK ERROR LOG

```bash
tail -50 storage/logs/laravel.log
```

---

## 💡 TIPS TAMBAHAN

1. **Clear browser cache** (Ctrl+Shift+Del)
2. **Coba incognito mode**
3. **Pastikan HTTPS aktif** jika SESSION_SECURE_COOKIE=true
4. **Cek PHP version**: `php -v` (minimal 8.1)

---

## 📞 MASIH ERROR?

Lihat file lengkap: **FIX-403-FORBIDDEN.md**

Atau kirim error dari: `storage/logs/laravel.log`
