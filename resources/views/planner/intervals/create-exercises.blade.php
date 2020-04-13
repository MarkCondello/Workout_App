@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{  $workoutName[0]->name  }} | Add Cardio to Interval</div>
                    <div class="card-body">
{{-- The form route needs to change to update the interval--}}
{{-- There should be an option to select either cardio or weights, then a form will display...--}}
{{--                        {!! Form::open(['action' => ['PlannerController@storeCardio', $workoutId], 'method' => 'POST']) !!}--}}

{{--                        <div class="form-group">--}}
{{--                            {{Form::label('exercise', 'Select Cardio Exercise')}}--}}

{{--                            {{Form::select('exercise', $cardio, null, ['placeholder' => '...'])}}--}}
{{--                            @error('exercise')--}}
{{--                            <p class="help is-danger">{{$errors->first('exercise')}}</p>--}}
{{--                            @enderror--}}
{{--                        </div>--}}

{{--                        <div class="form-group">--}}
{{--                            <div>--}}
{{--                                {{Form::label('mins', 'Minutes')}}--}}
{{--                                {{Form::number('mins', 0, ['min' => 0 , 'max' => 59] )}}--}}
{{--                                @error('mins')--}}
{{--                                <p class="help is-danger">{{$errors->first('mins')}}</p>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                            <div>--}}
{{--                                {{Form::label('seconds', 'Seconds')}}--}}
{{--                                {{Form::number('seconds', 0, ['step' => '10', 'min' => 0 ,'max' => '50'])}}--}}
{{--                                @error('seconds')--}}
{{--                                <p class="help is-danger">{{$errors->first('exercise')}}</p>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            {{Form::label('sets', 'Sets')}}--}}
{{--                            {{Form::number('sets', 0, ['min' => '1'])}}--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            {{Form::submit('Add Exercises', ['class' =>'btn btn-success'])}}--}}
{{--                        </div>--}}

{{--                        {!! Form::close() !!}--}}
{{--                    </div>--}}
{{--                    <div class="card-footer">--}}
{{--                        <a href="/planner" class="btn btn-primary">Back to planner</a>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
