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
            // Update status to include all approval stages
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

            // Add approval workflow fields
            $table->foreignId('supervisor_approver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('supervisor_approved_at')->nullable();
            $table->text('supervisor_remarks')->nullable();

            $table->foreignId('hod_approver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('hod_approved_at')->nullable();
            $table->text('hod_remarks')->nullable();

            $table->foreignId('da_approver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('da_approved_at')->nullable();
            $table->text('da_remarks')->nullable();

            $table->foreignId('so_approver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('so_approved_at')->nullable();
            $table->text('so_remarks')->nullable();

            $table->foreignId('ar_approver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('ar_approved_at')->nullable();
            $table->text('ar_remarks')->nullable();

            $table->foreignId('dr_approver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('dr_approved_at')->nullable();
            $table->text('dr_remarks')->nullable();

            $table->foreignId('hvc_approver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('hvc_approved_at')->nullable();
            $table->text('hvc_remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thesis_submissions', function (Blueprint $table) {
            $table->dropColumn([
                'supervisor_approver_id',
                'supervisor_approved_at',
                'supervisor_remarks',
                'hod_approver_id',
                'hod_approved_at',
                'hod_remarks',
                'da_approver_id',
                'da_approved_at',
                'da_remarks',
                'so_approver_id',
                'so_approved_at',
                'so_remarks',
                'ar_approver_id',
                'ar_approved_at',
                'ar_remarks',
                'dr_approver_id',
                'dr_approved_at',
                'dr_remarks',
                'hvc_approver_id',
                'hvc_approved_at',
                'hvc_remarks',
            ]);

            // Revert status to original
            $table->string('status')->default('pending_supervisor_validation')->change();
        });
    }
};
