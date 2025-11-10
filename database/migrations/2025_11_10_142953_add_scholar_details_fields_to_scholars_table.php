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
        Schema::table('scholars', function (Blueprint $table) {
            // Enrollment Type
            $table->enum('enrollment_type', ['preenroll', 'new_enroll'])->nullable()->after('enrollment_number');
            
            // Cash Receipt fields (for new enroll)
            $table->string('cash_receipt_number')->nullable()->after('enrollment_type');
            $table->date('cash_receipt_date')->nullable()->after('cash_receipt_number');
            
            // Photo and Signature
            $table->string('photo')->nullable()->after('cash_receipt_date');
            $table->string('sign')->nullable()->after('photo');
            
            // Letter Number and Supervisor Recognition Date
            $table->string('letter_number')->nullable()->after('sign');
            $table->date('supervisor_recognition_date')->nullable()->after('letter_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholars', function (Blueprint $table) {
            $table->dropColumn([
                'enrollment_type',
                'cash_receipt_number',
                'cash_receipt_date',
                'photo',
                'sign',
                'letter_number',
                'supervisor_recognition_date',
            ]);
        });
    }
};
