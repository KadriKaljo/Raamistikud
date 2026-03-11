<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;
use App\Mail\Timetable;
use Illuminate\Support\Facades\Mail;

class TimetableNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:timetable-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startDate = now()->startOfWeek();
        $endDate = now()->endOfWeek();

        $from = $startDate->toISOString();
        $thru = $endDate->toISOString();

        $response = Http::get('https://tahveltp.edu.ee/hois_back/timetableevents/timetableSearch', [
            'from' => $from,
            'lang' => 'ET',
            'page' => 0,
            'schoolId' => 38,
            'size' => 50,
            'studentGroups' => '39248890-7051-4182-9a9a-8a82259b862b',
            'thru' => $thru,
        ]);
        $data = $response->json();

        $events = collect(data_get($data, 'content', []));
    
        // sort by date and timeStart
        $sorted = $events->sortBy([
            ['date', 'asc'],
            ['timeStart', 'asc'],
        ]);
    
        // group by localized day name
        $grouped = $sorted->groupBy(function ($event) {
            $date = Carbon::parse(data_get($event, 'date'))->locale('et');
            return $date->dayName;
        });
    
        // build the same structure as before
        $entries = $grouped->map(function ($eventsForDay, $dayName) {
            $date = Carbon::parse(data_get($eventsForDay->first(), 'date'))->locale('et');
    
            return [
                'date' => $date->translatedFormat('d, F, Y'),
                'day' => $dayName,
                'entries' => $eventsForDay->map(function ($event) {
                    return [
                        'name' => data_get($event, 'nameEt'),
                        'timeStart' => data_get($event, 'timeStart'),
                        'timeEnd' => data_get($event, 'timeEnd'),
                        'room' => data_get($event, 'rooms.0.roomCode'),
                    ];
                })->values()->all(),
            ];
        })->all();
    
        Mail::to('kadrikaljo@gmail.com')->send(
            new Timetable(collect($entries), $startDate, $endDate)
        );
        
        $this->info('Timetable email sent.');
    }
}
