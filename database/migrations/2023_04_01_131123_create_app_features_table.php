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
        Schema::create('app_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uploaded_by');
            $table->foreign('uploaded_by')->on('users')->references('id')->cascadeOnDelete();
            $table->string('title');
            $table->string('message');
            $table->unsignedDecimal('total', 19,2);
            $table->enum('type', ["airtime"]);
            $table->string('sms_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_features');
    }
};
