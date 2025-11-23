<?php

namespace App\Console\Commands;

use App\Mail\NotificationEmail;
use App\Models\ApplicationNotification;
use App\Models\Seeker;
use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendNotificationEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notifications to seekers and companies (top 20 every 2 minutes)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get top 20 notifications that haven't been emailed yet
        $notifications = ApplicationNotification::where('email_sent', false)
            ->orderBy('created_at', 'asc')
            ->limit(20)
            ->get();

        if ($notifications->isEmpty()) {
            $this->info('No pending email notifications found.');
            return 0;
        }

        $this->info("Found {$notifications->count()} pending email notifications");

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

        $sentCount = 0;
        $failedCount = 0;

        foreach ($notifications as $notification) {
            try {
                // Get recipient based on type
                if ($notification->recipient_type === 'seeker') {
                    $recipient = Seeker::find($notification->recipient_id);
                    $email = $recipient->email ?? null;
                } else {
                    $recipient = Company::find($notification->recipient_id);
                    $email = $recipient->email ?? null;
                }

                if (!$email || !$recipient) {
                    $this->warn("Skipping notification {$notification->id}: Recipient not found");
                    // Mark as sent to avoid retrying
                    $notification->update([
                        'email_sent' => true,
                        'email_sent_at' => now(),
                    ]);
                    continue;
                }

                // Send email
                Mail::to($email)->send(new NotificationEmail($notification, $recipient));

                // Mark as sent
                $notification->update([
                    'email_sent' => true,
                    'email_sent_at' => now(),
                ]);

                $sentCount++;
                $this->info("Email sent successfully to {$email} (Notification ID: {$notification->id})");

            } catch (\Exception $e) {
                $failedCount++;
                $this->error("Failed to send email for notification {$notification->id}: {$e->getMessage()}");
                Log::error("Email sending failed for notification", [
                    'notification_id' => $notification->id,
                    'recipient_type' => $notification->recipient_type,
                    'recipient_id' => $notification->recipient_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Email sending process completed. Sent: {$sentCount}, Failed: {$failedCount}");
        return 0;
    }
}
