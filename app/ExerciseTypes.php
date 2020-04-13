<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExerciseTypes extends Model
{
    //
    public function exercises(){
        return $this->hasMany('App\Exercise', 'exercise_type_id');
    }

}
