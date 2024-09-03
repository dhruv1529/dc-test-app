<?php

use Database\Seeders\ActivityDataSeed;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->enum("label",["Jumping","Running","Cycling"])->default("Cycling");
            $table->integer("points")->default(10);
            $table->timestamps();
        });

        Artisan::call("db:seed",array("--class"=>ActivityDataSeed::class));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
