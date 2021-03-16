<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skins', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('gun_type');
            $table->string('name');
            $table->integer('cost');
            $table->text('image');
            $table->smallInteger('quality');
            $table->smallInteger('rarity');
            $table->boolean('stat_track');
            $table->boolean('souvenir');
            $table->string('aliases');
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
        Schema::dropIfExists('skins');
    }
}
