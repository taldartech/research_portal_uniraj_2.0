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
        Schema::create('supervisor_capacity_increase_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervisor_id')->constrained('supervisors')->onDelete('cascade');
            $table->integer('current_capacity');
            $table->integer('requested_capacity');
            $table->text('justification');
            $table->enum('status', ['pending_da', 'pending_so', 'pending_ar', 'pending_dr', 'pending_hvc', 'approved', 'rejected'])->default('pending_da');
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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisor_capacity_increase_requests');
    }
};
