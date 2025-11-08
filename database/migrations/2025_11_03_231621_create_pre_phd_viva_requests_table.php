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
        Schema::create('pre_phd_viva_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->foreignId('supervisor_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending_rac_approval'); // pending_rac_approval, approved, rejected, expired
            $table->text('request_remarks')->nullable();
            $table->date('requested_date')->default(now());
            $table->date('viva_date')->nullable(); // Set by RAC (must be at least 1 month from request)
            $table->foreignId('rac_approver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('rac_approved_at')->nullable();
            $table->text('rac_remarks')->nullable();
            $table->date('thesis_submission_deadline')->nullable(); // viva_date + 6 months
            $table->boolean('thesis_submitted')->default(false);
            $table->foreignId('thesis_submission_id')->nullable()->constrained('thesis_submissions')->onDelete('set null');
            $table->timestamps();
            
            $table->index('scholar_id');
            $table->index('status');
            $table->index('viva_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_phd_viva_requests');
    }
};
