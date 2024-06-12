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
        Schema::create('admission_forms', function (Blueprint $table) {
            $table->id();

            $table->string('admission_type')->default('new')->comment('new or old');
            $table->foreignId('academic_session_id')->constrained('academic_sessions');

            $table->foreignId('student_id')->nullable()->constrained('students');

            $table->json('student_photo')->nullable();

            $table->json('basic_info')->nullable();

            $table->json('father_info')->nullable();
            $table->json('mother_info')->nullable();
            $table->json('guardian_info')->nullable();

            $table->json('present_address_info')->nullable();
            $table->boolean('is_same_address')->default(false);
            $table->json('permanent_address_info')->nullable();

            $table->json('previous_info')->nullable();

            $table->foreignId('academic_class_id')->nullable()->constrained('academic_classes');
            $table->foreignId('package_id')->nullable()->constrained('packages');
            
            $table->unsignedTinyInteger('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_forms');
    }
};
