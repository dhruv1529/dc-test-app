<?php

namespace Database\Seeders;

use App\Models\Activities;
use Illuminate\Database\Seeder;

class ActivityDataSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = [
            [
                "label" => "Jumping",
                "points" => 10
            ],
            [
                "label" => "Running",
                "points" => 20
            ],
            [
                "label" => "Cycling",
                "points" => 30
            ]
        ];
        foreach($array as $s){
            Activities::create($s);
        }
    }
}
