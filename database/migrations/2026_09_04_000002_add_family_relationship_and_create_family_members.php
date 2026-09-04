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
        Schema::table('users', function (Blueprint $table) {
            $table->string('family_relationship', 100)->nullable()->after('gender');
        });

        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('family_card_no', 16)->nullable()->index();
            $table->string('nik', 16)->nullable()->index();
            $table->string('name');
            $table->string('birth_place', 150)->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->string('family_relationship', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_members');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('family_relationship');
        });
    }
};
