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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name')->nullable();
            $table->string('user_role')->nullable(); // 'admin', 'warga', 'system'
            $table->string('action_type', 50); // 'create', 'update', 'approve', 'reject', 'delete', 'archive', 'restore'
            $table->string('module', 50); // 'birth_certificate', 'death_certificate', 'citizen', 'profile_update', 'auth'
            $table->string('title');
            $table->text('description');
            $table->string('reference_no')->nullable(); // No Registrasi atau NIK
            $table->nullableMorphs('subject'); // subject_type, subject_id
            $table->json('properties')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['module', 'action_type']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
