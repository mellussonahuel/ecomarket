<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatesColumnsToCouponsTable extends Migration
{
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
        });
    }

    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
           $table->dropColumn('valid_fromm');
           $table->dropColumn('valid_to'); 
        });
    }
}
