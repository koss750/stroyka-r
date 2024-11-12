<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DatabaseBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';
    protected $description = 'Backup the database';

    public function handle()
    {
        $this->info('Starting remote database backup...');

        $dbUsername = env('DB_USERNAME', 'alex_client');
        $dbName = env('DB_DATABASE', 'stroyka_dev');
        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbPort = env('DB_PORT', 3306); // Default MySQL port
        $backupPath = storage_path('backups/' . date('Y-m-d_His') . '_backup.sql');

        $command = "mysqldump --set-gtid-purged=OFF -h{$dbHost} -P{$dbPort} -u{$dbUsername} -p {$dbName} > {$backupPath}";

        $process = Process::fromShellCommandline($command);
        try {
            $process->mustRun();
            $this->info('Remote database backup completed successfully.');
        } catch (ProcessFailedException $exception) {
            $this->error('Remote database backup failed: ' . $exception->getMessage());
        }
    }
}
