# 🔄 Git Workflow - Auto Push ke GitHub

Repository ini sudah terhubung ke GitHub: https://github.com/mandalap/finance-qinshoo-filament.git

## 🚀 Cara Push Update ke GitHub

### **Metode 1: Quick Push (Paling Mudah)**
```bash
git add .
git commit -m "Update: deskripsi perubahan"
git push origin main
```

### **Metode 2: Menggunakan Script (Paling Praktis) ⭐**

**Windows PowerShell:**
```powershell
.\git-push.ps1 "Update: deskripsi perubahan"
```

**Linux/Mac:**
```bash
chmod +x git-push.sh
./git-push.sh "Update: deskripsi perubahan"
```

Atau tanpa message (akan pakai timestamp):
```powershell
.\git-push.ps1
```

### **Metode 3: Auto Push dengan Hook (Otomatis)**
Setelah setiap commit, otomatis push ke GitHub (jika hook sudah diaktifkan).

## ⚙️ Setup Alias (Sekali Saja)

Jalankan command berikut untuk setup alias:

**Windows PowerShell:**
```powershell
git config --global alias.pushup '!git add . && git commit -m "Update: $(Get-Date -Format \"yyyy-MM-dd HH:mm:ss\")" && git push origin main'
```

**Linux/Mac:**
```bash
git config --global alias.pushup '!git add . && git commit -m "Update: $(date +%Y-%m-%d\ %H:%M:%S)" && git push origin main'
```

## 📝 Best Practices

1. **Commit Message yang Jelas**
   - Gunakan format: `"Update: deskripsi singkat perubahan"`
   - Contoh: `"Update: tambah fitur upload bukti transaksi"`

2. **Commit Sering**
   - Jangan tunggu terlalu lama untuk commit
   - Commit setiap fitur selesai

3. **Jangan Commit File Sensitif**
   - Pastikan `.env` sudah di `.gitignore`
   - Jangan commit file dengan data sensitif

## 🔍 Cek Status

```bash
# Cek status perubahan
git status

# Cek remote repository
git remote -v

# Cek commit history
git log --oneline
```

## 🆘 Troubleshooting

### Jika push gagal karena conflict:
```bash
# Pull dulu dari GitHub
git pull origin main

# Resolve conflict, lalu:
git add .
git commit -m "Merge: resolve conflict"
git push origin main
```

### Jika lupa commit message:
```bash
# Edit commit message terakhir
git commit --amend -m "Update: message baru"
git push origin main --force
```

### Reset jika ada kesalahan:
```bash
# Undo commit terakhir (tapi tetap simpan perubahan)
git reset --soft HEAD~1

# Atau undo commit dan perubahan
git reset --hard HEAD~1
```

## 📌 Catatan Penting

- ✅ Repository sudah terhubung ke GitHub
- ✅ Branch utama: `main`
- ✅ Remote: `origin` → https://github.com/mandalap/finance-qinshoo-filament.git
- ⚠️ Jangan force push ke main branch jika ada collaborator lain
- ⚠️ Selalu pull sebelum push jika bekerja dalam tim

---

**Happy Coding! 🎉**
