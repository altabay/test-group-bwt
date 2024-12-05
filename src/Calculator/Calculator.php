<?php

declare(strict_types=1);

namespace App\OaTest\Calculator;

use App\OaTest\BinCountry\BinCountry;
use App\OaTest\DTO\Transaction;
use App\OaTest\Rate\Rate;

final readonly class Calculator
{
    private const string NO_CONVERSION_CURRENCY = 'EUR';
    private const float DEFAULT_RATE = 1.0;

    public function __construct(
        private BinCountry $binCountry,
        private Rate $rate,
    ) {}

    public function calculate(Transaction $transaction): float
    {
        $country = $this->binCountry->getCountry(
            $transaction->bin
        );

        return $transaction->amount
            / $this->getConditionalRate($transaction->currency)
            * $this->getConditionalEuCorrection($country);
    }

    private function getConditionalRate(string $currency): float
    {
        if ($currency === self::NO_CONVERSION_CURRENCY) {
            return self::DEFAULT_RATE;
        }

        $rate = $this->rate->getRate($currency);

        return (null !== $rate) && ($rate > 0)
            ? $rate
            : self::DEFAULT_RATE;
    }

    private function getConditionalEuCorrection(?string $country): float
    {
        return $this->isEu($country)
            ? 0.01
            : 0.02;
    }

    private function isEu(?string $country): bool
    {
        return null !== $country
            && null !== EuCountryEnum::tryFrom($country);
    }
}
