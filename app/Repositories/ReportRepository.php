<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\ReportRepositoryInterface;
use App\Models\ReportCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;



class ReportRepository implements ReportRepositoryInterface

{
    public function getAllReports()
    {
        return Report::all();
    }

    public function getLatesReports()
    {
        return Report::latest()->get()->take(5);
    }

    public function getReportsByResidentId(string $status)
    {
        return Report::where('resident_id', Auth::user()->resident->id)
            ->whereHas('reportStatuses', function (Builder $query) use ($status) {
                $query->where('status', $status)
                    ->whereIn('id', function ($subQuery) {
                        $subQuery->selectRaw('MAX(id)')
                            ->from('report_statuses')
                            ->groupBy('report_id');
                    });
            })->get();
    }

    public function getReportById(int $id)
    {
        return Report::where('id', $id)->first();
    }

    public function getReportByCode(string $code)
    {
        return Report::where('code', $code)->first();
    }

    public function getReportsByCategory(string $category)
    {
        $category = ReportCategory::where('name', $category)->first();

        return Report::where('report_category_id', $category->id)->get();
    }

    public function createReport(array $data)
    {
        $report = Report::create($data);

        $report->reportStatuses()->create([
            'status' => 'delivered',
            'description' => 'Laporan Berhasil Diterima'
        ]);
    }

    public function updateReport(array $data, int $id)
    {
        $report = $this->getReportById($id);

        return $report->update($data);
    }

    public function deleteReport(int $id)
    {
        $report = $this->getReportById($id);

        return $report->delete();
    }

    // Count reports whose latest status is 'delivered' (newly submitted by users) -> for admin
    public function countAdminNewReports(): int
    {
        return Report::whereHas('reportStatuses', function (Builder $query) {
            $query->whereIn('id', function ($subQuery) {
                $subQuery->selectRaw('MAX(id)')
                    ->from('report_statuses')
                    ->groupBy('report_id');
            })
            ->where('status', 'delivered');
        })->count();
    }

    // Count user's reports whose latest status is not 'delivered' (admin has responded) and is newer than user's last seen -> for user
    public function countUserNewResponses(): int
    {
        $residentId = Auth::user()?->resident?->id;
        if (!$residentId) {
            return 0;
        }

        // Subquery to get latest status id per report
        $latestPerReport = DB::table('report_statuses')
            ->selectRaw('report_id, MAX(id) as latest_status_id')
            ->groupBy('report_id');

        return Report::query()
            ->where('resident_id', $residentId)
            ->joinSub($latestPerReport, 'ls', function ($join) {
                $join->on('ls.report_id', '=', 'reports.id');
            })
            ->join('report_statuses as rs', 'rs.id', '=', 'ls.latest_status_id')
            ->where('rs.status', '!=', 'delivered')
            ->where(function ($q) {
                $q->whereNull('user_last_seen_status_id')
                  ->orWhereColumn('user_last_seen_status_id', '<', 'ls.latest_status_id');
            })
            ->count();
    }

    // Fetch the user's report with the most recent status change that is not 'delivered' and unseen by the user
    public function getLatestChangedReportForUser()
    {
        $residentId = Auth::user()?->resident?->id;
        if (!$residentId) {
            return null;
        }

        $latestPerReport = DB::table('report_statuses')
            ->selectRaw('report_id, MAX(id) as latest_status_id')
            ->groupBy('report_id');

        return Report::query()
            ->where('resident_id', $residentId)
            ->joinSub($latestPerReport, 'ls', function ($join) {
                $join->on('ls.report_id', '=', 'reports.id');
            })
            ->join('report_statuses as rs', 'rs.id', '=', 'ls.latest_status_id')
            ->where('rs.status', '!=', 'delivered')
            ->where(function ($q) {
                $q->whereNull('user_last_seen_status_id')
                  ->orWhereColumn('user_last_seen_status_id', '<', 'ls.latest_status_id');
            })
            ->orderByDesc('ls.latest_status_id')
            ->select('reports.*')
            ->first();
    }

    // Mark the report's latest status as seen by the current user
    public function markReportAsSeen(int $reportId): void
    {
        $report = $this->getReportById($reportId);
        if (!$report) {
            return;
        }

        $ownerId = Auth::user()?->resident?->id;
        if (!$ownerId || $report->resident_id !== $ownerId) {
            return;
        }

        $latestStatusId = $report->reportStatuses()->max('id');
        if ($latestStatusId) {
            $report->update(['user_last_seen_status_id' => $latestStatusId]);
        }
    }
}
