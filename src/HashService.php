<?php

namespace Laranonce;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class HashService
{
    /**
     * \Adapter
     */
    private $adapter;

    /**
     * Create an instance of the service.
     *
     * @return void
     */
    public function __construct()
    {
        if (! in_array(Config::driver(), ['database', 'file'])) {
            throw new DriverException;
        }

        if (Config::driver() == 'database') {
            $this->adapter = new DatabaseAdapter;
        } else {
            $this->adapter = new FileAdapter;
        }
    }

    /**
     * Generate a hashed string.
     *
     * @param  string  $name
     *
     * @return string
     */
    public function generate($name): string
    {
        $salt = $this->generateSalt();

        $this->adapter->updateOrCreateNonce($name, $salt, $this->getUserId());

        return $this->generateHash($name, $salt);
    }

    /**
     * Verify the hash.
     *
     * @param  string  $name
     * @param  mixed  $hash
     *
     * @return bool
     */
    public function verify($name, $hash): bool
    {
        if (! Config::enabled()) {
            return true;
        }

        if (! $nonce = $this->adapter->getNonceContents($name, $this->getUserId())) {
            return false;
        }

        if (! $this->checkHash($name, $hash, $nonce['salt'])) {
            return $this->handleInvalidHash($name, $hash, $nonce['salt']);
        }

        if ($this->isExpired($nonce['time'])) {
            return $this->handleExpiredNonce($name, $nonce['time']);
        }

        $this->adapter->destroyNonce($name, $this->getUserId());

        return true;
    }

    /**
     * Handle the invalid hash.
     *
     * @param  string  $name
     * @param  string  $hash
     * @param  string  $salt
     *
     * @return bool
     */
    protected function handleInvalidHash($name, $hash, $salt)
    {
        $this->adapter->destroyNonce($name, $this->getUserId());
        return false;
    }

    /**
     * Handle the expired nonce.
     *
     * @param  string  $name
     * @param  string  $time
     *
     * @return bool
     */
    protected function handleExpiredNonce($name, $time)
    {
        $this->adapter->destroyNonce($name, $this->getUserId());
        return false;
    }

    /**
     * Generate a hashed string.
     *
     * @param  string  $name
     * @param  string  $salt
     *
     * @return string
     */
    protected function generateHash($name, $salt): string
    {
        return hash(Config::algorithm(), $name . Config::secret() . $salt . $this->getUserId());
    }

    /**
     * Check to see if the nonce hash is valid.
     *
     * @param  string  $name
     * @param  mixed  $hash
     * @param  string  $salt
     *
     * @return bool
     */
    protected function checkHash($name, $hash, $salt): bool
    {
        return $this->generateHash($name, $salt) === $hash;
    }

    /**
     * Generate a salt.
     *
     * @return string
     */
    protected function generateSalt(): string
    {
        return bin2hex(random_bytes(64));
    }

    /**
     * Check if a nonce is expired.
     *
     * @param  string  $time  Time formatted YYYY-mm-dd HH:mm:ss
     *
     * @return bool
     */
    protected function isExpired(string $time): bool
    {
        $expirationTimestamp = strtotime($time) + Config::lifetime();

        if (now()->timestamp > $expirationTimestamp) {
            return true;
        }

        return false;
    }

    /**
     * Get the user id.
     *
     * @return int|string
     */
    protected function getUserId()
    {
        return Auth::user()->id ?? csrf_token();
    }
}
