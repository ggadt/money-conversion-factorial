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
}
