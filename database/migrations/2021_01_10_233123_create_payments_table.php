<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
   
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('topic')->nullable();
            $table->bigInteger('mp_payment_id');
            $table->string('status');
            $table->bigInteger('external_reference');
            $table->string('merchant_order_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

   
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
