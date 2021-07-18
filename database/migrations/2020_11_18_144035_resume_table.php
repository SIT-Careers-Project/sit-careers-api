<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ResumeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resumes', function (Blueprint $table) {
            $table->uuid('resume_id')->primary();
            $table->uuid('student_id')->nullable(false);
            $table->date('resume_date');
            $table->string('name_title');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('curriculum', 10);
            $table->string('year', 10);
            $table->string('email');
            $table->string('tel_no', 10);
            $table->string('resume_link');
            $table->string('path_file');
            $table->string('university_name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('resumes', function (Blueprint $table) {
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
        Schema::dropIfExists('resumes');
        Schema::enableForeignKeyConstraints();
    }
}
