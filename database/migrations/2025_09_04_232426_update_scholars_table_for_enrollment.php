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
            // Add enrollment status
            $table->enum('enrollment_status', [
                'pending',
                'enrolled',
                'graduated',
                'withdrawn',
                'suspended'
            ])->default('pending')->after('status');

            // Add enrollment date
            $table->timestamp('enrolled_at')->nullable()->after('enrollment_status');

            // Add registration form relationship
            $table->unsignedBigInteger('registration_form_id')->nullable()->after('enrolled_at');

            // Add foreign key constraint
            $table->foreign('registration_form_id')->references('id')->on('registration_forms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholars', function (Blueprint $table) {
            $table->dropForeign(['registration_form_id']);
            $table->dropColumn(['enrollment_status', 'enrolled_at', 'registration_form_id']);
        });
    }
};
