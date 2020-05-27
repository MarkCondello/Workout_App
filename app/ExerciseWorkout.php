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

    //only for interval exercises // needs to be refactored, split out on its own model etc
    public function intervalGroup(){
        return $this->belongsTo('App\IntervalGroup', 'interval_group_id');
    }

    public function result(){
        return $this->hasOne('App\Result');
     }
}
