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
    
            // Check and create migrations table if not exists
            if (!Schema::hasTable('migrations')) {
                $this->info('Creating migrations table for client: ' . $client->db_name);
                Artisan::call('migrate:install', ['--database' => 'dynamic']);
                $this->info('Migration table created for client: ' . $client->db_name);
            }
    
            if ($this->option('status')) {
                $this->info('Checking migration status for client: ' . $client->db_name);
            
                // সম্পন্ন করা মাইগ্রেশন চেক করা
                $completedMigrations = DB::table('migrations')->pluck('migration')->toArray();
                
                // মাইগ্রেশন ফাইলগুলো খুঁজে পাওয়া
                $migrationFiles = glob(database_path('migrations/clients') . '/*.php');
                $pendingMigrations = [];
            
                foreach ($migrationFiles as $file) {
                    $migrationName = basename($file, '.php');
                    if (!in_array($migrationName, $completedMigrations)) {
                        $pendingMigrations[] = $migrationName;
                    }
                }
            
                // পেন্ডিং মাইগ্রেশনগুলো দেখানো (হলুদ রঙে)
                if (count($pendingMigrations)) {
                    $this->warn('Pending Migrations for client: ' . $client->db_name);
                    foreach ($pendingMigrations as $pending) {
                        $this->line("\033[33m$pending\033[0m"); // ANSI কোড হলুদ রঙের জন্য
                    }
                } else {
                    $this->info('All migrations are up to date for client: ' . $client->db_name);
                }
            
                // সম্পন্ন করা মাইগ্রেশনগুলো দেখানো (সবুজ রঙে)
                $this->info('Completed Migrations for client: ' . $client->db_name);
                foreach ($completedMigrations as $completed) {
                    $this->line("\033[32m$completed\033[0m"); // ANSI কোড সবুজ রঙের জন্য
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
