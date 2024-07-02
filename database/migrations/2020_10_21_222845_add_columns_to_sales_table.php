<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSalesTable extends Migration
{
   
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('total',12,2);
            $table->string('payment_status');
            $table->string('order_note')->nullable();
        });
    }

  
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('total',12,2);
            $table->dropColumn('payment_status');
            $table->dropColumn('order_note')->nullable();
            
        });
    }
}
