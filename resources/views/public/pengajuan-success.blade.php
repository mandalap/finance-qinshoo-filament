<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Berhasil - Yayasan</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }
        
        .success-card {
            background: white;
            border-radius: 12px;
            padding: 35px;
            max-width: 650px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            animation: slideUp 0.5s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .success-icon {
            font-size: 3.5rem;
            margin-bottom: 15px;
            animation: bounce 1s ease;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-15px);
            }
            60% {
                transform: translateY(-8px);
            }
        }
        
        h1 {
            color: #27ae60;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        .message {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        
        .info-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 18px;
            margin: 20px 0;
            text-align: left;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 9px 0;
            border-bottom: 1px solid #e0e0e0;
            font-size: 0.9rem;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #555;
        }
        
        .info-value {
            color: #333;
            font-weight: 500;
        }
        
        .nomor-pengajuan {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 1.15rem;
            font-weight: 700;
            margin: 15px 0;
            display: inline-block;
        }
        
        .status-badge {
            display: inline-block;
            background: #ffc107;
            color: #333;
            padding: 6px 16px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .barang-list {
            margin-top: 14px;
        }
        
        .barang-item {
            background: white;
            border: 1.5px solid #e9ecef;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            text-align: left;
        }
        
        .barang-header {
            font-weight: 700;
            color: #667eea;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }
        
        .barang-detail {
            font-size: 0.85rem;
            color: #666;
            line-height: 1.5;
        }
        
        .btn-home {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            font-size: 0.95rem;
        }
        
        .btn-home:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }
        
        .note {
            background: #e3f2fd;
            border-left: 3px solid #2196f3;
            padding: 12px;
            margin-top: 18px;
            text-align: left;
            border-radius: 4px;
            font-size: 0.85rem;
        }
        
        .note strong {
            color: #1976d2;
        }
        
        @media (max-width: 768px) {
            .success-card {
                padding: 25px 18px;
            }
            
            h1 {
                font-size: 1.3rem;
            }
            
            .nomor-pengajuan {
                font-size: 1rem;
            }
            
            .info-row {
                flex-direction: column;
                gap: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="success-card">
        <div class="success-icon">✅</div>
        
        <h1>Pengajuan Berhasil Dikirim!</h1>
        
        <p class="message">
            Terima kasih atas pengajuan Anda. Pengajuan barang telah berhasil dicatat dalam sistem dan akan segera ditinjau oleh pengurus yayasan.
        </p>
        
        <div class="nomor-pengajuan">
            {{ $pengajuan->nomor_pengajuan }}
        </div>
        
        <div class="info-box">
            <div class="info-row">
                <span class="info-label">Nama Pengaju:</span>
                <span class="info-value">{{ $pengajuan->nama_pengaju }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Jumlah Barang:</span>
                <span class="info-value">{{ $pengajuan->detailBarang->count() }} item</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Pengajuan:</span>
                <span class="info-value">{{ $pengajuan->tanggal_pengajuan->format('d F Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="status-badge">{{ $pengajuan->status->getLabel() }}</span>
            </div>
        </div>
        
        <div class="info-box">
            <h3 style="color: #667eea; margin-bottom: 12px; font-size: 1rem;">📦 Detail Barang yang Diajukan</h3>
            <div class="barang-list">
                @foreach($pengajuan->detailBarang as $index => $barang)
                <div class="barang-item">
                    <div class="barang-header">{{ $index + 1 }}. {{ $barang->nama_barang }}</div>
                    <div class="barang-detail">
                        <strong>Spesifikasi:</strong> {{ $barang->spesifikasi_barang }}<br>
                        <strong>Jumlah:</strong> {{ $barang->jumlah }} {{ $barang->satuan }}<br>
                        <strong>Estimasi Harga:</strong> Rp {{ number_format($barang->estimasi_harga, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>
            
            <div style="margin-top: 12px; padding-top: 12px; border-top: 2px solid #e9ecef;">
                <div class="info-row">
                    <span class="info-label">Total Estimasi:</span>
                    <span class="info-value" style="font-size: 1.05rem; color: #667eea;">
                        Rp {{ number_format($pengajuan->detailBarang->sum('estimasi_harga'), 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="note">
            <strong>📌 Catatan Penting:</strong><br>
            Simpan nomor pengajuan di atas untuk referensi Anda. Pengajuan akan diproses oleh pengurus dan Anda akan dihubungi melalui kontak yang telah Anda berikan.
        </div>
        
        <a href="{{ route('pengajuan.create') }}" class="btn-home">
            🏠 Kembali ke Beranda
        </a>
    </div>
</body>
</html>
