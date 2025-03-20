<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\MoneyConverterService;

class MoneyConverterServiceTest extends TestCase
{
    public function testOnePoundEquals240Pences(): void
    {
        $this->assertTrue(MoneyConverterService::convertPoundsToPences(1) == 240);
    }
    public function testOnePoundEquals20Shillings(): void
    {
        $this->assertTrue(MoneyConverterService::convertPoundsToShillings(1) == 20);
    }

    public function testOneShillingEquals12Pences(): void
    {
        $this->assertTrue(MoneyConverterService::convertShillingsToPences(1) == 12);
    }

    public function testPoundsInShillings() : void
    {
        $result = MoneyConverterService::convertPencesToShillings(150);
        $this->assertTrue($result[0] == 12);
        $this->assertTrue($result[1] == 6);
    }

    public function test(){ //5p 17s 8d = 1412 pences (first value)
        $firstValueInPences = MoneyConverterService::convertPoundsToPences(5) + MoneyConverterService::convertShillingsToPences(17) + 8;

        // 3p 4s 10d
        $secondValueInPences =MoneyConverterService::convertPoundsToPences(3) + MoneyConverterService::convertShillingsToPences(4) + 10;



        var_dump(MoneyConverterService::convertPencesToPoundsCompleteFormat($firstValueInPences+$secondValueInPences));
    }
}
