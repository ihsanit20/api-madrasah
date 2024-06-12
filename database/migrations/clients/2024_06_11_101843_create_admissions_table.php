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
            $table->unsignedTinyInteger('academic_session_id');
            
            $table->unsignedBigInteger('admission_form_id');
            $table->unsignedTinyInteger('academic_class_id');

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
