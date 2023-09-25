<?php

namespace App\Support;

class AmountToString
{
    private string $null = 'ноль';

    private array $hundreds = ['', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот'];

    private array $elevens = ['', 'десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать', 'двадцать'];

    private array $tens = ['', 'десять', 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто'];

    private array $sex = [
        // m
        ['', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
        // f
        ['', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
    ];

    private array $forms = [
        // 10^-2
        ['копейка', 'копейки', 'копеек', 1],
        // 10^ 0
        ['рубль', 'рубля', 'рублей', 0],
        // 10^ 3
        ['тысяча', 'тысячи', 'тысяч', 1],
        // 10^ 6
        ['миллион', 'миллиона', 'миллионов', 0],
        // 10^ 9
        ['миллиард', 'миллиарда', 'миллиардов', 0],
        // 10^12
        ['триллион', 'триллиона', 'триллионов', 0],
    ];

    public function do(float|int $numeric, bool $kopToString = false): string
    {
        $out = $tmp = [];

        $tmp = explode('.', str_replace(',', '.', $numeric));
        $rub = number_format($tmp[0], 0, '', '-');

        if ($rub === 0) {
            $out[] = $this->null;
        }
        // нормализация копеек
        $kop = isset($tmp[1]) ? substr(str_pad($tmp[1], 2, '0', STR_PAD_RIGHT), 0, 2) : '00';
        $segments = explode('-', $rub);
        $offset = count($segments);

        if ((int) $rub === 0) {
            // если 0 рублей
            $o[] = $this->null;
            $o[] = $this->morph(0, $this->forms[1][0], $this->forms[1][1], $this->forms[1][2]);
        } else {
            foreach ($segments as $lev) {
                // определяем род
                $sexi = (int) $this->forms[$offset][3];
                // текущий сегмент
                $ri = (int) $lev;
                if ($ri === 0 && $offset > 1) {
                    // если сегмент==0 & не последний уровень(там Units)
                    $offset--;

                    continue;
                }
                // нормализация
                $ri = str_pad($ri, 3, '0', STR_PAD_LEFT);
                // получаем циферки для анализа
                //первая цифра
                $r1 = (int) substr($ri, 0, 1);
                //вторая
                $r2 = (int) substr($ri, 1, 1);
                //третья
                $r3 = (int) substr($ri, 2, 1);
                //вторая и третья
                $r22 = (int) $r2.$r3;
                // разгребаем порядки
                if ($ri > 99) {
                    $o[] = $this->hundreds[$r1];
                }
                // Сотни
                if ($r22 > 20) {
                    // >20
                    $o[] = $this->tens[$r2];
                    $o[] = $this->sex[$sexi][$r3];
                } else {
                    // <=20
                    if ($r22 > 9) {
                        $o[] = $this->elevens[$r22 - 9];
                    }
                    // 10-20
                    elseif ($r22 > 0) {
                        $o[] = $this->sex[$sexi][$r3];
                    }
                    // 1-9
                }
                // Рубли
                $o[] = $this->morph($ri, $this->forms[$offset][0], $this->forms[$offset][1], $this->forms[$offset][2]);
                $offset--;
            }
        }
        // Копейки
        if (! $kopToString) {
            $o[] = $kop;
            $o[] = $this->morph($kop, $this->forms[0][0], $this->forms[0][1], $this->forms[0][2]);
        }

        return preg_replace('/\s{2,}/', ' ', implode(' ', $o));
    }

    /**
     * Склоняем словоформу
     */
    private function morph($number, $form1, $form2, $form5)
    {
        $number = abs($number) % 100;

        $number1 = $number % 10;

        if ($number > 10 && $number < 20) {
            return $form5;
        }

        if ($number1 > 1 && $number1 < 5) {
            return $form2;
        }

        if ($number1 === 1) {
            return $form1;
        }

        return $form5;
    }
}
