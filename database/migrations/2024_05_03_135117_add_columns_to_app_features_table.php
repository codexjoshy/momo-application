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
        Schema::table('app_features', function (Blueprint $table) {
            $table->string('airtime_ref')->nullable();
            $table->json('other_info')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_features', function (Blueprint $table) {
            $table->dropColumn(['airtime_ref', 'other_info']);
        });
    }
};
