<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class JobTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_types', function (Blueprint $table) {
            $table->uuid('job_id')->primary();
            $table->uuid('announcement_id')->nullable(false);
            $table->string('job_type');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('job_types', function (Blueprint $table) {
            $table->foreign('announcement_id')->references('announcement_id')->on('announcements');
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
        Schema::dropIfExists('job_types');
        Schema::enableForeignKeyConstraints();

        Schema::table('job_types', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
