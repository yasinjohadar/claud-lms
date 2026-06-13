<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('student_code')->nullable()->unique();
            $table->string('gender', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nationality')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->string('education_level')->nullable();
            $table->string('university')->nullable();
            $table->string('major')->nullable();
            $table->string('occupation')->nullable();
            $table->string('company')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('preferred_language', 10)->nullable();
            $table->string('timezone')->nullable();
            $table->text('bio')->nullable();
            $table->text('learning_goals')->nullable();
            $table->string('status', 30)->default('active');
            $table->timestamp('onboarding_completed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
