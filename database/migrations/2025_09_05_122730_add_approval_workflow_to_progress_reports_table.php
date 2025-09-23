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
            // Add approval workflow columns
            $table->unsignedBigInteger('so_approver_id')->nullable()->after('da_remarks');
            $table->timestamp('so_approved_at')->nullable()->after('so_approver_id');
            $table->text('so_remarks')->nullable()->after('so_approved_at');

            $table->unsignedBigInteger('ar_approver_id')->nullable()->after('so_remarks');
            $table->timestamp('ar_approved_at')->nullable()->after('ar_approver_id');
            $table->text('ar_remarks')->nullable()->after('ar_approved_at');

            $table->unsignedBigInteger('dr_approver_id')->nullable()->after('ar_remarks');
            $table->timestamp('dr_approved_at')->nullable()->after('dr_approver_id');
            $table->text('dr_remarks')->nullable()->after('dr_approved_at');

            $table->unsignedBigInteger('hvc_approver_id')->nullable()->after('dr_remarks');
            $table->timestamp('hvc_approved_at')->nullable()->after('hvc_approver_id');
            $table->text('hvc_remarks')->nullable()->after('hvc_approved_at');

            // Add foreign key constraints
            $table->foreign('so_approver_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('ar_approver_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('dr_approver_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('hvc_approver_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('progress_reports', function (Blueprint $table) {
            $table->dropForeign(['so_approver_id']);
            $table->dropForeign(['ar_approver_id']);
            $table->dropForeign(['dr_approver_id']);
            $table->dropForeign(['hvc_approver_id']);

            $table->dropColumn([
                'so_approver_id', 'so_approved_at', 'so_remarks',
                'ar_approver_id', 'ar_approved_at', 'ar_remarks',
                'dr_approver_id', 'dr_approved_at', 'dr_remarks',
                'hvc_approver_id', 'hvc_approved_at', 'hvc_remarks'
            ]);
        });
    }
};
