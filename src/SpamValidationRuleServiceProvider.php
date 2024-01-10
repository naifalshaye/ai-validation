<?php

namespace Naif\SpamValidationRule;

use Illuminate\Support\ServiceProvider;
use OpenAI;

class SpamValidationRuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SpamRule::class, function ($app) {

            return new SpamRule();
        });
    }

    public function boot()
    {
        //
    }
}