<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Mail\Timetable;
use Illuminate\Support\Facades\Mail;
    

#[Signature('app:timetable-notification')]
#[Description('Command description')]
class TimetableNotification extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
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
        Mail::to('test@test.ee')->send(new Timetable($timetableEvents, $startDate, $endDate));

        $this->info('Timetable email sent.');

        return self::SUCCESS;
    }
}
