<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slider_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('autoplay_enabled')->default(true);
            $table->unsignedInteger('autoplay_delay_ms')->default(6000);
            $table->unsignedInteger('transition_speed_ms')->default(900);
            $table->boolean('loop')->default(true);
            $table->boolean('pause_on_hover')->default(true);
            $table->string('effect', 20)->default('fade');
            $table->boolean('show_navigation')->default(true);
            $table->boolean('show_pagination')->default(true);
            $table->boolean('show_progress_bar')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slider_settings');
    }
};
