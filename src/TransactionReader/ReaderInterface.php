<?php

namespace App\OaTest\TransactionReader;

use App\OaTest\DTO\Transaction;
use Generator;

interface ReaderInterface
{
    /**
     * @param string $source
     *
     * @return Generator<Transaction>
     */
    public function getTransactions(string $source): Generator;
}
