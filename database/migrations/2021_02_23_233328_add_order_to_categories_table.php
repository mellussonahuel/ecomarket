<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderToCategoriesTable extends Migration
{
    
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('order')->default(1);
        });
    }

  
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
