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
            $table->uuid('announcement_resume_id')->primary();
            $table->uuid('announcement_id')->nullable(false);
            $table->uuid('resume_id')->nullable(false);
            $table->string('status');
            $table->string('note');
            $table->timestamps();
            $table->softDeletes();
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
        Schema::table('announcement_resumes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
