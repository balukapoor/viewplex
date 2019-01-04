<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableForNoJsVisits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('no_js_visits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('browser_name')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('os')->nullable();
            $table->string('viewplex_user')->nullable();
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
        Schema::dropIfExists('no_js_visits');
    }
}
