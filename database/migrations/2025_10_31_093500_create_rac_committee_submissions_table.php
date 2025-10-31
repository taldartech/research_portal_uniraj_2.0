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
        Schema::create('rac_committee_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->foreignId('supervisor_id')->constrained()->onDelete('cascade');
            $table->string('member1_name');
            $table->string('member2_name');
            $table->string('status')->default('pending_hod_approval'); // pending_hod_approval, approved, rejected
            $table->foreignId('hod_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('drc_date')->nullable();
            $table->text('hod_remarks')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();

            // Index for faster queries
            $table->index(['scholar_id', 'status']);
            $table->index('supervisor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rac_committee_submissions');
    }
};
