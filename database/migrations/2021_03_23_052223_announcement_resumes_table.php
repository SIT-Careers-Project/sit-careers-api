<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AnnouncementResumesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcement_resumes', function (Blueprint $table) {
            $table->uuid('announcement_resumes_id')->primary();
            $table->uuid('announcement_id')->nullable(false);
            $table->uuid('resume_id')->nullable(false);
            $table->string('message');
            $table->string('url');
            $table->string('read_at');
        });

        Schema::table('announcement_resumes', function (Blueprint $table) {
            $table->foreign('announcement_id')->references('announcement_id')->on('announcements');
            $table->foreign('resume_id')->references('resume_id')->on('resumes');
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
        Schema::dropIfExists('announcement_resumes');
        Schema::enableForeignKeyConstraints();
    }
}