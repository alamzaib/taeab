<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendPendingEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send pending email notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $notifications = Notification::where('status', 'pending')
            ->where('failed_count', '<', 3)
            ->get();

        $this->info("Found {$notifications->count()} pending notifications");

        // Get SMTP settings from database
        $settings = \App\Models\Setting::getAll();
        
        // Configure mail settings dynamically
        if (!empty($settings['smtp_host'])) {
            config([
                'mail.mailers.smtp.host' => $settings['smtp_host'],
                'mail.mailers.smtp.port' => $settings['smtp_port'] ?? 587,
                'mail.mailers.smtp.username' => $settings['smtp_username'] ?? '',
                'mail.mailers.smtp.password' => $settings['smtp_password'] ?? '',
                'mail.mailers.smtp.encryption' => $settings['smtp_encryption'] ?? 'tls',
                'mail.from.address' => $settings['smtp_from_address'] ?? config('mail.from.address'),
                'mail.from.name' => $settings['smtp_from_name'] ?? config('mail.from.name'),
            ]);
        }

        foreach ($notifications as $notification) {
            try {
                Mail::raw($notification->message, function ($message) use ($notification, $settings) {
                    $message->to($notification->to_email)
                        ->subject($notification->subject);
                    
                    if (!empty($settings['smtp_from_address'])) {
                        $message->from($settings['smtp_from_address'], $settings['smtp_from_name'] ?? 'Job Portal UAE');
                    }
                });

                // Update status to sent (status = 1 means sent)
                $notification->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);

                $this->info("Email sent successfully to {$notification->to_email}");
            } catch (\Exception $e) {
                // Increment failed count
                $failedCount = $notification->failed_count + 1;
                
                $notification->update([
                    'failed_count' => $failedCount,
                    'error_message' => $e->getMessage(),
                    'status' => $failedCount >= 3 ? 'failed' : 'pending',
                ]);

                $this->error("Failed to send email to {$notification->to_email}: {$e->getMessage()}");
                Log::error("Email sending failed", [
                    'notification_id' => $notification->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Email sending process completed");
        return 0;
    }
}
