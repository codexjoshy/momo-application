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
        Schema::create('app_feature_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_feature_id');
            $table->foreign('app_feature_id')->on('app_features')->references('id')->cascadeOnDelete();
            $table->string('phone_no');
            $table->unsignedDecimal('amount', 19,2);
            $table->enum("status", ["pending","success","fail"])->default("pending");
            $table->string('transaction_id')->nullable();
            $table->json('other_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_feature_customers');
    }
};
