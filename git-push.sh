#!/bin/bash
# Bash Script untuk Auto Push ke GitHub
# Usage: ./git-push.sh "Update: deskripsi perubahan"

MESSAGE=${1:-"Update: $(date +%Y-%m-%d\ %H:%M:%S)"}

echo "🔄 Starting auto-push to GitHub..."

# Add all changes
echo "📦 Adding files..."
git add .

# Check if there are changes
if [ -z "$(git status --porcelain)" ]; then
    echo "⚠️  No changes to commit!"
    exit 0
fi

# Commit
echo "💾 Committing changes..."
git commit -m "$MESSAGE"

if [ $? -ne 0 ]; then
    echo "❌ Commit failed!"
    exit 1
fi

# Push
echo "🚀 Pushing to GitHub..."
git push origin main

if [ $? -eq 0 ]; then
    echo "✅ Successfully pushed to GitHub!"
else
    echo "❌ Push failed!"
    exit 1
fi
