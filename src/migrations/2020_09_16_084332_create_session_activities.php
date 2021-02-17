<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateSessionActivities
 */
class CreateSessionActivities extends Migration
{
    protected $tableName;
    public function __construct()
    {
        $this->tableName = config('analytics.table_prefix', '').'session_'.'activities';
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('at');
            $table->integer('session_id');
            $table->string('name', 255);
            $table->string('type', 255)->nullable();
            $table->integer('type_id')->nullable();
            $table->string('source', 255)->nullable();
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
