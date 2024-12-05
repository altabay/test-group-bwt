<?php

namespace App\OaTest\Rate\Providers\Memory;

final class RateMemory
{
    /**
     * @var array<null|float>
     */
    private array $rates = [];

    public function hasRate(string $currency): bool
    {
        return array_key_exists($currency, $this->rates);
    }

    public function getRate(string $currency): ?float
    {
        return $this->rates[$currency] ?? null;
    }

    public function setRate(string $currency, ?float $rate): void
    {
        $this->rates[$currency] = $rate;
    }
}
