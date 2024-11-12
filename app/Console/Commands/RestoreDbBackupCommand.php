<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class RestoreDbBackupCommand extends Command
{
    protected $signature = 'db:restore {backupFile?}';
    protected $description = 'Restore the database from a backup file';

    public function handle()
    {
        $databaseName = env('DB_DATABASE');
        $userName = "root";
        $password = $this->ask('Please enter MySQL Root user password');

        $backupFile = $this->argument('backupFile') ?? $this->chooseBackupFile();

        $fullPath = storage_path('backups/' . $backupFile);

        if (!File::exists($fullPath)) {
            $this->error("$backupFile file does not exist in the backups folder.");
            return;
        }

        // Drop and recreate the database
        DB::statement("DROP DATABASE IF EXISTS {$databaseName}");
        DB::statement("CREATE DATABASE {$databaseName}");

        // Import the backup file
        $command = sprintf('mysql -u %s -p%s %s < %s',
            $userName, $password, $databaseName, $fullPath);

        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->info('Database restored successfully from ' . $backupFile);
    }

    protected function chooseBackupFile()
    {
        $files = File::files(storage_path('backups'));
        $sqlFiles = array_filter($files, function ($file) {
            return $file->getExtension() === 'sql';
        });
        $fileNames = array_map(function ($file) {
            return $file->getFilename();
        }, $sqlFiles);

        if (empty($fileNames)) {
            $this->error('No .sql files found in the backups folder.');
            exit(1);
        }

        return $this->choice('Select a backup file', $fileNames, 0);
    }
}