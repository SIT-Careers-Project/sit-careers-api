<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DataOwnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_owner', function (Blueprint $table) {
            $table->uuid('data_owner_id')->primary();
            $table->uuid('user_id')->nullable(false);
            $table->uuid('company_id')->nullable(false);
            $table->boolean('request_delete');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('data_owner', function (Blueprint $table) {
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('company_id')->references('company_id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('data_owner');
        Schema::enableForeignKeyConstraints();
    }
}
