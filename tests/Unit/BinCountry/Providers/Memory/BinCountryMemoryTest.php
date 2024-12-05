<?php

namespace Tests\Unit\BinCountry\Providers\Memory;

use App\OaTest\BinCountry\Providers\Memory\BinCountryMemory;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversMethod(BinCountryMemory::class, 'setCountry')]
#[CoversMethod(BinCountryMemory::class, 'hasCountry')]
#[CoversMethod(BinCountryMemory::class, 'getCountry')]
class BinCountryMemoryTest extends TestCase
{
    #[Test]
    public function BinCountryMemoryClass(): void
    {
        $binCountryMemory = new BinCountryMemory();

        $binCountryMemory->setCountry('00', 'US');

        $this->assertEquals('US', $binCountryMemory->getCountry('00'));
        $this->assertFalse($binCountryMemory->hasCountry('01'));
        $this->assertTrue($binCountryMemory->hasCountry('00'));
        $this->assertEquals(null, $binCountryMemory->getCountry('01'));
    }
}
