<?php

require_once "vendor/autoload.php";

use App\OaTest\BinCountry\BinCountry;
use App\OaTest\BinCountry\Providers\LookUpBinListProvider;
use App\OaTest\Calculator\Calculator;
use App\OaTest\Provider\SimpleUrlCaller;
use App\OaTest\Rate\Providers\ExchangeRatesApiProvider;
use App\OaTest\Rate\Rate;
use App\OaTest\TransactionMapper\JsonLineMapper;
use App\OaTest\TransactionReader\FileReader;

if (PHP_SAPI !== 'cli') {
    throw new \RuntimeException('This script must be run from the command line.');
}

$transactionReader = new FileReader(
    new JsonLineMapper()
);
$binCountryProvider = new LookUpBinListProvider(
    new SimpleUrlCaller()
);
$rateProvider = new ExchangeRatesApiProvider(
    'some_registered_api_key_for_exchange_rates_api',
    new SimpleUrlCaller()
);
$calculator = new Calculator(
    new BinCountry($binCountryProvider),
    new Rate($rateProvider)
);

foreach ($transactionReader->getTransactions($argv[1] ?? '') as $transaction) {
    echo sprintf(
        '%0.2f' . PHP_EOL,
        $calculator->calculate($transaction)
    );
}
