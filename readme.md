# Laranonce

## About

Laranonce is a nonce generator for your laravel application.

## Installation

Install via composer command `composer require jenryollivierre/laranonce`

## Configuration

Publish the package's configuration file to configure options before starting to use the package. Use artisan command `php artisan vendor:publish --tag=laranonce-config`. 

## Configuration Options

- **enabled**: Whether to enable the nonce functionality. If this is set to false, all nonce checks will pass as true wherever the check is done. This is particularly useful when testing your application to disable nonce checks. Defaults to true.

- **algorithm**: The hashing algorithm to use while generating the nonce. Defaults to 'sha256'. See [https://www.php.net/manual/en/function.hash-algos.php](https://www.php.net/manual/en/function.hash-algos.php)

- **lifetime**: The time in seconds that the nonce will be valid for. Defaults to 900 seconds (15 minutes).

- **secret**: The secret key to use in the nonce generation process. This should not be a public key. Ideally, you should reference this value from your .env file. Defaults to App_Key .env value.

- **driver**: The driver to use for the nonce process. Available drivers are database and file. File driver is recommended as it's twice as fast as the database driver. Defaults to file.

- **table_name**: The name of the table to create if the database driver is being used.

- **storage_disk**: The storage disk to store the nonces on when using the file driver. The public disk should never be used! Defaults to local.

- **storage_directory**: A directory within the storage disk to store the nonces when using the file driver. Defaults to nonces.

## How To Use

On your form input field, call the `Laranonce\Facades\Nonce::generate()` method, which accepts one parameter, which is an identifier for the nonce. Try to make the name as unique as possible i.e `Laranonce\Facades\Nonce::generate('submit_cart_paypal_payment');`

```php
<input type="hidden" name="nonce" value="{{ Laranonce\Facades\Nonce::generate('submit_cart_paypal_payment') }}">
```

Then on the backend, you check by using the Laranonce\Facades\Nonce::verify($name, $nonce) method, which accepts to parameters. The first parameter is the name of the nonce action, and the 2nd parameter is the nonce value that was submitted with the request:

```php
<?php

namespace App\Http\Controllers\CartController;

use Illuminate\Http\Request;
use Laranonce\Facades\Nonce;

class CartController extends Controller
{
    public function __invoke(Request $request)
    {
        $nonce = $request->nonce;

        if (! Nonce::verify('submit_cart_paypal_payment', $nonce)) {
            // handle failed next action
        }
    }
}
```

## Alias

In your application config/app.php file, you can setup an alias to use in your view files.

`'Nonce' => Laranonce\Facades\Nonce::class,`

## Clean Up

In order for the package to be a true nonce package, data is stored either in the database or file storage depending on the driver chosen to use. To clean up these files, take advantage of laravel task scheduling. See [Task Scheduling Docs](https://laravel.com/docs/master/scheduling).

You can call the `php artisan nonce:prune command` which by defaults deletes all the expired nonces. You can supply the 'all' argument to delete all the nonces `php artisan nonce:prune all`.

In your task scheduling:

```php
<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Frequency determined based on expiration time set in config file
        $schedule->command('nonce:prune')->everyHour();
    }

```
