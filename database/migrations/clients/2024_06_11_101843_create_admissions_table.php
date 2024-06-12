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
            $table->unsignedBigInteger('student_id')->constrained('students');
            $table->unsignedBigInteger('academic_session_id')->constrained('academic_sessions');
            
            $table->unsignedBigInteger('academic_class_id')->constrained('academic_class_id');
            $table->unsignedBigInteger('admission_form_id')->constrained('admission_forms');

            $table->boolean('active')->default(1);

            $table->unsignedSmallInteger('roll')->nullable();

            $table->json('concessions')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['student_id', 'academic_session_id']);
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
