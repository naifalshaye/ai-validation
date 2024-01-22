<?php

namespace Naif\AIValidation;

use Illuminate\Support\ServiceProvider;
use Naif\AIValidation\Rules\AIValidation;

class AIValidationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('aivalidation', function ($app) {
            return new AIValidation();
        });

        $this->mergeConfigFrom(__DIR__ . '/../config/ai-validation.php', 'ai-validation');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/ai-validation.php' => config_path('ai-validation.php'),
            ], 'config');
        }
    }
}
