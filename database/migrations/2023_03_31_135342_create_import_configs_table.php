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
        Schema::create('import_configs', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('counter')->nullable();
            $table->tinyInteger('is_water')->nullable();
            $table->foreignId('device_place_id')->nullable();
            $table->foreignId('device_type_id')->nullable();
            $table->tinyInteger('is_active')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_configs');
    }

};
