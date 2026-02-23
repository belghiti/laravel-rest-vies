<?php
namespace Belghiti\Vies\Laravel;

use Illuminate\Support\ServiceProvider;
use Belghiti\Vies\Client\HttpViesClient;
use Belghiti\Vies\Client\ViesClientInterface;
use Belghiti\Vies\Decorator\RetryingViesClient;
use Belghiti\Vies\Laravel\Cache\LaravelCacheViesClient;
use Belghiti\Vies\Support\Adapter\GuzzlePsr18Client;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Psr7\HttpFactory;

final class ViesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/config/vies.php', 'vies');

        $this->app->singleton(ViesClientInterface::class, function ($app) {
            $psr18 = new GuzzlePsr18Client(new Guzzle());
            $httpFactory = new HttpFactory();
            $core = new HttpViesClient(
                $psr18,
                $httpFactory,
                $httpFactory,
                baseUri: config('vies.base_uri')
            );
            $retry = new RetryingViesClient($core, 3, 200);
            return new LaravelCacheViesClient($retry, $app['cache']->store(), (int) config('vies.cache_ttl'));
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/config/vies.php' => config_path('vies.php'),
        ], 'vies-config');
    }
}
