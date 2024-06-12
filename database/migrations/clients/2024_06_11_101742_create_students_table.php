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
            $table->string('registration')->unique();

            $table->boolean('active')->default(1);

            $table->foreignId('package_id')->constrained('packages');

            $table->float('account')->default(0);

            $table->date('date_of_birth');
            $table->unsignedTinyInteger('gender')->comment('1=Male, 2=Female');

            $table->string('birth_certificate')->nullable();
            $table->unsignedTinyInteger('blood_group')->nullable();

            $table->foreignId('father_info_id')->constrained('guardians');
            $table->foreignId('mother_info_id')->constrained('guardians');
            $table->foreignId('guardian_info_id')->constrained('guardians');

            $table->foreignId('present_address_id')->constrained('addresses');
            $table->foreignId('permanent_address_id')->constrained('addresses');

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
