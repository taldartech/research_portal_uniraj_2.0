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
        Schema::table('synopses', function (Blueprint $table) {
            // Update status to include specific rejection statuses
            $table->enum('status', [
                'pending_supervisor_approval',
                'pending_hod_approval',
                'pending_da_approval',
                'pending_so_approval',
                'pending_ar_approval',
                'pending_dr_approval',
                'pending_hvc_approval',
                'approved',
                'rejected_by_supervisor',
                'rejected_by_hod',
                'rejected_by_da',
                'rejected_by_so',
                'rejected_by_ar',
                'rejected_by_dr',
                'rejected_by_hvc'
            ])->default('pending_supervisor_approval')->change();

            // Add RAC and DRC minutes upload fields
            $table->string('rac_minutes_file')->nullable()->after('supervisor_remarks');
            $table->string('drc_minutes_file')->nullable()->after('hod_remarks');

            // Add rejection tracking fields
            $table->unsignedBigInteger('rejected_by')->nullable()->after('hvc_remarks');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            $table->text('rejection_reason')->nullable()->after('rejected_at');
            $table->integer('rejection_count')->default(0)->after('rejection_reason');
            $table->unsignedBigInteger('original_synopsis_id')->nullable()->after('rejection_count');

            // Add foreign key constraints for rejection tracking
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('original_synopsis_id')->references('id')->on('synopses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('synopses', function (Blueprint $table) {
            $table->dropForeign(['rejected_by']);
            $table->dropForeign(['original_synopsis_id']);

            $table->dropColumn([
                'rac_minutes_file', 'drc_minutes_file',
                'rejected_by', 'rejected_at', 'rejection_reason', 'rejection_count', 'original_synopsis_id'
            ]);

            // Revert status to previous version
            $table->enum('status', [
                'pending_supervisor_approval',
                'pending_hod_approval',
                'pending_da_approval',
                'pending_so_approval',
                'pending_ar_approval',
                'pending_dr_approval',
                'pending_hvc_approval',
                'approved',
                'rejected'
            ])->default('pending_supervisor_approval')->change();
        });
    }
};
