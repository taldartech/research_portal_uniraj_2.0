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
        Schema::create('viva_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_submission_id')->constrained()->onDelete('cascade');
            $table->foreignId('hvc_assigned_expert_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('hod_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('supervisor_id')->constrained()->onDelete('cascade');
            $table->date('viva_date')->nullable();
            $table->string('viva_report_file')->nullable();
            $table->string('status')->default('scheduled');
            $table->string('decision')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viva_processes');
    }
};
