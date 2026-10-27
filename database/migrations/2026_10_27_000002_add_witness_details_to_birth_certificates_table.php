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
            // Data Saksi 1 Tambahan
            $table->unsignedInteger('witness_1_age')->nullable()->after('witness_1_birth_date');
            $table->string('witness_1_gender', 10)->nullable()->after('witness_1_age');
            $table->string('witness_1_rt', 5)->nullable()->after('witness_1_address');
            $table->string('witness_1_rw', 5)->nullable()->after('witness_1_rt');
            $table->string('witness_1_village', 100)->nullable()->default('Purwobinangun')->after('witness_1_rw');
            $table->string('witness_1_district', 100)->nullable()->default('Pakem')->after('witness_1_village');
            $table->string('witness_1_city', 100)->nullable()->default('Sleman')->after('witness_1_district');
            $table->string('witness_1_province', 100)->nullable()->default('D.I. Yogyakarta')->after('witness_1_city');

            // Data Saksi 2 Tambahan
            $table->unsignedInteger('witness_2_age')->nullable()->after('witness_2_birth_date');
            $table->string('witness_2_gender', 10)->nullable()->after('witness_2_age');
            $table->string('witness_2_rt', 5)->nullable()->after('witness_2_address');
            $table->string('witness_2_rw', 5)->nullable()->after('witness_2_rt');
            $table->string('witness_2_village', 100)->nullable()->default('Purwobinangun')->after('witness_2_rw');
            $table->string('witness_2_district', 100)->nullable()->default('Pakem')->after('witness_2_village');
            $table->string('witness_2_city', 100)->nullable()->default('Sleman')->after('witness_2_district');
            $table->string('witness_2_province', 100)->nullable()->default('D.I. Yogyakarta')->after('witness_2_city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('birth_certificates', function (Blueprint $table) {
            $table->dropColumn([
                'witness_1_age',
                'witness_1_gender',
                'witness_1_rt',
                'witness_1_rw',
                'witness_1_village',
                'witness_1_district',
                'witness_1_city',
                'witness_1_province',
                'witness_2_age',
                'witness_2_gender',
                'witness_2_rt',
                'witness_2_rw',
                'witness_2_village',
                'witness_2_district',
                'witness_2_city',
                'witness_2_province',
            ]);
        });
    }
};
