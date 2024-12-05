<?php

declare(strict_types=1);

namespace App\OaTest\Rate\Providers;

use App\OaTest\Exceptions\RateProviderInvalidRateTypeException;
use App\OaTest\Exceptions\RateProviderMalformedJsonException;
use App\OaTest\Provider\SimpleUrlCaller;
use App\OaTest\Rate\Providers\Memory\RateMemory;
use Generator;
use JsonException;

/**
 * Implementation based on https://exchangeratesapi.io/documentation/
 */
final readonly class ExchangeRatesApiProvider implements ProviderInterface
{
    private const string API_URL = 'https://api.exchangeratesapi.io/latest?access_key=';

    public function __construct(
        private string $apiKey,
        private SimpleUrlCaller $caller = new SimpleUrlCaller(),
        private RateMemory $memory = new RateMemory(),
    ) {}

    public function getRate(string $currency): ?float
    {
        if ($this->memory->hasRate($currency)) {
            return $this->memory->getRate($currency);
        }

        foreach ($this->extractRatesFromApiResponse() as $apiCurrency => $rate) {
            $this->memory->setRate($apiCurrency, $rate);
        }

        return $this->memory->getRate($currency);
    }

    private function extractRatesFromApiResponse(): Generator
    {
        $ratesDataJson = $this->caller->fileGetContents(self::API_URL . $this->apiKey);

        try {
            $ratesData = json_decode(
                $ratesDataJson,
                false,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException) {
            throw new RateProviderMalformedJsonException();
        }

        $rates = (array)($ratesData?->rates ?? null);

        if (count($rates) === 0) {
            yield from [];
        }

        foreach ($rates as $currency => $rate) {
            if (is_float($rate)) {
                throw new RateProviderInvalidRateTypeException(
                    sprintf(
                        'Rate "%s" expected to be "float", but "%s" given instead.',
                        $currency,
                        get_debug_type($rate)
                    )
                );
            }

            yield $currency => $rate;
        }
    }
}
