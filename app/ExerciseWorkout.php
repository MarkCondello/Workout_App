<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExerciseWorkout extends Model
{
    //
    protected $fillable = ['workout_id', 'exercise_id', 'reps', 'weight', 'time'];
}
