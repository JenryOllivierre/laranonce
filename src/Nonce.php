<?php

namespace Laranonce;

use Illuminate\Database\Eloquent\Model;

class Nonce extends Model
{
    /**
     * The mass assignable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'salt', 'user_id',
    ];

    /**
     * Get the table name for the model.
     *
     * @return string
     */
    public function getTable()
    {
        return Config::tableName();
    }
}
