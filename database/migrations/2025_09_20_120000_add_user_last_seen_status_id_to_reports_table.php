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
        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedBigInteger('user_last_seen_status_id')->nullable()->after('address');
            // Optional FK constraint to report_statuses
            $table->foreign('user_last_seen_status_id')
                ->references('id')->on('report_statuses')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'user_last_seen_status_id')) {
                $table->dropForeign(['user_last_seen_status_id']);
                $table->dropColumn('user_last_seen_status_id');
            }
        });
    }
};