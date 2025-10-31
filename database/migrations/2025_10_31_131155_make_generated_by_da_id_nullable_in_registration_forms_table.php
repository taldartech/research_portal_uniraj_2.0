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
        Schema::table('registration_forms', function (Blueprint $table) {
            $table->unsignedBigInteger('generated_by_da_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_forms', function (Blueprint $table) {
            $table->unsignedBigInteger('generated_by_da_id')->nullable(false)->change();
        });
    }
};
