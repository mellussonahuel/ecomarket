<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSalesItemTable extends Migration
{
   
    public function up()
    {
        Schema::table('sales_item', function (Blueprint $table) {
            $table->bigInteger('variant_id')->nullable();
            $table->string('variant_description')->nullable();
            $table->decimal('total',12,2);
        });
    }

   
    public function down()
    {
        Schema::table('sales_item', function($table) {
            $table->dropColumn('variant_id');
            $table->dropColumn('variant_description');
            $table->dropColumn('total');
        });
    }
}
