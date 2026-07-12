<?php

namespace App\Services;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Kirim email laporan via Resend REST API.
     *
     * @param  string  $pdfContent  Binary content PDF
     * @param  string  $filename    Nama file PDF
     * @param  array   $meta        Metadata laporan (periode, total, dll)
     * @return array{success: bool, message: string}
     */
    public function sendReportEmail(string $pdfContent, string $filename, array $meta): array
    {
        $apiKey    = config('services.resend.key');
        $fromEmail = config('services.resend.from_email', 'onboarding@resend.dev');
        $toEmail   = config('services.resend.receiver_email') ?: config('mail.from.address');

        if (empty($toEmail)) {
            return [
                'success' => false,
                'message' => 'Email tujuan (CONTACT_RECEIVER_EMAIL atau MAIL_FROM_ADDRESS) belum dikonfigurasi.',
            ];
        }

        $tanggalGenerate = now()->format('d F Y');
        $periodeText     = $this->buildPeriodeText($meta);

        $subject = "Laporan Ticket Laboran - {$tanggalGenerate}";

        $htmlBody = $this->buildEmailHtml($periodeText, $meta['total_data'] ?? 0);

        try {
            if (!empty($apiKey)) {
                $response = Http::withToken($apiKey)
                    ->timeout(30)
                    ->post('https://api.resend.com/emails', [
                        'from'        => "Sistem Ticketing Laboran <{$fromEmail}>",
                        'to'          => [$toEmail],
                        'subject'     => $subject,
                        'html'        => $htmlBody,
                        'attachments' => [
                            [
                                'filename' => $filename,
                                'content'  => base64_encode($pdfContent),
                            ],
                        ],
                    ]);

                if ($response->successful()) {
                    return [
                        'success' => true,
                        'message' => "Laporan berhasil dibuat dan dikirim ke email {$toEmail}",
                    ];
                }

                $errorBody = $response->json();
                $errorMsg  = $errorBody['message'] ?? $response->body();

                Log::warning('Resend API gagal, mencoba fallback SMTP', [
                    'status' => $response->status(),
                    'body'   => $errorBody,
                ]);
            }

            return $this->sendWithLaravelMailer($toEmail, $subject, $htmlBody, $pdfContent, $filename);
        } catch (\Exception $e) {
            Log::error('Resend API Exception', [
                'error' => $e->getMessage(),
            ]);

            return $this->sendWithLaravelMailer($toEmail, $subject, $htmlBody, $pdfContent, $filename);
        }
    }

    /**
     * Kirim email melalui driver Laravel mail (SMTP/sendmail) sebagai fallback.
     */
    private function sendWithLaravelMailer(string $toEmail, string $subject, string $htmlBody, string $pdfContent, string $filename): array
    {
        $fromEmail = config('mail.from.address') ?: config('services.resend.from_email', 'onboarding@resend.dev');
        $fromName  = config('mail.from.name') ?: 'Sistem Ticketing Laboran';
        $mailer    = $this->resolveMailer();

        try {
            Mail::mailer($mailer)->html($htmlBody, function (Message $message) use ($toEmail, $subject, $fromEmail, $fromName, $pdfContent, $filename): void {
                $message->to($toEmail)
                    ->subject($subject)
                    ->from($fromEmail, $fromName);

                $message->attachData($pdfContent, $filename, ['mime' => 'application/pdf']);
            });

            return [
                'success' => true,
                'message' => "Laporan berhasil dibuat dan dikirim ke email {$toEmail}",
            ];
        } catch (\Throwable $e) {
            Log::warning('Laravel mailer fallback gagal, mencoba transport sendmail', [
                'mailer' => $mailer,
                'error' => $e->getMessage(),
            ]);

            try {
                Mail::mailer('sendmail')->html($htmlBody, function (Message $message) use ($toEmail, $subject, $fromEmail, $fromName, $pdfContent, $filename): void {
                    $message->to($toEmail)
                        ->subject($subject)
                        ->from($fromEmail, $fromName);

                    $message->attachData($pdfContent, $filename, ['mime' => 'application/pdf']);
                });

                return [
                    'success' => true,
                    'message' => "Laporan berhasil dibuat dan dikirim ke email {$toEmail}",
                ];
            } catch (\Throwable $fallbackError) {
                Log::error('SMTP fallback gagal', ['error' => $fallbackError->getMessage()]);

                return [
                    'success' => false,
                    'message' => "Error saat mengirim email: {$fallbackError->getMessage()}",
                ];
            }
        }
    }

    /**
     * Tentukan mailer yang paling aman untuk hosting.
     */
    private function resolveMailer(): string
    {
        $mailer = config('mail.default') ?: env('MAIL_MAILER', 'sendmail');

        if (empty($mailer) || in_array($mailer, ['log', 'array', 'null'], true)) {
            return 'sendmail';
        }

        return $mailer;
    }

    /**
     * Bangun teks periode laporan.
     */
    private function buildPeriodeText(array $meta): string
    {
        if (!empty($meta['periode_awal']) && !empty($meta['periode_akhir'])) {
            return $meta['periode_awal'] . ' s/d ' . $meta['periode_akhir'];
        }

        return 'Semua Periode';
    }

    /**
     * Bangun body HTML email.
     */
    private function buildEmailHtml(string $periodeText, int $totalTicket): string
    {
        return <<<HTML
        <div style="font-family: 'Inter', Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 32px;">
            <div style="background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); border-radius: 12px; padding: 32px; margin-bottom: 24px;">
                <h1 style="color: #ffffff; font-size: 20px; margin: 0 0 4px;">📋 Laporan Ticket Laboran</h1>
                <p style="color: rgba(255,255,255,0.8); font-size: 14px; margin: 0;">Sistem Ticketing Laboran — UKSW</p>
            </div>

            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin-bottom: 24px;">
                <p style="color: #334155; font-size: 15px; line-height: 1.7; margin: 0 0 20px;">
                    Halo Admin Laboran,
                </p>
                <p style="color: #334155; font-size: 15px; line-height: 1.7; margin: 0 0 20px;">
                    Terlampir laporan ticket laboran dalam bentuk PDF.
                </p>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin-bottom: 20px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 6px 0; color: #64748b; font-size: 14px; width: 120px;">Periode:</td>
                            <td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 600;">{$periodeText}</td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; color: #64748b; font-size: 14px;">Total Ticket:</td>
                            <td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 600;">{$totalTicket}</td>
                        </tr>
                    </table>
                </div>

                <p style="color: #64748b; font-size: 13px; line-height: 1.6; margin: 0;">
                    Email ini dikirim secara otomatis oleh Sistem Ticketing Laboran.
                </p>
            </div>

            <div style="text-align: center; padding: 16px 0;">
                <p style="color: #94a3b8; font-size: 12px; margin: 0;">
                    &copy; Universitas Kristen Satya Wacana — Fakultas Teknologi Informasi
                </p>
            </div>
        </div>
        HTML;
    }

    /**
     * Kirim email laporan rekap teknisi via Resend REST API.
     *
     * @param  string  $pdfContent  Binary content PDF
     * @param  string  $filename    Nama file PDF
     * @param  array   $meta        Metadata laporan
     * @return array{success: bool, message: string}
     */
    public function sendRekapTeknisiEmail(string $pdfContent, string $filename, array $meta): array
    {
        $apiKey    = config('services.resend.key');
        $fromEmail = config('services.resend.from_email', 'onboarding@resend.dev');
        $toEmail   = config('services.resend.receiver_email') ?: config('mail.from.address');

        if (empty($toEmail)) {
            return [
                'success' => false,
                'message' => 'Email tujuan (CONTACT_RECEIVER_EMAIL atau MAIL_FROM_ADDRESS) belum dikonfigurasi.',
            ];
        }

        $subject  = 'Laporan Rekap Pekerjaan Teknisi';
        $htmlBody = $this->buildRekapTeknisiEmailHtml($meta);

        try {
            if (!empty($apiKey)) {
                $response = Http::withToken($apiKey)
                    ->timeout(30)
                    ->post('https://api.resend.com/emails', [
                        'from'        => "Sistem Ticketing Laboran <{$fromEmail}>",
                        'to'          => [$toEmail],
                        'subject'     => $subject,
                        'html'        => $htmlBody,
                        'attachments' => [
                            [
                                'filename' => $filename,
                                'content'  => base64_encode($pdfContent),
                            ],
                        ],
                    ]);

                if ($response->successful()) {
                    return [
                        'success' => true,
                        'message' => "Laporan berhasil dikirim ke email {$toEmail}",
                    ];
                }

                $errorBody = $response->json();
                $errorMsg  = $errorBody['message'] ?? $response->body();

                Log::warning('Resend API gagal (Rekap Teknisi), mencoba fallback SMTP', [
                    'status' => $response->status(),
                    'body'   => $errorBody,
                ]);
            }

            return $this->sendWithLaravelMailer($toEmail, $subject, $htmlBody, $pdfContent, $filename);
        } catch (\Exception $e) {
            Log::error('Resend API Exception (Rekap Teknisi)', [
                'error' => $e->getMessage(),
            ]);

            return $this->sendWithLaravelMailer($toEmail, $subject, $htmlBody, $pdfContent, $filename);
        }
    }

    /**
     * Bangun body HTML email rekap teknisi.
     */
    private function buildRekapTeknisiEmailHtml(array $meta): string
    {
        $tanggalGenerate = $meta['tanggal_generate'] ?? now()->format('d F Y H:i');
        $userName        = $meta['user_name'] ?? 'System';
        $totalSelesai    = $meta['total_ticket_selesai'] ?? 0;
        $jumlahTeknisi   = $meta['jumlah_teknisi'] ?? 0;

        return <<<HTML
        <div style="font-family: 'Inter', Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 32px;">
            <div style="background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); border-radius: 12px; padding: 32px; margin-bottom: 24px;">
                <h1 style="color: #ffffff; font-size: 20px; margin: 0 0 4px;">📊 Laporan Rekap Pekerjaan Teknisi</h1>
                <p style="color: rgba(255,255,255,0.8); font-size: 14px; margin: 0;">Sistem Ticketing Laboran — UKSW</p>
            </div>

            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin-bottom: 24px;">
                <p style="color: #334155; font-size: 15px; line-height: 1.7; margin: 0 0 20px;">
                    Halo Admin Laboran,
                </p>
                <p style="color: #334155; font-size: 15px; line-height: 1.7; margin: 0 0 20px;">
                    Terlampir laporan rekap hasil pekerjaan teknisi yang dihasilkan secara otomatis oleh Sistem Ticketing Laboran.
                </p>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin-bottom: 20px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 6px 0; color: #64748b; font-size: 14px; width: 140px;">Di-generate oleh:</td>
                            <td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 600;">{$userName}</td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; color: #64748b; font-size: 14px;">Tanggal:</td>
                            <td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 600;">{$tanggalGenerate}</td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; color: #64748b; font-size: 14px;">Jumlah Teknisi:</td>
                            <td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 600;">{$jumlahTeknisi}</td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; color: #64748b; font-size: 14px;">Total Ticket Selesai:</td>
                            <td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 600;">{$totalSelesai}</td>
                        </tr>
                    </table>
                </div>

                <p style="color: #334155; font-size: 15px; line-height: 1.7; margin: 0 0 10px;">
                    Terima kasih.
                </p>

                <p style="color: #64748b; font-size: 13px; line-height: 1.6; margin: 0;">
                    Email ini dikirim secara otomatis oleh Sistem Ticketing Laboran.
                </p>
            </div>

            <div style="text-align: center; padding: 16px 0;">
                <p style="color: #94a3b8; font-size: 12px; margin: 0;">
                    &copy; Universitas Kristen Satya Wacana — Fakultas Teknologi Informasi
                </p>
            </div>
        </div>
        HTML;
    }
}
