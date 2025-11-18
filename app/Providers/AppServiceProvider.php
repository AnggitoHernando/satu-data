<?php

namespace App\Providers;

use App\Models\JenisData;
use App\Models\User;
use App\Policies\JenisDataPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
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
        Vite::prefetch(concurrency: 3);
        App::setLocale(config('app.locale'));
        // Gate::policy(JenisData::class, JenisDataPolicy::class);
        // Gate::policy(User::class, UserPolicy::class);
    }
}
