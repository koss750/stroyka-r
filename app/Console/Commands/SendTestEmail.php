<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Log;

class SendTestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email? : Email address to send test to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to verify email configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? $this->ask('Please provide an email address');

        $this->info("Sending test email to: {$email}");
        $this->info("Using mail configuration:");
        $from_name = env('TEST_ENVIRONMENT') != 'test' ? env('MAIL_FROM_NAME') : env('MAIN_SUBJECT_PREFIX').env('MAIL_FROM_NAME');
        $this->table(
            ['Setting', 'Value'],
            [
                ['Driver', env('MAIL_DRIVER')],
                ['Host', env('MAIL_HOST')],
                ['Port', env('MAIL_PORT')],
                ['From Address', env('MAIL_FROM_ADDRESS')],
                ['Reply To', env('MAIL_REPLY_TO')],
                ['From Name', $from_name],
            ]
        );

        try {
            // Set timeout for SMTP connection
            config(['mail.mailers.smtp.timeout' => 5]);
            
            Mail::raw('This is a test email from your Laravel application.', function (Message $message) use ($email) {
                $message->to($email)
                    ->subject('Test Email')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            $this->info('Test email sent successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send test email!');
            $this->error($e->getMessage());
            Log::error('Test email failed: ' . $e->getMessage());
            return 1;
        }
    }
}
