<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Project;
use App\Notifications\ConfirmEmail;
use App\Notifications\LegalRegistrationEmail;
use App\Notifications\ReceiptNotification;
use App\Notifications\ResetPassword;

class SendEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send
                          {--type= : Type of email (confirm/legal/receipt/reset)}
                          {--user_id= : ID of the user}
                          {--project_id= : ID of the project (required for receipt)}
                          {--design_id= : ID of the design (required for receipt)}
                          {--token= : Reset password token}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send various types of emails to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        
        if (!$type) {
            $type = $this->choice(
                'What type of email do you want to send?',
                ['confirm', 'legal', 'receipt', 'reset']
            );
        }

        $userId = $this->option('user_id');
        if (!$userId) {
            $userId = $this->ask('Enter user ID');
        }

        $user = User::find($userId);
        if (!$user) {
            $this->error('User not found!');
            return 1;
        }

        switch ($type) {
            case 'confirm':
                $user->notify(new ConfirmEmail($userId));
                break;

            case 'legal':
                $user->notify(new LegalRegistrationEmail($user));
                break;

            case 'receipt':
                $projectId = $this->option('project_id') ?: $this->ask('Enter project ID');
                $designId = $this->option('design_id') ?: $this->ask('Enter design ID');
                
                $project = Project::find($projectId);
                $design = Design::find($designId);
                
                if (!$project || !$design) {
                    $this->error('Project or Design not found!');
                    return 1;
                }
                
                $user->notify(new ReceiptNotification($project, $design, $user->email));
                break;

            case 'reset':
                $token = $this->option('token') ?: $this->ask('Enter reset token');
                $user->notify(new ResetPassword($token));
                break;

            default:
                $this->error('Invalid email type!');
                return 1;
        }

        $this->info('Email sent successfully!');
        return 0;
    }
}
