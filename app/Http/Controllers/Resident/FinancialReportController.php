<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\FinancialReport;
use Illuminate\Http\Request;

class FinancialReportController extends Controller
{
    public function index()
    {
        $reports = FinancialReport::published()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(12);

        return view('resident.financial-reports.index', compact('reports'));
    }

    public function show(FinancialReport $financialReport)
    {
        if (!$financialReport->is_published) {
            abort(404);
        }

        return view('resident.financial-reports.show', compact('financialReport'));
    }

    public function announcements()
    {
        $announcements = Announcement::published()
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return view('resident.announcements', compact('announcements'));
    }
}
