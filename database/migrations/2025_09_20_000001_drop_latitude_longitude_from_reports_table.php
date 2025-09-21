<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'latitude')) {
                $table->dropColumn('latitude');
            }
            if (Schema::hasColumn('reports', 'longitude')) {
                $table->dropColumn('longitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (!Schema::hasColumn('reports', 'latitude')) {
                $table->string('latitude')->nullable();
            }
            if (!Schema::hasColumn('reports', 'longitude')) {
                $table->string('longitude')->nullable();
            }
        });
    }
};