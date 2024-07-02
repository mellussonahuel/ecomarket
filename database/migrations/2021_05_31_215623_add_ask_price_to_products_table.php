<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAskPriceToProductsTable extends Migration
{
    
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('ask_price')->default(0);
        });
    }

  
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('ask_price');
        });
    }
}
