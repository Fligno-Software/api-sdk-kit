<?php

namespace Fligno\ApiSdkKit;

use Fligno\ApiSdkKit\Console\Commands\ApiSdkKitClearCacheCommand;
use Fligno\ApiSdkKit\Console\Commands\DeleteUnattachedAuditLogsCommand;
use Fligno\ApiSdkKit\Containers\MakeRequest;
use Fligno\ApiSdkKit\Models\AuditLog;
use Fligno\ApiSdkKit\Observers\AuditLogObserver;
use Fligno\ApiSdkKit\Repositories\AuditLogRepository;
use Fligno\StarterKit\Interfaces\ProviderConsoleKernelInterface;
use Fligno\StarterKit\Providers\BaseStarterKitServiceProvider as ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

/**
 * Class ApiSdkKitServiceProvider
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since  2022-01-20
 */
class ApiSdkKitServiceProvider extends ServiceProvider implements ProviderConsoleKernelInterface
{
    /**
     * @var array|string[]
     */
    protected array $commands = [
        DeleteUnattachedAuditLogsCommand::class,
        ApiSdkKitClearCacheCommand::class,
    ];

    /**
     * @var array|string[]
     */
    protected array $morph_map = [
        'audit_log' => AuditLog::class,
    ];

    /**
     * @var array|string[]
     */
    protected array $observer_map = [
        AuditLogObserver::class => AuditLog::class,
    ];

    /**
     * @var array|string[]
     */
    protected array $repository_map = [
        AuditLogRepository::class => AuditLog::class
    ];

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/api-sdk-kit.php', 'api-sdk-kit');

        // Register the service the package provides.
        $this->app->singleton(
            'api-sdk-kit',
            function () {
                return new ApiSdkKit();
            }
        );

        // Register the MakeRequest Service Container
        $this->app->bind(
            'make-request',
            function ($app, $params) {
                return new MakeRequest($params['base_url'] ?? null);
            }
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
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
        $this->publishes(
            [
            __DIR__.'/../config/api-sdk-kit.php' => config_path('api-sdk-kit.php'),
            ],
            'api-sdk-kit.config'
        );

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
        $this->commands($this->commands);
    }

    /**
     * @param Schedule $schedule
     * @return void
     */
    public function registerToConsoleKernel(Schedule $schedule): void
    {
        $schedule->command('ask:delete-unattached-logs')
            ->daily()
            ->runInBackground()
            ->onOneServer();
    }

    public function areHelpersEnabled(): bool
    {
        return false;
    }
}
