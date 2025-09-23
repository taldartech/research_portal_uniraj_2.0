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
        Schema::create('late_submission_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->foreignId('thesis_submission_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('justification');
            $table->text('supporting_documents')->nullable(); // JSON array of document paths
            $table->date('original_due_date');
            $table->date('requested_extension_date');
            $table->enum('status', [
                'pending_supervisor_approval',
                'pending_hod_approval',
                'pending_dean_approval',
                'pending_da_approval',
                'pending_so_approval',
                'pending_ar_approval',
                'pending_dr_approval',
                'pending_hvc_approval',
                'approved',
                'rejected_by_supervisor',
                'rejected_by_hod',
                'rejected_by_dean',
                'rejected_by_da',
                'rejected_by_so',
                'rejected_by_ar',
                'rejected_by_dr',
                'rejected_by_hvc'
            ])->default('pending_supervisor_approval');

            // Approval workflow fields
            $table->foreignId('supervisor_approver_id')->nullable()->constrained('users');
            $table->timestamp('supervisor_approved_at')->nullable();
            $table->text('supervisor_remarks')->nullable();

            $table->foreignId('hod_approver_id')->nullable()->constrained('users');
            $table->timestamp('hod_approved_at')->nullable();
            $table->text('hod_remarks')->nullable();

            $table->foreignId('dean_approver_id')->nullable()->constrained('users');
            $table->timestamp('dean_approved_at')->nullable();
            $table->text('dean_remarks')->nullable();

            $table->foreignId('da_approver_id')->nullable()->constrained('users');
            $table->timestamp('da_approved_at')->nullable();
            $table->text('da_remarks')->nullable();

            $table->foreignId('so_approver_id')->nullable()->constrained('users');
            $table->timestamp('so_approved_at')->nullable();
            $table->text('so_remarks')->nullable();

            $table->foreignId('ar_approver_id')->nullable()->constrained('users');
            $table->timestamp('ar_approved_at')->nullable();
            $table->text('ar_remarks')->nullable();

            $table->foreignId('dr_approver_id')->nullable()->constrained('users');
            $table->timestamp('dr_approved_at')->nullable();
            $table->text('dr_remarks')->nullable();

            $table->foreignId('hvc_approver_id')->nullable()->constrained('users');
            $table->timestamp('hvc_approved_at')->nullable();
            $table->text('hvc_remarks')->nullable();

            // Rejection tracking
            $table->foreignId('rejected_by')->nullable()->constrained('users');
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->integer('rejection_count')->default(0);
            $table->foreignId('original_request_id')->nullable()->constrained('late_submission_requests');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('late_submission_requests');
    }
};
