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
        Schema::table('admission_forms', function (Blueprint $table) {
            $table->json('admission_test')->nullable()->after('previous_info');
            $table->json('concessions')->nullable()->after('admission_test');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admission_forms', function (Blueprint $table) {
            $table->dropColumn('admission_test');
            $table->dropColumn('concessions');
        });
    }
};
