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


    public static function convertShillingsToPounds(mixed $shillings): array
    {
        $pounds = intdiv($shillings, self::POUND_IN_SHILLING);
        $remaining = ($shillings % self::POUND_IN_SHILLING) + $shillings[1];
        return [$pounds, $remaining];
    }

    /**
     * @param  int  $pounds
     * @return array    result[0] - Pounds
     *                  result[1] - Shillings
     *                  result[2] - pences
     *
     */
    public static function convertPencesToPoundsCompleteFormat(int $pences): array
    {
        [$shillings, $pences] = self::convertPencesToShillings($pences);

        [$pounds, $shillings] = self::convertShillingsToPounds($shillings);

        return [$pounds, $shillings, $pences];
    }
}