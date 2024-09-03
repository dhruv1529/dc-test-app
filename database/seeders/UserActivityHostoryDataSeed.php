<?php

namespace Database\Seeders;

use App\Models\UserActivityHistory;
use Illuminate\Database\Seeder;
use Throwable;

class UserActivityHostoryDataSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($x=0;$x<1000;$x++){
            try {
                //code...
                $month = rand(1,12);

                UserActivityHistory::create([
                    'user_id' => rand(1,100),
                    'activity_id' => rand(1,3),
                    'entry_date' => date('Y')."-".($month > 10 ? $month : ("0".$month))."-".rand(1,20)
                ]);
            } catch (Throwable $th) {
                continue;
            }
        }
    }
}
