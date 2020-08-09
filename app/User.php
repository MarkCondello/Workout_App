<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function workouts() 
    {
       return $this->hasMany(User_Workout::class);
    }

    public function roles(){
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function assignRole($role) {

        if(is_string($role)){
           $role = Role::whereName($role)->firstOrFail();
        }

        //if there was an assignment, replace all pivot table values using sync method
        $this->roles()->sync($role, false);
    }

    public function abilities(){
        //higher order function map
        return $this->roles->map->abilities->flatten()->pluck('name')->unique();
    }
}
