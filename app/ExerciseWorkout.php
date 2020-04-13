<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExerciseWorkout extends Model
{
    //
    protected $fillable = ['workout_id', 'exercise_id', 'reps', 'weight', 'time'];

    public function workoutInfo(){
       return $this->belongsTo('App\User_Workout', 'workout_id');
    }

    public function exercises(){
        return $this->belongsTo('App\Exercise', 'exercise_id');
    }

    public function result(){
        return $this->hasOne('App\Result');
     }
}
