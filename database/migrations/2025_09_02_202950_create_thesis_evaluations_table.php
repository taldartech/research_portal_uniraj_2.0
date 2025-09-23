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
        Schema::create('thesis_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('thesis_submission_id');
            $table->unsignedBigInteger('expert_id')->nullable();
            $table->unsignedBigInteger('supervisor_id');
            $table->string('report_file')->nullable();
            $table->date('assigned_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('submission_date')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('hvc_selected_expert_id')->nullable();
            $table->unsignedBigInteger('da_assigned_expert_id')->nullable();
            $table->string('decision')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thesis_evaluations');
    }
};
