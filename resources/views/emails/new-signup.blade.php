@component('mail::message')
# Új jelentkező az eseményedhez

Szia {{ $event->user->name }}!

Új jelentkező van az eseményedhez:

**Esemény:** {{ $event->name }}<br>
**Jelentkező:** {{ $user->name }}<br>
**E-mail:** {{ $user->email }}<br>
**Szabad helyek:** {{ $event->available_spots }} / {{ $event->limit }}

@component('mail::button', ['url' => route('events.edit', $event)])
Esemény megtekintése
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
