<?php

declare(strict_types=1);

namespace App\OaTest\BinCountry;

use App\OaTest\BinCountry\Providers\ProviderInterface;

final readonly class BinCountry
{
    public function __construct(
        private ProviderInterface $provider,
    ) {}

    public function getCountry(string $bin): ?string
    {
        return $this->provider->getCountry($bin);
    }
}
