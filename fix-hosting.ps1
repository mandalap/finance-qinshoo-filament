# FIX 403 FORBIDDEN - Laravel/Filament
# Script untuk clear cache dan optimize (Windows version)

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "  FIX 403 FORBIDDEN - Laravel/Filament" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "CATATAN: Script ini untuk testing di local Windows." -ForegroundColor Yellow
Write-Host "Untuk hosting Linux, gunakan file: fix-hosting.sh" -ForegroundColor Yellow
Write-Host ""

Write-Host "[1/6] Clearing application cache..." -ForegroundColor Yellow
php artisan cache:clear
Write-Host "✓ Cache cleared" -ForegroundColor Green

Write-Host ""
Write-Host "[2/6] Clearing configuration cache..." -ForegroundColor Yellow
php artisan config:clear
Write-Host "✓ Config cache cleared" -ForegroundColor Green

Write-Host ""
Write-Host "[3/6] Clearing route cache..." -ForegroundColor Yellow
php artisan route:clear
Write-Host "✓ Route cache cleared" -ForegroundColor Green

Write-Host ""
Write-Host "[4/6] Clearing view cache..." -ForegroundColor Yellow
php artisan view:clear
Write-Host "✓ View cache cleared" -ForegroundColor Green

Write-Host ""
Write-Host "[5/6] Creating storage symbolic link..." -ForegroundColor Yellow
php artisan storage:link
Write-Host "✓ Storage link created/verified" -ForegroundColor Green

Write-Host ""
Write-Host "[6/6] Optimizing application..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache
Write-Host "✓ Application optimized" -ForegroundColor Green

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "✓ ALL DONE!" -ForegroundColor Green
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "UNTUK HOSTING:" -ForegroundColor Yellow
Write-Host "1. Upload file fix-hosting.sh ke hosting" -ForegroundColor White
Write-Host "2. Login via SSH/Terminal di cPanel" -ForegroundColor White
Write-Host "3. Jalankan: bash fix-hosting.sh" -ForegroundColor White
Write-Host "4. Atau jalankan manual command di FIX-403-FORBIDDEN.md" -ForegroundColor White
Write-Host ""
Write-Host "Tips:" -ForegroundColor Cyan
Write-Host "- Clear browser cache (Ctrl+Shift+Del)" -ForegroundColor White
Write-Host "- Gunakan incognito/private mode" -ForegroundColor White
Write-Host "- Periksa .env file (APP_URL, SESSION_DOMAIN)" -ForegroundColor White
Write-Host ""
