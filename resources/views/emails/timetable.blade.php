<x-mail::message>
# Sinu tunniplaan

Sinu tunnid ja sündmused üheks nädalaks, kenasti ühes kohas.

<x-mail::panel>
**Nädal**  
{{ $startDate->copy()->locale('et')->translatedFormat('j. F') }}–{{ $endDate->copy()->locale('et')->translatedFormat('j. F Y') }}

**Kokku**  
{{ $timetableEvents->flatten(1)->count() }} {{ $timetableEvents->flatten(1)->count() === 1 ? 'tund' : 'tundi' }}
</x-mail::panel>

@if ($timetableEvents->isEmpty())
## Sel nädalal on vaba

Praegu ei ole selleks nädalaks tunniplaani sündmusi lisatud. Mõnusat nädalat!
@else
@foreach ($timetableEvents as $day => $events)
<x-mail::panel>
## {{ ucfirst($day) }}

@foreach ($events as $event)
**{{ $event['timeStart'] ?? 'Aeg täpsustub' }}@if (! empty($event['timeEnd']))–{{ $event['timeEnd'] }}@endif**  
{{ data_get($event, 'subjectName') ?? data_get($event, 'name') ?? data_get($event, 'nameEt') ?? 'Õppetund' }}

@endforeach
</x-mail::panel>

@endforeach
@endif

Head nädalat!  
{{ config('app.name') }}
</x-mail::message>
