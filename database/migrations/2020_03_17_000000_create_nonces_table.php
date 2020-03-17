<?php

use Laranonce\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoncesTable extends Migration
{
    /**
     * The table name for the migration.
     *
     * @var string
     */
    private $tableName;

    /**
     * Create an instance of the migration.
     *
     * @return void
     */
    public function __construct()
    {
        $this->tableName = Config::tableName();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('salt');
            $table->string('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
