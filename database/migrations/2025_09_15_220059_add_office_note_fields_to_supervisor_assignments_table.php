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
        Schema::table('supervisor_assignments', function (Blueprint $table) {
            // Office Note fields
            $table->string('office_note_file')->nullable();
            $table->boolean('office_note_generated')->default(false);
            $table->timestamp('office_note_generated_at')->nullable();
            $table->foreignId('office_note_signed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('office_note_signed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supervisor_assignments', function (Blueprint $table) {
            $table->dropColumn([
                'office_note_file',
                'office_note_generated',
                'office_note_generated_at',
                'office_note_signed_by',
                'office_note_signed_at'
            ]);
        });
    }
};
