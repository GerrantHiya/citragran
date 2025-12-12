<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IplBill;
use App\Models\NotificationLog;
use App\Models\Resident;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Halaman broadcast notifikasi
     */
    public function index()
    {
        // Get unpaid bills summary
        $unpaidBills = IplBill::with('resident')
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->get();

        $residentsWithUnpaid = $unpaidBills->pluck('resident')->unique('id');

        // Recent notification logs
        $recentLogs = NotificationLog::with('resident')
            ->latest()
            ->take(20)
            ->get();

        return view('admin.notifications.index', compact('unpaidBills', 'residentsWithUnpaid', 'recentLogs'));
    }

    /**
     * Kirim reminder via WhatsApp
     */
    public function sendWhatsAppReminder(Request $request)
    {
        $residents = $this->getTargetResidents($request);
        $sent = 0;
        $failed = 0;

        foreach ($residents as $resident) {
            if (!$resident->whatsapp && !$resident->phone) {
                $failed++;
                continue;
            }

            $whatsappNumber = $resident->whatsapp ?: $resident->phone;
            $message = $this->generateReminderMessage($resident);

            // Log the notification (actual sending would be implemented with WA API)
            NotificationLog::create([
                'resident_id' => $resident->id,
                'type' => 'whatsapp',
                'subject' => 'Pengingat Tagihan IPL',
                'message' => $message,
                'recipient' => $whatsappNumber,
                'status' => 'pending', // Will be updated when actually sent
                'metadata' => json_encode([
                    'total_outstanding' => $resident->total_outstanding,
                    'unpaid_bills' => $resident->unpaid_bills->count(),
                ]),
            ]);

            $sent++;
        }

        return back()->with('success', "Berhasil mengirim {$sent} reminder WhatsApp. {$failed} gagal (tidak ada nomor WA).");
    }

    /**
     * Kirim reminder via Email
     */
    public function sendEmailReminder(Request $request)
    {
        $residents = $this->getTargetResidents($request);
        $sent = 0;
        $failed = 0;

        foreach ($residents as $resident) {
            if (!$resident->email) {
                $failed++;
                continue;
            }

            $message = $this->generateReminderMessage($resident);

            // Log the notification (actual sending would be implemented with Mail)
            NotificationLog::create([
                'resident_id' => $resident->id,
                'type' => 'email',
                'subject' => 'Pengingat Tagihan IPL - Perumahan Citra Gran',
                'message' => $message,
                'recipient' => $resident->email,
                'status' => 'pending', // Will be updated when actually sent
                'metadata' => json_encode([
                    'total_outstanding' => $resident->total_outstanding,
                    'unpaid_bills' => $resident->unpaid_bills->count(),
                ]),
            ]);

            // TODO: Implement actual email sending
            // Mail::to($resident->email)->send(new BillReminderMail($resident));

            $sent++;
        }

        return back()->with('success', "Berhasil mengirim {$sent} reminder Email. {$failed} gagal (tidak ada email).");
    }

    /**
     * Get residents with unpaid bills
     */
    private function getTargetResidents(Request $request)
    {
        $query = Resident::where('status', 'active')
            ->whereHas('iplBills', function ($q) {
                $q->whereIn('status', ['pending', 'partial', 'overdue']);
            });

        if ($request->resident_ids) {
            $query->whereIn('id', $request->resident_ids);
        }

        return $query->get();
    }

    /**
     * Generate reminder message
     */
    private function generateReminderMessage(Resident $resident)
    {
        $unpaidBills = $resident->unpaid_bills;
        $totalOutstanding = $resident->total_outstanding;

        $message = "Yth. Bapak/Ibu {$resident->name},\n\n";
        $message .= "Kami ingin mengingatkan bahwa Anda memiliki tagihan IPL yang belum dibayar:\n\n";

        foreach ($unpaidBills as $bill) {
            $remaining = $bill->total_amount - $bill->paid_amount;
            $message .= "- {$bill->period_name}: Rp " . number_format($remaining, 0, ',', '.') . "\n";
        }

        $message .= "\nTotal: Rp " . number_format($totalOutstanding, 0, ',', '.') . "\n\n";
        $message .= "Mohon segera melakukan pembayaran.\n\n";
        $message .= "Terima kasih,\nManajemen Perumahan Citra Gran";

        return $message;
    }
}
