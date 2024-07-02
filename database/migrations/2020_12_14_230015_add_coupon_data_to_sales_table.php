<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCouponDataToSalesTable extends Migration
{
   
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('coupon_code')->nullable();
            $table->integer('coupon_amount')->nullable();
        });
    }

    
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('coupon_code');
            $table->dropColumn('coupon_amount');
        });
    }
}
