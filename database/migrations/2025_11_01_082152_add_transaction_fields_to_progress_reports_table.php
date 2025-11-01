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
        Schema::table('progress_reports', function (Blueprint $table) {
            // Add transaction fields (nullable so supervisors can submit without them)
            $table->decimal('transaction_amount', 10, 2)->nullable()->after('supervisor_remarks');
            $table->date('transaction_date')->nullable()->after('transaction_amount');
            $table->string('transaction_no')->nullable()->after('transaction_date');
            $table->string('pay_mode')->nullable()->after('transaction_no'); // e.g., 'Cash', 'Online', 'Cheque', etc.
            $table->string('receipt_file')->nullable()->after('pay_mode'); // Receipt file path
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('progress_reports', function (Blueprint $table) {
            $table->dropColumn([
                'transaction_amount',
                'transaction_date',
                'transaction_no',
                'pay_mode',
                'receipt_file'
            ]);
        });
    }
};
