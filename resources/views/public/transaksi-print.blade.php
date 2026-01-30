<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Transaksi - {{ $transaksi->nomor_transaksi }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            padding: 40px;
            color: #1f2937;
            -webkit-print-color-adjust: exact;
        }
        
        .page {
            background: white;
            width: 21cm;
            min-height: 29.7cm;
            margin: 0 auto;
            padding: 2cm;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            position: relative;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .company-info h1 {
            margin: 0;
            font-size: 24px;
            color: #111827;
            margin-bottom: 5px;
        }
        
        .company-info p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }
        
        .document-title {
            text-align: right;
        }
        
        .document-title h2 {
            margin: 0;
            font-size: 20px;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 5px;
        }
        
        .type-pemasukan { background-color: #d1fae5; color: #065f46; }
        .type-pengeluaran { background-color: #fee2e2; color: #991b1b; }
        
        .meta-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .meta-column h3 {
            font-size: 14px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        
        .meta-row {
            display: flex;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .meta-label {
            font-weight: 600;
            width: 120px;
            color: #4b5563;
        }
        
        .meta-value {
            flex: 1;
        }
        
        .nominal-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .nominal-label {
            font-size: 14px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .nominal-value {
            font-size: 32px;
            font-weight: 800;
            color: #111827;
        }
        
        .description-box {
            margin-bottom: 30px;
            padding: 15px;
            background-color: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }
        
        .description-label {
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
            color: #4b5563;
        }

        .proof-images {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 10px;
        }

        .proof-image-container {
            border: 1px solid #e5e7eb;
            padding: 5px;
            border-radius: 4px;
        }

        .proof-image {
            width: 100%;
            height: auto;
            max-height: 300px;
            object-fit: contain;
        }
        
        .footer {
            margin-top: 60px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            text-align: center;
            font-size: 14px;
        }
        
        .signature-box {
            margin-top: 40px;
            border-top: 1px solid #9ca3af;
            width: 60%;
            margin-left: auto;
            margin-right: auto;
            padding-top: 5px;
        }
        
        .print-actions {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .btn-primary {
            background-color: #2563eb;
            color: white;
            border: none;
        }
        
        .btn-secondary {
            background-color: white;
            color: #374151;
            border: 1px solid #d1d5db;
        }
        
        @media print {
            body { 
                background: none; 
                padding: 0; 
            }
            .page { 
                box-shadow: none; 
                margin: 0; 
                width: auto; 
            }
            .print-actions { 
                display: none; 
            }
        }
    </style>
</head>
<body>

    <div class="print-actions">
        <a href="javascript:window.print()" class="btn btn-primary">
            🖨️ Cetak PDF
        </a>
        <a href="javascript:window.close()" class="btn btn-secondary">
            ❌ Tutup
        </a>
    </div>

    <div class="page">
        <div class="header">
            <div class="company-info">
                <h1>YAYASAN QINSHOO</h1>
                <p>Gunung Geulis - Bogor Indonesia</p>
            </div>
            
            <div class="document-title">
                <h2>BUKTI TRANSAKSI</h2>
                <span class="status-badge type-{{ $transaksi->jenis->value }}">
                    {{ ucfirst($transaksi->jenis->value) }}
                </span>
            </div>
        </div>
        
        <div class="meta-grid">
            <div class="meta-column">
                <h3>Info Transaksi</h3>
                <div class="meta-row">
                    <span class="meta-label">No. Transaksi</span>
                    <span class="meta-value">: {{ $transaksi->nomor_transaksi }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Tanggal</span>
                    <span class="meta-value">: {{ $transaksi->tanggal_transaksi->format('d M Y') }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Kategori</span>
                    <span class="meta-value">: {{ $transaksi->kategori->nama ?? '-' }}</span>
                </div>
            </div>
            
            <div class="meta-column">
                <h3>Operator</h3>
                <div class="meta-row">
                    <span class="meta-label">Dibuat Oleh</span>
                    <span class="meta-value">: {{ $transaksi->creator->name ?? '-' }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Waktu Input</span>
                    <span class="meta-value">: {{ $transaksi->created_at->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>
        
        <div class="nominal-box">
            <div class="nominal-label">Total {{ ucfirst($transaksi->jenis->value) }}</div>
            <div class="nominal-value">
                Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}
            </div>
        </div>
        
        <div class="description-box">
            <div class="description-label">Deskripsi / Keterangan:</div>
            <p style="margin:0; line-height: 1.5;">{{ $transaksi->deskripsi }}</p>
        </div>
        
        @if($transaksi->bukti_path)
        <div class="description-box">
            <div class="description-label">Bukti Lampiran:</div>
            
            <div class="proof-images">
                @if(is_array($transaksi->bukti_path))
                    @foreach($transaksi->bukti_path as $path)
                        <div class="proof-image-container">
                            @if(Str::endsWith(strtolower($path), '.pdf'))
                                <div style="padding: 20px; text-align: center;">
                                    <p>📄 Dokumen PDF Terlampir</p>
                                    <span style="font-size: 12px; color: #666;">({{ basename($path) }})</span>
                                </div>
                            @else
                                <img src="{{ Storage::disk('public')->url($path) }}" class="proof-image" alt="Bukti Transaksi">
                            @endif
                        </div>
                    @endforeach
                @else
                    {{-- Fallback untuk data lama single string --}}
                    <div class="proof-image-container">
                         @if(Str::endsWith(strtolower($transaksi->bukti_path), '.pdf'))
                            <div style="padding: 20px; text-align: center;">
                                <p>📄 Dokumen PDF Terlampir</p>
                            </div>
                        @else
                            <img src="{{ Storage::disk('public')->url($transaksi->bukti_path) }}" class="proof-image" alt="Bukti Transaksi">
                        @endif
                    </div>
                @endif
            </div>
        </div>
        @endif
        
        <div class="footer">
            <div>
                <p>Mengetahui,</p>
                <br><br><br>
                <div class="signature-box">
                    <strong>Pimpinan / Ketua</strong>
                </div>
            </div>
            
            <div>
                <p>Bagian Keuangan,</p>
                <br><br><br>
                <div class="signature-box">
                    <strong>{{ $transaksi->creator->name ?? 'Admin' }}</strong>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 40px; font-size: 10px; color: #9ca3af; text-align: center;">
            Dicetak pada: {{ now()->format('d M Y H:i:s') }} | ID: {{ $transaksi->nomor_transaksi }}
        </div>
    </div>

</body>
</html>
