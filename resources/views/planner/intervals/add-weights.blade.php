@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">WO ID: {{$workoutId}} | Add Weights to Interval</div>
                    <div class="card-body">

                        {!! Form::open(['route' => ['interval.saveweights', $intervalId, $workoutId], 'method' => 'POST']) !!}

                        {{Form::hidden('exercise_type', 'weights')}}

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
                            {{Form::submit('Add Exercises', ['class' =>'btn btn-success'])}}
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
