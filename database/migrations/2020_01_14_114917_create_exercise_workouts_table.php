<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExerciseWorkoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interval_groups', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('workout_id');
            $table->time('time')->nullable();
            $table->integer('sets')->nullable();
            $table->timestamps();

            $table->foreign('workout_id')
                ->references('id')
                ->on('user_workouts')
                ->onDelete('cascade');
        });

        Schema::create('exercise_workouts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('workout_id');
            $table->unsignedBigInteger('exercise_id');

            //Can FK be nullable???
            $table->unsignedBigInteger('interval_group_id')->nullable();

            $table->integer('reps')->nullable();
            $table->integer('weight')->nullable();
            $table->integer('sets')->nullable();
            $table->time('time')->nullable();
            $table->integer('distance')->nullable();

            $table->timestamps();

            $table->foreign('workout_id')
                ->references('id')
                ->on('user_workouts')
                ->onDelete('cascade');

            $table->foreign('exercise_id')
                ->references('id')
                ->on('exercises')
                ->onDelete('cascade');

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
        Schema::dropIfExists('exercise_workouts');
        Schema::dropIfExists('interval_groups');

    }
}
