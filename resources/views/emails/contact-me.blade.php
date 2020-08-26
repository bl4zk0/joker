@component('mail::message')
    # Contact
    From: {!! "\"$name\"<$email>" !!}}

    {{ $message }}
@endcomponent
