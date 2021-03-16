<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExtendPartnersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gamblings', function (Blueprint $table) {
            $table->string('promo_code_description')->nullable();
        });

        Schema::table('roulettes', function (Blueprint $table) {
            $table->string('promo_code_description')->nullable();
        });

        Schema::table('markets', function (Blueprint $table) {
            $table->string('promo_code_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gamblings', function (Blueprint $table) {
            $table->dropColumn('promo_code_description');
        });

        Schema::table('roulettes', function (Blueprint $table) {
            $table->dropColumn('promo_code_description');
        });

        Schema::table('markets', function (Blueprint $table) {
            $table->dropColumn('promo_code_description');
        });
    }
}
