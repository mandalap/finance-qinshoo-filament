@echo off
REM ========================================
REM QUICK OPTIMIZATION SCRIPT
REM Untuk mempercepat loading halaman
REM ========================================

echo.
echo ========================================
echo   KEUANGAN FILAMENT - OPTIMIZATION
echo ========================================
echo.

echo [1/5] Clearing old cache...
call php artisan optimize:clear
echo.

echo [2/5] Caching configuration...
call php artisan config:cache
echo.

echo [3/5] Caching routes...
call php artisan route:cache
echo.

echo [4/5] Caching views...
call php artisan view:cache
echo.

echo [5/5] Optimizing autoloader...
call composer dump-autoload --optimize
echo.

echo ========================================
echo   OPTIMIZATION COMPLETE!
echo ========================================
echo.
echo Next steps:
echo 1. Refresh your browser
echo 2. Check LCP in DevTools (F12 ^> Lighthouse)
echo 3. Expected improvement: 30-40%% faster
echo.
echo For maximum speed, run: npm run build
echo.

pause
