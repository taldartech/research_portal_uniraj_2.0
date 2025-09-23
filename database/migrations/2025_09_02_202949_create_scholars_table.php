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
        Schema::create('scholars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('admission_id')->nullable();
            $table->string('enrollment_number')->unique()->nullable();
            $table->date('date_of_confirmation')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('contact_number')->nullable();
            $table->text('address')->nullable();
            $table->json('academic_information')->nullable();
            $table->json('documents_uploaded')->nullable();
            $table->string('research_area')->nullable();
            $table->string('status')->default('pending_admission');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholars');
    }
};
