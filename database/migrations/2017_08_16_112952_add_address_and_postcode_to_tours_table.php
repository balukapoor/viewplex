<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressAndPostcodeToToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->renameColumn('name', 'address_first_line');
            $table->string('address_city');
            $table->string('address_postcode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->renameColumn('address_first_line', 'name');
            $table->dropColumn('address_city');
            $table->dropColumn('address_postcode');
        });
    }
}
