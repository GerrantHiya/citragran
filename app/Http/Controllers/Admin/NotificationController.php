<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BillReminderMail;
use App\Models\IplBill;
use App\Models\NotificationLog;
use App\Models\Resident;
use App\Services\FontteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    protected FontteService $fontteService;

    public function __construct(FontteService $fontteService)
    {
        $this->fontteService = $fontteService;
    }

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

        // Check if Fonnte is configured
        $fontteConfigured = $this->fontteService->isConfigured();

        return view('admin.notifications.index', compact(
            'unpaidBills', 
            'residentsWithUnpaid', 
            'recentLogs',
            'fontteConfigured'
        ));
    }

    /**
     * Kirim reminder via WhatsApp menggunakan Fonnte API
     */
    public function sendWhatsAppReminder(Request $request)
    {
        // Check if Fonnte is configured
        if (!$this->fontteService->isConfigured()) {
            return back()->with('error', 'Token Fonnte belum dikonfigurasi. Silakan tambahkan FONNTE_TOKEN di file .env');
        }

        $residents = $this->getTargetResidents($request);
        $sent = 0;
        $failed = 0;
        $skipped = 0;

        foreach ($residents as $resident) {
            // Skip jika tidak ada nomor WA
            if (!$resident->whatsapp && !$resident->phone) {
                $skipped++;
                
                NotificationLog::create([
                    'resident_id' => $resident->id,
                    'type' => 'whatsapp',
                    'subject' => 'Pengingat Tagihan IPL',
                    'message' => 'Dilewati: Tidak ada nomor WhatsApp',
                    'recipient' => '-',
                    'status' => 'skipped',
                    'metadata' => json_encode([
                        'reason' => 'No WhatsApp number',
                    ]),
                ]);
                
                continue;
            }

            $whatsappNumber = $resident->whatsapp ?: $resident->phone;
            $message = $this->generateReminderMessage($resident);

            // Kirim via Fonnte API
            $response = $this->fontteService->sendMessage($whatsappNumber, $message);

            // Determine status from response
            $success = isset($response['status']) && $response['status'] === true;

            // Log the notification
            NotificationLog::create([
                'resident_id' => $resident->id,
                'type' => 'whatsapp',
                'subject' => 'Pengingat Tagihan IPL',
                'message' => $message,
                'recipient' => $whatsappNumber,
                'status' => $success ? 'sent' : 'failed',
                'metadata' => json_encode([
                    'total_outstanding' => $resident->total_outstanding,
                    'unpaid_bills' => $resident->unpaid_bills->count(),
                    'fonnte_response' => $response,
                ]),
            ]);

            if ($success) {
                $sent++;
            } else {
                $failed++;
            }
        }

        // Prepare result message
        $messages = [];
        if ($sent > 0) {
            $messages[] = "✅ Berhasil mengirim {$sent} pesan WhatsApp";
        }
        if ($failed > 0) {
            $messages[] = "❌ Gagal mengirim {$failed} pesan";
        }
        if ($skipped > 0) {
            $messages[] = "⏭️ Dilewati {$skipped} warga (tidak ada nomor WA)";
        }

        $resultMessage = implode('. ', $messages);

        if ($sent > 0 && $failed === 0) {
            return back()->with('success', $resultMessage);
        } elseif ($sent > 0 && $failed > 0) {
            return back()->with('warning', $resultMessage);
        } else {
            return back()->with('error', $resultMessage ?: 'Tidak ada pesan yang dikirim.');
        }
    }

    /**
     * Kirim reminder via Email
     */
    public function sendEmailReminder(Request $request)
    {
        $residents = $this->getTargetResidents($request);
        $sent = 0;
        $failed = 0;
        $errors = [];

        foreach ($residents as $resident) {
            if (!$resident->email) {
                $failed++;
                continue;
            }

            try {
                // Send actual email
                Mail::to($resident->email)->send(new BillReminderMail($resident));

                // Log success
                NotificationLog::create([
                    'resident_id' => $resident->id,
                    'type' => 'email',
                    'subject' => 'Pengingat Tagihan IPL - Perumahan Citra Gran',
                    'message' => 'Email template sent',
                    'recipient' => $resident->email,
                    'status' => 'sent',
                    'metadata' => json_encode([
                        'total_outstanding' => $resident->total_outstanding,
                        'unpaid_bills' => $resident->unpaid_bills->count(),
                    ]),
                ]);

                $sent++;
            } catch (\Exception $e) {
                // Log failure
                NotificationLog::create([
                    'resident_id' => $resident->id,
                    'type' => 'email',
                    'subject' => 'Pengingat Tagihan IPL - Perumahan Citra Gran',
                    'message' => 'Failed: ' . $e->getMessage(),
                    'recipient' => $resident->email,
                    'status' => 'failed',
                    'metadata' => json_encode([
                        'error' => $e->getMessage(),
                    ]),
                ]);

                $errors[] = $resident->email . ': ' . $e->getMessage();
                $failed++;
            }
        }

        if ($sent > 0 && $failed === 0) {
            return back()->with('success', "Berhasil mengirim {$sent} email reminder.");
        } elseif ($sent > 0 && $failed > 0) {
            return back()->with('warning', "Berhasil mengirim {$sent} email. {$failed} gagal.");
        } else {
            return back()->with('error', "Gagal mengirim email. " . implode(', ', $errors));
        }
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
     * Generate reminder message for WhatsApp
     */
    private function generateReminderMessage(Resident $resident)
    {
        $unpaidBills = $resident->unpaid_bills;
        $totalOutstanding = $resident->total_outstanding;

        $message = "🏠 *PENGINGAT TAGIHAN IPL*\n";
        $message .= "Perumahan Citra Gran\n";
        $message .= "━━━━━━━━━━━━━━━━\n\n";
        
        $message .= "Yth. Bapak/Ibu *{$resident->name}*\n";
        $message .= "Blok: *{$resident->block_number}*\n\n";
        
        $message .= "Kami ingin mengingatkan bahwa Anda memiliki tagihan IPL yang belum dibayar:\n\n";

        foreach ($unpaidBills as $bill) {
            $remaining = $bill->total_amount - $bill->paid_amount;
            $message .= "📋 *{$bill->period_name}*\n";
            $message .= "    Sisa: Rp " . number_format($remaining, 0, ',', '.') . "\n";
        }

        $message .= "\n━━━━━━━━━━━━━━━━\n";
        $message .= "💰 *Total Tunggakan:*\n";
        $message .= "*Rp " . number_format($totalOutstanding, 0, ',', '.') . "*\n\n";
        
        $message .= "Mohon segera melakukan pembayaran.\n\n";
        $message .= "Terima kasih 🙏\n";
        $message .= "_Manajemen Perumahan Citra Gran_";

        return $message;
    }
}
