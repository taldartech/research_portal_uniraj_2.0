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
            // Registration Letter fields
            $table->string('registration_letter_file')->nullable();
            $table->boolean('registration_letter_generated')->default(false);
            $table->timestamp('registration_letter_generated_at')->nullable();
            $table->foreignId('registration_letter_signed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('registration_letter_signed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholars', function (Blueprint $table) {
            $table->dropColumn([
                'registration_letter_file',
                'registration_letter_generated',
                'registration_letter_generated_at',
                'registration_letter_signed_by',
                'registration_letter_signed_at'
            ]);
        });
    }
};
