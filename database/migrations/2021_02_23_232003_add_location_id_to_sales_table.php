<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationIdToSalesTable extends Migration
{
  
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->integer('location_id');
        });
    }

   
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('location_id');
        });
    }
}
