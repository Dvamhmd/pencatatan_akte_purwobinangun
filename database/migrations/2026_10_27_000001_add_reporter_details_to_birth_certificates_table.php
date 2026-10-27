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
            $table->date('applicant_birth_date')->nullable()->after('applicant_name');
            $table->unsignedInteger('applicant_age')->nullable()->after('applicant_birth_date');
            $table->string('applicant_gender', 10)->nullable()->after('applicant_age');
            $table->string('applicant_job', 100)->nullable()->after('applicant_gender');
            $table->string('applicant_village', 100)->nullable()->default('Purwobinangun')->after('rw');
            $table->string('applicant_district', 100)->nullable()->default('Pakem')->after('applicant_village');
            $table->string('applicant_city', 100)->nullable()->default('Sleman')->after('applicant_district');
            $table->string('applicant_province', 100)->nullable()->default('D.I. Yogyakarta')->after('applicant_city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('birth_certificates', function (Blueprint $table) {
            $table->dropColumn([
                'applicant_birth_date',
                'applicant_age',
                'applicant_gender',
                'applicant_job',
                'applicant_village',
                'applicant_district',
                'applicant_city',
                'applicant_province',
            ]);
        });
    }
};
