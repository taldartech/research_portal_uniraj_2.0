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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->foreignId('admission_id')->constrained()->onDelete('cascade');
            $table->date('registration_date');
            $table->string('registration_form_file')->nullable();
            $table->string('dispatch_number')->nullable();
            $table->foreignId('dr_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('ar_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('hvc_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('status')->default('pending_hvc_approval');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
