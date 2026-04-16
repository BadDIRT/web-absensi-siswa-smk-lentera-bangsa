<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} — SMK Lentera Bangsa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
            color: #111827;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ── Toolbar (hidden saat print) ── */
        .toolbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .toolbar-title {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }

        .toolbar-subtitle {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 2px;
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px;
            background: #111827;
            color: white;
            font-size: 13px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-print:hover {
            background: #1f2937;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: white;
            color: #374151;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s;
        }

        .btn-back:hover {
            background: #f9fafb;
        }

        /* ── Container ── */
        .print-container {
            max-width: 1080px;
            margin: 24px auto;
            padding: 0 24px 48px;
        }

        /* ── Grid Kartu ── */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        /* ── Kartu ── */
        .card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .card-header {
            background: linear-gradient(135deg, #1e40af, #2563eb);
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header .school-name {
            color: white;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.03em;
            line-height: 1.3;
        }

        .card-header .card-label {
            color: rgba(255, 255, 255, 0.75);
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            background: rgba(255, 255, 255, 0.15);
            padding: 3px 8px;
            border-radius: 4px;
        }

        .card-body {
            padding: 16px;
            text-align: center;
        }

        .card-name {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 2px;
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-identities {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 14px;
        }

        .card-identity {
            font-size: 10px;
            color: #6b7280;
        }

        .card-identity strong {
            color: #374151;
            font-weight: 600;
        }

        .card-kelas {
            display: inline-block;
            font-size: 10px;
            font-weight: 600;
            color: #1e40af;
            background: #eff6ff;
            padding: 2px 10px;
            border-radius: 20px;
            margin-bottom: 14px;
        }

        .card-barcode {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding-top: 12px;
            border-top: 1px dashed #e5e7eb;
        }

        .card-barcode img {
            height: 52px;
            width: auto;
        }

        .card-barcode-code {
            font-family: 'Courier New', monospace;
            font-size: 9px;
            color: #9ca3af;
            letter-spacing: 0.05em;
        }

        /* ── Print ── */
        @media print {
            @page {
                size: A4;
                margin: 12mm;
            }

            body {
                background: white;
            }

            .toolbar {
                display: none !important;
            }

            .print-container {
                margin: 0;
                padding: 0;
                max-width: 100%;
            }

            .card-grid {
                gap: 10px;
            }

            .card {
                border: 1px solid #d1d5db;
            }

            .card-header {
                background: #1e40af !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    {{-- ── Toolbar ── --}}
    <div class="toolbar">
        <div>
            <div class="toolbar-title">{{ $title }}</div>
            <div class="toolbar-subtitle">{{ $siswas->count() }} siswa</div>
        </div>
        <div class="toolbar-actions">
            <a href="javascript:history.back()" class="btn-back">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali
            </a>
            <button onclick="window.print()" class="btn-print">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.75 7.131H5.25" />
                </svg>
                Cetak
            </button>
        </div>
    </div>

    {{-- ── Kartu ── --}}
    <div class="print-container">
        <div class="card-grid">
            @foreach ($siswas as $siswa)
                <div class="card">
                    <div class="card-header">
                        <span class="school-name">SMK Lentera<br>Bangsa</span>
                        <span class="card-label">Kartu Absensi</span>
                    </div>
                    <div class="card-body">
                        <div class="card-name" title="{{ $siswa->nama }}">{{ $siswa->nama }}</div>
                        <div class="card-identities">
                            <span class="card-identity">NIS <strong>{{ $siswa->nis }}</strong></span>
                            <span class="card-identity">NIPD <strong>{{ $siswa->nipd ?? '—' }}</strong></span>
                        </div>
                        <span class="card-kelas">{{ $siswa->kelas->nama }}</span>
                        <div class="card-barcode">
                            <img src="data:image/png;base64, {{ base64_encode($generator->getBarcode($siswa->no_barcode, $generator::TYPE_CODE_128, 2, 52)) }}"
                                alt="Barcode {{ $siswa->no_barcode }}">
                            <span class="card-barcode-code">{{ $siswa->no_barcode }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</body>

</html>
