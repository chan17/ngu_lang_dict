<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhoneticsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phonetics', function (Blueprint $table) {
            $table->increments('phonetic_id');
            $table->smallInteger('region_type')->nullable();
            $table->integer('entry_id')->nullable();
            $table->string('value', 255)->nullable();
            $table->timestamps('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phonetics');
    }
}
