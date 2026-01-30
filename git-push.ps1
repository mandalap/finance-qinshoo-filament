# PowerShell Script untuk Auto Push ke GitHub
# Usage: .\git-push.ps1 "Update: deskripsi perubahan"

param(
    [string]$Message = "Update: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
)

Write-Host "🔄 Starting auto-push to GitHub..." -ForegroundColor Cyan

# Add all changes
Write-Host "📦 Adding files..." -ForegroundColor Yellow
git add .

# Check if there are changes
$status = git status --porcelain
if ([string]::IsNullOrWhiteSpace($status)) {
    Write-Host "⚠️  No changes to commit!" -ForegroundColor Yellow
    exit 0
}

# Commit
Write-Host "💾 Committing changes..." -ForegroundColor Yellow
git commit -m $Message

if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Commit failed!" -ForegroundColor Red
    exit 1
}

# Push
Write-Host "🚀 Pushing to GitHub..." -ForegroundColor Yellow
git push origin main

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Successfully pushed to GitHub!" -ForegroundColor Green
} else {
    Write-Host "❌ Push failed!" -ForegroundColor Red
    exit 1
}
