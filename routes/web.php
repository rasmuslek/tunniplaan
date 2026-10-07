<?php

use App\Mail\Timetable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mailable', function () {

    $startDate = Carbon::now()->startOfWeek();
    $endDate = Carbon::now()->endOfWeek();

    $data = Http::get('https://tahveltp.edu.ee/hois_back/timetableevents/timetableSearch', [
        'from' => $startDate,
        'lang' => 'ET',
        'page' => 0,
        'schoolId' => 38,
        'size' => 50,
        'studentGroups' => 'ea0550fb-8387-4aa2-880a-9abbd37a69ce',
        'thru' => $endDate,
    ])->json();

    $timetableEvents = collect($data['content'])
        ->sortBy(['date', 'timeStart'])
        ->groupBy(function ($event) {
            return Carbon::parse($event['date'])->locale('et_EE')->dayName;
        });

    return new Timetable($timetableEvents, $startDate, $endDate);
});
