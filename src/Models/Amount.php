<?php

namespace App\Models;

use Exception;

class Amount {
    public const REGEX_VALIDATION_VALUE = "/^([0-9]+)p([0-9]+)s([0-9]+)d$/i";

    const POUND_IN_SHILLING = 20;
    const SHILLING_IN_PENCES = 12;
    const POUND_IN_PENCES = self::POUND_IN_SHILLING * self::SHILLING_IN_PENCES;

    protected int $pounds;
    protected int $shillings;
    protected int $pences;

    /**
     * @throws Exception
     */
    public function __construct(int $pounds, int $shillings, int $pences) {
        // [int](pP)[int](sS)[int](dD)

        $this->pounds = $pounds;
        $this->shillings = $shillings;
        $this->pences = $pences;
    }

    /**
     * @throws Exception
     */
    public static function fromString(string $amount): Amount {

        // Validate if the string matches the structure.
        if(!preg_match(self::REGEX_VALIDATION_VALUE, $amount, $valueMatches)) {
            throw new Exception("Amount string not valid: " . $amount);
        }

        [$echoString, $pounds, $shillings, $pences] = $valueMatches;

        // Conversion from strings already validated into integers
        $pounds = intval($pounds);
        $shillings = intval($shillings);
        $pences = intval($pences);

        return new Amount($pounds, $shillings, $pences);
    }


    // It has been evaluated that it is not necessary for this use case to create a method that belongs to the amount class,
    // hence i've created static methods like a calculator

    public static function sum(Amount $firstAmount, Amount $secondAmount) : Amount
    {
        $firstAmountInPences = $firstAmount->convertAmountToPences();
        $secondAmountInPences = $secondAmount->convertAmountToPences();
        return self::convertPencesToAmount($firstAmountInPences + $secondAmountInPences);
    }

    public static function subtraction(Amount $firstAmount, Amount $secondAmount) : Amount
    {
        $firstAmountInPences = $firstAmount->convertAmountToPences();
        $secondAmountInPences = $secondAmount->convertAmountToPences();

        return self::convertPencesToAmount($firstAmountInPences - $secondAmountInPences);
    }

    // As the assignment requires, the multiplication should be done with an integer
    public static function multiplication(Amount $firstAmount, int $integer) : Amount
    {
        $firstAmountInPences = $firstAmount->convertAmountToPences();

        return self::convertPencesToAmount($firstAmountInPences * $integer);
    }

    // Since not specified, I assume that the division should be done with an integer

    /**
     * @param  Amount  $firstAmount
     * @param  int  $integer
     * @return array it returns an array of two values, the first is the integer part, and the second one is the remainder
     */
    public static function division(Amount $firstAmount, int $integer) : array {
        $firstAmountInPences = $firstAmount->convertAmountToPences();

        return [
            self::convertPencesToAmount(intdiv($firstAmountInPences, $integer)), // quotient
            self::convertPencesToAmount($firstAmountInPences % $integer) // remainder
        ];
    }

    public function convertAmountToPences(): int {
       return self::convertPoundsToPences($this->pounds) + self::convertShillingsToPences($this->shillings) + $this->pences;
    }

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


    public static function convertShillingsToPounds($shillings): array
    {
        $pounds = intdiv($shillings, self::POUND_IN_SHILLING);
        $remaining = $shillings % self::POUND_IN_SHILLING;
        return [$pounds, $remaining];
    }

    /**
     * @param  int  $pounds
     * @return Amount   result[0] - Pounds
     *                  result[1] - Shillings
     *                  result[2] - pences
     *
     */
    public static function convertPencesToAmount(int $pences): Amount {

        [$shillings, $pences] = self::convertPencesToShillings($pences);

        [$pounds, $shillings] = self::convertShillingsToPounds($shillings);

        return new Amount($pounds, $shillings, $pences);
    }

    public function __toString(): string
    {
        return $this->pounds . 'p' . $this->shillings . 's' . $this->pences . 'd';
    }
}
