<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamblingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gamblings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_canonical');
            $table->string('website');
            $table->string('promo_code')->nullable();
            $table->string('ref_link');
            $table->text('logo');
            $table->integer('order');
            $table->boolean('published');
            $table->string('assessment');
            $table->smallInteger('recommendation_level');
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
        Schema::dropIfExists('gamblings');
    }
}
