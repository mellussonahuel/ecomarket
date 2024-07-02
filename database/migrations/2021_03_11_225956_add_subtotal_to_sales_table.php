<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubtotalToSalesTable extends Migration
{
    
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('subtotal',12,2)->default(0);
        });
    }

    
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('subtotal');
        });
    }
}
