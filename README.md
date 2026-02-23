
# belghiti/laravel-rest-vies

[![CI](https://github.com/belghiti/laravel-rest-vies/actions/workflows/ci.yml/badge.svg)](https://github.com/belghiti/laravel-rest-vies/actions)
[![Packagist](https://img.shields.io/packagist/v/belghiti/laravel-rest-vies.svg)](https://packagist.org/packages/belghiti/laravel-rest-vies)
[![Downloads](https://img.shields.io/packagist/dt/belghiti/laravel-rest-vies.svg)](https://packagist.org/packages/belghiti/laravel-rest-vies)

Laravel integration for **VIES**: Service Provider + Facade + Validation Rule + Cache decorator. It wires the core `belghiti/vies-client` with retry and Laravel Cache.

---

## Requirements

- **Laravel 11.x or 12.x**
- PHP **8.2 – 8.5**

## Installation

```bash
composer require belghiti/laravel-rest-vies
php artisan vendor:publish --tag=vies-config
```

**config/vies.php**
```php
return [
    'base_uri'  => env('VIES_BASE_URI', 'https://ec.europa.eu/taxation_customs/vies/rest-api'),
    'cache_ttl' => (int) env('VIES_CACHE_TTL', 3600),
];
```

**.env**
```env
VIES_BASE_URI=https://ec.europa.eu/taxation_customs/vies/rest-api
VIES_CACHE_TTL=3600
```

## Usage

### Facade
```php
use Vies; // Belghiti\Vies\Laravel\ViesFacade alias
use Belghiti\Vies\Dto\CheckVatRequest;

$res = Vies::checkVatNumber(new CheckVatRequest('FR', '40303265045'));
if ($res->valid) {
    // ...
}
```

### Dependency Injection
```php
use Belghiti\Vies\Client\ViesClientInterface;
use Belghiti\Vies\Dto\CheckVatRequest;

public function __construct(private ViesClientInterface $vies) {}

public function __invoke()
{
    $status = $vies->checkStatus();
    // ...
}
```

### Validation Rule
```php
use Belghiti\Vies\Laravel\Rules\ValidVatInVies;
use Illuminate\Support\Facades\Validator;

$validator = Validator::make(
  ['vat' => '40303265045'],
  ['vat' => [new ValidVatInVies(app(\Belghiti\Vies\Client\ViesClientInterface::class), 'FR')]]
);
```

## Notes

- `CheckVatRequest` covers the contract fields (`countryCode`, `vatNumber`, optional trader info)
- The package caches `checkVatNumber()` responses using your configured Laravel Cache store (TTL from config)

## Quality

```bash
composer lint   # PSR-12
composer stan   # PHPStan level max
vendor/bin/pest -d
```

## License

MIT
