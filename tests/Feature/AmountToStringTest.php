<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AmountToStringTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */
    public function test_converting_to_string(int|float $input, string $expected): void
    {
        $result = amount_to_string($input);

        $this->assertEquals($expected, $result);
    }

    public static function additionProvider(): array
    {
        return [
            [12, 'Двенадцать рублей 00 копеек'],
            [1_512.6, 'Одна тысяча пятьсот двенадцать рублей 60 копеек'],
            [540_102, 'Пятьсот сорок тысяч сто два рубля 00 копеек'],
            [8_653_215, 'Восемь миллионов шестьсот пятьдесят три тысячи двести пятнадцать рублей 00 копеек'],
            [0, 'Ноль рублей 00 копеек'],
            [1, 'Один рубль 00 копеек'],
        ];
    }
}
