@foreach($intervalsGrouped as $key => $intervalGroup)
    <div class="card-body border border-secondary mb-4">
        <p>Group #{{$key}} | {{ $intervalGroup['time'] }} |
            Sets: {{ $intervalGroup['sets']  }} </p>
        <hr>
        @foreach($intervalGroup['exercises_grouped'] as $key => $intervalGroup)
            @php $exerciseType = $intervalGroup[0]['type']; @endphp
            {{$intervalGroup[0]['name']}}

            @switch($exerciseType)
                @case('weight')
                <ul style="padding:0; margin-top: 1rem">
                    @foreach($intervalGroup as $exercise)
                        <li class="d-flex justify-content-between">
                            <span>reps:  {!! $exercise['reps'] !!}</span>
                            <span>weight:  {!! $exercise['weight'] !!}</span>
                        </li>
                    @endforeach
                </ul>
                <hr>
                @break
                @case('cardio')
                <ul style="padding:0; margin-top: 1rem">
                    @foreach($intervalGroup as $exercise)
                        <li class="d-flex justify-content-between">
                            <span>Reps:  {!! $exercise['reps'] !!}</span>
                            <span>Distance:  {!! $exercise['distance'] !!} meters</span>
                        </li>
                    @endforeach
                </ul>
                <hr>
                @break
            @endswitch
        @endforeach
    </div>
@endforeach