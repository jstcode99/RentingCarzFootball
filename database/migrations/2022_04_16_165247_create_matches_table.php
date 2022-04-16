<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_id');
            $table->string('status');
            $table->string('stage');
            $table->string('group')->nullable();
            $table->foreignId('competition_id');
            $table->foreignId('season_id');
            $table->foreignId('home_team_id');
            $table->foreignId('away_team_id');
            $table->dateTime('utc_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
};
