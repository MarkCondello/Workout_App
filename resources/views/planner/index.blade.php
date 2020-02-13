@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Workout Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                         {!! Form::open(['action' => 'PlannerController@createWorkout', 'method' => 'POST']) !!}
                        <div class="form-group">
                            {{Form::label('name', 'Create a new workout.')}}
                            {{Form::text('name', '', ['class'=> 'form-control', 'placeholder' => 'Workout name'])}}
                            @error('name')
                            <p class="help is-danger">{{$errors->first('name')}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            {{Form::submit('Save', ['class' =>'btn btn-primary'])}}
                        </div>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Your saved workouts</div>
                    <div class="card-body">
                        @forelse($workouts as $workout)
                            <div class="">
                                <h3>{{$workout->name}}</h3>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="/planner/{{$workout->id}}/weight-training" class="btn btn-primary">Add
                                            Weights</a>
                                        <a href="/planner/{{$workout->id}}/cardio-training" class="btn btn-primary">Add
                                            Cardio</a>
                                        <a href="/planner/{{$workout->id}}/interval-training" class="btn btn-primary">Add
                                            Intervals</a>
                                    </div>
                                    <div>
                                        <a href="/planner/{{$workout->id}}/delete" class="btn btn-danger ">Delete</a>
                                         <a href="/planner/{{$workout->id}}/show" class="btn btn-success ">Show</a>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        @empty
                            <p>You have no workouts. Create one above.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
