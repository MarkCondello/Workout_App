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

//for logout route
Auth::routes();

//Route::get('/admin', function () {
//    return "Admin";
//})->middleware('can:access_admin');

Route::group(
    [
        'middleware' => ['auth' ],
        'can' => 'access_admin'
    ],
    function() {
        Route::get('/admin', function () {
            return "Admin";
        });
    }
);


Route::group(['middleware' => 'auth'], function () {

    Route::group([
        'namespace' => 'Weights',
    ], function () {
        Route::post('/planner/{workoutId}/store-weight',
            ['uses' => 'WeightsController@store', 'as' => 'weights.store']);
        Route::get('/planner/{workoutId}/weight-training', 'WeightsController@addWeights')->name('weight-training');
    });

    Route::group([
        'namespace' => 'Cardio'
    ], function () {
        Route::post('/planner/{workoutId}/store-cardio', ['uses' => 'CardioController@store', 'as' => 'cardio.store']);
        Route::get('/planner/{workoutId}/cardio-training', 'CardioController@addCardio')->name('cardio-training');
    });

    Route::group([
        'namespace' => 'Interval',
    ], function () {
        Route::get('/planner/{workoutId}/interval-training', 'IntervalController@intervals')->name('interval-training');

        Route::post('/planner/{workoutId}/create-interval',
            ['uses' => 'IntervalController@create', 'as' => 'interval.create']);

        Route::get('/planner/{workoutId}/show-interval/{intervalId}', 'IntervalController@show' )->name('interval-show');

        Route::get('/planner/{workoutId}/interval-training/{intervalId}/add-weights',
            'IntervalController@addWeights')->name('interval-weights');

        Route::post('/planner/{workoutId}/interval-training/{intervalId}/save-weights',
            ['uses' => 'IntervalController@intervalDetails', 'as' => 'interval.saveweights']);
//
        Route::get('/planner/{workoutId}/interval-training/{intervalId}/add-cardio', 'IntervalController@addCardio')->name('interval-cardio');


        Route::post('/planner/{workoutId}/interval-training/{intervalId}/save-cardio',
            ['uses' => 'IntervalController@intervalDetails', 'as' => 'interval.savecardio']);

//        Route::post('/planner/storeIntervalWeights/{intervalId}', 'IntervalController@intervalDetails');

        Route::post('/planner/delete-interval/{intervalId}',
            ['uses' => 'IntervalController@delete', 'as' => 'interval.delete']);

    });


    Route::group([
        'namespace' => 'Workout',
        'as' => 'workout.',
    ], function () {

        Route::get('/planner', 'WorkoutController@index')->name('planner');
        Route::post('/planner/createWorkout', 'WorkoutController@createWorkout')->name('create');

        Route::get('planner/{workoutId}/delete', 'WorkoutController@deleteWorkout');

        Route::get('planner/{workoutId}/show', 'WorkoutController@showWorkout');
        Route::get('/planner/{workoutId}/start-workout', 'WorkoutController@startWorkout');
        Route::post('/planner/{workoutId}/save-workout-results',
            ['as' => 'planner.save-workout-results', 'uses' => 'WorkoutController@saveResults']);

        Route::get('/planner/{workoutId}/copy-workout', 'WorkoutController@copyWorkout');
        Route::post('/planner/{workoutId}/save-copied-workout',
            ['as' => 'planner.save-copied-workout', 'uses' => 'WorkoutController@saveCopiedWorkout']);

        Route::get('/planner/{workoutId}/edit-workout', 'WorkoutController@editWorkout');
        Route::post('/planner/{workoutId}/update-workout',
            ['as' => 'planner.update-workout', 'uses' => 'WorkoutController@updateWorkout']);
    });

});