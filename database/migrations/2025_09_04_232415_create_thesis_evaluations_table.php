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
        Schema::table('thesis_evaluations', function (Blueprint $table) {
            // Add new columns for enhanced workflow
            $table->text('evaluation_report')->nullable()->after('report_file');
            $table->text('remarks')->nullable()->after('decision');
            $table->integer('priority_order')->nullable()->after('da_assigned_expert_id'); // 1-4 for HVC selection, 1-2 for DA assignment

            // Update status enum
            $table->enum('status', [
                'assigned',
                'in_progress',
                'submitted',
                'overdue',
                'completed'
            ])->default('assigned')->change();

            // Update decision enum
            $table->enum('decision', [
                'approved',
                'approved_with_minor_revisions',
                'approved_with_major_revisions',
                'rejected'
            ])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thesis_evaluations', function (Blueprint $table) {
            $table->dropColumn(['evaluation_report', 'remarks', 'priority_order']);
        });
    }
};
