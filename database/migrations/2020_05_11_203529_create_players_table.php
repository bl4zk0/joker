<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->unsignedBigInteger('game_id')->nullable();
            $table->string('card')->nullable();
            $table->mediumText('cards')->nullable();
            $table->unsignedTinyInteger('position')->nullable();
            $table->unsignedTinyInteger('rank')->default(0);
            $table->unsignedInteger('rank_points')->default(0);
            $table->unsignedInteger('games_played')->default(0);
            $table->boolean('disconnected')->default(false);
            $table->timestamps();

            $table->foreign('game_id')->references('id')->on('games')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');
    }
}
