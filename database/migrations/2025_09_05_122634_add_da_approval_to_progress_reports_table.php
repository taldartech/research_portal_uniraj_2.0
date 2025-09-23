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
            $table->unsignedBigInteger('da_approver_id')->nullable()->after('hod_id');
            $table->timestamp('da_approved_at')->nullable()->after('da_approver_id');
            $table->text('da_remarks')->nullable()->after('da_approved_at');
            $table->text('da_negative_remarks')->nullable()->after('da_remarks');

            $table->foreign('da_approver_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('progress_reports', function (Blueprint $table) {
            $table->dropForeign(['da_approver_id']);
            $table->dropColumn(['da_approver_id', 'da_approved_at', 'da_remarks', 'da_negative_remarks']);
        });
    }
};
