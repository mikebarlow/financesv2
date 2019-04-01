<?php

namespace App\Money;

use Money\Money;
use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;
use Money\Formatter\DecimalMoneyFormatter;

class Parser
{
    /**
     * @param float $amount
     * @return \Money\Money
     */
    public function convertToMoney($amount)
    {
        $currencies = new ISOCurrencies();

        $moneyParser = new DecimalMoneyParser($currencies);

        return $moneyParser->parse($amount, 'GBP');
    }

    /**
     * @param Money $money
     * @return float
     */
    public function moneyToOutput($money)
    {
        $currencies = new ISOCurrencies();

        $moneyParser = new DecimalMoneyFormatter($currencies);

        return $moneyParser->format($money, 'GBP');
    }
}
