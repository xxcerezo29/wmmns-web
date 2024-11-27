<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Repositories\ReportRepository;
use App\Repositories\RoamRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected $reportRepository;
    protected $roamRepository;
    public function __construct(ReportRepository $reportRepository, RoamRepository $roamRepository)
    {
        $this->reportRepository = $reportRepository;
        $this->roamRepository = $roamRepository;
    }

    public function index()
    {
        $complaintSummary = $this->reportRepository->getComplaintsSummary('month');
        $complaintPendingSummary = $this->reportRepository->getComplaintsSummaryByType('pending', 'month');
        $complaintReviewedSummary = $this->reportRepository->getComplaintsSummaryByType('reviewed', 'month');
        $complaintResolevedSummary = $this->reportRepository->getComplaintsSummaryByType('resolved', 'month');
        $complaintChartData = $this->reportRepository->getReportsChartData('day');
        $roamSummary = $this->roamRepository->getRoamsSummary('day');
        $users = [
            'label' => 'Users',
            'value' => Auth::user()->hasRole('admin') ? User::count() : User::where('barangay', Auth::user()->barangay)->count(),
            'description' => 'Total User'
        ];

        return Inertia::render(
            'Dashboard',
            [
                'stats' => [
                    $users,
                    $complaintSummary,
                    $complaintPendingSummary,
                    $complaintReviewedSummary,
                    $complaintResolevedSummary
                ],
                'complaintChartData' => $complaintChartData
            ]
        );
    }
}
