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
        Schema::create('academic_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained('users');
            $table->foreignId('academic_session_id')->nullable()->constrained();
            $table->foreignId('department_class_id')->nullable()->constrained();
            $table->unsignedTinyInteger('priority')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['academic_session_id', 'department_class_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_classes');
    }
};
