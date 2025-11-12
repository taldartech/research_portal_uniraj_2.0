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
            // Update status enum to include pending_expert_assignment
            $table->enum('status', [
                'pending_supervisor_approval',
                'pending_hod_approval',
                'pending_da_approval',
                'pending_so_approval',
                'pending_ar_approval',
                'pending_dr_approval',
                'pending_hvc_approval',
                'approved',
                'pending_expert_assignment',
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
            // Revert status enum (remove pending_expert_assignment)
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
};
