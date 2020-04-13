@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{  $workoutName[0]->name  }} | Add Weights</div>
                     <div class="card-body">
                        {!! Form::open(['action' => ['PlannerController@storeWeight', $workoutId], 'method' => 'POST']) !!}
                         <div class="form-group">
                            {{Form::label('exercise', 'Select Exercise')}}

                            {{Form::select('exercise', $weights, null, ['placeholder' => '...'])}}
                            @error('exercise')
                            <p class="help is-danger">{{$errors->first('exercise')}}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            {{Form::label('weight', 'Weight')}}
                            {{Form::number('weight', 0, ['min' => '1'])}}
                            @error('weight')
                            <p class="help is-danger">{{$errors->first('weight')}}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            {{Form::label('reps', 'Add Reps')}}
                            {{Form::number('reps', 0, ['min' => '1'])}}
                            @error('reps')
                            <p class="help is-danger">{{$errors->first('reps')}}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            {{Form::label('sets', 'Add Sets')}}
                            {{Form::number('sets', 0, ['min' => '1'])}}
                            @error('sets')
                            <p class="help is-danger">{{$errors->first('sets')}}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            {{Form::submit('Add Exercises', ['class' =>'btn btn-success'])}}
                        </div>

                    {!! Form::close() !!}
                    </div>
                    <div class="card-footer">
                        <a href="/planner" class="btn btn-primary">Back to planner</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
