<?php

namespace App\Service;

class MoneyConverterService {
    const POUND_IN_SHILLING = 20;
    const SHILLING_IN_PENCES = 12;
    const POUND_IN_PENCES = self::POUND_IN_SHILLING * self::SHILLING_IN_PENCES;

    public static function convertPoundsToPences(int $pounds): int {
        $poundsInShillings = self::convertPoundsToShillings($pounds);
        return self::convertShillingsToPences($poundsInShillings);
    }

    public static function convertPoundsToShillings(int $pounds): int {
        return $pounds * self::POUND_IN_SHILLING;
    }
    public static function convertShillingsToPences(int $shillings): int {
        return $shillings * self::SHILLING_IN_PENCES;
    }

    /** Return an array in which the first value is the shillings, and the other is the remaining part in pences */
    public static function convertPencesToShillings(int $pences): array
    {
        $shillings = intdiv($pences, self::SHILLING_IN_PENCES);
        $remaining = $pences % self::SHILLING_IN_PENCES;
        return [$shillings, $remaining];
    }
}