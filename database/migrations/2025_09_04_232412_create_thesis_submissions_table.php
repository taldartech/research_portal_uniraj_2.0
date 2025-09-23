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
        Schema::table('thesis_submissions', function (Blueprint $table) {
            // Add RAC and DRC minutes upload fields
            $table->string('rac_minutes_file')->nullable()->after('supervisor_remarks');
            $table->string('drc_minutes_file')->nullable()->after('hod_remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thesis_submissions', function (Blueprint $table) {
            $table->dropColumn([
                'rac_minutes_file', 'drc_minutes_file'
            ]);
        });
    }
};
