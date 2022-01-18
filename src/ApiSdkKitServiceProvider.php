<?php

namespace Fligno\ApiSdkKit;

use Fligno\ApiSdkKit\Containers\MakeRequest;
use Illuminate\Support\ServiceProvider;

class ApiSdkKitServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'fligno');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'fligno');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/api-sdk-kit.php', 'api-sdk-kit');

        // Register the service the package provides.
        $this->app->singleton('api-sdk-kit', function ($app) {
            return new ApiSdkKit;
        });

        // Register the MakeRequest Service Container
        $this->app->bind('make-request', function ($app, $params) {
            return new MakeRequest($params['base_url'] ?? null, $params['http'] ?? null);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['api-sdk-kit'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/api-sdk-kit.php' => config_path('api-sdk-kit.php'),
        ], 'api-sdk-kit.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/fligno'),
        ], 'api-sdk-kit.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/fligno'),
        ], 'api-sdk-kit.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/fligno'),
        ], 'api-sdk-kit.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
