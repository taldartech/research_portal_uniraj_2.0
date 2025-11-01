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
        Schema::create('coursework_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade'); // HOD who uploaded
            $table->string('marksheet_file');
            $table->date('exam_date');
            $table->enum('result', ['pass', 'fail'])->default('fail');
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->index(['scholar_id', 'exam_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coursework_results');
    }
};
