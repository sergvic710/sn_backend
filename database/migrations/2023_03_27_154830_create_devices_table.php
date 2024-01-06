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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable();
            $table->unsignedInteger('code')->nullable();
            $table->string('counter')->default('');
            $table->dateTime('date_check')->nullable();
            $table->dateTime('last_answer')->nullable();
            $table->foreignId('device_type_id')->constrained();
            $table->tinyInteger('status');
            $table->foreignId('device_place_id')->constrained();
            $table->foreignId('location_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
