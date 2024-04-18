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
        Schema::create('academic_class_package_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained('users');
            $table->foreignId('academic_class_id')->constrained();
            $table->foreignId('package_id')->constrained();
            $table->foreignId('fee_id')->constrained();

            $table->float('amount')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['academic_class_id', 'package_id', 'fee_id'], 'academic_class_id_package_id_fee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_class_package_fees');
    }
};
