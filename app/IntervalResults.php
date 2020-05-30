<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntervalResults extends Model
{
    //
    protected $fillable = ['interval_group_id', 'sets_completed'];

    public function intervalGroup(){
        return $this->belongsTo('App\IntervalGroup', 'interval_group_id');
    }
}
