<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type')->default(9);
            $table->smallInteger('penalty')->default(-500);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('turn')->default(0);
            $table->string('trump')->nullable();
            $table->string('cards')->default('[]');
            $table->string('state', 10)->default(0);
            $table->unsignedTinyInteger('call_count')->default(0);
            $table->unsignedTinyInteger('hand_count')->default(1);
            $table->unsignedTinyInteger('quarter')->default(1);
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
        Schema::dropIfExists('games');
    }
}
