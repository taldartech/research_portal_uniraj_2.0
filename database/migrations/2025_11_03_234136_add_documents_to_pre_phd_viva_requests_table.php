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
        Schema::table('pre_phd_viva_requests', function (Blueprint $table) {
            $table->text('supportive_documents')->nullable()->after('request_remarks'); // JSON array of file paths
            $table->string('thesis_summary_file')->nullable()->after('supportive_documents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_phd_viva_requests', function (Blueprint $table) {
            $table->dropColumn(['supportive_documents', 'thesis_summary_file']);
        });
    }
};
