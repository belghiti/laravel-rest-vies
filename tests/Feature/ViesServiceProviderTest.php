<?php
use Belghiti\Vies\Laravel\ViesServiceProvider;
use Belghiti\Vies\Client\ViesClientInterface;

it('registers the Vies client', function () {
    $app = $this->app;
    $app->register(ViesServiceProvider::class);
    expect($app->bound(ViesClientInterface::class))->toBeTrue();
});
