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
        Schema::create('racs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scholar_id');
            $table->unsignedBigInteger('supervisor_id');
            $table->string('minutes_file');
            $table->date('formed_date');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('racs');
    }
};
