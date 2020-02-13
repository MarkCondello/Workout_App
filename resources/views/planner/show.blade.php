@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card mb-5">
                    <div class="card-header d-flex justify-content-between">
                        <h2> {{$workoutName[0]->name}}</h2>
                        <a href="/planner/{{$workoutId}}/start-workout" class="btn btn-success">Start workout</a>
                    </div>
                    <div class="card-body">
                        @forelse($exercises as $exercise)
                            <div data-exercise-id="{{$exercise->exercise_id}}">
                                <div class="d-flex justify-content-between">
                                    <h3> {{$exercise->name}}</h3>
{{--                                    <a href="#" class="btn btn-warning">Edit exercise</a>--}}
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p>Reps: {{$exercise->reps}} </p>
                                    <p>Weight: {{$exercise->weight}}kgs </p>
                                    <p>Sets: {{$exercise->sets}} </p>
                                </div>
                                <hr/>
                            </div>
                        @empty
                            <p>You have no exercises. Go back to add exercises...</p>
                        @endforelse
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="/planner" class="btn btn-primary">Back to dashboard</a>
                    <a href="/planner/{{$workoutId}}/delete" class="btn btn-danger">Delete workout</a>
                </div>
            </div>
        </div>
    </div>
@endsection
