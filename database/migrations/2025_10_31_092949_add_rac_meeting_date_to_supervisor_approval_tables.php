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
        // Add rac_meeting_date to progress_reports
        Schema::table('progress_reports', function (Blueprint $table) {
            $table->date('rac_meeting_date')->nullable()->after('rac_minutes_file');
        });

        // Add rac_meeting_date to synopses
        Schema::table('synopses', function (Blueprint $table) {
            $table->date('rac_meeting_date')->nullable()->after('rac_minutes_file');
        });

        // Add rac_meeting_date to thesis_submissions (separate from rac_meeting_dates JSON)
        Schema::table('thesis_submissions', function (Blueprint $table) {
            $table->date('rac_meeting_date')->nullable()->after('rac_minutes_file');
        });

        // Add rac_meeting_date to coursework_exemptions
        Schema::table('coursework_exemptions', function (Blueprint $table) {
            $table->date('rac_meeting_date')->nullable()->after('minutes_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('progress_reports', function (Blueprint $table) {
            $table->dropColumn('rac_meeting_date');
        });

        Schema::table('synopses', function (Blueprint $table) {
            $table->dropColumn('rac_meeting_date');
        });

        Schema::table('thesis_submissions', function (Blueprint $table) {
            $table->dropColumn('rac_meeting_date');
        });

        Schema::table('coursework_exemptions', function (Blueprint $table) {
            $table->dropColumn('rac_meeting_date');
        });
    }
};
