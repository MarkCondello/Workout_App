@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card mb-5">
                    <div class="card-header d-flex justify-content-between">
                        <h2>Copy {{$workout->name}}</h2>
                    </div>
                    {!! Form::open(['route' => ['workout.save.copy', $workout], 'method' => 'POST']) !!}
                    <div class="card-body">
                        {{Form::label('workoutName', 'New workout name:'  ) }}
                        {{Form::text('workoutName', '')}}
                        @error('workoutName')
                        <p class="help is-danger">{{$errors->first('workoutName')}}</p>
                        @enderror
                     </div>
                    <div class="card-footer">
                        <div class="form-group">
                            {{Form::submit('Copy workout', ['class' =>'btn btn-success'])}}
                        </div>
                    </div
                    {!! Form::close() !!}
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{route('workout.planner')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
