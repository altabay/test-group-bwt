<?php

namespace Tests\Unit\BinCountry\Providers;

use App\OaTest\BinCountry\Providers\LookUpBinListProvider;
use App\OaTest\BinCountry\Providers\Memory\BinCountryMemory;
use App\OaTest\Exceptions\BinProviderMalformedJsonException;
use App\OaTest\Provider\SimpleUrlCaller;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

#[CoversClass(LookUpBinListProvider::class)]
class LookUpBinListProviderTest extends TestCase
{
    private const string VALID_PROPER_JSON = '{"country":{"alpha2":"US"}}';

    #[Test]
    public function extractCountryFromApiResponseException(): void
    {
        $lookUpBinListProvider = new LookUpBinListProvider(
            $this->createPreDefinedSimpleUrlCaller('[}'),
            $this->createMock(BinCountryMemory::class)
        );

        $reflection = new ReflectionClass(LookUpBinListProvider::class);
        $extractCountryFromApiResponseMethod = $reflection->getMethod(
            'extractCountryFromApiResponse'
        );

        $this->expectException(BinProviderMalformedJsonException::class);
        $extractCountryFromApiResponseMethod->invoke(
            $lookUpBinListProvider,
            '00'
        );
    }

    #[Test]
    public function extractCountryFromApiResponseNull(): void
    {
        $lookUpBinListProvider = new LookUpBinListProvider(
            $this->createPreDefinedSimpleUrlCaller('{"foo":"bar"}'),
            $this->createMock(BinCountryMemory::class)
        );

        $reflection = new ReflectionClass(LookUpBinListProvider::class);
        $extractCountryFromApiResponseMethod = $reflection->getMethod(
            'extractCountryFromApiResponse'
        );

        $this->assertNull(
            $extractCountryFromApiResponseMethod->invoke(
                $lookUpBinListProvider,
                '00'
            )
        );
    }

    #[Test]
    public function extractCountryFromApiResponseValue(): void
    {
        $lookUpBinListProvider = new LookUpBinListProvider(
            $this->createPreDefinedSimpleUrlCaller(self::VALID_PROPER_JSON),
            $this->createMock(BinCountryMemory::class)
        );

        $reflection = new ReflectionClass(LookUpBinListProvider::class);
        $extractCountryFromApiResponseMethod = $reflection->getMethod(
            'extractCountryFromApiResponse'
        );

        $this->assertEquals(
            'US',
            $extractCountryFromApiResponseMethod->invoke(
                $lookUpBinListProvider,
                '00'
            )
        );
    }

    #[Test]
    public function getCountryMemoryHasNoCountry(): void
    {
        $memoryMock = $this->createMock(BinCountryMemory::class);
        $memoryMock
            ->expects($this->once())
            ->method('hasCountry')
            ->willReturn(false);
        $memoryMock
            ->expects($this->once())
            ->method('setCountry');
        $memoryMock
            ->expects($this->once())
            ->method('getCountry');

        $lookUpBinListProvider = new LookUpBinListProvider(
            $this->createPreDefinedSimpleUrlCaller(self::VALID_PROPER_JSON),
            $memoryMock
        );
        $lookUpBinListProvider->getCountry('00');
    }

    #[Test]
    public function getCountryMemoryHasCountry(): void
    {
        $memoryMock = $this->createMock(BinCountryMemory::class);
        $memoryMock
            ->expects($this->once())
            ->method('hasCountry')
            ->willReturn(true);
        $memoryMock
            ->expects($this->never())
            ->method('setCountry');
        $memoryMock
            ->expects($this->once())
            ->method('getCountry');
        $callerMock = $this->createMock(SimpleUrlCaller::class);
        $callerMock
            ->expects($this->never())
            ->method('fileGetContents');

        $lookUpBinListProvider = new LookUpBinListProvider(
            $callerMock,
            $memoryMock
        );
        $lookUpBinListProvider->getCountry('00');
    }

    private function createPreDefinedSimpleUrlCaller(string $response): SimpleUrlCaller|MockObject
    {
        $callerMock = $this->createMock(SimpleUrlCaller::class);
        $callerMock
            ->expects($this->once())
            ->method('fileGetContents')
            ->willReturn($response);

        return $callerMock;
    }
}
