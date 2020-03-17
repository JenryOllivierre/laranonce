<?php

namespace Laranonce;

use Exception;

class DriverException extends Exception
{
    public function __construct()
    {
        parent::__construct("Nonce driver '" . Config::driver() . "' not supported.");
    }
}
