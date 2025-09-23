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
        Schema::create('registration_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scholar_id');
            $table->string('dispatch_number')->unique();
            $table->string('form_file_path');
            $table->enum('status', [
                'generated',
                'signed_by_dr',
                'signed_by_ar',
                'completed',
                'downloaded'
            ])->default('generated');

            // Generation tracking
            $table->unsignedBigInteger('generated_by_da_id');
            $table->timestamp('generated_at');

            // Digital signature tracking
            $table->unsignedBigInteger('signed_by_dr_id')->nullable();
            $table->timestamp('signed_by_dr_at')->nullable();
            $table->string('dr_signature_file')->nullable();

            $table->unsignedBigInteger('signed_by_ar_id')->nullable();
            $table->timestamp('signed_by_ar_at')->nullable();
            $table->string('ar_signature_file')->nullable();

            // Download tracking
            $table->timestamp('downloaded_at')->nullable();
            $table->integer('download_count')->default(0);

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('scholar_id')->references('id')->on('scholars')->onDelete('cascade');
            $table->foreign('generated_by_da_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('signed_by_dr_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('signed_by_ar_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_forms');
    }
};
