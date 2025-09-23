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
        Schema::create('coursework_exemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->foreignId('supervisor_id')->constrained()->onDelete('cascade');
            $table->foreignId('rac_id')->constrained()->onDelete('cascade');
            $table->foreignId('drc_id')->constrained()->onDelete('cascade');
            $table->text('reason');
            $table->string('minutes_file');
            $table->date('request_date');
            $table->date('hod_approval_date')->nullable();
            $table->date('dean_approval_date')->nullable();
            $table->string('status')->default('pending_hod_approval');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coursework_exemptions');
    }
};
