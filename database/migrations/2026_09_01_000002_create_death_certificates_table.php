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
        Schema::create('death_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('registration_no')->unique();
            
            // Data Almarhum / Almarhumah
            $table->string('deceased_nik', 16);
            $table->string('deceased_name');
            $table->enum('gender', ['L', 'P']);
            $table->date('birth_date')->nullable();
            $table->string('religion')->default('Islam');
            $table->string('padukuhan');
            $table->string('rt', 5)->default('01');
            $table->string('rw', 5)->default('01');

            // Data Kematian
            $table->date('death_date');
            $table->string('death_time')->nullable();
            $table->string('death_place')->default('Rumah'); // Rumah, RS, Puskesmas, dll
            $table->string('cause_of_death')->default('Sakit / Usia Tua');
            $table->string('reported_by_title')->default('Kepala Dusun / RT'); // Dokter, Perawat, RT

            // Data Pelapor & Saksi
            $table->string('applicant_nik', 16);
            $table->string('applicant_name');
            $table->string('applicant_phone', 20);
            $table->string('applicant_relation'); // Anak, Suami/Istri, Saudara, dll
            
            $table->string('witness_nik', 16)->nullable();
            $table->string('witness_name')->nullable();

            // Dokumen Pendukung
            $table->string('doc_death_statement')->nullable(); // Surat Kematian RS/RT
            $table->string('doc_family_card')->nullable(); // Kartu Keluarga
            $table->string('doc_deceased_ktp')->nullable(); // KTP Jenazah
            $table->string('doc_applicant_ktp')->nullable(); // KTP Pelapor

            // Status Pelayanan
            $table->enum('status', ['pending', 'verified', 'in_process', 'completed', 'rejected', 'archived'])->default('pending');
            $table->text('rejection_note')->nullable();
            $table->string('processed_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('death_certificates');
    }
};
