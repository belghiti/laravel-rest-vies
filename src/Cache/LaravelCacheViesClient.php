<?php
namespace Belghiti\Vies\Laravel\Cache;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Belghiti\Vies\Client\ViesClientInterface;
use Belghiti\Vies\Dto\CheckVatRequest;
use Belghiti\Vies\Dto\CheckVatResponse;
use Belghiti\Vies\Dto\StatusInformationResponse;

final class LaravelCacheViesClient implements ViesClientInterface
{
    public function __construct(private ViesClientInterface $inner, private CacheRepository $cache, private int $ttlSeconds) {}

    public function checkVatNumber(CheckVatRequest $request): CheckVatResponse
    {
        $key = $this->key('vat', $request->countryCode.':'.$request->vatNumber);
        return $this->cache->remember($key, $this->ttlSeconds, function () use ($request) {
            return $this->inner->checkVatNumber($request);
        });
    }

    public function checkVatTestService(CheckVatRequest $request): CheckVatResponse
    { return $this->inner->checkVatTestService($request); }

    public function checkStatus(): StatusInformationResponse
    { return $this->inner->checkStatus(); }

    private function key(string $prefix, string $id): string
    { return 'belghiti_vies:'.$prefix.':'.md5($id); }
}
