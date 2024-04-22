<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    const BLOOD_GROUPS = [
        1 => "A (+)",
        2 => "A (-)",
        3 => "B (+)",
        4 => "B (-)",
        5 => "AB (+)",
        6 => "AB (-)",
        7 => "O (+)",
        8 => "O (-)",
    ];

    const GENDERS = [
        1 => 'Male',
        2 => 'Female',
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
