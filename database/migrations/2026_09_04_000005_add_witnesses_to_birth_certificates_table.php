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
            // Data Saksi 1
            $table->string('witness_1_nik', 16)->nullable()->after('rw');
            $table->string('witness_1_name')->nullable()->after('witness_1_nik');
            $table->date('witness_1_birth_date')->nullable()->after('witness_1_name');
            $table->string('witness_1_job')->nullable()->after('witness_1_birth_date');
            $table->text('witness_1_address')->nullable()->after('witness_1_job');

            // Data Saksi 2
            $table->string('witness_2_nik', 16)->nullable()->after('witness_1_address');
            $table->string('witness_2_name')->nullable()->after('witness_2_nik');
            $table->date('witness_2_birth_date')->nullable()->after('witness_2_name');
            $table->string('witness_2_job')->nullable()->after('witness_2_birth_date');
            $table->text('witness_2_address')->nullable()->after('witness_2_job');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('birth_certificates', function (Blueprint $table) {
            $table->dropColumn([
                'witness_1_nik',
                'witness_1_name',
                'witness_1_birth_date',
                'witness_1_job',
                'witness_1_address',
                'witness_2_nik',
                'witness_2_name',
                'witness_2_birth_date',
                'witness_2_job',
                'witness_2_address',
            ]);
        });
    }
};
