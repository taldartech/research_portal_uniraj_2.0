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
        Schema::table('progress_reports', function (Blueprint $table) {
            // Add supervisor approval columns after supervisor_id
            $table->unsignedBigInteger('supervisor_approver_id')->nullable()->after('supervisor_id');
            $table->timestamp('supervisor_approved_at')->nullable()->after('supervisor_approver_id');
            $table->text('supervisor_remarks')->nullable()->after('supervisor_approved_at');
            
            // Add HOD approval columns if they don't exist
            if (!Schema::hasColumn('progress_reports', 'hod_approver_id')) {
                $table->unsignedBigInteger('hod_approver_id')->nullable()->after('hod_id');
                $table->timestamp('hod_approved_at')->nullable()->after('hod_approver_id');
                $table->text('hod_remarks')->nullable()->after('hod_approved_at');
                
                $table->foreign('hod_approver_id')->references('id')->on('users')->onDelete('set null');
            }

            // Add foreign key constraint
            $table->foreign('supervisor_approver_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('progress_reports', function (Blueprint $table) {
            $table->dropForeign(['supervisor_approver_id']);
            $table->dropColumn(['supervisor_approver_id', 'supervisor_approved_at', 'supervisor_remarks']);
            
            if (Schema::hasColumn('progress_reports', 'hod_approver_id')) {
                $table->dropForeign(['hod_approver_id']);
                $table->dropColumn(['hod_approver_id', 'hod_approved_at', 'hod_remarks']);
            }
        });
    }
};
