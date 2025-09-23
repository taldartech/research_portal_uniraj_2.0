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
        Schema::create('progress_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->foreignId('supervisor_id')->constrained()->onDelete('cascade');
            $table->foreignId('hod_id')->constrained('users')->onDelete('cascade');
            $table->string('report_file');
            $table->string('rac_minutes_file')->nullable();
            $table->string('drc_minutes_file')->nullable();
            $table->date('submission_date');
            $table->string('report_period'); // e.g., 'April', 'October'
            $table->text('feedback_da')->nullable();
            $table->boolean('special_remark')->default(false);
            $table->string('status')->default('pending_supervisor_submission');
            $table->foreignId('so_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('ar_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('dr_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('hvc_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('cancellation_request')->default(false);
            $table->boolean('supervisor_change_request')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_reports');
    }
};
