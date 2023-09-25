<?php

namespace App\ValueObjects;

use InvalidArgumentException;
use JsonSerializable;

final class Amount implements JsonSerializable
{
    private array $currencies = [
        'RUB' => '₽',
    ];

    public function __construct(
        private readonly int $value,
        private readonly string $currency = 'RUB',
        private readonly int $presition = 100,
    ) {
        if ($value < 0) {
            throw new InvalidArgumentException('Amount must be greater than zero');
        }

        if (! isset($this->currencies[$currency])) {
            throw new InvalidArgumentException('Currency not allowed');
        }
    }

    public function amount(): int|float
    {
        return $this->value / $this->presition;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function currencySign(): string
    {
        return $this->currencies[$this->currency];
    }

    public function raw(): int
    {
        return $this->value;
    }

    public function jsonSerialize(): array
    {
        return [
            'raw' => $this->raw(),
            'sign' => $this->currencySign(),
            'currency' => $this->currency(),
            'amount' => $this->amount(),
            'formatted' => $this->__toString(),
        ];
    }

    public function format(int $decimals): string
    {
        return number_format($this->amount(), $decimals, ',', ' ').' '.$this->currencySign();
    }

    public function __toString(): string
    {
        return number_format($this->amount(), 2, ',', ' ').' '.$this->currencySign();
    }
}
