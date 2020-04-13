@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"> {!!  $workoutName[0]->name  !!} Interval | <a
                                href="/planner/addIntervalWeights/{{ $intervalGroup->id}}">Add Weights</a> |
                        <a href="">Add Cardio</a></div>
                    <div class="card-body">
                        @if(count($intervalExercises))
                            <ul>
                                @foreach($intervalExercises as $exercise)
                                    @if($exercise->exercise_type === 'weight')
                                        <li>  {{$exercise->name}} | reps: {{$exercise->reps }} |
                                            weight: {{ $exercise->weight }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif

                    </div>
                    <div class="card-footer">id: {!!  $intervalGroup->id !!} | sets: {!!  $intervalGroup->sets !!}
                        | {!!  $intervalGroup->time !!}   </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{--@dump($intervalGroup )--}}
