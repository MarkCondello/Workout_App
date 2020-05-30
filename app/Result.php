<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    //
    protected $fillable = ['exercise_workout_id', 'recorded_reps', 'recorded_weight', 'recorded_time' ];

    public function exerciseWorkout(){
        return $this->belongsTo('App\ExerciseWorkout', 'exercise_workout_id');
    }
}
