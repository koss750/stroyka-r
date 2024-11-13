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
        $backupFile = $this->argument('backupFile') ?? $this->chooseBackupFile();
        $fullPath = storage_path('backups/' . $backupFile);

        if (!File::exists($fullPath)) {
            $this->error("$backupFile file does not exist in the backups folder.");
            return;
        }

        // Drop and recreate database using sudo
        $dropDb = Process::fromShellCommandline("sudo mysql -e 'DROP DATABASE IF EXISTS {$databaseName}'");
        $dropDb->run();
        if (!$dropDb->isSuccessful()) {
            throw new ProcessFailedException($dropDb);
        }

        $createDb = Process::fromShellCommandline("sudo mysql -e 'CREATE DATABASE {$databaseName}'");
        $createDb->run();
        if (!$createDb->isSuccessful()) {
            throw new ProcessFailedException($createDb);
        }

        // Import the backup file using sudo with increased timeout
        $command = sprintf('sudo mysql %s < %s', $databaseName, $fullPath);
        $process = Process::fromShellCommandline($command);
        $process->setTimeout(3600); // Set timeout to 1 hour
        
        $this->info('Starting database import...');
        $process->run(function ($type, $buffer) {
            $this->output->write('.');
        });

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->newLine();
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