@component('mail::message')
# Eseményre való jelentkezés megerősítése

Szia!

Sikeresen jelentkeztél az alábbi eseményre:

**{{ $event->name }}**

**Dátum:** {{ $event->event_date->format('Y.m.d. H:i') }}

Köszönjük a jelentkezést!

@component('mail::button', ['url' => route('home')])
Események megtekintése
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
