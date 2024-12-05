<?php

declare(strict_types=1);

namespace App\OaTest\TransactionReader;

use App\OaTest\Exceptions\InvalidTransactionSourceException;
use App\OaTest\TransactionMapper\MapperInterface;
use Generator;

final readonly class FileReader implements ReaderInterface
{
    public function __construct(
        private MapperInterface $mapper,
    ) {}

    /**
     * @inheritdoc
     */
    public function getTransactions(string $source): Generator
    {
        $this->validateSource($source);

        $haveLines = false;
        $fh = fopen($source, 'r');

        while (($line = fgets($fh)) !== false) {
            $haveLines = true;

            yield $this->mapper->map($line);
        }

        if (!$haveLines) {
            yield from [];
        }
    }

    private function validateSource(string $source): void
    {
        if (
            !is_readable($source)
            || !is_file($source)
        ) {
            throw (new InvalidTransactionSourceException('Source is not a file, or is not readable.'))
                ->setTransactionSource($source);
        }
    }
}
