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
            // Header KK
            $table->string('head_of_family_name')->nullable()->after('family_card_no');

            // Jenazah
            $table->string('deceased_birth_place')->nullable()->after('birth_date');
            $table->text('deceased_address')->nullable()->after('rw');
            $table->string('deceased_child_order', 50)->nullable()->after('deceased_address');
            $table->string('death_day', 20)->nullable()->after('deceased_child_order');
            $table->string('death_location_detail')->nullable()->after('cause_of_death');

            // Ibu
            $table->string('mother_nik', 16)->nullable()->after('reported_by_title');
            $table->string('mother_name')->nullable()->after('mother_nik');
            $table->string('mother_birth_place')->nullable()->after('mother_name');
            $table->date('mother_birth_date')->nullable()->after('mother_birth_place');
            $table->string('mother_job')->nullable()->after('mother_birth_date');
            $table->text('mother_address')->nullable()->after('mother_job');
            $table->string('mother_citizenship', 50)->default('WNI')->after('mother_address');

            // Ayah
            $table->string('father_nik', 16)->nullable()->after('mother_citizenship');
            $table->string('father_name')->nullable()->after('father_nik');
            $table->string('father_birth_place')->nullable()->after('father_name');
            $table->date('father_birth_date')->nullable()->after('father_birth_place');
            $table->string('father_job')->nullable()->after('father_birth_date');
            $table->text('father_address')->nullable()->after('father_job');
            $table->string('father_citizenship', 50)->default('WNI')->after('father_address');

            // Pelapor Tambahan
            $table->string('applicant_birth_place')->nullable()->after('applicant_name');
            $table->date('applicant_birth_date')->nullable()->after('applicant_birth_place');
            $table->unsignedInteger('applicant_age')->nullable()->after('applicant_birth_date');
            $table->string('applicant_job')->nullable()->after('applicant_age');
            $table->text('applicant_address')->nullable()->after('applicant_job');
            $table->date('report_date')->nullable()->after('applicant_relation');

            // Saksi I & Saksi II
            $table->string('witness1_nik', 16)->nullable()->after('report_date');
            $table->string('witness1_name')->nullable()->after('witness1_nik');
            $table->unsignedInteger('witness1_age')->nullable()->after('witness1_name');
            $table->text('witness1_address')->nullable()->after('witness1_age');

            $table->string('witness2_nik', 16)->nullable()->after('witness1_address');
            $table->string('witness2_name')->nullable()->after('witness2_nik');
            $table->unsignedInteger('witness2_age')->nullable()->after('witness2_name');
            $table->text('witness2_address')->nullable()->after('witness2_age');

            // Dokumen Unggahan Tambahan
            $table->string('doc_rt_rw_statement')->nullable()->after('doc_death_statement');
            $table->string('doc_heir_family_card')->nullable()->after('doc_deceased_ktp');
            $table->string('doc_heir_ktp')->nullable()->after('doc_heir_family_card');

            // Data Administrasi Petugas Desa
            $table->string('officer_registration_type')->nullable()->after('doc_applicant_ktp');
            $table->string('officer_name')->default('Teguh Kurniawan, S.Pi')->nullable()->after('officer_registration_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('death_certificates', function (Blueprint $table) {
            $table->dropColumn([
                'head_of_family_name',
                'deceased_birth_place',
                'deceased_address',
                'deceased_child_order',
                'death_day',
                'death_location_detail',
                'mother_nik',
                'mother_name',
                'mother_birth_place',
                'mother_birth_date',
                'mother_job',
                'mother_address',
                'mother_citizenship',
                'father_nik',
                'father_name',
                'father_birth_place',
                'father_birth_date',
                'father_job',
                'father_address',
                'father_citizenship',
                'applicant_birth_place',
                'applicant_birth_date',
                'applicant_age',
                'applicant_job',
                'applicant_address',
                'report_date',
                'witness1_nik',
                'witness1_name',
                'witness1_age',
                'witness1_address',
                'witness2_nik',
                'witness2_name',
                'witness2_age',
                'witness2_address',
                'doc_rt_rw_statement',
                'doc_heir_family_card',
                'doc_heir_ktp',
                'officer_registration_type',
                'officer_name',
            ]);
        });
    }
};

