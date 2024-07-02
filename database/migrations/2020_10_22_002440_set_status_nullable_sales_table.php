<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetStatusNullableSalesTable extends Migration
{
   
    public function up()
    {
        Schema::table('sales', function($table)
        {
            $table->string('state')->nullable()->change();
        });
    }

    public function down()
    {
        //
    }
}
