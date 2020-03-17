<?php

namespace Laranonce;

use Illuminate\Support\Facades\Storage;

class FileAdapter implements Adapter
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
        $contents = now() . ',' . $salt;

        Storage::disk(Config::disk())->put($this->getFileName($name, $userId), $contents, 'private');
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
        if (! Storage::disk(Config::disk())->exists($this->getFileName($name, $userId))) {
            return false;
        }

        if (! $contents = Storage::disk(Config::disk())->get($this->getFileName($name, $userId), true)) {
            return false;
        }

        $contents = explode(',', $contents);

        if (count($contents) != 2) {
            return false;
        }

        return [
            'time' => $contents[0],
            'salt' => $contents[1],
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
        return Storage::disk(Config::disk())->delete($this->getFileName($name, $userId));
    }

    /**
     * Get the filename for the nonce.
     *
     * @param  string  $name
     * @param  int|string  $userId
     *
     * @return string
     */
    private function getFileName($name, $userId)
    {
        $directory = Config::directory();
        return $directory . '/' . $name . '__' . $userId;
    }
}
