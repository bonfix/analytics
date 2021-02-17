<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessions extends Migration
{
    protected $tableName;
    public function __construct()
    {
        $this->tableName = config('analytics.table_prefix', '').'sessions';
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
            $table->string('app', 100);
            $table->string('version_code', 100);
            $table->string('version_number', 100);
            $table->string('device_id', 255);
            $table->integer('country_id');
            $table->string('username', 255);
            $table->string('language', 50);
            $table->decimal('longitude', 11, 8);
            $table->decimal('latitude', 11, 8);
            $table->integer('village_id')->nullable();
            $table->integer('duration');
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
