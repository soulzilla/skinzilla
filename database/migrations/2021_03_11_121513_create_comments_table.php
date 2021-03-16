<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->integer('entity_id');
            $table->string('entity_table');
            $table->text('content');
            $table->integer('user_id');
            $table->integer('parent_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->boolean('blocked')->default(false);
            $table->string('block_reason')->nullable();
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
        Schema::dropIfExists('comments');
    }
}
