<?php
namespace Belghiti\Vies\Laravel\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Belghiti\Vies\Client\ViesClientInterface;
use Belghiti\Vies\Dto\CheckVatRequest;

final class ValidVatInVies implements ValidationRule
{
    public function __construct(private ViesClientInterface $client, private string $countryCode) {}

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $res = $this->client->checkVatNumber(new CheckVatRequest($this->countryCode, (string)$value));
        if (!$res->valid) {
            $fail('The :attribute is not valid in VIES.');
        }
    }
}
