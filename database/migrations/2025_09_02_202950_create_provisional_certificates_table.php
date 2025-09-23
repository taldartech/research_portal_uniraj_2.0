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
        Schema::create('provisional_certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scholar_id');
            $table->unsignedBigInteger('viva_process_id');
            $table->date('issue_date');
            $table->string('certificate_file')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('d-id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provisional_certificates');
    }
};
