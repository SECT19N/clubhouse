<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--path= : Custom backup path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a backup of the database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        $this->info("Creating database backup for {$connection} ({$driver})...");

        $backupPath = $this->option('path') ?? storage_path('backups');
        
        // Create backup directory if it doesn't exist
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $timestamp = Carbon::now()->format('Y-m-d_His');
        $filename = "backup_{$connection}_{$timestamp}";

        try {
            if ($driver === 'sqlite') {
                $this->backupSqlite($connection, $backupPath, $filename);
            } elseif ($driver === 'mysql') {
                $this->backupMysql($connection, $backupPath, $filename);
            } else {
                $this->error("Backup not supported for driver: {$driver}");
                return Command::FAILURE;
            }

            $this->info("✓ Backup created successfully!");
            $this->info("Location: {$backupPath}/{$filename}");
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Backup failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Backup SQLite database
     */
    private function backupSqlite(string $connection, string $backupPath, string $filename): void
    {
        $databasePath = config("database.connections.{$connection}.database");
        
        if (!File::exists($databasePath)) {
            throw new \Exception("Database file not found: {$databasePath}");
        }

        $backupFile = "{$backupPath}/{$filename}.sqlite";
        File::copy($databasePath, $backupFile);
        
        $this->info("SQLite database copied to: {$backupFile}");
    }

    /**
     * Backup MySQL database
     */
    private function backupMysql(string $connection, string $backupPath, string $filename): void
    {
        $host = config("database.connections.{$connection}.host");
        $port = config("database.connections.{$connection}.port");
        $database = config("database.connections.{$connection}.database");
        $username = config("database.connections.{$connection}.username");
        $password = config("database.connections.{$connection}.password");

        $backupFile = "{$backupPath}/{$filename}.sql";

        // Check if mysqldump is available
        $mysqldump = $this->findMysqldump();
        
        if (!$mysqldump) {
            throw new \Exception("mysqldump not found. Please install MySQL client tools.");
        }

        $command = sprintf(
            '%s -h %s -P %s -u %s %s %s > %s',
            escapeshellarg($mysqldump),
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            $password ? '-p' . escapeshellarg($password) : '',
            escapeshellarg($database),
            escapeshellarg($backupFile)
        );

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception("mysqldump failed with return code: {$returnVar}");
        }

        if (!File::exists($backupFile)) {
            throw new \Exception("Backup file was not created");
        }

        $this->info("MySQL database dumped to: {$backupFile}");
    }

    /**
     * Find mysqldump executable
     */
    private function findMysqldump(): ?string
    {
        $paths = [
            'mysqldump',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\wamp\\bin\\mysql\\mysql8.0.xx\\bin\\mysqldump.exe',
        ];

        foreach ($paths as $path) {
            if ($this->commandExists($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Check if command exists
     */
    private function commandExists(string $command): bool
    {
        $whereIsCommand = (PHP_OS == 'WINNT') ? 'where' : 'which';
        
        $process = proc_open(
            "{$whereIsCommand} {$command}",
            [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ],
            $pipes
        );

        $output = stream_get_contents($pipes[1]);
        proc_close($process);

        return !empty($output);
    }
}
