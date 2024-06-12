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
            $table->foreignId('academic_session_id')->constrained('academic_sessions');
            $table->foreignId('student_id')->constrained('students');
            
            $table->foreignId('academic_class_id')->constrained('academic_class_id');
            $table->foreignId('admission_form_id')->constrained('admission_forms');

            $table->boolean('active')->default(1);

            $table->unsignedSmallInteger('roll');

            $table->json('concessions')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['academic_session_id', 'student_id']);
            $table->unique(['academic_session_id', 'academic_class_id', 'roll']);
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
