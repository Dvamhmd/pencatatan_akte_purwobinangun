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
            $table->string('role')->default('warga')->after('id'); // 'admin', 'warga'
            $table->string('nik', 16)->nullable()->unique()->after('role');
            $table->string('family_card_no', 16)->nullable()->index()->after('nik');
            $table->string('birth_place')->nullable()->after('name');
            $table->date('birth_date')->nullable()->after('birth_place');
            $table->enum('gender', ['L', 'P'])->nullable()->after('birth_date');
            $table->text('address')->nullable()->after('gender');
            $table->string('rt', 5)->nullable()->after('address');
            $table->string('rw', 5)->nullable()->after('rt');
            $table->string('phone', 20)->nullable()->after('rw');
            $table->string('email')->nullable()->change();
            $table->enum('status', ['pending', 'active', 'rejected', 'archived'])->default('pending')->after('password');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->timestamp('verified_at')->nullable()->after('rejection_reason');
            $table->string('verified_by')->nullable()->after('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'nik',
                'family_card_no',
                'birth_place',
                'birth_date',
                'gender',
                'address',
                'rt',
                'rw',
                'phone',
                'status',
                'rejection_reason',
                'verified_at',
                'verified_by',
            ]);
        });
    }
};
