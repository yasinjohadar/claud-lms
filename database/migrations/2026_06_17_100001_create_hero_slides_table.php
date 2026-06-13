<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('admin_title');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('pagination_label', 60)->nullable();

            $table->string('layout', 40)->default('content_right_visual_left');
            $table->string('content_align', 20)->default('start');
            $table->string('min_height', 20)->default('default');
            $table->string('mobile_content_align', 20)->nullable();

            $table->string('background_type', 20)->default('theme');
            $table->string('background_color', 20)->nullable();
            $table->string('gradient_from', 20)->nullable();
            $table->string('gradient_to', 20)->nullable();
            $table->unsignedSmallInteger('gradient_angle')->default(135);
            $table->string('gradient_type', 20)->default('linear');
            $table->string('background_image')->nullable();
            $table->string('background_overlay_color', 20)->nullable();
            $table->decimal('background_overlay_opacity', 3, 2)->nullable();
            $table->string('background_position', 40)->default('center center');
            $table->string('background_size', 40)->default('cover');

            $table->string('accent_color', 20)->default('#059669');
            $table->string('accent_color_2', 20)->nullable();
            $table->boolean('show_decorative_shapes')->default(true);
            $table->string('theme_variant', 20)->default('main');

            $table->string('badge_text')->nullable();
            $table->string('badge_icon', 80)->nullable();
            $table->string('heading_mode', 20)->default('static');
            $table->string('heading_prefix')->nullable();
            $table->string('heading_highlight')->nullable();
            $table->json('heading_typing_phrases')->nullable();
            $table->text('description')->nullable();

            $table->json('buttons')->nullable();

            $table->string('visual_type', 20)->default('main');
            $table->string('visual_image')->nullable();
            $table->string('visual_image_alt')->nullable();
            $table->string('visual_icon', 80)->nullable();
            $table->json('visual_extras')->nullable();
            $table->boolean('hide_visual_on_mobile')->default(true);

            $table->string('custom_css_class')->nullable();
            $table->string('aria_label')->nullable();

            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
