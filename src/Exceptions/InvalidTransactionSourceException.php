<?php

namespace App\OaTest\Exceptions;

use RuntimeException;

class InvalidTransactionSourceException extends RuntimeException
{
    private string $transactionSource;

    public function getTransactionSource(): string
    {
        return $this->transactionSource;
    }

    public function setTransactionSource(string $value): self
    {
        $this->transactionSource = $value;

        return $this;
    }
}
