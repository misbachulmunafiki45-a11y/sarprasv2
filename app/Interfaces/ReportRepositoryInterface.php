<?php

namespace App\Interfaces;

interface ReportRepositoryInterface

{

    public function getAllReports();

    public function getLatesReports();

    public function getReportsByResidentId(string $status);

    public function getReportById(int $id);

    public function getReportByCode(string $code);

    public function getReportsByCategory(string $category);

    public function createReport(array $data);

    public function updateReport(array $data, int $id);

    public function deleteReport(int $id);

    // Notification counters
    public function countAdminNewReports(): int;

    // Count user's reports with unseen latest responses (latest status not delivered and newer than user's last seen)
    public function countUserNewResponses(): int;

    // Fetch the user's report with the most recent (latest) status change that is not delivered and unseen
    public function getLatestChangedReportForUser();

    // Mark a report's latest status as seen by the user (stores latest status id on report)
    public function markReportAsSeen(int $reportId): void;
}
