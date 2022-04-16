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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('winner_team_id')->nullable();
            $table->tinyInteger('full_time_home_team')->nullable();
            $table->tinyInteger('full_time_away_team')->nullable();
            $table->tinyInteger('half_time_home_team')->nullable();
            $table->tinyInteger('half_time_away_team')->nullable();
            $table->tinyInteger('extra_time_home_team')->nullable();
            $table->tinyInteger('extra_time_away_team')->nullable();
            $table->tinyInteger('penalties_home_team')->nullable();
            $table->tinyInteger('penalties_away_team')->nullable();
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
        Schema::dropIfExists('scores');
    }
};
