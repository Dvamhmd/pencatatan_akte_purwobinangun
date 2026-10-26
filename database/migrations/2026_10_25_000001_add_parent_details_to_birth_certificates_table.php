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
            // Data Ayah Tambahan
            $table->unsignedInteger('father_age')->nullable()->after('father_birth_date');
            $table->text('father_address')->nullable()->after('father_job');
            $table->string('father_rt', 5)->nullable()->after('father_address');
            $table->string('father_rw', 5)->nullable()->after('father_rt');
            $table->string('father_village', 100)->nullable()->after('father_rw');
            $table->string('father_district', 100)->nullable()->after('father_village');
            $table->string('father_city', 100)->nullable()->after('father_district');
            $table->string('father_province', 100)->nullable()->after('father_city');
            $table->string('father_citizenship', 20)->default('WNI')->after('father_province');
            $table->string('father_nationality', 50)->default('INDONESIA')->after('father_citizenship');

            // Data Ibu Tambahan
            $table->unsignedInteger('mother_age')->nullable()->after('mother_birth_date');
            $table->text('mother_address')->nullable()->after('mother_job');
            $table->string('mother_rt', 5)->nullable()->after('mother_address');
            $table->string('mother_rw', 5)->nullable()->after('mother_rt');
            $table->string('mother_village', 100)->nullable()->after('mother_rw');
            $table->string('mother_district', 100)->nullable()->after('mother_village');
            $table->string('mother_city', 100)->nullable()->after('mother_district');
            $table->string('mother_province', 100)->nullable()->after('mother_city');
            $table->string('mother_citizenship', 20)->default('WNI')->after('mother_province');
            $table->string('mother_nationality', 50)->default('INDONESIA')->after('mother_citizenship');
            $table->date('marriage_date')->nullable()->after('mother_nationality');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('birth_certificates', function (Blueprint $table) {
            $table->dropColumn([
                'father_age',
                'father_address',
                'father_rt',
                'father_rw',
                'father_village',
                'father_district',
                'father_city',
                'father_province',
                'father_citizenship',
                'father_nationality',
                'mother_age',
                'mother_address',
                'mother_rt',
                'mother_rw',
                'mother_village',
                'mother_district',
                'mother_city',
                'mother_province',
                'mother_citizenship',
                'mother_nationality',
                'marriage_date',
            ]);
        });
    }
};
