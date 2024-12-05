<?php

declare(strict_types=1);

namespace App\OaTest\Provider;

use App\OaTest\Exceptions\NotAvailableUrlException;

final readonly class SimpleUrlCaller
{
    public function fileGetContents(string $url): string
    {
        $content = file_get_contents($url);

        if (false === $content) {
            throw new NotAvailableUrlException(
                'Url "' . $url . '" is not available.'
            );
        }

        return $content;
    }
}
