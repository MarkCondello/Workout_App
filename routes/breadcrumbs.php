<?php

//planner
Breadcrumbs::for('planner', function($trail) {
    $trail->push('Planner', route('workout.planner'));
});

//planner / weights
Breadcrumbs::for('weights', function($trail, $workoutId) {
    $trail->parent('planner');
    $trail->push('Weights', route('weight-training', $workoutId));
});

//planner / cardio
Breadcrumbs::for('cardio', function($trail, $workoutId) {
    $trail->parent('planner');
    $trail->push('Cardio', route('cardio-training', $workoutId));
});

//planner / intervals
Breadcrumbs::for('intervals', function($trail, $workoutId) {
    $trail->parent('planner');
    $trail->push('Intervals', route('interval-training', $workoutId));
});

//planner / intervals / weights
Breadcrumbs::for('interval-weights', function($trail, $workoutId, $intervalId) {
    $trail->parent('intervals', $workoutId);
    $trail->push('Weights', route('interval-weights', [$workoutId, $intervalId]));
});

//planner / intervals / cardio
Breadcrumbs::for('interval-cardio', function($trail, $workoutId, $intervalId) {
    $trail->parent('intervals', $workoutId);
    $trail->push('Cardio', route('interval-cardio', [$workoutId, $intervalId]));
});

//
//planner / intervals / details
Breadcrumbs::for('interval-details', function($trail, $workoutId, $intervalId) {
    $trail->parent('intervals', $workoutId);
    $trail->push('Details', route('interval-show', [$workoutId, $intervalId]));
});