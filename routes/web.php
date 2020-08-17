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
        'middleware' => ['auth'],
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
        'namespace' => 'Workout',
        'as' => 'workout.',
    ], function () {

        Route::get('/planner', 'WorkoutController@index')->name('planner');

        Route::post('/workout/create', 'WorkoutController@create')->name('create');
        Route::delete('workout/{workout}/delete', 'WorkoutController@destroy')->name('destroy');
        Route::get('workout/{workout}', 'WorkoutController@show')->name('show');
        Route::get('/workout/{workout}/start', 'WorkoutController@start')->name('start');
        Route::post('/workout/{workout}/save', 'WorkoutController@save')->name('save');

        Route::get('/planner/{workout}/copy', 'WorkoutController@copy')->name('copy');
        Route::post('/planner/{workoutCopy}/save-copy', 'WorkoutController@saveCopy')->name('save.copy');

        Route::get('/planner/{workout}/edit', 'WorkoutController@edit')->name('edit');
        Route::post('/planner/{workout}/update', 'WorkoutController@update')->name('update');
    });


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




});