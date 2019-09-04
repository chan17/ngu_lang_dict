<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMetaTypesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta_type', function (Blueprint $table) {
            $table->smallIncrements('type_id');
            $table->string('title', 50);
            $table->smallInteger('pid')->nullable();
            $table->string('remark', 255)->nullable();
            $table->String('group', 33)->nullable();
            $table->smallInteger('listorder')->nullable();
            $table->timestamps('');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meta_type');
    }
}
