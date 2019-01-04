<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeAllNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->nullable()->change();
            $table->string('path')->unsigned()->nullable()->change();
            $table->string('name')->unsigned()->nullable()->change();
            $table->string('filename')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->change();
            $table->string('path')->unsigned()->change();
            $table->string('name')->unsigned()->change();
            $table->string('filename')->unsigned()->change();
        });
    }
}
