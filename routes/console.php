<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('server', function () {
    $port = env('APP_PORT', 1087);
    $host = env('APP_HOST', gethostbyname(trim(`hostname`)));

    $this->call('serve', [
        '--port' => $port,
        '--host' => $host,
    ]);

    $this->info("Server is running on port {$port} and host {$host}");
})->purpose("Run server with custom port and host");