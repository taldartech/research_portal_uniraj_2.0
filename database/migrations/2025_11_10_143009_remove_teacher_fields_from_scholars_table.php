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
            // Remove teacher-related fields as they are no longer needed in the registration form
            $table->dropColumn(['is_teacher', 'teacher_employer']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholars', function (Blueprint $table) {
            // Restore teacher-related fields
            $table->boolean('is_teacher')->default(false)->after('occupation');
            $table->string('teacher_employer')->nullable()->after('is_teacher');
        });
    }
};
