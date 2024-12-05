<?php

namespace App\OaTest\Exceptions;

use RuntimeException;

class InvalidTransactionItemException extends RuntimeException
{
    private mixed $transactionItem;

    public function getTransactionItem(): mixed
    {
        return $this->transactionItem;
    }

    public function setTransactionItem($value): self
    {
        $this->transactionItem = $value;

        return $this;
    }
}
