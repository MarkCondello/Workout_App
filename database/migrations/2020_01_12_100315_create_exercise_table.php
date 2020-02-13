<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExerciseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('exercise_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('exercises', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('exercise_type_id');
            $table->string('name')->unique();
            $table->text('instructions');
            $table->text('equipment');
            $table->string('muscle_group');
            $table->timestamps();

            $table->foreign('exercise_type_id')
                ->references('id')
                ->on('exercise_type')
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
        Schema::dropIfExists('exercise_type');
        Schema::dropIfExists('exercise');

    }
}
