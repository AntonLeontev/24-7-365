<?php

namespace App\Contracts;

interface SuggestionsContract
{
    public function company(string $query): array;

    public function bank(string $query): array;
}
