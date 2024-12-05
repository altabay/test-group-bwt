<?php

namespace App\OaTest\Exceptions;

use RuntimeException;

class RateProviderInvalidRateTypeException extends RuntimeException
{
    private ?string $rateName;
    private ?string $invalidType;
}
