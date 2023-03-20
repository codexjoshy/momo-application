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
        Schema::create('momo_schedule_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('momo_schedule_id');
            $table->foreign('momo_schedule_id')->on('momo_schedules')->references('id')->cascadeOnDelete();
            $table->string('phone_no');
            $table->unsignedDecimal('amount', 19,2);
            $table->enum("status", ["pending","success","failed"])->default("pending");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('momo_schedule_customers');
    }
};
