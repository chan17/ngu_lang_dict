<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommonTypesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('common_type', function (Blueprint $table) {
            $table->smallIncrements('type_id');
            $table->string('title', 50);
            $table->string('remark', 255);
            $table->String('group', 33)->nullable();
            $table->smallInteger('listorder');
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
        Schema::dropIfExists('common_type');
    }
}
