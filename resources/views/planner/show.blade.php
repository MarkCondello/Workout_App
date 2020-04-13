@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card mb-5">
                    <div class="card-header d-flex justify-content-between">
                        <h2> {{$workoutName[0]->name}}</h2>
                        <a href="/planner/{{$workoutId}}/edit-workout" class="btn btn-warning">Edit workout</a>
                        <a href="/planner/{{$workoutId}}/start-workout" class="btn btn-success">Start workout</a>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @forelse($exercisesGrouped as $exerciseGroup)
                            @include('components/exercise-groups', ['exerciseGroup' => $exerciseGroup])
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
