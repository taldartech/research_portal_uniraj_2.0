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
            // Add rejection workflow fields
            $table->boolean('is_resubmission')->default(false)->after('status');
            $table->integer('rejection_count')->default(0)->after('is_resubmission');
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null')->after('rejection_count');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            $table->text('rejection_reason')->nullable()->after('rejected_at');
            $table->foreignId('original_thesis_id')->nullable()->constrained('thesis_submissions')->onDelete('set null')->after('rejection_reason');

            // Update status enum to include rejection statuses
            $table->enum('status', [
                'pending_supervisor_approval',
                'pending_hod_approval',
                'pending_da_approval',
                'pending_so_approval',
                'pending_ar_approval',
                'pending_dr_approval',
                'pending_hvc_approval',
                'approved',
                'rejected',
                'rejected_by_supervisor',
                'rejected_by_hod',
                'rejected_by_da',
                'rejected_by_so',
                'rejected_by_ar',
                'rejected_by_dr',
                'rejected_by_hvc',
                'rejected_by_expert',
                'rejected_by_viva',
                'resubmitted',
                'final_approved'
            ])->default('pending_supervisor_approval')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thesis_submissions', function (Blueprint $table) {
            $table->dropColumn([
                'is_resubmission',
                'rejection_count',
                'rejected_by',
                'rejected_at',
                'rejection_reason',
                'original_thesis_id'
            ]);

            // Revert status enum
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
