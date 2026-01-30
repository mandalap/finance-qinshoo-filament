<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pengajuan - {{ $pengajuan->nomor_pengajuan }}</title>
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
        
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-approved { background-color: #d1fae5; color: #065f46; }
        .status-rejected { background-color: #fee2e2; color: #991b1b; }
        
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
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        th {
            background-color: #f9fafb;
            text-align: left;
            padding: 12px;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            color: #1f2937;
            vertical-align: top;
        }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .total-row td {
            font-weight: 700;
            background-color: #f9fafb;
            border-top: 2px solid #e5e7eb;
        }
        
        .notes-section {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .notes-section h3 {
            margin: 0 0 10px 0;
            font-size: 15px;
            color: #374151;
        }
        
        .footer {
            margin-top: 60px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            text-align: center;
            font-size: 14px;
        }
        
        .signature-box {
            margin-top: 40px;
            border-top: 1px solid #9ca3af;
            width: 80%;
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
                <h2>BUKTI PENGAJUAN</h2>
                <span class="status-badge status-{{ $pengajuan->status->value }}">
                    {{ ucfirst($pengajuan->status->value) }}
                </span>
            </div>
        </div>
        
        <div class="meta-grid">
            <div class="meta-column">
                <h3>Informasi Pengajuan</h3>
                <div class="meta-row">
                    <span class="meta-label">No. Pengajuan</span>
                    <span class="meta-value">: {{ $pengajuan->nomor_pengajuan }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Tanggal</span>
                    <span class="meta-value">: {{ $pengajuan->tanggal_pengajuan->format('d M Y') }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Urgensi</span>
                    <span class="meta-value">: {{ ucfirst($pengajuan->tingkat_urgensi->value ?? 'Normal') }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Tgl. Dibutuhkan</span>
                    <span class="meta-value">: {{ \Carbon\Carbon::parse($pengajuan->tanggal_dibutuhkan)->format('d M Y') }}</span>
                </div>
            </div>
            
            <div class="meta-column">
                <h3>Data Pengaju</h3>
                <div class="meta-row">
                    <span class="meta-label">Nama</span>
                    <span class="meta-value">: {{ $pengajuan->nama_pengaju }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Divisi</span>
                    <span class="meta-value">: {{ $pengajuan->divisi }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Jabatan</span>
                    <span class="meta-value">: {{ $pengajuan->jabatan }}</span>
                </div>
                @if($pengajuan->kontak)
                <div class="meta-row">
                    <span class="meta-label">Kontak</span>
                    <span class="meta-value">: {{ $pengajuan->kontak }}</span>
                </div>
                @endif
            </div>
        </div>
        
        <div class="notes-section">
            <h3>Tujuan Pengjuan:</h3>
            <p style="margin:0;">{{ $pengajuan->tujuan_pengajuan }}</p>
        </div>
        
        <h3>Detail Barang</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama Barang</th>
                    <th style="width: 25%;">Spesifikasi</th>
                    <th style="width: 10%;" class="text-center">Jml</th>
                    <th style="width: 15%;" class="text-right">Harga Satuan</th>
                    <th style="width: 10%;" class="text-center">Status</th>
                    <th style="width: 10%;">Ket</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuan->detailBarang as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->nama_barang }}</strong>
                    </td>
                    <td>{{ $item->spesifikasi_barang }}</td>
                    <td class="text-center">{{ $item->jumlah }} {{ $item->satuan }}</td>
                    <td class="text-right">Rp {{ number_format($item->estimasi_harga, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($item->status == 'approved')
                            <span style="color: green;">✓ OK</span>
                        @elseif($item->status == 'rejected')
                            <span style="color: red;">✗ NO</span>
                        @else
                            <span style="color: orange;">? Pending</span>
                        @endif
                    </td>
                    <td>{{ $item->catatan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4" class="text-right">Total Estimasi:</td>
                    <td class="text-right">Rp {{ number_format($pengajuan->detailBarang->sum('estimasi_harga'), 0, ',', '.') }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
        
        @if($pengajuan->catatan_persetujuan)
        <div class="notes-section" style="background-color: #eff6ff; border-color: #bfdbfe;">
            <h3>Catatan Persetujuan:</h3>
            <p style="margin:0;">{{ $pengajuan->catatan_persetujuan }}</p>
        </div>
        @endif
        
        <div class="footer">
            <div>
                <p>Diajukan Oleh,</p>
                <br><br><br>
                <div class="signature-box">
                    <strong>{{ $pengajuan->nama_pengaju }}</strong>
                </div>
            </div>
            
            <div>
                <!-- Spacer -->
            </div>
            
            <div>
                <p>Disetujui Oleh,</p>
                @if($pengajuan->status->value == 'approved' && $pengajuan->approver)
                    <div style="margin-top:20px; color: green; font-weight:bold;">
                        Digital Signed <br>
                        {{ $pengajuan->tanggal_persetujuan ? $pengajuan->tanggal_persetujuan->format('d M Y') : '' }}
                    </div>
                    <div class="signature-box" style="margin-top: 10px;">
                        <strong>{{ $pengajuan->approver->name ?? 'Admin' }}</strong>
                    </div>
                @else
                    <br><br><br>
                    <div class="signature-box">
                        <strong>( ....................... )</strong>
                    </div>
                @endif
            </div>
        </div>
        
        <div style="margin-top: 40px; font-size: 10px; color: #9ca3af; text-align: center;">
            Dicetak pada: {{ now()->format('d M Y H:i:s') }} | ID: {{ $pengajuan->nomor_pengajuan }} | UUID: {{ $pengajuan->uuid }}
        </div>
    </div>

</body>
</html>
