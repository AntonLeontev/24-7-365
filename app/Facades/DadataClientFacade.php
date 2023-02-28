<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class DadataClientFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'dadata.client';
    }
}