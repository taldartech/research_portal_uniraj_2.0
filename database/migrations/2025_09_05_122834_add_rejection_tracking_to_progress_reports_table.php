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
        Schema::table('progress_reports', function (Blueprint $table) {
            // Add rejection tracking columns
            $table->unsignedBigInteger('rejected_by')->nullable()->after('hvc_remarks');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            $table->text('rejection_reason')->nullable()->after('rejected_at');
            $table->integer('rejection_count')->default(0)->after('rejection_reason');
            $table->unsignedBigInteger('original_report_id')->nullable()->after('rejection_count');

            // Add foreign key constraints
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('original_report_id')->references('id')->on('progress_reports')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('progress_reports', function (Blueprint $table) {
            $table->dropForeign(['rejected_by']);
            $table->dropForeign(['original_report_id']);

            $table->dropColumn([
                'rejected_by', 'rejected_at', 'rejection_reason',
                'rejection_count', 'original_report_id'
            ]);
        });
    }
};
