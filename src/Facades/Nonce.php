<?php

namespace Laranonce\Facades;

use Laranonce\HashService;
use Illuminate\Support\Facades\Facade;

class Nonce extends Facade
{
    protected static function getFacadeAccessor()
    {
        return HashService::class;
    }
}
