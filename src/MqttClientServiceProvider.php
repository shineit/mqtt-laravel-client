<?php

namespace PhpMqtt\Client;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the php-mqtt/laravel-client within the application.
 *
 * @package PhpMqtt\Client
 */
class MqttClientServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->registerServices();
    }

    /**
     * Publishes and merges the configuration of the package.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $configPath = __DIR__ . '/../config/mqtt-client.php';

        if ($this->app->runningInConsole()) {
            $this->publishes([$configPath => config_path('mqtt-client.php')], 'config');
        }

        $this->mergeConfigFrom($configPath, 'mqtt-client');
    }

    /**
     * Registers the services offered by this package.
     *
     * @return void
     */
    protected function registerServices(): void
    {
        $this->app->bind(ConnectionManager::class, function (Application $app) {
            $config = $app->make('config')->get('mqtt-client', []);
            return new ConnectionManager($app, $config);
        });
    }
}
