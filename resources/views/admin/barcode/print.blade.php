<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} — SMK Lentera Bangsa</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600;700&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #fff;
            color: #1a1a1a;
            padding: 20mm;
        }

        .print-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            margin-bottom: 24px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .print-header h1 {
            font-size: 16px;
            font-weight: 700;
        }

        .print-header p {
            font-size: 13px;
            color: #64748b;
        }

        .print-header button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #16a34a;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .print-header button:hover {
            background: #15803d;
        }

        .school-header {
            display: none;
            text-align: center;
            margin-bottom: 8mm;
            padding-bottom: 4mm;
            border-bottom: 2px solid #000;
        }

        .school-header h2 {
            font-size: 16pt;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .school-header p {
            font-size: 10pt;
            color: #444;
            margin-top: 2mm;
        }

        .barcode-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 5mm;
        }

        .barcode-card {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 4mm;
            text-align: center;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .barcode-card .school-name {
            font-size: 7pt;
            font-weight: 700;
            color: #16a34a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2mm;
        }

        .barcode-card .student-name {
            font-size: 10.5pt;
            font-weight: 700;
            color: #111;
            line-height: 1.3;
            margin-bottom: 1mm;
        }

        .barcode-card .student-info {
            font-size: 7.5pt;
            color: #666;
            margin-bottom: 3mm;
            line-height: 1.4;
        }

        .barcode-card .qr-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 2mm;
        }

        .barcode-card .qr-wrapper img {
            width: 28mm;
            height: 28mm;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }

        .barcode-card .cut-line {
            margin-top: 3mm;
            border-top: 1.5px dashed #94a3b8;
            padding-top: 3mm;
        }

        .barcode-card .nipd-label {
            font-size: 7pt;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1mm;
        }

        .barcode-card .nipd-value {
            font-size: 13pt;
            font-weight: 800;
            font-family: 'JetBrains Mono', monospace;
            color: #111;
            letter-spacing: 2px;
        }

        .barcode-card .scan-hint {
            font-size: 6pt;
            color: #b0b0b0;
            margin-top: 1.5mm;
        }

        @media (max-width: 900px) {
            .barcode-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 600px) {
            .barcode-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            body {
                padding: 10mm;
            }
        }

        @media print {
            @page {
                size: A4;
                margin: 10mm;
            }

            body {
                padding: 0;
            }

            .print-header {
                display: none !important;
            }

            .school-header {
                display: block !important;
            }

            .barcode-card {
                border-color: #ccc;
            }
        }
    </style>
</head>

<body>

    <div class="print-header">
        <div>
            <h1>{{ $title }}</h1>
            <p>{{ $siswas->count() }} siswa</p>
        </div>
        <button onclick="window.print()">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.75 7.131s0 0 0 0" />
            </svg>
            Cetak / Save PDF
        </button>
    </div>

    <div class="school-header">
        <h2>SMK LENTERA BANGSA</h2>
        <p>Kartu Absensi Siswa</p>
    </div>

    <div class="barcode-grid">
        @foreach ($siswas as $siswa)
            <div class="barcode-card">
                <div class="school-name">SMK Lentera Bangsa</div>
                <div class="student-name">{{ $siswa->nama }}</div>
                <div class="student-info">
                    NIS: {{ $siswa->nis }} · {{ $siswa->kelas->nama }}
                </div>

                <div class="qr-wrapper">
                    <img src="{{ route('admin.barcode.download', $siswa) }}" alt="QR Code {{ $siswa->nipd }}">
                </div>

                <div class="cut-line">
                    <div class="nipd-label">NIPD</div>
                    <div class="nipd-value">{{ $siswa->nipd }}</div>
                </div>

                <div class="scan-hint">Scan QR code untuk absensi</div>
            </div>
        @endforeach
    </div>

</body>

</html>
