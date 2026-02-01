#!/bin/bash

echo "=========================================="
echo "  FIX 403 FORBIDDEN - COMPLETE VERSION"
echo "=========================================="
echo ""

# Warna
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

# Get current user
CURRENT_USER=$(whoami)
echo -e "${BLUE}Current user: $CURRENT_USER${NC}"
echo -e "${BLUE}Current directory: $(pwd)${NC}"
echo ""

# 1. Set permission
echo -e "${YELLOW}[1/12] Setting permissions for storage...${NC}"
chmod -R 775 storage
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Storage permissions set to 775${NC}"
else
    echo -e "${RED}✗ Failed to set storage permissions${NC}"
fi

echo ""
echo -e "${YELLOW}[2/12] Setting permissions for bootstrap/cache...${NC}"
chmod -R 775 bootstrap/cache
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Bootstrap cache permissions set to 775${NC}"
else
    echo -e "${RED}✗ Failed to set bootstrap cache permissions${NC}"
fi

# 2. Set ownership
echo ""
echo -e "${YELLOW}[3/12] Setting ownership for storage...${NC}"
chown -R $CURRENT_USER:$CURRENT_USER storage 2>/dev/null
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Storage ownership updated${NC}"
else
    echo -e "${YELLOW}⚠ Could not change ownership (might need sudo)${NC}"
fi

echo ""
echo -e "${YELLOW}[4/12] Setting ownership for bootstrap/cache...${NC}"
chown -R $CURRENT_USER:$CURRENT_USER bootstrap/cache 2>/dev/null
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Bootstrap cache ownership updated${NC}"
else
    echo -e "${YELLOW}⚠ Could not change ownership (might need sudo)${NC}"
fi

# 3. Create directories
echo ""
echo -e "${YELLOW}[5/12] Creating required directories...${NC}"
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
mkdir -p storage/app/public
echo -e "${GREEN}✓ Directories created${NC}"

# 4. Clear cache
echo ""
echo -e "${YELLOW}[6/12] Clearing application cache...${NC}"
php artisan cache:clear
echo -e "${GREEN}✓ Cache cleared${NC}"

echo ""
echo -e "${YELLOW}[7/12] Clearing configuration cache...${NC}"
php artisan config:clear
echo -e "${GREEN}✓ Config cache cleared${NC}"

echo ""
echo -e "${YELLOW}[8/12] Clearing route cache...${NC}"
php artisan route:clear
echo -e "${GREEN}✓ Route cache cleared${NC}"

echo ""
echo -e "${YELLOW}[9/12] Clearing view cache...${NC}"
php artisan view:clear
echo -e "${GREEN}✓ View cache cleared${NC}"

# 5. Recreate cache
echo ""
echo -e "${YELLOW}[10/12] Optimizing application...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✓ Application optimized${NC}"

# 6. Storage link
echo ""
echo -e "${YELLOW}[11/12] Creating storage symbolic link...${NC}"
php artisan storage:link 2>/dev/null
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Storage link created${NC}"
else
    echo -e "${YELLOW}⚠ Storage link might already exist${NC}"
fi

# 7. Final optimize
echo ""
echo -e "${YELLOW}[12/12] Final optimization...${NC}"
php artisan optimize
echo -e "${GREEN}✓ Done${NC}"

# Show current permissions
echo ""
echo "=========================================="
echo -e "${BLUE}CURRENT PERMISSIONS:${NC}"
echo "=========================================="
echo ""
echo -e "${YELLOW}Storage folder:${NC}"
ls -la storage/ | head -10
echo ""
echo -e "${YELLOW}Bootstrap cache:${NC}"
ls -la bootstrap/cache/
echo ""

# Show PHP version
echo "=========================================="
echo -e "${BLUE}SYSTEM INFO:${NC}"
echo "=========================================="
echo -e "${YELLOW}PHP Version:${NC} $(php -v | head -1)"
echo -e "${YELLOW}Current User:${NC} $CURRENT_USER"
echo -e "${YELLOW}Working Directory:${NC} $(pwd)"
echo ""

# Final message
echo "=========================================="
echo -e "${GREEN}✓ ALL DONE!${NC}"
echo "=========================================="
echo ""
echo -e "${BLUE}Next steps:${NC}"
echo "1. Clear browser cache (Ctrl+Shift+Del)"
echo "2. Try login again"
echo "3. If still error, check web server error log:"
echo "   ${YELLOW}tail -50 ~/logs/error_log${NC}"
echo ""
echo -e "${BLUE}Test permission:${NC}"
echo "   ${YELLOW}https://yourdomain.com/test-permission.php${NC}"
echo ""
