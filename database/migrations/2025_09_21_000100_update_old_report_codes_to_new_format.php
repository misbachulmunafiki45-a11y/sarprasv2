<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        // Update existing report codes to the new format: SRPddmmyySMKH2[n]
        DB::table('reports')->orderBy('id')->chunkById(100, function ($reports) {
            foreach ($reports as $report) {
                $currentCode = $report->code ?? '';

                // Skip if already in the desired format
                if (preg_match('/^SRP\d{6}SMKH2(?:\d+)?$/', $currentCode)) {
                    continue;
                }

                $date = $report->created_at ? Carbon::parse($report->created_at) : now();
                $base = sprintf('SRP%02d%02d%02dSMKH2', $date->day, $date->month, $date->format('y'));

                $newCode = $base;
                $suffix = 1;
                while (DB::table('reports')->where('code', $newCode)->where('id', '!=', $report->id)->exists()) {
                    $suffix++;
                    $newCode = $base . $suffix;
                }

                DB::table('reports')->where('id', $report->id)->update(['code' => $newCode]);
            }
        });
    }

    public function down(): void
    {
        // This data migration is not easily reversible because original codes are not preserved.
        // Intentionally left blank.
    }
};