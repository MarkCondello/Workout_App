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
Route::post('/planner/{workoutId}/storeCardio', 'PlannerController@storeCardio')->middleware('auth');

Route::post('/planner/{workoutId}/createInterval', 'PlannerController@createInterval')->middleware('auth');
Route::get('/planner/addIntervalWeights/{intervalId}', 'PlannerController@addIntervalWeights')->middleware('auth');


Route::post('/planner/storeIntervalWeights/{intervalId}', 'PlannerController@intervalDetails')->middleware('auth');



Route::post('/planner/createWorkout', 'PlannerController@createWorkout')->middleware('auth');

//a named route was required to send form data
Route::post('/planner/{workoutId}/save-workout-results', [
        'as' => 'planner.save-workout-results',
        'uses' => 'PlannerController@saveResults'
    ]
)->middleware('auth');

Route::get('/planner', 'PlannerController@index')->name('planner')->middleware('auth');
Route::get('/planner/{workoutId}/weight-training', 'PlannerController@addWeights')->name('weight-training')->middleware('auth');
Route::get('/planner/{workoutId}/cardio-training', 'PlannerController@addCardio')->name('cardio-training')->middleware('auth');

Route::get('planner/{workoutId}/delete', 'PlannerController@deleteWorkout')->middleware('auth');
Route::get('planner/{workoutId}/show', 'PlannerController@showWorkout')->middleware('auth');
Route::get('/planner/{workoutId}/start-workout', 'PlannerController@startWorkout')->middleware('auth');
Route::get('/planner/{workoutId}/edit-workout', 'PlannerController@editWorkout')->middleware('auth');
Route::get('/planner/{workoutId}/copy-workout', 'PlannerController@copyWorkout')->middleware('auth');

//a named route was required to send form data
Route::post('/planner/{workoutId}/update-workout', [
        'as' => 'planner.update-workout',
        'uses' => 'PlannerController@updateWorkout'
    ]
)->middleware('auth');

//a named route was required to send form data
Route::post('/planner/{workoutId}/save-copied-workout', [
        'as' => 'planner.save-copied-workout',
        'uses' => 'PlannerController@saveCopiedWorkout'
    ]
)->middleware('auth');

