<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntervalResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interval_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('interval_group_id');
            $table->integer('sets_completed');
            $table->timestamps();

            $table->foreign('interval_group_id')
                ->references('id')
                ->on('interval_groups')
                ->onDelete('cascade');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interval_results');
    }
}
