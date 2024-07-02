<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{

    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('description',5000)->nullable();
            $table->string('information',5000)->nullable();
            $table->decimal('price',12,2)->nullable();
            $table->string('sale_type');
            $table->integer('stock')->default(0);
            $table->integer('rating')->nullable()->default(0);
            $table->integer('min_stock');
            $table->integer('discount')->nullable()->default(0);
            $table->string('tags')->nullable();
            $table->integer('highlighted')->nullable()->default(0);
            $table->string('slug')->unique();
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->boolean('active')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('packages');
    }
}
