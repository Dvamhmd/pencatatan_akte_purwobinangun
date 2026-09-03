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
        Schema::create('birth_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('registration_no')->unique();
            
            // Data Anak
            $table->string('child_name');
            $table->enum('gender', ['L', 'P']);
            $table->string('birth_place');
            $table->date('birth_date');
            $table->string('birth_time')->nullable();
            $table->string('birth_type')->default('Tunggal'); // Tunggal, Kembar 2, Kembar 3, Input Jumlah
            $table->integer('birth_order')->default(1);
            $table->string('birth_helper')->nullable()->default('Bidan'); // Penolong / Tempat Medis
            $table->string('birth_place_type')->nullable()->default('Rumah Sakit'); // Rumah Sakit, Puskesmas, Klinik, Rumah, Lainnya
            $table->decimal('weight_kg', 4, 2)->nullable();
            $table->decimal('length_cm', 4, 1)->nullable();

            // Data Orang Tua (Opsional)
            $table->string('father_nik', 16)->nullable();
            $table->string('father_name')->nullable();
            $table->date('father_birth_date')->nullable();
            $table->string('father_job')->nullable();
            
            $table->string('mother_nik', 16)->nullable();
            $table->string('mother_name')->nullable();
            $table->date('mother_birth_date')->nullable();
            $table->string('mother_job')->nullable();

            // Data Pemohon / Pelapor
            $table->string('applicant_nik', 16);
            $table->string('applicant_name');
            $table->string('applicant_phone', 20);
            $table->string('applicant_relation')->nullable()->default('Pemohon / Orang Tua');
            $table->text('address')->nullable();
            $table->string('padukuhan')->nullable()->default('Purwobinangun');
            $table->string('rt', 5)->nullable()->default('01');
            $table->string('rw', 5)->nullable()->default('01');

            // Berkas Dokumen Pendukung
            $table->string('doc_birth_cert')->nullable(); // Keterangan Lahir RS/Bidan
            $table->string('doc_family_card')->nullable(); // Kartu Keluarga
            $table->string('doc_marriage_cert')->nullable(); // Buku Nikah
            $table->string('doc_parents_ktp')->nullable(); // KTP Ortu / Pemohon
            $table->string('doc_witness_ktp')->nullable(); // KTP Saksi

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
        Schema::dropIfExists('birth_certificates');
    }
};
