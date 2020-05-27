@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {!!  $workoutName[0]->name  !!} Interval |
                        <a href="{{ route('interval.addweights', ['workoutId' => $workoutId, 'intervalId' => $intervalGroup->id]) }}">Add Weights</a> |
                         <a href="{{ route('interval.addcardio', ['workoutId' => $workoutId, 'intervalId' => $intervalGroup->id]) }}">Add Cardio</a>
                    </div>

                    <div class="card-body">
                        @if(isset($intervalExercises))
                            <ul>
                                @foreach($intervalExercises as $exercise)
                                    @if($exercise->exercise_type === 'weight')
                                        <li>  {{$exercise->name}} | reps: {{$exercise->reps }} |
                                            weight: {{ $exercise->weight }}</li>
                                    @endif

                                    @if($exercise->exercise_type === 'cardio')
                                        <li>  {{$exercise->name}} | reps: {{$exercise->reps }}
                                            @if($exercise->distance)
                                            | distance: {{ $exercise->distance }}
                                            @endif
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <p>Add exercises to your interval workout...</p>
                        @endif
                    </div>
                    <div class="card-footer">id: {!!  $intervalGroup->id !!} | sets: {!!  $intervalGroup->sets !!}
                        | {!!  $intervalGroup->time !!}
                        <a href="{{ route('planner') }}" class="btn btn-primary">
                            Back to {!!  $workoutName[0]->name  !!}</a>

                        {!! Form::open(['route' => ['interval.delete', $intervalGroup->id], 'method' => 'POST']) !!}
                        {{Form::submit(' Delete interval', ['class' =>'btn btn-danger'])}}
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

{{--@dump($intervalGroup )--}}
