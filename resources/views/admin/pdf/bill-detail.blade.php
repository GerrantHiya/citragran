<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $bill->bill_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; line-height: 1.5; color: #333; padding: 20px; }
        .invoice { max-width: 800px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #333; }
        .company { }
        .company h1 { font-size: 24px; margin-bottom: 5px; color: #4f46e5; }
        .company p { color: #666; font-size: 11px; }
        .invoice-info { text-align: right; }
        .invoice-info h2 { font-size: 18px; margin-bottom: 5px; }
        .invoice-info p { font-size: 11px; color: #666; }
        .parties { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .bill-to, .bill-from { width: 48%; }
        .bill-to h3, .bill-from h3 { font-size: 11px; color: #999; text-transform: uppercase; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: 600; font-size: 11px; text-transform: uppercase; }
        .amount { text-align: right; }
        .total-row { background-color: #e8f5e9; }
        .total-row td { font-weight: 700; font-size: 14px; }
        .status { padding: 5px 10px; border-radius: 20px; font-size: 10px; font-weight: 600; text-transform: uppercase; }
        .status-paid { background: #d1fae5; color: #059669; }
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-partial { background: #dbeafe; color: #2563eb; }
        .status-overdue { background: #fee2e2; color: #dc2626; }
        .footer { margin-top: 30px; text-align: center; color: #666; font-size: 10px; border-top: 1px solid #ddd; padding-top: 20px; }
        .notes { margin-top: 20px; padding: 15px; background: #f9fafb; border-radius: 5px; }
        .notes h4 { font-size: 11px; color: #999; margin-bottom: 5px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="header">
            <div class="company">
                <h1>Perumahan Citra Gran</h1>
                <p>Jl. Citra Gran Blok A No. 1</p>
                <p>Sistem Manajemen Perumahan</p>
            </div>
            <div class="invoice-info">
                <h2>INVOICE</h2>
                <p><strong>No:</strong> {{ $bill->bill_number }}</p>
                <p><strong>Tanggal:</strong> {{ $bill->created_at->format('d M Y') }}</p>
                <p><strong>Jatuh Tempo:</strong> {{ $bill->due_date->format('d M Y') }}</p>
                <p><strong>Status:</strong> 
                    <span class="status status-{{ $bill->status }}">
                        @switch($bill->status)
                            @case('paid') Lunas @break
                            @case('partial') Sebagian @break
                            @case('overdue') Terlambat @break
                            @default Belum Bayar
                        @endswitch
                    </span>
                </p>
            </div>
        </div>

        <div class="parties">
            <div class="bill-to">
                <h3>Tagihan Untuk:</h3>
                <p><strong>{{ $bill->resident->name }}</strong></p>
                <p>Blok {{ $bill->resident->block_number }}</p>
                @if($bill->resident->address)
                    <p>{{ $bill->resident->address }}</p>
                @endif
                @if($bill->resident->phone)
                    <p>Telp: {{ $bill->resident->phone }}</p>
                @endif
            </div>
            <div class="bill-from">
                <h3>Periode:</h3>
                <p><strong>{{ $bill->period_name }}</strong></p>
                @if($bill->resident->land_area)
                    <p>Luas Tanah: {{ number_format($bill->resident->land_area, 0) }} m²</p>
                @endif
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Deskripsi</th>
                    <th>Keterangan</th>
                    <th class="amount">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($bill->items as $item)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $item->billingType->name ?? 'Item' }}</td>
                        <td>{{ $item->notes ?? '-' }}</td>
                        <td class="amount">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;"><strong>TOTAL TAGIHAN</strong></td>
                    <td class="amount">Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</td>
                </tr>
                @if($bill->paid_amount > 0)
                    <tr>
                        <td colspan="3" style="text-align: right;">Sudah Dibayar</td>
                        <td class="amount" style="color: #059669;">- Rp {{ number_format($bill->paid_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;"><strong>SISA TAGIHAN</strong></td>
                        <td class="amount" style="color: #dc2626;">Rp {{ number_format($bill->total_amount - $bill->paid_amount, 0, ',', '.') }}</td>
                    </tr>
                @endif
            </tfoot>
        </table>

        @if($bill->payments->count() > 0)
            <h4 style="margin-bottom: 10px;">Riwayat Pembayaran</h4>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>No. Pembayaran</th>
                        <th>Metode</th>
                        <th class="amount">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bill->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->format('d M Y') }}</td>
                            <td>{{ $payment->payment_number }}</td>
                            <td>{{ ucfirst($payment->payment_method) }}</td>
                            <td class="amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="notes">
            <h4>Catatan:</h4>
            <p>Harap melakukan pembayaran sebelum tanggal jatuh tempo untuk menghindari denda keterlambatan.</p>
            <p>Untuk informasi lebih lanjut, silakan hubungi kantor pengelola perumahan.</p>
        </div>

        <div class="footer">
            <p>Dokumen ini dicetak secara otomatis oleh Sistem Manajemen Perumahan Citra Gran</p>
            <p>Dicetak pada: {{ date('d M Y H:i:s') }}</p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #4f46e5; color: white; border: none; border-radius: 5px; cursor: pointer;">
            <i class="bi bi-printer"></i> Cetak Invoice
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6b7280; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Tutup
        </button>
    </div>
</body>
</html>
