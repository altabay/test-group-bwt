<?php

namespace App\OaTest\BinCountry\Providers;

interface ProviderInterface
{
    public function getCountry(string $bin): ?string;
}
