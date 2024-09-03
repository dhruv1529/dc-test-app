<?php

use Database\Seeders\UserDataSeed;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("name",50);
            $table->string("email",50)->unique();
            $table->integer("rank")->nullable();
            $table->integer("user_points")->nullable();
            $table->timestamps();
        });

        Artisan::call("db:seed",array("--class"=>UserDataSeed::class));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
