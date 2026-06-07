<?php

class TaxCalculatorTest extends PHPUnit_Framework_TestCase
{
    public function testTaxCalculationWithPrecision()
    {
        $calc = new TaxCalculator();
        $amount = 100.55;
        $taxRate = 0.10;

        $result = $calc->calculate($amount, $taxRate);

        // 浮動小数点の比較には、第3引数に delta（許容誤差）を指定します
        // これを忘れると、100.550000000001 のような微差でテストが落ちます
        $this->assertEquals(110.605, $result, '', 0.0001);
    }
}
