<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoryTable extends Migration
{
   
    public function up()
    {
        Schema::create('category_product', function (Blueprint $table) {


            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('product_id');
            
      
        });
    }

   
    public function down()
    {
        Schema::dropIfExists('product_category');
    }
}
