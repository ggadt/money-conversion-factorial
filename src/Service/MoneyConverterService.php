<?php

namespace App\Service;

use App\Models\Amount;

class MoneyConverterService {

    /**
     * This function expects two parameters:
     *  @param Amount $firstAmount
     *  @param Amount $secondAmount
     *
     * @return Amount $sumAmounts The sum with the first amount added to the second one.
     */
    public static function sumAmounts(Amount $firstAmount, Amount $secondAmount): Amount
    {
        return Amount::sum($firstAmount, $secondAmount);
    }

    public static function subtractAmounts(Amount $firstAmount, Amount $secondAmount): Amount
    {
        return Amount::subtraction($firstAmount, $secondAmount);
    }

    public static function multiplyAmounts(Amount $amount, int $multiplier): Amount
    {
        return Amount::multiplication($amount, $multiplier);
    }


    public static function divideAmount(Amount $amount, int $divider): array
    {
        return Amount::division($amount, $divider);
    }
}