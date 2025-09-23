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
        Schema::table('viva_processes', function (Blueprint $table) {
            // Add new columns for enhanced workflow
            $table->time('viva_time')->nullable()->after('viva_date');
            $table->string('venue')->nullable()->after('viva_time');
            $table->text('viva_report')->nullable()->after('viva_report_file');
            $table->text('committee_remarks')->nullable()->after('decision');
            $table->text('scholar_performance')->nullable()->after('committee_remarks');
            $table->json('committee_members')->nullable()->after('scholar_performance'); // Store committee member IDs

            // Update status enum
            $table->enum('status', [
                'scheduled',
                'conducted',
                'completed',
                'cancelled',
                'rescheduled'
            ])->default('scheduled')->change();

            // Update decision enum
            $table->enum('decision', [
                'passed',
                'passed_with_minor_revisions',
                'passed_with_major_revisions',
                'failed',
                'pending'
            ])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('viva_processes', function (Blueprint $table) {
            $table->dropColumn([
                'viva_time', 'venue', 'viva_report', 'committee_remarks',
                'scholar_performance', 'committee_members'
            ]);
        });
    }
};
