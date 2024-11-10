<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_names', function (Blueprint $table) {
            $table->id();
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name')->unique();
            $table->enum('type', ['monthly', 'one_time'])->default('one_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fee_names');
    }
};
