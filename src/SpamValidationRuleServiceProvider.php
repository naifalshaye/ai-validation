<?php

namespace Naif\SpamValidationRule;

use Illuminate\Support\ServiceProvider;
use OpenAI;

class SpamValidationRuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SpamRule::class, function ($app) {
            // Assuming the OpenAI API key is set in your .env file
            $apiKey = env('OPENAI_API_KEY');

            // Creating an instance of the OpenAI client
            $client = new OpenAI($apiKey);

            // Injecting the OpenAI client into SpamRule
            return new SpamRule($client);
        });
    }

    public function boot()
    {
        //
    }
}