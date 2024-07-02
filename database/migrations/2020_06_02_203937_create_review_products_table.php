<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewProductsTable extends Migration
{

    public function up()
    {
        Schema::create('review_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('product_id')->index()->nullable();
            $table->integer('value')->default(0);
            $table->string('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('review_products');
        Schema::enableForeignKeyConstraints();
    }
}
