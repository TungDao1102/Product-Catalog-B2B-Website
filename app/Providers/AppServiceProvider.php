<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Spatie\Translatable\Facades\Translatable;

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
        // Use Bootstrap 5 pagination views (not default Tailwind)
        Paginator::useBootstrapFive();

        // Set Vietnamese as fallback locale for spatie/laravel-translatable
        // When a translation for the requested locale doesn't exist,
        // the package will fall back to 'vi' automatically.
        Translatable::fallback('vi');
    }
}
