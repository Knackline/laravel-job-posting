<?php

namespace Knackline\LaravelJobPosting;

use Illuminate\Support\ServiceProvider;

class LaravelJobPostingServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge the package's config with the app's config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-job-posting.php',
            'laravel-job-posting'
        );
    }

    public function boot()
    {
        // Publish the config file
        $this->publishes([
            __DIR__ . '/../config/laravel-job-posting.php' => config_path('laravel-job-posting.php'),
        ]);
    }
}
