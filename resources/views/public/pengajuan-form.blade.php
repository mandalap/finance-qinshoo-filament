<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengajuan Barang - Yayasan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 15px;
        }
        
        .container {
            max-width: 850px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            color: white;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 1.5rem;
            margin-bottom: 5px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .header p {
            font-size: 0.9rem;
            opacity: 0.95;
        }
        
        .form-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        .form-section {
            margin-bottom: 25px;
        }
        
        .form-section h2 {
            color: #667eea;
            font-size: 1.05rem;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-group {
            margin-bottom: 14px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .form-group label .required {
            color: #e74c3c;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1.5px solid #e0e0e0;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 70px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
        
        .error-message {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 0.85rem;
        }
        
        .error-message ul {
            margin-left: 18px;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 35px;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }
        
        .submit-btn:active {
            transform: translateY(0);
        }
        
        .info-text {
            font-size: 0.75rem;
            color: #666;
            margin-top: 4px;
        }
        
        /* Repeater Styles */
        .barang-item {
            background: #f8f9fa;
            border: 1.5px solid #e9ecef;
            border-radius: 10px;
            padding: 18px;
            margin-bottom: 14px;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .barang-item:hover {
            border-color: #667eea;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.08);
        }
        
        .barang-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }
        
        .barang-number {
            font-weight: 700;
            color: #667eea;
            font-size: 0.95rem;
        }
        
        .remove-barang-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 5px 14px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .remove-barang-btn:hover {
            background: #c0392b;
            transform: scale(1.05);
        }
        
        .add-barang-btn {
            background: #27ae60;
            color: white;
            border: none;
            padding: 9px 24px;
            border-radius: 7px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .add-barang-btn:hover {
            background: #229954;
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(39, 174, 96, 0.25);
        }
        
        @media (max-width: 768px) {
            .form-card {
                padding: 18px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 1.3rem;
            }
            
            .barang-item {
                padding: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Form Pengajuan Barang</h1>
            <p>Sistem Pencatatan Pengajuan Barang Yayasan</p>
        </div>
        
        <div class="form-card">
            @if ($errors->any())
                <div class="error-message">
                    <strong>Terdapat kesalahan pada form:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('pengajuan.store') }}" method="POST" id="pengajuanForm">
                @csrf
                
                <!-- Data Pengaju -->
                <div class="form-section">
                    <h2>👤 Data Pengaju</h2>
                    
                    <div class="form-group">
                        <label>Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="nama_pengaju" value="{{ old('nama_pengaju') }}" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Divisi / Bidang <span class="required">*</span></label>
                            <input type="text" name="divisi" value="{{ old('divisi') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Jabatan <span class="required">*</span></label>
                            <input type="text" name="jabatan" value="{{ old('jabatan') }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Kontak (WhatsApp/Email)</label>
                        <input type="text" name="kontak" value="{{ old('kontak') }}" placeholder="Opsional">
                    </div>
                </div>
                
                <!-- Detail Barang (Repeater) -->
                <div class="form-section">
                    <h2>📦 Detail Barang</h2>
                    <p class="info-text" style="margin-bottom: 14px;">Anda dapat menambahkan lebih dari satu barang dalam pengajuan ini</p>
                    
                    <div id="barangContainer">
                        <!-- Item barang akan ditambahkan di sini via JavaScript -->
                    </div>
                    
                    <button type="button" class="add-barang-btn" onclick="addBarangItem()">
                        ➕ Tambah Barang
                    </button>
                </div>
                
                <!-- Kebutuhan -->
                <div class="form-section">
                    <h2>🎯 Kebutuhan</h2>
                    
                    <div class="form-group">
                        <label>Tujuan Pengajuan <span class="required">*</span></label>
                        <textarea name="tujuan_pengajuan" required>{{ old('tujuan_pengajuan') }}</textarea>
                        <p class="info-text">Jelaskan untuk apa barang-barang ini dibutuhkan</p>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Tanggal Dibutuhkan <span class="required">*</span></label>
                            <input type="date" name="tanggal_dibutuhkan" value="{{ old('tanggal_dibutuhkan') }}" min="{{ date('Y-m-d') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Tingkat Urgensi <span class="required">*</span></label>
                            <select name="tingkat_urgensi" required>
                                <option value="">-- Pilih --</option>
                                <option value="normal" {{ old('tingkat_urgensi') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="mendesak" {{ old('tingkat_urgensi') == 'mendesak' ? 'selected' : '' }}>Mendesak</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="submit-btn">
                    📤 Kirim Pengajuan
                </button>
            </form>
        </div>
    </div>

    <script>
        let barangCount = 0;
        
        // Add first item on page load
        document.addEventListener('DOMContentLoaded', function() {
            addBarangItem();
        });
        
        function addBarangItem() {
            barangCount++;
            const container = document.getElementById('barangContainer');
            
            const itemDiv = document.createElement('div');
            itemDiv.className = 'barang-item';
            itemDiv.id = `barang-item-${barangCount}`;
            
            itemDiv.innerHTML = `
                <div class="barang-header">
                    <span class="barang-number">Barang #${barangCount}</span>
                    ${barangCount > 1 ? `<button type="button" class="remove-barang-btn" onclick="removeBarangItem(${barangCount})">🗑️ Hapus</button>` : ''}
                </div>
                
                <div class="form-group">
                    <label>Nama Barang <span class="required">*</span></label>
                    <input type="text" name="barang[${barangCount}][nama_barang]" required>
                </div>
                
                <div class="form-group">
                    <label>Spesifikasi Barang <span class="required">*</span></label>
                    <textarea name="barang[${barangCount}][spesifikasi_barang]" required></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Jumlah <span class="required">*</span></label>
                        <input type="number" name="barang[${barangCount}][jumlah]" min="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Satuan <span class="required">*</span></label>
                        <input type="text" name="barang[${barangCount}][satuan]" placeholder="Unit, Pcs, Box, dll" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Estimasi Harga (Rp) <span class="required">*</span></label>
                    <input type="number" name="barang[${barangCount}][estimasi_harga]" min="0" step="0.01" required>
                </div>
            `;
            
            container.appendChild(itemDiv);
            updateBarangNumbers();
        }
        
        function removeBarangItem(id) {
            const item = document.getElementById(`barang-item-${id}`);
            if (item) {
                item.remove();
                updateBarangNumbers();
            }
        }
        
        function updateBarangNumbers() {
            const items = document.querySelectorAll('.barang-item');
            items.forEach((item, index) => {
                const numberSpan = item.querySelector('.barang-number');
                if (numberSpan) {
                    numberSpan.textContent = `Barang #${index + 1}`;
                }
            });
        }
    </script>
</body>
</html>
