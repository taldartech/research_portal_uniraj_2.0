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
        Schema::create('supervisor_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scholar_id');
            $table->unsignedBigInteger('supervisor_id');
            $table->date('assigned_date');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisor_assignments');
    }
};
