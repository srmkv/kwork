<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;


use App\Models\User;
use App\Models\Pin;

use Illuminate\Support\Facades\DB;


//custom
use Carbon\Carbon;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('course:updateMinMaxPrices')->hourly()->withoutOverlapping();
        $schedule->command('course:updateMinMaxDates')->daily()->withoutOverlapping();
           
        $schedule->call(function () {
           DB::table('users')->where('verified', '=', 0)->delete();
        })->everyFiveMinutes(); 

        $schedule->call(function (){
            $pins = Pin::all();
            foreach ($pins as  $pin) {
                if( now() > $pin->created_at->addMinutes(5)) {
                    // Pin::destroy($pin->id);

                    $pin->delete();

                    // dd($pin->id);
                    // $pin->count_timeout = 'delete';
                    // $pin->updated_at    = now();
                    // $pin->save();
                }
            }
        });

        //на серве время мск, но даты ставятся -3ч.





    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
