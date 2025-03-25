<?php

namespace App\Tests\Service;

use App\Models\Amount;
use PHPUnit\Framework\TestCase;
use App\Service\MoneyConverterService;

class MoneyConverterServiceTest extends TestCase
{
    public function testOnePoundEquals240Pences(): void
    {
        $this->assertTrue(Amount::convertPoundsToPences(1) == 240);
    }
    public function testOnePoundEquals20Shillings(): void
    {
        $this->assertTrue(Amount::convertPoundsToShillings(1) == 20);
    }

    public function testOneShillingEquals12Pences(): void
    {
        $this->assertTrue(Amount::convertShillingsToPences(1) == 12);
    }

    public function testPencesInShillings() : void
    {
        $result = Amount::convertPencesToShillings(150);
        $this->assertTrue($result[0] == 12);
        $this->assertTrue($result[1] == 6);
    }

    //public function test(){ //5p 17s 8d = 1412 pences (first value)
     //   $firstValueInPences = Amount::convertPoundsToPences(18) + Amount::convertShillingsToPences(16) + 1;
//
     //   // 3p 4s 10d
     //   $secondValueInPences =Amount::convertPoundsToPences(3) + Amount::convertShillingsToPences(4) + 10;
//
     //   var_dump(Amount::convertPencesToAmount(intdiv($firstValueInPences, 15)));
     //   var_dump(Amount::convertPencesToAmount($firstValueInPences%15));


   // }
}
