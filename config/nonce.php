<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable Nonce
    |--------------------------------------------------------------------------
    |
    | If this is set to false, all nonce check in your scripts will pass the
    | verify test. This is a convenient way to temporarily disable nonce checks
    | without having to dig into your codebase.
    |
    */

    'enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Hash Algorithm
    |--------------------------------------------------------------------------
    |
    | The hashing algorithm to use when generating the nonce.
    |
    | See https://www.php.net/manual/en/function.hash-algos.php for supported
    | algorithms.
    |
    */

    'algorithm' => 'sha256',

    /*
    |--------------------------------------------------------------------------
    | Lifetime
    |--------------------------------------------------------------------------
    |
    | The lifetime of the nonce before expiration in seconds.
    |
    */

    'lifetime' => 900,

    /*
    |--------------------------------------------------------------------------
    | Secret Key
    |--------------------------------------------------------------------------
    |
    | The secret key that will be used to help generate the nonces. This is what
    | gives your nonce it's most security. If this is set to null, it will
    | default to use the application key. Always ensure this value is not public.
    | It is a good practice to fetch this value from your .env file.
    |
    */

    'secret' => null,

    /*
    |--------------------------------------------------------------------------
    | Driver
    |--------------------------------------------------------------------------
    |
    | The driver for the nonce. Accepted drivers are 'database' and 'file'. The
    | file driver is recommended as it's twice as fast as the database driver.
    |
    */

    'driver' => 'file',

    /*
    |--------------------------------------------------------------------------
    | Table Name
    |--------------------------------------------------------------------------
    |
    | The table name to use for the table migration. The migration only works if
    | the driver is set to database.
    |
    */

    'table_name' => 'nonces',

    /*
    |--------------------------------------------------------------------------
    | Storage Disk
    |--------------------------------------------------------------------------
    |
    | The storage disk to store the files on when the file driver is being used.
    |
    */

    'storage_disk' => 'local',

    /*
    |--------------------------------------------------------------------------
    | File Directory
    |--------------------------------------------------------------------------
    |
    | The file directory to store the nonce files when the file driver is used.
    | This directory will be placed in your local storage.
    |
    */

    'storage_directory' => 'nonces',

];
