@component('mail::message')
# Nädala tunniplaan

Algus: {{ $startDate->translatedFormat('d. F Y') }}  
Lõpp: {{ $endDate->translatedFormat('d. F Y') }}

@foreach($timetableEvents as $day)
### {{ $day['day'] }} – {{ $day['date'] }}

@foreach($day['entries'] as $entry)
- {{ $entry['timeStart'] }}–{{ $entry['timeEnd'] }}: {{ $entry['name'] }} ({{ $entry['room'] ?? 'Tuba määramata' }})
@endforeach

@endforeach

@endcomponent

