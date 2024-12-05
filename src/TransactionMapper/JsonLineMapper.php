<?php

declare(strict_types=1);

namespace App\OaTest\TransactionMapper;

use App\OaTest\DTO\Transaction;
use App\OaTest\Exceptions\InvalidTransactionItemException;
use JsonException;
use stdClass;

final readonly class JsonLineMapper implements MapperInterface
{
    /**
     * @throws InvalidTransactionItemException
     */
    public function map(mixed $item): Transaction
    {
        $this->validateItem($item);
        $itemObject = $this->extractJson($item);
        $this->validateItemObject($itemObject);

        return new Transaction(
            $itemObject->bin,
            (float)$itemObject->amount,
            $itemObject->currency,
        );
    }

    /**
     * @throws InvalidTransactionItemException
     */
    private function validateItem(mixed $item): void
    {
        if (!is_string($item)) {
            throw (new InvalidTransactionItemException(
                'Invalid transaction item type: "string" expected, received: "' . get_debug_type($item) . '".'
            ))
                ->setTransactionItem($item);
        }
    }

    /**
     * @throws InvalidTransactionItemException
     */
    private function extractJson(mixed $item): stdClass
    {
        try {
            return json_decode(
                $item,
                false,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException) {
            throw (new InvalidTransactionItemException('Invalid item json.'))
                ->setTransactionItem($item);
        }
    }

    /**
     * @throws InvalidTransactionItemException
     */
    private function validateItemObject(stdClass $item): void
    {
        if (
            ($item->bin ?? null) === null
            || !is_string($item->bin)
        ) {
            throw (new InvalidTransactionItemException('Invalid item "bin" type.'))
                ->setTransactionItem($item);
        }

        if (
            ($item->amount ?? null) === null
            || !is_string($item->amount)
        ) {
            throw (new InvalidTransactionItemException('Invalid item "amount" type.'))
                ->setTransactionItem($item);
        }

        if (
            ($item->currency ?? null) === null
            || !is_string($item->currency)
        ) {
            throw (new InvalidTransactionItemException('Invalid item "currency" type.'))
                ->setTransactionItem($item);
        }
    }
}
