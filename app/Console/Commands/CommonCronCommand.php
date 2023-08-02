<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use App\Models\Pin;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class CommonCronCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'common:clearing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Очищаем базу от устаревших пинов, неверифицрованных юзеров, и т.д ..';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // 1. очищаем базу от неверифицрованных юзеров..  


        // eq() равно
        // ne() не равно
        // gt() больше, чем
        // gte() больше или равно
        // lt() менее
        // lte() меньше или равно


        $slowpocks = DB::table('users')->where('verified', '=', 0)->get();

        foreach ($slowpocks as $slowpock) {
            
        

            if(Carbon::now()->gt(Carbon::parse($slowpock->created_at)->addMinutes(6))) {

                DB::table('profiles_individuals')
                    ->where('user_id', '=', $slowpock->id)->delete();

               User::find($slowpock->id)->delete();
            }
        }




        $pins = Pin::all();
        foreach ($pins as  $pin) {
            
            //т.к привязка времени не переделана ( начинается от 1 полченного пина) , 
            // то удаляем любые пины только через 6 минут ( todo #99)
            if( Carbon::now()->gt(Carbon::parse($pin->created_at)->addMinutes(2))) {
                $pin->delete();
            }
        }


    }

}
