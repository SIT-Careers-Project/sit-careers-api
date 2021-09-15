<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('company_id')->primary();
            $table->string('company_name_th');
            $table->string('company_name_en');
            $table->string('company_type')->nullable();
            $table->text('description')->nullable();
            $table->text('about_us')->nullable();
            $table->string('logo')->nullable();
            $table->string('e_mail_manager', 50)->nullable();
            $table->string('e_mail_coordinator', 50)->nullable();
            $table->string('tel_no', 10)->nullable();
            $table->string('phone_no', 10)->nullable();
            $table->string('website')->nullable();
            $table->string('start_business_day', 20)->nullable();
            $table->string('end_business_day', 20)->nullable();
            $table->string('start_business_time', 6)->nullable();
            $table->string('end_business_time', 6)->nullable();
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('companies');
        Schema::enableForeignKeyConstraints();
    }
}
