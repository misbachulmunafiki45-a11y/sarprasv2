<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Interfaces\ReportCategoryRepositoryInterface;
use App\Interfaces\ReportRepositoryInterface;
use App\Interfaces\ResidentRepositoryInterface;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ReportController extends Controller
{
    private ReportRepositoryInterface $reportRepository;
    private ReportCategoryRepositoryInterface $reportCategoryRepository;
    private ResidentRepositoryInterface $residentRepository;

    public function __construct(
        ReportRepositoryInterface $reportRepository,
        ReportCategoryRepositoryInterface $reportCategoryRepository,
        ResidentRepositoryInterface $residentRepository
    ) {
        $this->reportRepository = $reportRepository;
        $this->reportCategoryRepository = $reportCategoryRepository;
        $this->residentRepository = $residentRepository;
    }

    public function index()
    {
        $reports = $this->reportRepository->getAllReports();

        return view('pages.admin.report.index', compact('reports'));
    }

    public function create()
    {
        $categories = $this->reportCategoryRepository->getAllReportCategories();
        $residents = $this->residentRepository->getAllResidents();

        return view('pages.admin.report.create', compact('categories', 'residents'));
    }

    public function store(StoreReportRequest $request)
    {
        $data = $request->validated();

        $this->reportRepository->createReport($data);

        return redirect()->route('admin.report.index');
    }

    public function show(string $id)
    {
        $report = $this->reportRepository->getReportById($id);

        return view('pages.admin.report.show', compact('report'));
    }

    public function edit(string $id)
    {
        $report = $this->reportRepository->getReportById($id);
        $categories = $this->reportCategoryRepository->getAllReportCategories();
        $residents = $this->residentRepository->getAllResidents();

        return view('pages.admin.report.edit', compact('report', 'categories', 'residents'));
    }

    public function update(UpdateReportRequest $request, string $id)
    {
        $data = $request->validated();

        $this->reportRepository->updateReport($data, $id);

        return redirect()->route('admin.report.index');
    }

    public function destroy(string $id)
    {
        $this->reportRepository->deleteReport($id);

        return redirect()->route('admin.report.index');
    }

    public function print(string $id)
    {
        $report = $this->reportRepository->getReportById($id);

        $pdf = PDF::loadView('pdf.admin-report', compact('report'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Admin-'.$report->code.'.pdf');
    }

    public function notificationCount()
    {
        return response()->json([
            'count' => $this->reportRepository->countAdminNewReports()
        ]);
    }
}
