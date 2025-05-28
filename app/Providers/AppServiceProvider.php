<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
        Gate::before(function ($user, $ability) {
            return $user->hasRole('superadmin') ? true : null;
        });
        // Configuração especial para InfinityFree
        if (app()->environment('production')) {
            config([
                'filesystems.disks.public.root' => '/home/vol1_1/infinityfree.com/if0_38241904/moraw.ct.ws/htdocs/storage/app/public',
                'filesystems.disks.public_uploads.root' => '/home/vol1_1/infinityfree.com/if0_38241904/moraw.ct.ws/htdocs/uploads'
            ]);
        }
    }
}
