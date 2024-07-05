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
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id')->index();

            $table->unsignedBigInteger('admission_id')->index();
            $table->unsignedBigInteger('fee_id')->index()->nullable();
            $table->string('month')->nullable();

            $table->unsignedTinyInteger('period');
            $table->string('title');

            $table->float('amount');
            $table->float('concession')->default(0);

            $table->softDeletes();

            $table->unique(['admission_id', 'fee_id', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_details');
    }
};
