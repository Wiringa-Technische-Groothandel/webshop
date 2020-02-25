@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level == 'error')
# Whoops!
@else
# Hallo!
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
            $color = 'green';
            break;
        case 'error':
            $color = 'red';
            break;
        default:
            $color = 'blue';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
Met vriendelijke groet,<br>Wiringa Technische Groothandel
@endif

{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')
Als u problemen ondervind bij het aanklikken van de "{{ $actionText }}" knop, kopieer en plak onderstaande url
in uw web browser: [{{ $actionUrl }}]({{ $actionUrl }})
@endcomponent
@endisset
@endcomponent
