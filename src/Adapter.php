<?php

namespace Laranonce;

interface Adapter
{
    /**
     * Update or create the nonce.
     *
     * @param  string  $name
     * @param  string  $salt
     * @param  int|string  $userId
     *
     * @return void
     */
    public function updateOrCreateNonce($name, $salt, $userId);

    /**
     * Find the nonce information.
     *
     * @param  string  $name
     * @param  int|string  $userId
     *
     * @return array|bool
     */
    public function getNonceContents($name, $userId);

    /**
     * Destroy the nonce.
     *
     * @param  string  $name
     * @param  int|string  $userId
     *
     * @return bool
     */
    public function destroyNonce($name, $userId);
}
