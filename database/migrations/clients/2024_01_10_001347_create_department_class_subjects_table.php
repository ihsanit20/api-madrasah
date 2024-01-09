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
        Schema::create('department_class_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained('users');
            $table->foreignId('department_class_id')->nullable()->constrained();
            $table->string('name');
            $table->unsignedSmallInteger('subject_code')->unique();
            $table->unsignedTinyInteger('priority')->default(0);
            $table->boolean('is_active')->default(1);
            $table->string('book')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['department_class_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_class_subjects');
    }
};
