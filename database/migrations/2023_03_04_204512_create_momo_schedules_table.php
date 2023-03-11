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
        Schema::create('momo_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uploaded_by');
            $table->foreign('uploaded_by')->on('users')->references('id')->cascadeOnDelete();
            $table->string('title');
            $table->string('customer_message');
            $table->unsignedDecimal('disbursed_amount', 19,2);
            $table->string('reference')->unique()->comment('format is ymdCCCCCC, the primary key for the table')->nullable();
            $table->boolean('treated')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('momo_schedules');
    }
};
