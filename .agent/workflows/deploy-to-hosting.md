---
description: Deploy aplikasi dari GitHub ke hosting via terminal
---

# 🚀 Deploy ke Hosting via Git Pull

Panduan lengkap untuk pull code dari GitHub ke hosting menggunakan terminal/SSH.

---

## 📋 Persiapan Awal

### 1. Pastikan Anda Punya Akses SSH ke Hosting
```bash
# Login ke hosting via SSH
ssh username@your-hosting-domain.com
# atau
ssh username@123.456.789.0
```

### 2. Cek Apakah Git Sudah Terinstall
```bash
git --version
```
Jika belum terinstall, hubungi hosting provider untuk install Git.

---

## 🔧 Setup Pertama Kali (Clone Repository)

### 1. Masuk ke Directory Web Root
```bash
# Biasanya di salah satu dari ini:
cd /home/username/public_html
# atau
cd /var/www/html
# atau
cd ~/htdocs
```

### 2. Backup File Lama (Opsional tapi Disarankan)
```bash
# Buat backup folder
mkdir -p ../backups
tar -czf ../backups/backup-$(date +%Y%m%d-%H%M%S).tar.gz .
```

### 3. Clone Repository dari GitHub
```bash
# Jika folder kosong, langsung clone:
git clone https://github.com/mandalap/finance-qinshoo-filament.git .

# Jika folder tidak kosong, clone ke folder baru dulu:
cd ..
git clone https://github.com/mandalap/finance-qinshoo-filament.git temp-app
mv temp-app/* public_html/
mv temp-app/.* public_html/ 2>/dev/null
rm -rf temp-app
cd public_html
```

### 4. Install Dependencies
```bash
# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Install NPM dependencies (jika perlu)
npm install
npm run build
```

### 5. Setup Environment
```bash
# Copy .env.example ke .env
cp .env.example .env

# Edit .env dengan nano atau vi
nano .env
```

**Isi .env dengan konfigurasi hosting:**
```env
APP_NAME="Sistem Keuangan Yayasan"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# ... konfigurasi lainnya
```

### 6. Generate App Key
```bash
php artisan key:generate
```

### 7. Setup Database
```bash
# Run migrations
php artisan migrate --force

# Seed data (jika perlu)
php artisan db:seed --force
```

### 8. Setup Permissions
```bash
# Set permissions untuk storage dan cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# atau jika menggunakan user lain:
chown -R username:username storage bootstrap/cache
```

### 9. Optimize untuk Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:cache-components
```

---

## 🔄 Update Aplikasi (Git Pull)

Setelah setup awal, untuk update aplikasi selanjutnya:

### 1. Login ke Hosting via SSH
```bash
ssh username@your-hosting-domain.com
```

### 2. Masuk ke Directory Aplikasi
```bash
cd /home/username/public_html
# atau sesuai path Anda
```

### 3. Backup Database (Sangat Disarankan!)
```bash
# Backup database
php artisan backup:run --only-db

# atau manual dengan mysqldump:
mysqldump -u username -p database_name > ../backups/db-backup-$(date +%Y%m%d-%H%M%S).sql
```

### 4. Set Aplikasi ke Maintenance Mode
```bash
php artisan down
```

### 5. Pull Latest Code dari GitHub
```bash
# Cek status git
git status

# Stash perubahan lokal (jika ada)
git stash

# Pull dari GitHub
git pull origin main

# atau jika ada conflict:
git fetch origin
git reset --hard origin/main
```

### 6. Update Dependencies
```bash
# Update Composer dependencies
composer install --no-dev --optimize-autoloader

# Update NPM dependencies (jika ada perubahan)
npm install
npm run build
```

### 7. Run Migrations (Jika Ada)
```bash
# Check migrations
php artisan migrate:status

# Run new migrations
php artisan migrate --force
```

### 8. Clear & Rebuild Cache
```bash
# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:cache-components
```

### 9. Set Permissions (Jika Perlu)
```bash
chmod -R 775 storage bootstrap/cache
```

### 10. Bring Aplikasi Back Online
```bash
php artisan up
```

---

## 🔐 Setup SSH Key untuk GitHub (Opsional)

Agar tidak perlu input password setiap pull:

### 1. Generate SSH Key di Hosting
```bash
ssh-keygen -t ed25519 -C "your-email@example.com"
# Tekan Enter untuk default location
# Tekan Enter untuk no passphrase (atau buat passphrase)
```

### 2. Copy Public Key
```bash
cat ~/.ssh/id_ed25519.pub
```

### 3. Tambahkan ke GitHub
1. Buka GitHub → Settings → SSH and GPG keys
2. Click "New SSH key"
3. Paste public key
4. Save

### 4. Test Connection
```bash
ssh -T git@github.com
```

### 5. Update Remote URL ke SSH
```bash
git remote set-url origin git@github.com:mandalap/finance-qinshoo-filament.git
```

---

## 🛡️ Tips Keamanan

### 1. Jangan Commit File Sensitif
Pastikan `.env` ada di `.gitignore`:
```bash
cat .gitignore | grep .env
```

### 2. Set Proper Permissions
```bash
# File permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;

# Storage & cache
chmod -R 775 storage bootstrap/cache
```

### 3. Disable Directory Listing
Tambahkan di `.htaccess`:
```apache
Options -Indexes
```

### 4. Protect .env File
Tambahkan di `.htaccess`:
```apache
<Files .env>
    Order allow,deny
    Deny from all
</Files>
```

---

## 🐛 Troubleshooting

### Error: Permission Denied
```bash
# Fix permissions
sudo chown -R www-data:www-data .
sudo chmod -R 775 storage bootstrap/cache
```

### Error: Composer Not Found
```bash
# Install composer locally
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"

# Use it:
php composer.phar install
```

### Error: Git Pull Conflict
```bash
# Hard reset to remote
git fetch origin
git reset --hard origin/main
```

### Error: Storage Link Not Working
```bash
php artisan storage:link
```

---

## 📝 Checklist Deploy

- [ ] Backup database
- [ ] Set maintenance mode (`php artisan down`)
- [ ] Pull latest code (`git pull origin main`)
- [ ] Update dependencies (`composer install`)
- [ ] Run migrations (`php artisan migrate --force`)
- [ ] Clear cache (`php artisan cache:clear`)
- [ ] Rebuild cache (`php artisan config:cache`)
- [ ] Test aplikasi
- [ ] Bring back online (`php artisan up`)

---

## 🎯 Quick Command Reference

```bash
# Quick update (copy-paste semua):
php artisan down && \
git pull origin main && \
composer install --no-dev --optimize-autoloader && \
php artisan migrate --force && \
php artisan cache:clear && \
php artisan config:cache && \
php artisan route:cache && \
php artisan view:cache && \
php artisan filament:cache-components && \
php artisan up

# Rollback jika ada masalah:
git reset --hard HEAD~1
php artisan migrate:rollback
php artisan up
```

---

## 📞 Support

Jika ada masalah:
1. Cek log: `tail -f storage/logs/laravel.log`
2. Cek error log hosting
3. Contact hosting support jika masalah server

---

**Selamat Deploy! 🚀**
