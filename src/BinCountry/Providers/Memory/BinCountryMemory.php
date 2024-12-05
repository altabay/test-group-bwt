<?php

declare(strict_types=1);

namespace App\OaTest\BinCountry\Providers\Memory;

final class BinCountryMemory
{
    /**
     * @var array<null|string>
     */
    private array $bins = [];

    public function hasCountry(string $bin): bool
    {
        return array_key_exists($bin, $this->bins);
    }

    public function getCountry(string $bin): ?string
    {
        return $this->bins[$bin] ?? null;
    }

    public function setCountry(string $bin, ?string $country): void
    {
        $this->bins[$bin] = $country;
    }
}
