<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->uuid('address_id')->primary();
            $table->string('address_one');
            $table->string('address_two');
            $table->string('lane');
            $table->string('road');
            $table->string('sub_district');
            $table->string('district');
            $table->string('province');
            $table->string('postal_code', 5);
            $table->string('address_type', 20);
            $table->uuid('company_id')->nullable(false);
            $table->timestamps();
            $table->softDeletes();

        });
        Schema::table('addresses', function (Blueprint $table) {
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
        Schema::dropIfExists('addresses');
        Schema::enableForeignKeyConstraints();
    }
}
