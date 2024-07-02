<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('has_change_default_password')->default(1);
            $table->boolean('active')->default(1);
        });
    }

   
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('has_change_default_password');
            $table->dropColumn('active');
        });
    }
}
