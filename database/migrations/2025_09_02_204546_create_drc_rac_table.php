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
        Schema::create('drc_rac', function (Blueprint $table) {
            $table->foreignId('drc_id')->constrained()->onDelete('cascade');
            $table->foreignId('rac_id')->constrained()->onDelete('cascade');
            $table->primary(['drc_id', 'rac_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drc_rac');
    }
};
