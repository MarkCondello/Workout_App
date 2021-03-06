<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Results extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('exercise_workout_id');
            $table->integer('recorded_reps');
            $table->integer('recorded_weight');
            $table->time('recorded_time');
            $table->timestamps();

            $table->foreign('exercise_workout_id')
                ->references('id')
                ->on('exercise_workouts')
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
        Schema::dropIfExists('results');
    }
}
