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
        Schema::table('birth_certificates', function (Blueprint $table) {
            $table->string('child_nik', 16)->nullable()->after('child_name');
            $table->string('birth_day', 20)->nullable()->after('birth_date');
            $table->unsignedInteger('weight_gram')->nullable()->after('weight_kg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('birth_certificates', function (Blueprint $table) {
            $table->dropColumn([
                'child_nik',
                'birth_day',
                'weight_gram',
            ]);
        });
    }
};
