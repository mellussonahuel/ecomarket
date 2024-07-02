<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReadColumnToSalesTable extends Migration
{
    
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->boolean('readed')->default(0);
        });
    }

   
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('readed');
        });
    }
}
