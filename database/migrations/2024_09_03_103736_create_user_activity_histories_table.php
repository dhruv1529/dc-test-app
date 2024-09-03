<?php

use Database\Seeders\UserActivityHostoryDataSeed;
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
        Schema::create('user_activity_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('activity_id');
            $table->date("entry_date");
            $table->timestamps();
        });

        Artisan::call("db:seed",array("--class"=>UserActivityHostoryDataSeed::class));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activity_histories');
    }
};
