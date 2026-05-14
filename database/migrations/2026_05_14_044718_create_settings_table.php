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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('primary_color')->default('#4F46E5');
            $table->string('secondary_color')->default('#10B981');
            $table->string('bg_gradient_start')->default('#f6d365');
            $table->string('bg_gradient_end')->default('#fda085');
            $table->string('logo_image')->default('img/logo.png');
            $table->string('slider_image')->default('img/slider.jpg');
            $table->string('app_name')->default('Pengumuman Kelulusan MTsN 2 Pesawaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
