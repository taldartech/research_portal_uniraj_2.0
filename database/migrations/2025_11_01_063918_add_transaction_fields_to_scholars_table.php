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
        Schema::table('scholars', function (Blueprint $table) {
            $table->decimal('transaction_amount', 10, 2)->nullable()->after('synopsis_status');
            $table->date('transaction_date')->nullable()->after('transaction_amount');
            $table->string('transaction_number')->nullable()->after('transaction_date');
            $table->string('pay_mode')->nullable()->after('transaction_number');
            $table->string('fee_receipt_file')->nullable()->after('pay_mode');
            $table->timestamp('fee_receipt_submitted_at')->nullable()->after('fee_receipt_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholars', function (Blueprint $table) {
            $table->dropColumn([
                'transaction_amount',
                'transaction_date',
                'transaction_number',
                'pay_mode',
                'fee_receipt_file',
                'fee_receipt_submitted_at',
            ]);
        });
    }
};
