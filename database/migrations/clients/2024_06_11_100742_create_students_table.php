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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('registration')->nullable();

            $table->boolean('active')->default(1);

            $table->unsignedTinyInteger('package_id');

            $table->unsignedTinyInteger('gender')->nullable()->comment('1=Male, 2=Female');
            $table->unsignedTinyInteger('blood_group')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('birth_certificate')->nullable();

            $table->unsignedBigInteger('father_info_id')->nullable();
            $table->unsignedBigInteger('mother_info_id')->nullable();
            $table->unsignedBigInteger('guardian_info_id')->nullable();

            $table->unsignedBigInteger('present_address_id')->nullable();
            $table->unsignedBigInteger('permanent_address_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
