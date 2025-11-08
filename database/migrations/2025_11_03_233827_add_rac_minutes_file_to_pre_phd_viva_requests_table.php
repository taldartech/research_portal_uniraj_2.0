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
            $table->string('rac_minutes_file')->nullable()->after('rac_remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_phd_viva_requests', function (Blueprint $table) {
            $table->dropColumn('rac_minutes_file');
        });
    }
};
