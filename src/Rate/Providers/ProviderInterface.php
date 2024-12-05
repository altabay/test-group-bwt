<?php

namespace App\OaTest\Rate\Providers;

interface ProviderInterface
{
    public function getRate(string $currency): ?float;
}
