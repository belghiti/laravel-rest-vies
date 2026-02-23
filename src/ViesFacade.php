<?php
namespace Belghiti\Vies\Laravel;

use Illuminate\Support\Facades\Facade;
use Belghiti\Vies\Client\ViesClientInterface;

final class ViesFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    { return ViesClientInterface::class; }
}
