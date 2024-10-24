<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\Client;
use Illuminate\Support\Facades\Schema;

class MigrateClients extends Command
{
    protected $signature = 'migrate:clients {--status}';
    protected $description = 'Run migrations or check migration status for all client databases';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $clients = Client::select('db_name', 'db_host', 'db_username', 'db_password')->get();
    
        foreach ($clients as $client) {
            $this->info('Processing for client: ' . $client->db_name);
    
            // Dynamic DB connection setup
            Config::set('database.connections.dynamic', [
                'driver'    => 'mysql',
                'host'      => $client->db_host ?? '127.0.0.1',
                'port'      => '3306',
                'database'  => $client->db_name,
                'username'  => $client->db_username ?? 'root',
                'password'  => $client->db_password ?? '',
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix'    => '',
            ]);
    
            DB::purge('dynamic');
            Config::set('database.default', 'dynamic');
            DB::reconnect('dynamic');
    
            if (!Schema::hasTable('migrations')) {
                $this->error('Migration table does not exist for client: ' . $client->db_name);
                continue;
            }
    
            if ($this->option('status')) {
                $this->info('Checking migration status for client: ' . $client->db_name);
    
                $completedMigrations = DB::table('migrations')->pluck('migration')->toArray();
                $migrationFiles = glob(database_path('migrations/clients') . '/*.php');
                $pendingMigrations = [];
    
                foreach ($migrationFiles as $file) {
                    $migrationName = basename($file, '.php');
                    if (!in_array($migrationName, $completedMigrations)) {
                        $pendingMigrations[] = $migrationName;
                    }
                }
    
                // Display pending migrations in yellow
                if (count($pendingMigrations)) {
                    $this->warn('Pending Migrations for client: ' . $client->db_name); // Warn will output in yellow
                    foreach ($pendingMigrations as $pending) {
                        // Custom yellow color
                        $this->line("\033[33m$pending\033[0m"); // ANSI escape code for yellow
                    }
                } else {
                    $this->info('All migrations are up to date for client: ' . $client->db_name);
                }
    
                // Display completed migrations in green
                $this->info('Completed Migrations for client: ' . $client->db_name);
                foreach ($completedMigrations as $completed) {
                    $this->line("\033[32m$completed\033[0m"); // ANSI escape code for green
                }
            } else {
                $this->info('Migrating database for client: ' . $client->db_name);
                Artisan::call('migrate', [
                    '--database' => 'dynamic',
                    '--path' => '/database/migrations/clients',
                    '--force' => true,
                ]);
                $this->info(Artisan::output());
            }
        }
    
        $this->info('Client database processing completed.');
    }
    

}
