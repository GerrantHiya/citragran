<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class FontteService
{
    protected string $token;
    protected string $apiUrl = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = config('services.fonnte.token', '');
    }

    /**
     * Kirim pesan WhatsApp menggunakan Fonnte API
     *
     * @param string $target Nomor tujuan (format: 08xxx atau 62xxx)
     * @param string $message Pesan yang akan dikirim
     * @return array Response dari API
     */
    public function sendMessage(string $target, string $message): array
    {
        // Format nomor telepon (pastikan format Indonesia)
        $target = $this->formatPhoneNumber($target);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $this->token,
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            Log::error('Fonnte API Error: ' . $error);
            return [
                'success' => false,
                'status' => false,
                'reason' => 'cURL Error: ' . $error,
            ];
        }

        $result = json_decode($response, true);

        // Log response for debugging
        Log::info('Fonnte API Response', [
            'target' => $target,
            'http_code' => $httpCode,
            'response' => $result,
        ]);

        return $result ?? [
            'success' => false,
            'status' => false,
            'reason' => 'Invalid response from API',
        ];
    }

    /**
     * Kirim pesan ke banyak nomor sekaligus
     *
     * @param array $recipients Array of ['target' => '08xxx', 'message' => 'xxx']
     * @return array Results
     */
    public function sendBulkMessages(array $recipients): array
    {
        $results = [];
        
        foreach ($recipients as $recipient) {
            $target = $recipient['target'] ?? '';
            $message = $recipient['message'] ?? '';
            
            if (empty($target) || empty($message)) {
                $results[] = [
                    'target' => $target,
                    'success' => false,
                    'reason' => 'Target or message is empty',
                ];
                continue;
            }

            $response = $this->sendMessage($target, $message);
            $results[] = [
                'target' => $target,
                'success' => isset($response['status']) && $response['status'] === true,
                'response' => $response,
            ];

            // Delay untuk menghindari rate limit
            usleep(500000); // 0.5 detik
        }

        return $results;
    }

    /**
     * Format nomor telepon ke format yang benar
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Hapus spasi dan karakter non-digit kecuali +
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        // Jika dimulai dengan +62, hapus +
        if (str_starts_with($phone, '+62')) {
            $phone = substr($phone, 1);
        }

        // Jika dimulai dengan 62, biarkan
        if (str_starts_with($phone, '62')) {
            return $phone;
        }

        // Jika dimulai dengan 0, ganti dengan 62
        if (str_starts_with($phone, '0')) {
            return '62' . substr($phone, 1);
        }

        // Default: tambahkan 62 di depan
        return '62' . $phone;
    }

    /**
     * Check apakah token sudah dikonfigurasi
     */
    public function isConfigured(): bool
    {
        return !empty($this->token);
    }
}
