<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $transaksi->kode_invoice }}</title>
    <style>
        @page {
            margin: 0;
            size: 80mm auto;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 76mm;
            margin: 0 auto;
            padding: 10px 5px;
            color: #000;
            font-size: 11px;
            line-height: 1.3;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .divider {
            border-bottom: 1px dashed #000;
            margin: 6px 0;
        }
        .double-divider {
            border-bottom: 2px dashed #000;
            margin: 8px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 2px 0;
            vertical-align: top;
        }
        .no-print {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 15px;
        }
        .btn {
            background: #4f46e5;
            color: white;
            padding: 6px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-family: sans-serif;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <button onclick="window.print()" class="btn">🖨️ Cetak Struk</button>
        <button onclick="window.close()" class="btn" style="background:#64748b;">Tutup</button>
    </div>

    <div class="text-center">
        <h2 style="margin:0; font-size:16px;">{{ strtoupper($transaksi->outlet->nama ?? 'LAUNDRY') }}</h2>
        <p style="margin:2px 0;">{{ $transaksi->outlet->alamat ?? '-' }}</p>
        <p style="margin:2px 0;">Telp/WA: {{ $transaksi->outlet->tlp ?? '-' }}</p>
    </div>

    <div class="double-divider"></div>

    <table>
        <tr>
            <td>No. Invoice</td>
            <td class="text-right font-bold">{{ $transaksi->kode_invoice }}</td>
        </tr>
        <tr>
            <td>Tgl Masuk</td>
            <td class="text-right">{{ $transaksi->tgl->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Batas Waktu</td>
            <td class="text-right">{{ $transaksi->batas_waktu->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td class="text-right">{{ $transaksi->user->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>Pelanggan</td>
            <td class="text-right font-bold">{{ $transaksi->member->nama ?? 'Umum' }}</td>
        </tr>
        <tr>
            <td>No. Telp</td>
            <td class="text-right">{{ $transaksi->member->tlp ?? '-' }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table>
        <thead>
            <tr style="border-bottom:1px solid #000;">
                <th class="text-left">Item</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->detailTransaksi as $item)
                <tr>
                    <td colspan="4" class="font-bold" style="padding-top:4px;">
                        {{ $item->paket->nama_paket ?? 'Paket' }}
                        @if($item->keterangan)
                            <div style="font-weight:normal; font-size:10px; font-style:italic;">* {{ $item->keterangan }}</div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-center">{{ $item->qty }} {{ ($item->paket && $item->paket->jenis === 'kiloan') ? 'Kg' : 'Pcs' }}</td>
                    <td class="text-right">{{ number_format($item->paket->harga ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right font-bold">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <table>
        <tr>
            <td>Subtotal</td>
            <td class="text-right">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</td>
        </tr>
        @if($transaksi->diskon > 0)
        <tr>
            <td>Diskon ({{ $transaksi->diskon }}%)</td>
            <td class="text-right">- Rp {{ number_format(($transaksi->diskon/100)*$transaksi->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if($transaksi->pajak > 0)
        <tr>
            <td>Pajak</td>
            <td class="text-right">+ Rp {{ number_format($transaksi->pajak, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if($transaksi->biaya_tambahan > 0)
        <tr>
            <td>Biaya Tambahan</td>
            <td class="text-right">+ Rp {{ number_format($transaksi->biaya_tambahan, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr style="font-size:13px; font-weight:bold;">
            <td style="padding-top:4px;">TOTAL</td>
            <td class="text-right" style="padding-top:4px;">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td style="padding-top:4px;">STATUS BAYAR</td>
            <td class="text-right font-bold" style="padding-top:4px;">
                {{ $transaksi->dibayar === 'dibayar' ? 'LUNAS' : 'BELUM LUNAS' }}
            </td>
        </tr>
    </table>

    <div class="double-divider"></div>

    <div class="text-center" style="font-size:10px;">
        <p style="margin:2px 0;"><strong>SYARAT & KETENTUAN:</strong></p>
        <p style="margin:2px 0;">1. Pengambilan barang wajib membawa struk ini.</p>
        <p style="margin:2px 0;">2. Klaim kerusakan/kehilangan maks 1x24 jam.</p>
        <p style="margin:2px 0;">3. Barang tidak diambil > 30 hari di luar tanggung jawab.</p>
        <p style="margin:8px 0 0 0; font-weight:bold;">-- TERIMA KASIH ATAS KUNJUNGAN ANDA --</p>
    </div>

</body>
</html>
