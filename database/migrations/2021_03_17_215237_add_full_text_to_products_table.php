<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddFullTextToProductsTable extends Migration
{

    public function up()
    {      
        DB::statement('ALTER TABLE products ADD FULLTEXT fulltext_index (description, title)');
    }

}
