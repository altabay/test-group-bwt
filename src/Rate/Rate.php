<?php

declare(strict_types=1);

namespace App\OaTest\Rate;

use App\OaTest\Rate\Providers\ProviderInterface;

final readonly class Rate
{
    public function __construct(
        private ProviderInterface $provider,
    ) {}

    public function getRate(string $currency): ?float
    {
        return $this->provider->getRate($currency);
    }
}
