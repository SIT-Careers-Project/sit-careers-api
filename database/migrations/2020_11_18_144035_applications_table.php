<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->uuid('application_id')->primary();
            $table->uuid('announcement_id')->nullable(false);
            $table->uuid('student_id')->nullable(false);
            $table->date('application_date');
            $table->string('status');
            $table->string('name_title');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('tel_no', 10);
            $table->string('note');
            $table->string('resume_link');
            $table->string('path_file');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->foreign('announcement_id')->references('announcement_id')->on('announcements');
            $table->foreign('student_id')->references('user_id')->on('users');
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
        Schema::dropIfExists('applications');
        Schema::enableForeignKeyConstraints();
    }
}
