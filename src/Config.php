<?php

namespace Laranonce;

class Config
{
    /**
     * Check whether nonces are enabled.
     *
     * @return bool
     */
    public static function enabled(): bool
    {
        return config('nonce.enabled', true);
    }

    /**
     * Get the hashing algorithm to use.
     *
     * @return string
     */
    public static function algorithm(): string
    {
        return config('nonce.algorithm', 'sha256');
    }

    /**
     * Get the lifetime in seconds that a nonce is valid for.
     *
     * @return int
     */
    public static function lifetime(): int
    {
        return config('nonce.lifetime', 900);
    }

    /**
     * Get the secret key.
     *
     * @return string|null
     */
    public static function secret(): ?string
    {
        $secret = config('app.key');
        return config('nonce.secret', base64_decode($secret));
    }

    /**
     * The driver for the nonce.
     *
     * @return string
     */
    public static function driver(): string
    {
        return config('nonce.driver', 'file');
    }

    /**
     * The table name for the migration.
     *
     * @return string
     */
    public static function tableName(): string
    {
        return config('nonce.table_name', 'nonces');
    }

    /**
     * The storage disk to store the nonce files on when the file driver is
     * being used.
     *
     * @return string
     */
    public static function disk(): string
    {
        return config('nonce.storage_disk', 'local');
    }

    /**
     * The name of the directory within the storage disk that will contain
     * our nonces when the file driver is being used.
     *
     * @return string
     */
    public static function directory(): string
    {
        return config('nonce.storage_directory', 'nonces');
    }
}
