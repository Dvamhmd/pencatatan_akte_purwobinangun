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
        Schema::table('death_certificates', function (Blueprint $table) {
            $table->string('deceased_job')->nullable()->after('religion');
            $table->string('witness1_birth_place')->nullable()->after('witness1_name');
            $table->date('witness1_birth_date')->nullable()->after('witness1_birth_place');
            $table->string('witness2_birth_place')->nullable()->after('witness2_name');
            $table->date('witness2_birth_date')->nullable()->after('witness2_birth_place');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('death_certificates', function (Blueprint $table) {
            $table->dropColumn([
                'deceased_job',
                'witness1_birth_place',
                'witness1_birth_date',
                'witness2_birth_place',
                'witness2_birth_date',
            ]);
        });
    }
};

