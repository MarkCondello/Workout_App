@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                {{ Breadcrumbs::render('planner') }}  
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Create a new workout</div>
                    <div class="card-body">
                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        {!! Form::open(['route' => 'workout.create', 'method' => 'POST']) !!}
                        <div class="form-group">
                            {{Form::label('name', 'Workout name.')}}
                            {{Form::text('name', '', ['class'=> 'form-control'])}}
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
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Your current workouts</div>
                    <div class="card-body">
                        @forelse($workouts as $workout)
                            @if(!empty($workout->exercisesGrouped) || !empty($workout->intervalsGrouped))
                                <div class="card-body border border-secondary mt-2  ">
                                    <div class="card-header d-flex justify-content-between mb-4">
                                        <h3> {!! $workout->name !!}</h3>
                                        <div class="btn btn-primary details-dd-btn">Details</div>
                                    </div>
                                    <div class="details-panel">
                                        @include('components/workout-options', ['workout' => $workout])
                                        @if($workout->exercisesGrouped)
                                            <div class="card-body border border-secondary mt-4 mb-4">
                                                <h4>Weights and Cardio</h4>
                                                <hr>
                                                @include('components/exercise-groups', ['exercisesGrouped' => $workout->exercisesGrouped])
                                            </div>
                                        @endif
                                        @if($workout->intervalsGrouped)
                                            <div class="card-body border border-secondary mb-4">
                                                <h4>Intervals</h4>
                                                @include('components/intervals-grouped', [ 'intervalsGrouped'=> $workout->intervalsGrouped])
                                            </div>
                                        @endif
                                     </div>
                                </div>
                            @else
                                @include('components/workout-options', ['workout' => $workout])
                                <p class="d-flex">Add exercises to {{$workout->name}}</p>
                                <hr>
                            @endif
                        @empty
                            <p>You have no workouts. Create one to get stated.</p>
                        @endforelse
                    </div>
                </div>

                @if(isset($results)   )
                <div class="card mt-4">
                    <div class="card-header">Previous Workout Results</div>
                    <div class="card-body">
                        @foreach($results as $workout)
                            <div class="card-body border border-secondary mb-4">
                                <div class="card-header d-flex justify-content-between">
                                    <h3> {!! $workout->name !!}</h3>
                                    <div class="d-flex justify-content-end  ">
                                        {!! Form::open(['route' => ['workout.destroy', $workout] ]) !!}
                                            @method('DELETE')
                                            <button class="btn btn-danger ml-1">Delete test</button>
                                         {!! Form::close() !!}
                                        <button class="btn btn-primary details-dd-btn  ml-1">Details</button>
                                        <a href="{{ route('workout.copy', $workout) }}"
                                           class="btn btn-success ml-1">Copy {!! $workout->name !!}</a>
                                    </div>
                                </div>
                                <div class="details-panel">
                                    @if($workout->exercisesGrouped)
                                        <div id="workout-{{$workout->id}}-exercise-details"
                                             class="card-body border border-secondary mt-2">
                                            @foreach($workout->exercisesGrouped as $exerciseGroup)
                                                <ul style="padding:0;">
                                                    <li class="d-flex">
                                                        <h4>{{ $exerciseGroup[0]['name'] }}</h4>
                                                    </li>
                                                    @foreach($exerciseGroup as $key=>$exercise)
                                                        <li class="d-flex"><h5>Target Set {{$key+1}}</h5></li>
                                                        <li class="d-flex justify-content-between">
                                                            <span>weight: {!! $exercise['weight'] !!}</span>
                                                            <span>sets: {!! $exercise['sets'] !!}</span>
                                                        </li>
                                                        <li class="d-flex"><h5>Result</h5></li>
                                                        <li class="d-flex justify-content-between">
                                                            <span>weight: {!! $exercise['result']['recorded_weight'] !!}</span>
                                                            <span>sets: {!! $exercise['sets'] !!}</span>
                                                        </li>
                                                        <hr>
                                                    @endforeach
                                                </ul>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($workout->intervalsGrouped)
                                        <div id="workout-{{$workout->id}}-interval-details"
                                             class="card-body border border-secondary mt-2">
                                            @foreach($workout->intervalsGrouped as $key=>$intervalGroup)
                                                 <ul style="padding:0;">
                                                    <li class="d-flex">
                                                        <h5>Interval Targets: {{ $intervalGroup['time'] }} | {{ $intervalGroup['sets'] }} sets</h5>
                                                    </li>
                                                    <li class="d-flex">
                                                        Results: {{ $intervalGroup['interval_results']['sets_completed'] }} sets
                                                    </li>
                                                </ul>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
@endsection
