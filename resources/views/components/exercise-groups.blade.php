@foreach($exercisesGrouped as $exerciseGroup)
    <div>
        <h5> {{ $exerciseGroup[0]['name']}}  </h5>
        @php $exerciseType = $exerciseGroup[0]['type']; @endphp
        @switch($exerciseType)
            @case('weight')
            <ul style="padding:0; margin-top: 1rem">
                @foreach($exerciseGroup as $exercise)
                    <li class="d-flex justify-content-between">
                        <span>reps:  {!! $exercise['reps'] !!}</span>
                        <span>weight:  {!! $exercise['weight'] !!}</span>
                        <span>sets:  {!! $exercise['sets'] !!}</span>
                    </li>
                @endforeach
            </ul>
            @break
            @case('cardio')
            <ul style="padding:0; margin-top: 1rem">
                @foreach($exerciseGroup as $exercise)
                    <li class="d-flex justify-content-between">
                        <span>time:  {!! $exercise['time'] !!}</span>
                        <span>sets:  {!! $exercise['sets'] !!}</span>
                    </li>
                @endforeach
            </ul>
            @break
        @endswitch
        <hr>
    </div>
@endforeach