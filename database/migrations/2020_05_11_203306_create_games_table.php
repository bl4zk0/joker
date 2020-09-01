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
            $table->unsignedTinyInteger('type');
            $table->smallInteger('penalty');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('turn')->default(0);
            $table->string('trump')->nullable();
            $table->string('cards')->default(json_encode([]));
            $table->string('state', 10)->default('start');
            $table->unsignedTinyInteger('call_count')->default(0);
            $table->unsignedTinyInteger('hand_count')->default(1);
            $table->unsignedTinyInteger('quarter')->default(1);
            $table->unsignedSmallInteger('password')->nullable();
            $table->string('kicked_users')->default(json_encode([]));
            $table->string('ready')->default(json_encode(['players' => [], 'count' => 0]));
            $table->text('admin_cards')->nullable();
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
