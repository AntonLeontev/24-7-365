<?php

namespace App\Traits;

trait GivesEnum
{
    public static function get(string $type)
    {
        foreach (self::cases() as $enum) {
            if ($type === $enum->value) {
                return $enum;
            }
        }
    }
}
