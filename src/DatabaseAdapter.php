<?php

namespace Laranonce;

class DatabaseAdapter implements Adapter
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
    public function updateOrCreateNonce($name, $salt, $userId)
    {
        Nonce::updateOrCreate(
            ['name' => $name, 'user_id' => $userId],
            ['salt' => $salt]
        );
    }

    /**
     * Find the nonce information.
     *
     * @param  string  $name
     * @param  int|string  $userId
     *
     * @return array|bool
     */
    public function getNonceContents($name, $userId)
    {
        $nonce = Nonce::where('name', $name)->where('user_id', $userId)->first();

        if (is_null($nonce)) {
            return false;
        }

        return [
            'time' => $nonce->updated_at,
            'salt' => $nonce->salt,
        ];
    }

    /**
     * Destroy the nonce.
     *
     * @param  string  $name
     * @param  int|string  $userId
     *
     * @return bool
     */
    public function destroyNonce($name, $userId)
    {
        return Nonce::where('name', $name)->where('user_id', $userId)->delete();
    }
}
