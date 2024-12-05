<?php

namespace App\OaTest\TransactionMapper;

use App\OaTest\DTO\Transaction;

interface MapperInterface
{
    public function map($item): Transaction;
}
