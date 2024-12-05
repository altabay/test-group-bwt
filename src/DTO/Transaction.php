<?php

declare(strict_types=1);

namespace App\OaTest\DTO;

final readonly class Transaction
{
    public function __construct(
        public string $bin,
        public float $amount,
        public string $currency,
    ) {}
}
