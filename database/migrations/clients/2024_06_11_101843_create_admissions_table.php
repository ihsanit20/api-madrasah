<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedTinyInteger('academic_class_id');
            $table->unsignedTinyInteger('academic_session_id');

            $table->unsignedTinyInteger('status')->default(1)->comment('1=Admission Form, 2=Fee Form, 3=Admission Complete');
            $table->unsignedSmallInteger('roll')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('previous_class')->nullable();
            $table->string('previous_roll')->nullable();
            $table->string('previous_result')->nullable();
            $table->unsignedFloat('admission_test_mark')->nullable();
            $table->json('verifications')->nullable();
            $table->unsignedInteger('verified_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique([
                'student_id',
                'academic_class_id',
                'academic_session_id'
            ], 'unique_student_id_academic_class_id_academic_session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
