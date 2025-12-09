<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\FinancialReport;
use App\Models\IplBill;
use App\Models\Report;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $resident = $user->resident;

        if (!$resident) {
            return view('resident.no-resident');
        }

        // Get bills summary
        $unpaidBills = $resident->iplBills()
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $totalOutstanding = $unpaidBills->sum(function ($bill) {
            return $bill->total_amount - $bill->paid_amount;
        });

        // Recent bills
        $recentBills = $resident->iplBills()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get();

        // Active reports
        $activeReports = $resident->reports()
            ->whereIn('status', ['received', 'analyzing', 'processing'])
            ->latest()
            ->take(5)
            ->get();

        // Announcements
        $announcements = Announcement::published()
            ->latest()
            ->take(5)
            ->get();

        // Recent financial reports
        $financialReports = FinancialReport::published()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(3)
            ->get();

        return view('resident.dashboard', compact(
            'resident',
            'unpaidBills',
            'totalOutstanding',
            'recentBills',
            'activeReports',
            'announcements',
            'financialReports'
        ));
    }
}
