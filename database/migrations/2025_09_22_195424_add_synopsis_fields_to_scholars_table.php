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
        Schema::table('scholars', function (Blueprint $table) {
            $table->string('synopsis_topic')->nullable()->after('coursework_completed');
            $table->string('synopsis_file')->nullable()->after('synopsis_topic');
            $table->timestamp('synopsis_submitted_at')->nullable()->after('synopsis_file');
            $table->string('synopsis_status')->nullable()->after('synopsis_submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholars', function (Blueprint $table) {
            $table->dropColumn(['synopsis_topic', 'synopsis_file', 'synopsis_submitted_at', 'synopsis_status']);
        });
    }
};
