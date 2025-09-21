<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Interfaces\ReportCategoryRepositoryInterface;
use App\Interfaces\ReportRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ReportController extends Controller
{
    private ReportRepositoryInterface $reportRepository;
    private ReportCategoryRepositoryInterface $reportCategoryRepository;


    public function __construct(
        ReportRepositoryInterface $reportRepository,
        ReportCategoryRepositoryInterface $reportCategoryRepository,
    ) {
        $this->reportRepository = $reportRepository;
        $this->reportCategoryRepository = $reportCategoryRepository;
    }

    public function index(Request $request)
    {
        if ($request->category) {
            $reports = $this->reportRepository->getReportsByCategory($request->category);
        } else {
            $reports = $this->reportRepository->getAllReports();
        }

        return view('pages.app.report.index', compact('reports'));
    }

    public function myReport(Request $request)
    {
        $status = $request->status ?? 'delivered';
        $reports = $this->reportRepository->getReportsByResidentId($status);

        return view('pages.app.report.my-report', compact('reports'));
    }

    public function show($code)
    {
        $report = $this->reportRepository->getReportByCode($code);

        // If the authenticated user is the owner, mark latest status as seen
        if ($report && Auth::check() && optional(Auth::user()->resident)->id === $report->resident_id) {
            $this->reportRepository->markReportAsSeen($report->id);
        }

        return view('pages.app.report.show', compact('report'));
    }

    public function take()
    {
        return view('pages.app.report.take');
    }

    public function preview()
    {
        return view('pages.app.report.preview');
    }

    public function create()
    {
        $categories = $this->reportCategoryRepository->getAllReportCategories();

        return view('pages.app.report.create', compact('categories'));
    }

    public function store(StoreReportRequest $request)
    {
        $data = $request->validated();
        // Kode laporan akan dihasilkan otomatis di model Report (format: SRP-dd-mm-yy-SMKH2[-n])
        $data['resident_id'] = Auth::user()->resident->id;
        $data['image'] = $request->file('image')->store('assets/report/image', 'public');

        $this->reportRepository->createReport($data);

        return redirect()->route('report.success');
    }

    public function success()
    {
        return view('pages.app.report.success');
    }

    public function print(string $code)
    {
        $report = $this->reportRepository->getReportByCode($code);

        $pdf = PDF::loadView('pdf.user-report', compact('report'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-'.$report->code.'.pdf');
    }

    public function notificationCount()
    {
        return response()->json([
            'count' => $this->reportRepository->countUserNewResponses()
        ]);
    }

    // Return latest changed report URL for the user and mark it as seen
    public function latestNotification(Request $request)
    {
        $report = $this->reportRepository->getLatestChangedReportForUser();
        if (!$report) {
            return response()->json(['url' => null], 200);
        }

        // Mark as seen now to immediately clear badge
        $this->reportRepository->markReportAsSeen($report->id);

        return response()->json([
            'url' => route('report.show', $report->code)
        ]);
    }
}
