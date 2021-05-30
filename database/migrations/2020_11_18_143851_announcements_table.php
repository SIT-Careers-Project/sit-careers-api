<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->uuid('announcement_id')->primary();
            $table->uuid('company_id')->nullable(false);
            $table->uuid('address_id')->nullable(false);
            $table->string('announcement_title');
            $table->string('job_description');
            $table->uuid('job_position_id')->nullable(false);
            $table->string('property');
            $table->string('priority')->nullable();
            $table->string('picture')->nullable();
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->string('salary');
            $table->string('welfare');
            $table->string('status');
            $table->string('start_business_day', 20);
            $table->string('end_business_day', 20);
            $table->string('start_business_time', 6);
            $table->string('end_business_time', 6);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->foreign('company_id')->references('company_id')->on('companies');
            $table->foreign('address_id')->references('address_id')->on('addresses');
            $table->foreign('job_position_id')->references('job_position_id')->on('job_positions');
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
        Schema::dropIfExists('announcements');
        Schema::enableForeignKeyConstraints();

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
