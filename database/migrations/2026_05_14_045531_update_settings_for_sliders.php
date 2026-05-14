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
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('slider_image');
            $table->json('slider_images')->nullable();
            $table->integer('slider_interval')->default(5000);
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('slider_image')->default('img/slider.jpg');
            $table->dropColumn(['slider_images', 'slider_interval']);
        });
    }
};
