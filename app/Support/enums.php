<?php

if (! function_exists('payment_type')) {
    function payment_type(string $value)
    {
        return App\Enums\PaymentType::get($value);
    }
}

if (! function_exists('payment_status')) {
    function payment_status(string $value)
    {
        return App\Enums\PaymentStatus::get($value);
    }
}

if (! function_exists('contract_status')) {
    function contract_status(string $value)
    {
        return App\Enums\ContractStatus::get($value);
    }
}
