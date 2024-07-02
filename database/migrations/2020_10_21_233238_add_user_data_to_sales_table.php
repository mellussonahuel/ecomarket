<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserDataToSalesTable extends Migration
{
    
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {    
            $table->string('name');
            $table->string('phone');
            $table->string('email');
        });
    }

   
    public function down()
    {
        Schema::table('sales', function($table) {
            $table->dropColumn('name');
            $table->dropColumn('phone');
            $table->dropColumn('address');
            $table->dropColumn('email');
        });
    }
}
