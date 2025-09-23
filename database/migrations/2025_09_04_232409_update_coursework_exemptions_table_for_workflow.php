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
        Schema::table('coursework_exemptions', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['supervisor_approver_id']);
            $table->dropForeign(['hod_approver_id']);

            // Remove supervisor and HOD approval fields (not needed for coursework exemption)
            $table->dropColumn([
                'supervisor_approver_id',
                'supervisor_approved_at',
                'supervisor_remarks',
                'hod_approver_id',
                'hod_approved_at',
                'hod_remarks'
            ]);

            // Update status to start with DEAN approval and include specific rejection statuses
            $table->enum('status', [
                'pending_dean_approval',
                'pending_da_approval',
                'pending_so_approval',
                'pending_ar_approval',
                'pending_dr_approval',
                'pending_hvc_approval',
                'approved',
                'rejected_by_dean',
                'rejected_by_da',
                'rejected_by_so',
                'rejected_by_ar',
                'rejected_by_dr',
                'rejected_by_hvc'
            ])->default('pending_dean_approval')->change();

            // Add DEAN approval fields
            $table->unsignedBigInteger('dean_approver_id')->nullable()->after('status');
            $table->timestamp('dean_approved_at')->nullable()->after('dean_approver_id');
            $table->text('dean_remarks')->nullable()->after('dean_approved_at');

            // Add rejection tracking fields
            $table->unsignedBigInteger('rejected_by')->nullable()->after('hvc_remarks');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            $table->text('rejection_reason')->nullable()->after('rejected_at');
            $table->integer('rejection_count')->default(0)->after('rejection_reason');
            $table->unsignedBigInteger('original_exemption_id')->nullable()->after('rejection_count');

            // Add foreign key constraints
            $table->foreign('dean_approver_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('original_exemption_id')->references('id')->on('coursework_exemptions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coursework_exemptions', function (Blueprint $table) {
            $table->dropForeign(['dean_approver_id']);
            $table->dropForeign(['rejected_by']);
            $table->dropForeign(['original_exemption_id']);

            $table->dropColumn([
                'dean_approver_id', 'dean_approved_at', 'dean_remarks',
                'rejected_by', 'rejected_at', 'rejection_reason', 'rejection_count', 'original_exemption_id'
            ]);

            // Re-add supervisor and HOD approval fields
            $table->foreignId('supervisor_approver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('supervisor_approved_at')->nullable();
            $table->text('supervisor_remarks')->nullable();

            $table->foreignId('hod_approver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('hod_approved_at')->nullable();
            $table->text('hod_remarks')->nullable();

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
