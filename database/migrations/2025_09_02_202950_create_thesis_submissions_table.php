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
        Schema::create('thesis_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->foreignId('supervisor_id')->constrained()->onDelete('cascade');
            $table->foreignId('hod_id')->constrained('users')->onDelete('cascade');
            $table->string('thesis_file');
            $table->json('supporting_documents')->nullable();
            $table->date('submission_date');
            $table->string('submission_fees_status')->default('pending');
            $table->string('submission_certificate_file')->nullable();
            $table->string('status')->default('pending_supervisor_validation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thesis_submissions');
    }
};
