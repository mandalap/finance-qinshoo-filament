#!/bin/bash

echo "=========================================="
echo "  FIX 403 FORBIDDEN - Laravel/Filament"
echo "=========================================="
echo ""

# Warna untuk output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${YELLOW}[1/8] Setting permissions for storage...${NC}"
chmod -R 775 storage
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Storage permissions updated${NC}"
else
    echo -e "${RED}✗ Failed to update storage permissions${NC}"
fi

echo ""
echo -e "${YELLOW}[2/8] Setting permissions for bootstrap/cache...${NC}"
chmod -R 775 bootstrap/cache
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Bootstrap cache permissions updated${NC}"
else
    echo -e "${RED}✗ Failed to update bootstrap cache permissions${NC}"
fi

echo ""
echo -e "${YELLOW}[3/8] Clearing application cache...${NC}"
php artisan cache:clear
echo -e "${GREEN}✓ Cache cleared${NC}"

echo ""
echo -e "${YELLOW}[4/8] Clearing configuration cache...${NC}"
php artisan config:clear
echo -e "${GREEN}✓ Config cache cleared${NC}"

echo ""
echo -e "${YELLOW}[5/8] Clearing route cache...${NC}"
php artisan route:clear
echo -e "${GREEN}✓ Route cache cleared${NC}"

echo ""
echo -e "${YELLOW}[6/8] Clearing view cache...${NC}"
php artisan view:clear
echo -e "${GREEN}✓ View cache cleared${NC}"

echo ""
echo -e "${YELLOW}[7/8] Creating storage symbolic link...${NC}"
php artisan storage:link
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Storage link created${NC}"
else
    echo -e "${YELLOW}⚠ Storage link might already exist${NC}"
fi

echo ""
echo -e "${YELLOW}[8/8] Optimizing application...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✓ Application optimized${NC}"

echo ""
echo "=========================================="
echo -e "${GREEN}✓ ALL DONE!${NC}"
echo "=========================================="
echo ""
echo "Silakan coba login lagi di browser Anda."
echo "Jika masih error, cek file: storage/logs/laravel.log"
echo ""
echo "Tips:"
echo "- Clear browser cache (Ctrl+Shift+Del)"
echo "- Gunakan incognito/private mode"
echo "- Periksa .env file (APP_URL, SESSION_DOMAIN)"
echo ""
