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
        Schema::table('synopses', function (Blueprint $table) {
            $table->text('proposed_topic_change')->nullable()->after('proposed_topic');
            $table->text('topic_change_reason')->nullable()->after('proposed_topic_change');
            $table->timestamp('topic_change_proposed_at')->nullable()->after('topic_change_reason');
            $table->unsignedBigInteger('topic_change_proposed_by')->nullable()->after('topic_change_proposed_at');
            $table->enum('topic_change_status', ['pending_scholar_response', 'accepted_by_scholar', 'rejected_by_scholar', 'withdrawn_by_supervisor'])->nullable()->after('topic_change_proposed_by');
            $table->timestamp('topic_change_responded_at')->nullable()->after('topic_change_status');
            $table->text('scholar_response_remarks')->nullable()->after('topic_change_responded_at');

            $table->foreign('topic_change_proposed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('synopses', function (Blueprint $table) {
            $table->dropForeign(['topic_change_proposed_by']);
            $table->dropColumn([
                'proposed_topic_change',
                'topic_change_reason',
                'topic_change_proposed_at',
                'topic_change_proposed_by',
                'topic_change_status',
                'topic_change_responded_at',
                'scholar_response_remarks'
            ]);
        });
    }
};
