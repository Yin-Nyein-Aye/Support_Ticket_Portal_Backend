<?php

namespace App\Providers;

use App\Http\Contracts\AuthInterface;
use App\Http\Contracts\BaseInterface;
use App\Http\Contracts\CommentInterface;
use App\Http\Contracts\PermissionInterface;
use App\Http\Contracts\RoleInterface;
use App\Http\Contracts\UserInterface;
use App\Http\Repositories\BaseRepository;
use App\Http\Repositories\CommentRepository;
use App\Http\Repositories\PermissionRepository;
use App\Http\Repositories\RoleRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Services\AuthService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthInterface::class, AuthService::class);
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(CommentInterface::class, CommentRepository::class);
        $this->app->bind(BaseInterface::class, BaseRepository::class);
        $this->app->bind(RoleInterface::class, RoleRepository::class);
        $this->app->bind(PermissionInterface::class, PermissionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function ($request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
