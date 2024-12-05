<?php

declare(strict_types=1);

namespace App\OaTest\BinCountry\Providers;

use App\OaTest\BinCountry\Providers\Memory\BinCountryMemory;
use App\OaTest\Exceptions\BinProviderMalformedJsonException;
use App\OaTest\Provider\SimpleUrlCaller;
use JsonException;

final readonly class LookUpBinListProvider implements ProviderInterface
{
    private const string API_URL = 'https://lookup.binlist.net/';

    public function __construct(
        private SimpleUrlCaller $caller = new SimpleUrlCaller(),
        private BinCountryMemory $memory = new BinCountryMemory(),
    ) {}

    public function getCountry(string $bin): ?string
    {
        if ($this->memory->hasCountry($bin)) {
            return $this->memory->getCountry($bin);
        }

        $this->memory->setCountry(
            $bin,
            $this->extractCountryFromApiResponse($bin)
        );

        return $this->memory->getCountry($bin);
    }

    private function extractCountryFromApiResponse(string $bin): ?string
    {
        $countryDataJson = $this->caller->fileGetContents(self::API_URL . $bin);

        try {
            $countryData = json_decode(
                $countryDataJson,
                false,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException) {
            throw new BinProviderMalformedJsonException();
        }

        return $countryData?->country?->alpha2 ?? null;
    }
}
