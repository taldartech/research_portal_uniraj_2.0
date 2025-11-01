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
        Schema::table('rac_committee_submissions', function (Blueprint $table) {
            $table->string('member1_designation')->nullable()->after('member1_name');
            $table->string('member1_department')->nullable()->after('member1_designation');
            $table->string('member2_designation')->nullable()->after('member2_name');
            $table->string('member2_department')->nullable()->after('member2_designation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rac_committee_submissions', function (Blueprint $table) {
            $table->dropColumn(['member1_designation', 'member1_department', 'member2_designation', 'member2_department']);
        });
    }
};
