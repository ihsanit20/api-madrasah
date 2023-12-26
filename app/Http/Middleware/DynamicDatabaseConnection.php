<?php

namespace App\Http\Middleware;

use App\Http\Controllers\AppController;
use App\Models\Client;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class DynamicDatabaseConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $domain = $this->getClientDomainFromRequest($request);

        // dd($domain);

        AppController::$domain = $domain;

        $client = Client::query()
            ->where('domain', $domain)
            ->active()
            ->first();

        if ($client) {

            AppController::$client = [
                "name"      => $client->name,
                "domain"    => $client->domain,
            ];

            Config::set('database.connections.dynamic', [
                'driver'    => 'mysql',
                'host'      => $client->db_host ?? '127.0.0.1',
                'port'      => '3306',
                'database'  => $client->db_name,
                'username'  => $client->db_username,
                'password'  => $client->db_password ?? '',
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix'    => '',
            ]);

            DB::reconnect('dynamic');

            Config::set("database.default", "dynamic");

            // dd(Config::all());
        }

        return $next($request);
    }

    protected function getClientDomainFromRequest($request)
    {
        $origin = $request->header('Origin'); // For CORS requests
        $referer = $request->header('Referer'); // Standard Referer header

        // Use the one that is available or fits your use case
        $domain = $origin ?? $referer;

        $domain = rtrim(preg_replace('/^http(|s)\:\/\/(www\.|)|www./','', $domain ), '/');
        
        $domain = env('FORCE_FRONTEND_HOST', $domain);

        return $domain;
    }
}
