<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Tagihan IPL</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .bill-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .bill-table th, .bill-table td {
            border: 1px solid #e5e7eb;
            padding: 12px;
            text-align: left;
        }
        .bill-table th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
        }
        .total-row {
            background: #fef3c7 !important;
            font-weight: 700;
        }
        .total-amount {
            font-size: 24px;
            color: #dc2626;
            text-align: center;
            background: #fee2e2;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .note {
            background: #f0f9ff;
            border-left: 4px solid #0ea5e9;
            padding: 15px;
            border-radius: 0 8px 8px 0;
            margin: 20px 0;
        }
        .footer {
            background: #1e293b;
            color: #94a3b8;
            padding: 20px 30px;
            text-align: center;
            font-size: 13px;
        }
        .footer a {
            color: #818cf8;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏠 Perumahan Citra Gran</h1>
            <p>Pengingat Tagihan IPL</p>
        </div>
        
        <div class="content">
            <p class="greeting">
                Yth. Bapak/Ibu <strong>{{ $resident->name }}</strong>,<br>
                (Blok {{ $resident->block_number }})
            </p>
            
            <p>Kami ingin mengingatkan bahwa Anda memiliki tagihan IPL yang belum dibayar dengan rincian sebagai berikut:</p>
            
            <table class="bill-table">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Total Tagihan</th>
                        <th>Terbayar</th>
                        <th>Sisa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unpaidBills as $bill)
                        <tr>
                            <td>{{ $bill->period_name }}</td>
                            <td>Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($bill->paid_amount, 0, ',', '.') }}</td>
                            <td style="color: #dc2626; font-weight: 600;">Rp {{ number_format($bill->total_amount - $bill->paid_amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="total-amount">
                <strong>Total Tunggakan</strong><br>
                Rp {{ number_format($totalOutstanding, 0, ',', '.') }}
            </div>
            
            <div class="note">
                <strong>📌 Catatan:</strong><br>
                Mohon segera melakukan pembayaran untuk menghindari denda keterlambatan. Jika Anda sudah melakukan pembayaran, mohon abaikan email ini.
            </div>
            
            <p>Untuk informasi lebih lanjut, silakan hubungi kantor pengelola perumahan.</p>
            
            <p style="margin-top: 30px;">
                Terima kasih atas perhatian Bapak/Ibu.<br><br>
                Hormat kami,<br>
                <strong>Manajemen Perumahan</strong>
            </p>
        </div>
        
        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh Sistem Manajemen Perumahan</p>
            <p>{{ date('Y') }} &copy; XTraDTechnology</p>
        </div>
    </div>
</body>
</html>
