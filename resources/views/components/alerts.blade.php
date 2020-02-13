@php
    $alerts = [];
   /* if ($errors->any()) {
        $alerts[] = (object)[
            'cssClass' => 'alert',
            'message' => $errors->first(),
        ];
    }
   if (Session::has('flashError')) {
        $alerts[] = (object)[
            'cssClass' => 'alert',
            'message' => Session::get('flashError'),
        ];
    }*/
   if (Session::has('flashWarning')) {
        $alerts[] = (object)[
            'cssClass' => 'warning',
            'message' => Session::get('flashWarning'),
        ];
    }
   if (Session::has('status')) {
        $alerts[] = (object)[
            'cssClass' => 'success',
            'message' => Session::get('status'),
        ];
    }
    if (Session::has('flashNotice')) {
        $alerts[] = (object)[
            'cssClass' => 'success',
            'message' => Session::get('flashNotice'),
        ];
    }
@endphp
@if (count($alerts))
    @foreach ($alerts as $alert)
        <div class="alerts {{ $alert->cssClass }}">
            <div class="callout " data-closable>
                <span><i class="fas fa-exclamation-circle"></i>{{ $alert->message }}</span>
                <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                </button>
            </div>
        </div>
    @endforeach
@endif
