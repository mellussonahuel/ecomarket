<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountToSalesItemTable extends Migration
{
    
    public function up()
    {
        Schema::table('sales_item', function (Blueprint $table) {
            $table->integer('discount')->nullable(); 
        });
    }

   
    public function down()
    {
        Schema::table('sales_item', function (Blueprint $table) {
            $table->dropColumn('discount');
        });
    }
}
