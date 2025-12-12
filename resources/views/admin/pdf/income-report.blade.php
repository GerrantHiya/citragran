<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan IPL</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; line-height: 1.5; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .header h1 { font-size: 20px; margin-bottom: 5px; }
        .header p { color: #666; }
        .info { margin-bottom: 20px; }
        .info p { margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: 600; }
        .amount { text-align: right; }
        .total-row { background-color: #e8f5e9; font-weight: 700; }
        .footer { margin-top: 30px; text-align: center; color: #666; font-size: 10px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PENDAPATAN IPL</h1>
        <p>Perumahan Citra Gran</p>
    </div>

    <div class="info">
        <p><strong>Periode:</strong> {{ date('d M Y', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ date('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Blok</th>
                <th>Nama Warga</th>
                <th>Periode Tagihan</th>
                <th class="amount">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $index => $payment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                    <td>{{ $payment->iplBill->resident->block_number ?? '-' }}</td>
                    <td>{{ $payment->iplBill->resident->name ?? '-' }}</td>
                    <td>{{ $payment->iplBill->period_name ?? '-' }}</td>
                    <td class="amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" style="text-align: right;"><strong>TOTAL PENDAPATAN</strong></td>
                <td class="amount"><strong>Rp {{ number_format($totalIncome, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh Sistem Manajemen Perumahan Citra Gran</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #4f46e5; color: white; border: none; border-radius: 5px; cursor: pointer;">
            <i class="bi bi-printer"></i> Cetak PDF
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6b7280; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Tutup
        </button>
    </div>
</body>
</html>
