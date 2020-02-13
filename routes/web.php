<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
Route::post('/planner/{workoutId}/storeWeight', 'PlannerController@storeWeight')->middleware('auth');
Route::post('/planner/createWorkout', 'PlannerController@createWorkout')->middleware('auth');

//a named route was required to send form data
Route::post('/planner/{workoutId}/save-workout-results', [
        'as' => 'planner.save-workout-results',
        'uses' => 'PlannerController@saveResults'
    ]
)->middleware('auth');

Route::get('/planner', 'PlannerController@index')->name('planner')->middleware('auth');
Route::get('/planner/{workoutId}/weight-training', 'PlannerController@addWeights')->name('weight-training')->middleware('auth');
Route::get('planner/{workoutId}/delete', 'PlannerController@deleteWorkout')->middleware('auth');
Route::get('planner/{workoutId}/show', 'PlannerController@showWorkout')->middleware('auth');
Route::get('/planner/{workoutId}/start-workout', 'PlannerController@startWorkout')->middleware('auth');
